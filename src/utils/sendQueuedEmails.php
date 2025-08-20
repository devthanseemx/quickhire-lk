<?php
require_once '../../db/db_connection.php';
require_once 'sendMail.php';

// Fetch unsent emails
$result = $conn->query("SELECT * FROM email_queue WHERE sent = 0 LIMIT 10");

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $name = $row['name'];
    $user_id = $row['user_id'];

    // Load confirmation template
    $template = file_get_contents('email/confirmation.html');
    $body = str_replace('{{name}}', htmlspecialchars($name), $template);

    $subject = "Welcome to QuickHire LK!";

    if (sendMail($email, $subject, $body)) {
        // Mark as sent
        $stmt = $conn->prepare("UPDATE email_queue SET sent = 1 WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();

        // Delete the row after sending
        $stmt_del = $conn->prepare("DELETE FROM email_queue WHERE id = ?");
        $stmt_del->bind_param("i", $row['id']);
        $stmt_del->execute();
        $stmt_del->close();
    }
}

$conn->close();