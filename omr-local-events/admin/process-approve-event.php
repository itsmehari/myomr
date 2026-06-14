<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COMMUNITY_EVENTS_ADMIN_ROUTED', '/superadmin/community-events/process-approve-event.php');
require_once __DIR__ . '/_urls.php';
// Force top-of-file error surfacing for this action
@ini_set('display_errors', 1);
@ini_set('display_startup_errors', 1);
@error_reporting(E_ALL);
if (function_exists('mysqli_report')) { @mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); }

require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../includes/admin-audit.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';

try {
  if (session_status() === PHP_SESSION_NONE) { session_start(); }
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') { throw new Exception('Invalid method'); }
  $tok = $_POST['csrf'] ?? '';
  if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)$tok)) { throw new Exception('Invalid CSRF token'); }
  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
  if ($id <= 0) { throw new Exception('Invalid ID'); }

  global $conn;
  if (approveSubmissionToListing($id)) {
    eventAdminAudit('approve_submission', ['submission_id' => (int)$id]);
    header('Location: ' . community_events_admin_manage_url() . '?approved=1');
    exit;
  }

  // Approval returned false → show diagnostics
  http_response_code(500);
  echo "<div style='background:#fee;border:2px solid #f00;padding:16px;margin:16px;border-radius:8px'>";
  echo "<h3>Approval Failed</h3>";
  echo "<p><strong>Submission ID:</strong> " . (int)$id . "</p>";
  if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
    echo "<p><strong>MySQL Error:</strong> " . htmlspecialchars(isset($conn) ? ($conn->error ?? 'n/a') : 'no connection') . "</p>";
    echo "<p>Enable server error log review for details.</p>";
  } else {
    echo "<p>Something went wrong. Please try again later.</p>";
  }
  echo "<p><a href='" . htmlspecialchars(community_events_admin_manage_url(), ENT_QUOTES, 'UTF-8') . "' style='color:#b91c1c'>Back to Manage Events</a></p>";
  echo "</div>";
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo "<div style='background:#fee;border:2px solid #f00;padding:16px;margin:16px;border-radius:8px'>";
  echo "<h3>Fatal Error (Approve)</h3>";
  echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
  echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
  echo "<p><strong>Line:</strong> " . (int)$e->getLine() . "</p>";
  echo "<pre style='white-space:pre-wrap'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
  echo "<p><a href='" . htmlspecialchars(community_events_admin_manage_url(), ENT_QUOTES, 'UTF-8') . "' style='color:#b91c1c'>Back to Manage Events</a></p>";
  echo "</div>";
  exit;
}