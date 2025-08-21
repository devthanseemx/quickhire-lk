<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forget Password</title>
    <link rel="stylesheet" href="../../dist/output.css">
    <link rel="stylesheet" href="../../dist/main.css">
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
</head>

<body class="m-0 h-screen box-border flex flex-row gap-8 bg-white p-5 md:p-2 overflow-hidden">
    <!-- Left Image Side -->
    <div class="hidden md:block w-1/2 h-full overflow-hidden rounded-xl">
        <img src="../../assets/images/forgot-password.jpg" alt="Login Background" class="w-full h-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 h-full flex items-center">
        <!-- Section 1: Check Email -->
        <div id="check-email-section" class="w-full max-w-md mx-auto">
            <h2 class="text-3xl font-bold mb-3 text-center">Forget Password</h2>
            <p class="text-gray-500 text-center mb-16">Enter your email and we'll send you a verification code.</p>
            <form id="send-code-form">
                <div class="mb-5">
                    <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email address</label>
                    <div class="relative">
                        <i class="bi bi-envelope text-gray-400 absolute inset-y-0 left-0 pl-3 flex items-center"></i>
                        <input type="email" id="email" name="email" class="w-full pl-10 pr-4 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
                    </div>
                    <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <button id="send-code-btn" type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-md cursor-pointer hover:bg-indigo-700 transition">Send Code</button>
                <p class="mt-6 text-sm text-center"><a href="login.php" class="text-indigo-600 hover:text-indigo-400 font-medium">Back to Login</a></p>
            </form>
        </div>

        <!-- Section 2: Verification Code -->
        <div id="code-section" class="w-full max-w-md mx-auto hidden">
            <h2 class="text-3xl font-bold mb-3 text-center">Check your Email</h2>
            <p class="text-gray-500 text-center mb-1">Code sent to your email address</p>

            <p id="timer" class="timer-paragraph text-sm text-center text-indigo-600 mb-8 hidden"></p>

            <form id="verify-code-form">
                <div class="mb-5">
                    <label for="code" class="block mb-1 text-sm font-medium text-gray-700">Verification Code</label>
                    <input type="text" id="code" name="code" class="w-full tracking-[1em] text-center border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="- - - - -" maxlength="5" required />
                    <p id="codeError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <button id="verify-code-btn" type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 cursor-pointer rounded-md hover:bg-indigo-700 transition">Verify Code</button>
            </form>
        </div>

        <!-- Section 3: Reset Password -->
        <div id="reset-section" class="w-full max-w-md mx-auto hidden">
            <!-- (This section is unchanged) -->
            <h2 class="text-3xl font-bold mb-3 text-center">Create New Password</h2>
            <p class="text-gray-500 text-center mb-16">Your new password must be different from previous ones.</p>
            <form id="reset-password-form">
                <div class="mb-5">
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
                </div>
                <div class="mb-5">
                    <label for="confirm-password" class="block mb-1 text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
                    <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <button id="reset-password-btn" type="submit" class="w-full bg-indigo-600 cursor-pointer text-white font-semibold py-3 rounded-md hover:bg-indigo-700 transition">Reset Password</button>
            </form>
        </div>
    </div>

    <!-- External JS files -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../../assets/js/toast-notifications.js"></script>
    <script src="../../assets/js/reset-password.js"></script>
</body>

</html>