<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

if (session_status() === PHP_SESSION_NONE) { session_start(); }

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') { throw new Exception('Invalid method'); }
  $tok = $_POST['csrf'] ?? '';
  if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)$tok)) {
    throw new Exception('Invalid CSRF token');
  }

  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
  if ($id <= 0) { throw new Exception('Invalid ID'); }
  $reason = isset($_POST['reason']) ? trim((string)$_POST['reason']) : '';

  if (rejectSubmission($id, $reason)) {
    header('Location: manage-events-omr.php?rejected=1');
    exit;
  }

  header('Location: manage-events-omr.php?error=reject_failed');
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Error: ' . htmlspecialchars($e->getMessage());
  exit;
}


