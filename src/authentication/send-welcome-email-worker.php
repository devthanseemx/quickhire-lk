<?php
// This script is designed to be called by the server, not a user.

// Check if we have the required POST data.
if (isset($_POST['email']) && isset($_POST['name'])) {

    $recipient_email = $_POST['email'];
    $recipient_name = $_POST['name'];

    // We need to include the necessary files again here.
    require_once '../templates/email/welcome.php';
    require_once '../utils/sendMail.php';

    try {
        $subject = "Welcome to QuickHire LK!";
        $body = welcomeTemplate($recipient_name);
        sendMail($recipient_email, $subject, $body);
        // No output is needed. This script just does its job and exits.
    } catch (Exception $e) {
        // If sending fails, log it to the server's error log for debugging.
        // The user will never see this.
        error_log("Email worker failed for {$recipient_email}: " . $e->getMessage());
    }
}