<?php
/**
 * OMR Classified Ads — process post
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';
require_once ROOT_PATH . '/core/omr-localities-classified-ads.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: post-listing-omr.php');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

ca_require_login();
ca_require_verified_email();

require_once __DIR__ . '/includes/rate-limit.php';
if (!checkClassifiedAdsPostRateLimit()) {
    $_SESSION['ca_post_errors'] = ['Posting limit: 5 ads per 24 hours from this network. Try again later.'];
    header('Location: post-listing-omr.php');
    exit;
}

global $conn;

$uid = ca_user_id();
$errors = [];
$category_id = (int) ($_POST['category_id'] ?? 0);
$title = sanitizeClassifiedAdsInput($_POST['title'] ?? '');
$description = sanitizeClassifiedAdsInput($_POST['description'] ?? '');
$price = isset($_POST['price']) && $_POST['price'] !== '' ? (float) $_POST['price'] : null;
$locality = sanitizeClassifiedAdsInput($_POST['locality'] ?? '');
$contact_phone = sanitizeClassifiedAdsInput($_POST['contact_phone'] ?? '');
$contact_email = filter_var(trim($_POST['contact_email'] ?? ''), FILTER_VALIDATE_EMAIL);

if ($category_id <= 0) {
    $errors[] = 'Please select a category.';
}
if (strlen($title) < 5) {
    $errors[] = 'Title must be at least 5 characters.';
}
if (strlen($description) < 20) {
    $errors[] = 'Description must be at least 20 characters.';
}
if ($price !== null && ($price < 0 || $price > 999999999)) {
    $errors[] = 'Invalid price.';
}
$allowed_loc = getClassifiedAdsLocalities();
if (empty($locality) || !in_array($locality, $allowed_loc, true)) {
    $errors[] = 'Please select a valid location.';
}
if (empty($contact_phone) || strlen(preg_replace('/\D/', '', $contact_phone)) < 10) {
    $errors[] = 'Please provide a valid phone number.';
}

$categories = getClassifiedAdsCategories();
$cat_ids = array_column($categories, 'id');
if (!in_array($category_id, $cat_ids, true)) {
    $errors[] = 'Invalid category.';
}

if (!empty($errors)) {
    $_SESSION['ca_post_errors'] = $errors;
    $_SESSION['ca_post_prefill'] = [
        'category_id' => $category_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'locality' => $locality,
        'contact_phone' => $contact_phone,
        'contact_email' => $contact_email ?: ($_POST['contact_email'] ?? ''),
    ];
    header('Location: post-listing-omr.php');
    exit;
}

$check = $conn->query("SHOW TABLES LIKE 'omr_classified_ads_listings'");
if (!$check || $check->num_rows === 0) {
    die('OMR Classified Ads not set up. Run migration: dev-tools/migrations/2026_03_20_create_omr_classified_ads.sql');
}

$slug = createSlugClassifiedAds($title);
if (strlen($slug) === 0) {
    $slug = 'listing';
}
$slug_final = $slug;
$idx = 0;
while (true) {
    $dup = $conn->prepare('SELECT id FROM omr_classified_ads_listings WHERE slug = ?');
    $dup->bind_param('s', $slug_final);
    $dup->execute();
    if ($dup->get_result()->num_rows === 0) {
        break;
    }
    $slug_final = $slug . '-' . (++$idx);
}

$price_val = $price !== null ? (float) $price : null;
$email_bind = $contact_email ?: '';

if ($price_val === null) {
    $stmt = $conn->prepare(
        'INSERT INTO omr_classified_ads_listings
        (user_id, category_id, title, slug, description, price, locality, contact_phone, contact_email, status, expiry_date)
        VALUES (?,?,?,?,?,NULL,?,?,?,\'pending\',NULL)'
    );
    $stmt->bind_param('iisssss', $uid, $category_id, $title, $slug_final, $description, $locality, $contact_phone, $email_bind);
} else {
    $stmt = $conn->prepare(
        'INSERT INTO omr_classified_ads_listings
        (user_id, category_id, title, slug, description, price, locality, contact_phone, contact_email, status, expiry_date)
        VALUES (?,?,?,?,?,?,?,?,?,\'pending\',NULL)'
    );
    $stmt->bind_param('iissdssss', $uid, $category_id, $title, $slug_final, $description, $price_val, $locality, $contact_phone, $email_bind);
}
$stmt->execute();
$new_id = (int) $conn->insert_id;

recordClassifiedAdsPost();

header('Location: listing-posted-success-omr.php?id=' . $new_id);
exit;
