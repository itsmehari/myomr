<?php
/**
 * Expire approved listings past expiry_date (10-day window from approval).
 */
$is_cli = (php_sapi_name() === 'cli');
$key_ok = false;
if (!$is_cli && isset($_GET['key'])) {
    $secret = getenv('EXPIRE_CRON_KEY') ?: (defined('EXPIRE_CRON_KEY') ? EXPIRE_CRON_KEY : '');
    $key_ok = $secret !== '' && hash_equals($secret, $_GET['key']);
}
if (!$is_cli && !$key_ok) {
    http_response_code(403);
    exit('Forbidden');
}

require_once dirname(__DIR__) . '/core/omr-connect.php';

$check = $conn->query("SHOW TABLES LIKE 'omr_classified_ads_listings'");
if (!$check || $check->num_rows === 0) {
    exit('Table not found');
}

$stmt = $conn->prepare("UPDATE omr_classified_ads_listings SET status = 'expired' WHERE status = 'approved' AND expiry_date IS NOT NULL AND expiry_date < CURDATE()");
$stmt->execute();
echo 'Expired ' . $conn->affected_rows . " listing(s)\n";
