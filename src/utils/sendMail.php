<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'makeenmohamed.thanseem@gmail.com';
        $mail->Password   = 'dnzrxxmvwglefsil';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom('makeenmohamed.thanseem@gmail.com', 'QuickHire LK');
        $mail->addAddress($to);
        $mail->addReplyTo('support@quickhire.lk', 'QuickHire LK Support'); // Optional

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// ...existing code...
function sendOtpEmail($email, $otp_code) {
    // Use absolute path for the template
    $template_path = __DIR__ . '/email/otp.html';
    if (!file_exists($template_path)) {
        // Handle error: template file not found
        return false;
    }
    $template = file_get_contents($template_path);

    $body = str_replace('{{otp_code}}', $otp_code, $template);
    $subject = "Your QuickHire Password Reset Code";
    return sendMail($email, $subject, $body);
}
// ...existing code...