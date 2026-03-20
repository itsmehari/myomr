<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /omr-classified-ads/');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

global $conn;
$listing_id = (int) ($_POST['listing_id'] ?? 0);
$reason = $_POST['reason'] ?? '';
$notes = trim($_POST['notes'] ?? '');

$allowed = ['spam', 'fake', 'inappropriate', 'other'];
if ($listing_id <= 0 || !in_array($reason, $allowed, true)) {
    header('Location: /omr-classified-ads/');
    exit;
}

if (!getClassifiedAdsListingById($listing_id)) {
    header('Location: /omr-classified-ads/');
    exit;
}

$stmt = $conn->prepare("INSERT INTO omr_classified_ads_reports (listing_id, reason, notes, status) VALUES (?,?,?,'pending')");
$stmt->bind_param('iss', $listing_id, $reason, $notes);
$stmt->execute();

header('Location: report-listing-omr.php?listing_id=' . $listing_id . '&submitted=1');
exit;
