<?php
/**
 * MyOMR Rent & Lease — Process Property Submission
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/property-functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-property-omr.php');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

$errors = [];

$listing_type = sanitizeInput($_POST['listing_type'] ?? '');
$title = sanitizeInput($_POST['title'] ?? '');
$locality = sanitizeInput($_POST['locality'] ?? '');
$monthly_rent = !empty($_POST['monthly_rent']) ? (float)$_POST['monthly_rent'] : 0;
$deposit = !empty($_POST['deposit']) ? (float)$_POST['deposit'] : 0;
$property_details = sanitizeInput($_POST['property_details'] ?? '');
$owner_name = sanitizeInput($_POST['owner_name'] ?? '');
$owner_phone = sanitizeInput($_POST['owner_phone'] ?? '');
$owner_email = sanitizeInput($_POST['owner_email'] ?? '');

$allowed_types = ['rent-house','rent-apartment','rent-land','lease-commercial','sell-house','sell-plot'];
if (!in_array($listing_type, $allowed_types, true)) $errors[] = 'Invalid listing type.';
if (strlen($title) < 5) $errors[] = 'Title must be at least 5 characters.';
if (empty($locality)) $errors[] = 'Locality is required.';
if (strlen($property_details) < 20) $errors[] = 'Property details must be at least 20 characters.';
if (empty($owner_name) || empty($owner_phone) || empty($owner_email)) $errors[] = 'Contact details required.';

if (!empty($errors)) {
    $_SESSION['property_errors'] = $errors;
    header('Location: add-property-omr.php');
    exit;
}

// Check tables exist
$check = $conn->query("SHOW TABLES LIKE 'rent_lease_owners'");
if (!$check || $check->num_rows === 0) {
    die('Rent & Lease module not set up. Run migration: dev-tools/migrations/run_2026_03_10_create_rent_lease_tables.sql');
}

// Upsert owner (by email)
$stmt = $conn->prepare("INSERT INTO rent_lease_owners (name, email, phone, status) VALUES (?,?,?,'pending') ON DUPLICATE KEY UPDATE name=VALUES(name), phone=VALUES(phone)");
$stmt->bind_param('sss', $owner_name, $owner_email, $owner_phone);
$stmt->execute();

$owner_id = (int)$conn->insert_id;
if ($owner_id === 0) {
    $st = $conn->prepare("SELECT id FROM rent_lease_owners WHERE email = ?");
    $st->bind_param('s', $owner_email);
    $st->execute();
    $res = $st->get_result();
    $owner_id = $res && ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;
}
if ($owner_id <= 0) {
    die('Could not create/find owner.');
}

// Insert property
$stmt = $conn->prepare("INSERT INTO rent_lease_properties (owner_id, listing_type, title, locality, property_details, monthly_rent, deposit, contact_phone, contact_email, status) VALUES (?,?,?,?,?,?,?,?,?,'pending')");
$stmt->bind_param('isssddsss', $owner_id, $listing_type, $title, $locality, $property_details, $monthly_rent, $deposit, $owner_phone, $owner_email);
$stmt->execute();

header('Location: property-posted-success-omr.php');
exit;
