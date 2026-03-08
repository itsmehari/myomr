<?php
/**
 * MyOMR Newsletter Subscribe Handler
 * Migrated from flat-file to omr_newsletter_subscribers DB table.
 * Accepts POST with: email (required), locality (optional), source_url (optional).
 * Redirects back to source_url with ?subscribed=1 on success.
 */

require_once __DIR__ . '/../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

$email      = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$locality   = htmlspecialchars(trim($_POST['locality']   ?? ''), ENT_QUOTES, 'UTF-8');
$source_raw = trim($_POST['source_url'] ?? '');

// Only allow relative paths as redirect target to prevent open redirect
$source_url = (preg_match('#^/[^/]#', $source_raw) || $source_raw === '/') ? $source_raw : '/';

$sep = (strpos($source_url, '?') !== false) ? '&' : '?';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $source_url . $sep . 'subscribe_error=invalid');
    exit;
}

// Create table once if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS omr_newsletter_subscribers (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(255) NOT NULL,
  locality    VARCHAR(100) DEFAULT NULL,
  source_page VARCHAR(255) DEFAULT NULL,
  status      ENUM('active','unsubscribed') NOT NULL DEFAULT 'active',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$stmt = $conn->prepare(
    "INSERT INTO omr_newsletter_subscribers (email, locality, source_page)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE status = 'active', updated_at = NOW()"
);
if ($stmt) {
    $stmt->bind_param('sss', $email, $locality, $source_url);
    $stmt->execute();
    $stmt->close();
}

header('Location: ' . $source_url . $sep . 'subscribed=1');
exit;
