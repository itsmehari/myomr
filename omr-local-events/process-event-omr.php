<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';
require_once __DIR__ . '/includes/organizer-manage.php';
require_once __DIR__ . '/../core/mailer.php';

session_start();

function deny($msg) {
  http_response_code(400);
  echo '<div class="container py-5"><div class="alert alert-danger">' . htmlspecialchars($msg) . '</div></div>';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { deny('Invalid request.'); }
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) { deny('Invalid CSRF token.'); }
// Honeypot
if (!empty($_POST['website'])) { deny('Spam detected.'); }
// Basic rate limiting: 1 per 60s per session
if (!empty($_SESSION['last_event_submit']) && (time() - $_SESSION['last_event_submit'] < 60)) {
  deny('Please wait a minute before submitting another event.');
}

$data = [
  'title' => sanitizeInput($_POST['title'] ?? ''),
  'category_id' => (int)($_POST['category_id'] ?? 0),
  'location' => sanitizeInput($_POST['location'] ?? ''),
  'locality' => sanitizeInput($_POST['locality'] ?? ''),
  'start_datetime' => $_POST['start_datetime'] ?? '',
  'end_datetime' => $_POST['end_datetime'] ?? '',
  'organizer_name' => sanitizeInput($_POST['organizer_name'] ?? ''),
  'organizer_email' => sanitizeInput($_POST['organizer_email'] ?? ''),
  'organizer_phone' => sanitizeInput($_POST['organizer_phone'] ?? ''),
  'tickets_url' => sanitizeInput($_POST['tickets_url'] ?? ''),
  'description' => $_POST['description'] ?? '',
  'is_free' => isset($_POST['is_free']) ? (int)$_POST['is_free'] : 1,
  'price' => sanitizeInput($_POST['price'] ?? ''),
];

$errors = validateEventSubmission($data);
if (!empty($errors)) {
  deny('Please correct the form and resubmit.');
}

$slug = generateSlug($data['title']);

// Handle poster upload (optional)
$image_url = '';
if (!empty($_FILES['poster']['name']) && is_uploaded_file($_FILES['poster']['tmp_name'])) {
  $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $_FILES['poster']['tmp_name']);
  finfo_close($finfo);
  $sizeOk = $_FILES['poster']['size'] <= 2 * 1024 * 1024; // 2MB
  if (!isset($allowed[$mime]) || !$sizeOk) {
    deny('Invalid poster file. Only JPG/PNG/WebP up to 2MB.');
  }
  $ext = $allowed[$mime];
  $dir = __DIR__ . '/../uploads/events';
  if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
  $fname = $slug . '-' . time() . '.' . $ext;
  $dest = $dir . '/' . $fname;
  if (!move_uploaded_file($_FILES['poster']['tmp_name'], $dest)) {
    deny('Failed to store poster.');
  }
  $image_url = '/omr-local-events/uploads/events/' . $fname;
}

try {
  global $conn;
  if (!isset($conn) || !$conn || $conn->connect_error) { deny('Database connection error.'); }

  $sql = "INSERT INTO event_submissions (title, slug, category_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, image_url, description, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, 'submitted')";
  $stmt = $conn->prepare($sql);
  $is_all_day = 0;
  // Use NULL for category when 0 to avoid FK errors
  $cat = ($data['category_id'] > 0) ? $data['category_id'] : NULL;
  $stmt->bind_param(
    'ssisssssssiissss',
    $data['title'],
    $slug,
    $cat,
    $data['organizer_name'],
    $data['organizer_email'],
    $data['organizer_phone'],
    $data['location'],
    $data['locality'],
    $data['start_datetime'],
    $data['end_datetime'],
    $is_all_day,
    $data['is_free'],
    $data['price'],
    $data['tickets_url'],
    $image_url,
    $data['description']
  );
  if (!$stmt->execute()) {
    error_log('Events: stmt execute error: ' . $stmt->error);
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
      deny('DB error: ' . $stmt->error);
    }
    deny('Submission failed. Please try again later.');
  }

  $newId = $conn->insert_id;
  // TEMP: auto-approve to listings to unblock testing
  if (!function_exists('approveSubmissionToListing')) {
    require_once __DIR__ . '/includes/event-functions-omr.php';
  }
  @approveSubmissionToListing((int)$newId);
  $_SESSION['last_event_submit'] = time();
  $token = eventsGenerateManageToken((int)$newId, $data['organizer_email']);

  // Email organizer with manage link (best-effort)
  if (!empty($data['organizer_email']) && filter_var($data['organizer_email'], FILTER_VALIDATE_EMAIL)) {
    $manageUrl = 'https://myomr.in/omr-local-events/manage-submission.php?id=' . (int)$newId . '&t=' . urlencode($token);
    $subject = 'Your event submission to MyOMR';
    $html = '<p>Hi ' . htmlspecialchars($data['organizer_name'] ?: 'Organizer') . ',</p>' .
            '<p>Thank you for submitting <strong>' . htmlspecialchars($data['title']) . '</strong> to MyOMR.</p>' .
            '<p>You can review or edit your submission (until approved) using this secure link:</p>' .
            '<p><a href="' . htmlspecialchars($manageUrl) . '">' . htmlspecialchars($manageUrl) . '</a></p>' .
            '<p>— MyOMR Team</p>';
    @myomrSendMail($data['organizer_email'], $subject, $html);
  }

  header('Location: event-submitted-success-omr.php?id=' . (int)$newId . '&t=' . urlencode($token));
  exit;
} catch (Throwable $e) {
  error_log('Events: submission insert failed: ' . $e->getMessage());
  deny('Submission failed. Please try again later.');
}


