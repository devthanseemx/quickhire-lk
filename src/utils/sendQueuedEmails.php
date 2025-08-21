<?php
require_once '../../db/db.php';
require_once 'sendMail.php';

// --- Base folder for email templates ---
$templateBase = __DIR__ . '/../utils/email/';

$result = $conn->query("SELECT * FROM email_queue WHERE sent = 0 ORDER BY created_at ASC LIMIT 10");

while ($row = $result->fetch_assoc()) {
    $templateName = $row['template']; // e.g., 'confirmation', 'otp', 'order'
    $templatePath = $templateBase . $templateName . '.html';

    // --- Skip if template not found ---
    if (!file_exists($templatePath)) {
        error_log("Template file not found: {$templatePath}");
        continue;
    }

    $templateContent = file_get_contents($templatePath);

    // --- Decode template data (JSON) and replace placeholders ---
    $data = json_decode($row['template_data'], true);
    if (!empty($data) && is_array($data)) {
        foreach ($data as $key => $value) {
            $templateContent = str_replace('{{' . $key . '}}', htmlspecialchars($value), $templateContent);
        }
    }

    // --- Send email ---
    $sent = sendMail($row['email'], $row['subject'], $templateContent);

    if ($sent) {
        // --- Delete email from queue after sending ---
        $stmt = $conn->prepare("DELETE FROM email_queue WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();

        echo "Email sent to {$row['email']} ({$templateName})\n";
    } else {
        // --- Increment retry_count if failed ---
        $stmt = $conn->prepare("UPDATE email_queue SET retry_count = retry_count + 1 WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();

        error_log("Failed to send email to {$row['email']} ({$templateName})");
    }
}

$conn->close();
