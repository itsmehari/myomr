<?php
@ini_set('display_errors', 1); @ini_set('display_startup_errors', 1); @error_reporting(E_ALL);
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
require_once __DIR__ . '/../includes/admin-audit.php';
requireAdmin();
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { die('Invalid ID'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die('Invalid method'); }
if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)($_POST['csrf'] ?? ''))) { die('Invalid CSRF token'); }

try {
  global $conn;
  if (!$conn || $conn->connect_error) { throw new Exception('DB not available'); }

  // Fetch listing
  $q = $conn->prepare("SELECT * FROM event_listings WHERE id=? LIMIT 1");
  $q->bind_param('i', $id);
  $q->execute();
  $ev = $q->get_result()->fetch_assoc();
  if (!$ev) { throw new Exception('Listing not found'); }

  // Move to submissions as 'submitted'
  $ins = $conn->prepare("INSERT INTO event_submissions (title, slug, category_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, image_url, description, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, 'submitted')");
  $is_all_day = (int)($ev['is_all_day'] ?? 0);
  $ins->bind_param(
    'ssisssssssiissss',
    $ev['title'],
    $ev['slug'],
    $ev['category_id'],
    $ev['organizer_name'],
    $ev['organizer_email'],
    $ev['organizer_phone'],
    $ev['location'],
    $ev['locality'],
    $ev['start_datetime'],
    $ev['end_datetime'],
    $is_all_day,
    $ev['is_free'],
    $ev['price'],
    $ev['tickets_url'],
    $ev['image_url'],
    $ev['description']
  );
  $ins->execute();

  // Delete listing
  $d = $conn->prepare("DELETE FROM event_listings WHERE id=?");
  $d->bind_param('i', $id);
  $d->execute();
  eventAdminAudit('listing_unapprove', ['listing_id' => (int)$id]);

  header('Location: view-listings.php?moved=1');
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Error: ' . htmlspecialchars($e->getMessage());
}


