<?php
/**
 * Save job alert subscription
 * POST form: alert_email, alert_keyword, alert_location
 */
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Method not allowed']));
}

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

$email    = filter_var(trim($_POST['alert_email'] ?? ''), FILTER_VALIDATE_EMAIL);
$keyword  = htmlspecialchars(trim($_POST['alert_keyword'] ?? ''), ENT_QUOTES, 'UTF-8');
$location = htmlspecialchars(trim($_POST['alert_location'] ?? 'OMR'), ENT_QUOTES, 'UTF-8');

if (!$email) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Please enter a valid email address.']));
}

// Store in omr_job_alerts table (create if missing via ALTER-safe INSERT)
if ($conn instanceof mysqli) {
    // Ensure table exists
    $conn->query("CREATE TABLE IF NOT EXISTS omr_job_alerts (
        id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email       VARCHAR(180) NOT NULL,
        keyword     VARCHAR(120) DEFAULT NULL,
        location    VARCHAR(100) DEFAULT 'OMR',
        active      TINYINT(1) NOT NULL DEFAULT 1,
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_alert (email, keyword, location)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $stmt = $conn->prepare(
        "INSERT INTO omr_job_alerts (email, keyword, location) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE active = 1, created_at = CURRENT_TIMESTAMP"
    );
    if ($stmt) {
        $stmt->bind_param('sss', $email, $keyword, $location);
        $stmt->execute();
    }
}

echo json_encode(['ok' => true, 'email' => $email]);
