
<?php
session_start();
header('Content-Type: application/json');

// --- INCLUDE YOUR REQUIRED FILES ---
require '../../db/db.php'; // Your mysqli database connection file
require '../utils/sendMail.php'; // Your file that now contains sendOtpEmail()

// --- HELPER FUNCTION FOR JSON RESPONSE ---
function json_response($status, $message, $field = null, $redirect = null)
{
    $response = ['status' => $status, 'message' => $message];
    if ($field) $response['field'] = $field;
    if ($redirect) $response['redirect'] = $redirect;
    echo json_encode($response);
    exit;
}

// --- MAIN LOGIC ROUTER ---
$action = $_POST['action'] ?? '';
// $conn is your mysqli connection variable from db_connection.php

switch ($action) {
    case 'send_code':
        handle_send_code($conn);
        break;
    case 'verify_code':
        handle_verify_code($conn);
        break;
    case 'reset_password':
        handle_reset_password($conn);
        break;
    default:
        json_response('error', 'Invalid action specified.', 'email');
}

// --- FUNCTION DEFINITIONS (USING MYSQLI) ---

function handle_send_code($conn)
{
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        json_response('error', 'Please enter a valid email address.', 'email');
    }

    $stmt = $conn->prepare("SELECT id FROM user_accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        json_response('error', 'No account found with that email address.', 'email');
    }
    $stmt->close();

    $code = random_int(10000, 99999);
    $expires = time() + (15 * 60);

    // Store code
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO password_resets (email, code, expires) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $email, $code, $expires);
    $stmt->execute();
    $stmt->close();

    // Use the new, dedicated function to send the OTP email
    if (sendOtpEmail($email, $code)) {
        json_response('success', 'Verification code sent');
    } else {
        json_response('error', 'Could not send the verification email. Please try again later.', 'email');
    }
}

function handle_verify_code($conn)
{
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $code = trim($_POST['code']);

    if (!$email || empty($code)) {
        json_response('error', 'Invalid request. Please try again.', 'code');
    }

    $stmt = $conn->prepare("SELECT expires FROM password_resets WHERE email = ? AND code = ?");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        json_response('error', 'The code you entered is incorrect.', 'code');
    }

    $reset = $result->fetch_assoc();
    $stmt->close();

    if (time() > $reset['expires']) {
        json_response('error', 'This verification code has expired. Please request a new one.', 'code');
    }

    $_SESSION['reset_email_verified'] = $email;
    json_response('success', 'Code verified! Reset your password.');
}

function handle_reset_password($conn)
{
    if (!isset($_SESSION['reset_email_verified'])) {
        json_response('error', 'Your session has expired. Please start the process over.', 'password');
    }

    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    if (empty($password) || strlen($password) < 6) {
        json_response('error', 'Password must be at least 6 characters long.', 'password');
    }
    if ($password !== $confirm_password) {
        json_response('error', 'The passwords do not match.', 'password');
    }

    $email = $_SESSION['reset_email_verified'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // FIX: Use the correct table name (user_accounts)
    $stmt = $conn->prepare("UPDATE user_accounts SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    if (!$stmt->execute()) {
        json_response('error', 'Database error: Could not update password.', 'password');
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['reset_email_verified']);

    json_response('success', 'Password updated successfully!', null, 'login.php');
}

$conn->close();
