<?php
/**
 * OMR Classified Ads — reveal phone (JSON); logged-in only; rate limited.
 */
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

ca_session_start();
$uid = ca_user_id();
if ($uid === null) {
    echo json_encode(['ok' => false, 'error' => 'Login required']);
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    echo json_encode(['ok' => false, 'error' => 'Invalid session']);
    exit;
}

$listing_id = (int) ($_POST['listing_id'] ?? 0);
if ($listing_id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Invalid listing']);
    exit;
}

$max_per_day = 40;
if (countClassifiedAdsRevealLast24h($uid) >= $max_per_day) {
    echo json_encode(['ok' => false, 'error' => 'Daily reveal limit reached. Try tomorrow.']);
    exit;
}

$listing = getClassifiedAdsListingById($listing_id);
if (!$listing || ($listing['status'] ?? '') !== 'approved') {
    echo json_encode(['ok' => false, 'error' => 'Listing not found']);
    exit;
}

$phone = trim($listing['contact_phone'] ?? '');
if ($phone === '') {
    echo json_encode(['ok' => false, 'error' => 'No phone on file']);
    exit;
}

global $conn;
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$stmt = $conn->prepare('INSERT INTO omr_classified_ads_phone_reveal_log (listing_id, user_id, ip_address) VALUES (?,?,?)');
if ($stmt) {
    $stmt->bind_param('iis', $listing_id, $uid, $ip);
    $stmt->execute();
}

echo json_encode(['ok' => true, 'phone' => $phone, 'listing_id' => $listing_id]);
