<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COMMUNITY_EVENTS_ADMIN_ROUTED', '/superadmin/community-events/process-reject-event.php');
require_once __DIR__ . '/_urls.php';
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';
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
    header('Location: ' . community_events_admin_manage_url() . '?rejected=1');
    exit;
  }

  header('Location: ' . community_events_admin_manage_url() . '?error=reject_failed');
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo 'Error: ' . htmlspecialchars($e->getMessage());
  exit;
}


