<?php
// Start the session
session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../authentication/login.php");
    exit;
}

// Get the username from the session to display it
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../../dist/output.css">
    <link rel="stylesheet" href="../../../dist/main.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../../../assets/js/toast-notifications.js"></script>

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <?php include '../../layouts/partials/dashboard_loading.php'; require_once '../partials/session_toast.php'; showLoginSuccessToast();?>

    <div class="bg-white p-12 rounded-lg shadow-lg text-center">
        <?php echo ($_SESSION['user_type']) ?>
        <h1 class="text-3xl font-bold text-gray-800">Welcome to the Dashboard!</h1>
        <p class="mt-4 text-xl text-gray-600">You are logged in as: <span class="font-semibold text-indigo-600"><?php echo $username; ?></span></p>
        <a href="../../authentication/logout.php" class="mt-8 inline-block bg-red-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-600 transition">
            Logout
        </a>
    </div>
</body>

</html>