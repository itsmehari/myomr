<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COMMUNITY_EVENTS_ADMIN_ROUTED', '/superadmin/community-events/process-close-past-events.php');
require_once __DIR__ . '/_urls.php';
/**
 * Bulk-update: set status=ended and featured=0 for listings whose effective end is in the past.
 */
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';
require_once __DIR__ . '/../includes/admin-audit.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Invalid method');
}
if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], (string)($_POST['csrf'] ?? ''))) {
    header('HTTP/1.1 403 Forbidden');
    exit('Invalid CSRF token');
}

try {
    global $conn;
    if (!$conn || $conn->connect_error) {
        throw new RuntimeException('DB not available');
    }
    $n = omr_event_close_past_listings($conn);
    eventAdminAudit('bulk_close_past_listings', ['updated' => $n]);
    header('Location: ' . community_events_admin_listings_url() . '?closed=' . (int)$n);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
