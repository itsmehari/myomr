<?php
/**
 * OMR Buy-Sell — Process Report Submission
 */
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /omr-buy-sell/');
    exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

global $conn;

$listing_id = (int)($_POST['listing_id'] ?? 0);
$reason = $_POST['reason'] ?? '';
$notes = trim($_POST['notes'] ?? '');

$allowed_reasons = ['spam', 'fake', 'inappropriate', 'other'];
if ($listing_id <= 0 || !in_array($reason, $allowed_reasons, true)) {
    header('Location: /omr-buy-sell/');
    exit;
}

$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_reports'");
if (!$check || $check->num_rows === 0) {
    header('Location: /omr-buy-sell/report-listing-omr.php?listing_id=' . $listing_id);
    exit;
}

$stmt = $conn->prepare("INSERT INTO omr_buy_sell_reports (listing_id, reason, notes, status) VALUES (?,?,?,'pending')");
$stmt->bind_param('iss', $listing_id, $reason, $notes);
$stmt->execute();

header('Location: report-listing-omr.php?listing_id=' . $listing_id . '&submitted=1');
exit;
