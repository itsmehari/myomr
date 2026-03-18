<?php
/**
 * OMR Buy-Sell — Process Listing Submission
 */
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: post-listing-omr.php');
    exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

require_once __DIR__ . '/includes/rate-limit.php';
if (!checkBuySellRateLimit()) {
    $_SESSION['post_listing_errors'] = ['You have reached the posting limit (5 ads per 24 hours). Please try again later.'];
    header('Location: post-listing-omr.php');
    exit;
}

require_once __DIR__ . '/includes/captcha-config.php';
if (isCaptchaEnabled() && !verifyRecaptcha($_POST['g-recaptcha-response'] ?? '')) {
    $_SESSION['post_listing_errors'] = ['Please complete the CAPTCHA.'];
    header('Location: post-listing-omr.php');
    exit;
}

global $conn;

$errors = [];
$category_id = (int)($_POST['category_id'] ?? 0);
$title = sanitizeInputBuySell($_POST['title'] ?? '');
$description = sanitizeInputBuySell($_POST['description'] ?? '');
$price = isset($_POST['price']) && $_POST['price'] !== '' ? (float)$_POST['price'] : null;
$price_negotiable = isset($_POST['price_negotiable']) ? 1 : 0;
$locality = sanitizeInputBuySell($_POST['locality'] ?? '');
$seller_name = sanitizeInputBuySell($_POST['seller_name'] ?? '');
$seller_phone = sanitizeInputBuySell($_POST['seller_phone'] ?? '');
$seller_email = filter_var(trim($_POST['seller_email'] ?? ''), FILTER_VALIDATE_EMAIL);

if ($category_id <= 0) $errors[] = 'Please select a category.';
if (strlen($title) < 5) $errors[] = 'Title must be at least 5 characters.';
if (strlen($description) < 20) $errors[] = 'Description must be at least 20 characters.';
if ($price !== null && ($price < 0 || $price > 999999999)) $errors[] = 'Invalid price.';
if (empty($locality)) $errors[] = 'Locality is required.';
if (empty($seller_name) || strlen($seller_name) < 2) $errors[] = 'Please provide your name.';
if (empty($seller_phone) || strlen($seller_phone) < 10) $errors[] = 'Please provide a valid phone number.';
if (!$seller_email) $errors[] = 'Please provide a valid email.';

require_once dirname(__DIR__) . '/core/omr-localities-buy-sell.php';
$allowed_locality = getBuySellLocalities();
if (!in_array($locality, $allowed_locality, true)) $errors[] = 'Invalid locality.';

$categories = getBuySellCategories();
$cat_ids = array_column($categories, 'id');
if (!in_array($category_id, $cat_ids, true)) $errors[] = 'Invalid category.';

if (!empty($errors)) {
    $_SESSION['post_listing_errors'] = $errors;
    $_SESSION['post_listing_prefill'] = [
        'category_id' => $category_id,
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'price_negotiable' => $price_negotiable,
        'locality' => $locality,
        'seller_name' => $seller_name,
        'seller_phone' => $seller_phone,
        'seller_email' => $seller_email ?: ($_POST['seller_email'] ?? ''),
    ];
    header('Location: post-listing-omr.php');
    exit;
}

$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_sellers'");
if (!$check || $check->num_rows === 0) {
    die('Buy-Sell module not set up. Run migration: dev-tools/migrations/run_2026_03_14_create_omr_buy_sell_tables.sql');
}

$stmt = $conn->prepare("INSERT INTO omr_buy_sell_sellers (name, email, phone, password_hash, status) VALUES (?,?,?,NULL,'pending')
    ON DUPLICATE KEY UPDATE name = VALUES(name), phone = VALUES(phone)");
$stmt->bind_param('sss', $seller_name, $seller_email, $seller_phone);
$stmt->execute();
$seller_id = (int)$conn->insert_id;
if ($seller_id === 0) {
    $st = $conn->prepare("SELECT id FROM omr_buy_sell_sellers WHERE email = ?");
    $st->bind_param('s', $seller_email);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();
    $seller_id = $r ? (int)$r['id'] : 0;
}

if ($seller_id <= 0) {
    die('Could not create or find seller.');
}

$slug = createSlugBuySell($title);
if (strlen($slug) === 0) $slug = 'item';
$expiry_date = date('Y-m-d', strtotime('+30 days'));

$upload_dir = __DIR__ . '/uploads/images/' . date('Y') . '/' . date('m');
$image_paths = [];

if (isset($_FILES['images']['name']) && is_array($_FILES['images']['name']) && !empty($_FILES['images']['name'][0])) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $max_size = 2 * 1024 * 1024;
    $files = $_FILES['images'];
    $count = min(5, count($files['name']));

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $tmp = $files['tmp_name'][$i];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        if (!in_array($mime, $allowed, true)) continue;
        if ($files['size'][$i] > $max_size) continue;

        $ext = $mime === 'image/jpeg' ? 'jpg' : ($mime === 'image/png' ? 'png' : 'webp');
        $fname = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $upload_dir . '/' . $fname;
        if (move_uploaded_file($tmp, $dest)) {
            $image_paths[] = 'uploads/images/' . date('Y') . '/' . date('m') . '/' . $fname;
        }
    }
}

$images_json = empty($image_paths) ? null : json_encode($image_paths);

$slug_final = $slug;
$idx = 0;
while (true) {
    $dup = $conn->prepare("SELECT id FROM omr_buy_sell_listings WHERE slug = ?");
    $dup->bind_param('s', $slug_final);
    $dup->execute();
    if ($dup->get_result()->num_rows === 0) break;
    $slug_final = $slug . '-' . (++$idx);
}

$price_val = $price !== null ? (float)$price : null;
$stmt = $conn->prepare("INSERT INTO omr_buy_sell_listings
    (seller_id, category_id, title, slug, description, price, price_negotiable, locality, contact_phone, contact_email, images, status, expiry_date)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,'pending',?)");
$stmt->bind_param('iissdissssss',
    $seller_id, $category_id, $title, $slug_final, $description,
    $price_val, $price_negotiable, $locality, $seller_phone, $seller_email,
    $images_json, $expiry_date
);
$stmt->execute();

recordBuySellPost();

header('Location: listing-posted-success-omr.php?ga=post_listing_submit');
exit;
