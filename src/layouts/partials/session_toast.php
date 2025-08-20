<?php
function showLoginSuccessToast() {
    if (isset($_SESSION['login_success'])) {
        echo '<script src="../../../assets/js/toast-notifications.js"></script>';
        echo '<script>showToast("' . $_SESSION['login_success'] . '", "success");</script>';
        unset($_SESSION['login_success']);
    }
}
?>