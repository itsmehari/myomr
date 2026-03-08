<?php
@ini_set('display_errors', 1); @ini_set('display_startup_errors', 1); @error_reporting(E_ALL);
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
require_once __DIR__ . '/../includes/admin-audit.php';
requireAdmin();
requireRole(['super_admin']);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { die('Invalid ID'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die('Invalid method'); }
if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)($_POST['csrf'] ?? ''))) { die('Invalid CSRF token'); }

try {
  global $conn;
  if (!$conn || $conn->connect_error) { throw new Exception('DB not available'); }
  $stmt = $conn->prepare("DELETE FROM event_listings WHERE id=?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  eventAdminAudit('listing_delete', ['listing_id' => (int)$id]);
  header('Location: view-listings.php?deleted=1');
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Error: ' . htmlspecialchars($e->getMessage());
}


