<?php
session_start();
require_once '../../db/company-db.php'; // DB connection

// Handle POST request for AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');

    $response = [];
    $provided_id = isset($_POST['verification_id']) ? strtoupper(trim($_POST['verification_id'])) : '';
    $user_type = null;
    $is_valid = false;
    $error_message = '';

    if (empty($provided_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Verification ID is required.']);
        exit();
    }

    // Determine user type
    if (strpos($provided_id, 'EMP') === 0) {
        $user_type = 'employee';
        $stmt = $company_conn->prepare("SELECT employee_id FROM employees WHERE employee_id = ?");
    } elseif (strpos($provided_id, 'ADM') === 0) {
        $user_type = 'admin';
        $stmt = $company_conn->prepare("SELECT admin_id FROM admins WHERE admin_id = ?");
    } else {
        echo json_encode(['status' => 'error', 'message' => "Invalid ID format. Use 'EMP...' or 'ADM...'."]);
        exit();
    }

    // Execute query
    if ($stmt) {
        $stmt->bind_param("s", $provided_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $is_valid = true;
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query failed.']);
        exit();
    }

    $company_conn->close();

    if ($is_valid) {
        $_SESSION['verified_id'] = $provided_id;
        $_SESSION['user_type'] = $user_type;

        echo json_encode(['status' => 'success', 'message' => 'ID Verified! Redirecting you now...']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'The provided ID was not found.']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QuickHire LK - ID Verification</title>
    <link rel="stylesheet" href="../../dist/output.css">
    <link rel="stylesheet" href="../../dist/main.css">
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
</head>

<body class="m-0 h-screen box-border flex flex-row gap-8 bg-white p-5 md:p-2 overflow-hidden">
    <div class="hidden md:block w-1/2 h-full overflow-hidden rounded-xl">
        <img src="../../assets/images//office-use-image.jpg" alt="Login Background" class="w-full h-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 h-full flex items-center">
        <div class="w-full max-w-md mx-auto">
            <h2 class="text-3xl font-bold mb-3 text-center">Verify Your ID</h2>
            <p class="info-text text-gray-500 text-center mb-20 3xl:mb-24">Please enter your Employee or Admin ID to proceed.</p>

            <form id="verify-form" action="" method="POST" novalidate>
                <div class="mb-5">
                    <label for="verification_id" class="block mb-1 text-sm font-medium text-gray-700">Verification ID</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-person-vcard text-gray-400"></i>
                        </div>
                        <input type="text" id="verification_id" name="verification_id"
                            class="w-full pl-10 pr-4 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <p id="id-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-md hover:bg-indigo-700 transition cursor-pointer">
                    Verify
                </button>
                <p class="mt-6 text-sm text-center text-gray-500">
                    <a href="../index.html" class="text-indigo-600 hover:text-indigo-400 font-medium">Go Back</a>
                </p>
            </form>

            <hr class="my-8" />
            <p class="mt-6 text-sm text-center text-gray-500">
                Are you a general user?
                <a href="register.php" class="text-indigo-400 hover:underline hover:text-indigo-600 font-medium">Register here</a>
            </p>
        </div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../../assets/js/toast-notifications.js"></script>

    <script>
        $(document).ready(function() {
            $('#verify-form').on('submit', function(e) {
                e.preventDefault();
                let verification = $('#verification_id').val().trim();
                $('#id-error').addClass('hidden').text('');

                if (verification === "") {
                    $('#id-error').removeClass('hidden').text("Verification ID is required.");
                    return;
                }

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'error') {
                            $('#id-error').removeClass('hidden').text(res.message);
                        } else {
                            showToast(res.message, "success");
                            setTimeout(() => window.location.href = "register.php", 1500);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#id-error').removeClass('hidden').text("Something went wrong. Try again.");
                        console.error("AJAX Error:", status, error);
                    }
                });
            });

            // --- Animation on Page Load ---
            const $leftImage = $('.hidden.md\\:block');
            const $title = $('.text-3xl.font-bold');
            const $subtitle = $('.info-text');
            const $formElements = $('#verify-form > *, .my-8, .mt-6');

            if ($leftImage.length) {
                $leftImage.addClass('animate-slide-in-from-left');
            }
            if ($title.length) {
                $title.addClass('animate-slide-in-from-bottom').css('animation-delay', '0.2s');
            }
            if ($subtitle.length) {
                $subtitle.addClass('animate-slide-in-from-bottom').css('animation-delay', '0.3s');
            }
            $formElements.each(function(index) {
                $(this).addClass('animate-slide-in-from-bottom')
                    .css('animation-delay', (0.4 + index * 0.1) + 's');
            });
        });
    </script>
</body>

</html>