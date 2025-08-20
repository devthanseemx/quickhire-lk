<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../../dist/output.css">
    <link rel="stylesheet" href="../../../dist/main.css">
    <meta http-equiv="refresh" content="1.5;url=login.php">
    <title>Logging Out...</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <?php include '../layouts/partials/dashboard-loading.html'; ?>
    <script>
        setTimeout(function() {
            // Clear session via AJAX or PHP
            fetch('logout-action.php', { method: 'POST' })
                .finally(() => window.location.href = 'login.php');
        }, 1200);
    </script>
</body>
</html>