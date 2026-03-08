<?php
@ini_set('display_errors', 1); @ini_set('display_startup_errors', 1); @error_reporting(E_ALL);
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
require_once __DIR__ . '/../includes/admin-audit.php';
requireAdmin();
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$mode = isset($_POST['mode']) ? $_POST['mode'] : 'pause';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die('Invalid method'); }
if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)($_POST['csrf'] ?? ''))) { die('Invalid CSRF token'); }
if ($id <= 0) { die('Invalid ID'); }

try {
  global $conn;
  if (!$conn || $conn->connect_error) { throw new Exception('DB not available'); }
  $newStatus = ($mode === 'resume') ? 'scheduled' : 'archived';
  $stmt = $conn->prepare("UPDATE event_listings SET status=? WHERE id=?");
  $stmt->bind_param('si', $newStatus, $id);
  $stmt->execute();
  eventAdminAudit('listing_status_change', ['listing_id' => (int)$id, 'status' => $newStatus]);
  header('Location: view-listings.php?updated=1');
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Error: ' . htmlspecialchars($e->getMessage());
}


