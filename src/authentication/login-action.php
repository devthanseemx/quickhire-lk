<?php
session_start();

header('Content-Type: application/json');
require_once '../../db/db.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = strtolower(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $response = ['status' => 'error', 'field' => empty($login) ? 'username' : 'password', 'message' => 'Username/Email and password are required.'];
        echo json_encode($response);
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, user_type FROM user_accounts WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['login_success'] = 'Login successful';

            if ($user['user_type'] === 'admin') {
                $dashboard = '../layouts/admin/admin_dashboard.php';
            } elseif ($user['user_type'] === 'employee') {
                $dashboard = '../layouts/employee/employee_dashboard.php';
            } else {
                $dashboard = '../layouts/user/user_dashboard.php';
            }

            $response = [
                'status' => 'success',
                'message' => 'Login successful',
                'redirect' => $dashboard
            ];
        } else {
            $response = ['status' => 'error', 'field' => 'password', 'message' => 'Incorrect password.'];
        }
    } else {
        $response = ['status' => 'error', 'field' => 'username', 'message' => 'Incorrect username or email.'];
    }

    $stmt->close();
}


$conn->close();
echo json_encode($response);
