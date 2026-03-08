<?php
require_once __DIR__ . '/includes/error-reporting.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function endWith($msg, $ga_event_js = '') {
  $ga_script = $ga_event_js
    ? '<script async src="https://www.googletagmanager.com/gtag/js?id=G-JYSF141J1H"></script>'
      . '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","G-JYSF141J1H");</script>'
      . '<script>' . $ga_event_js . '</script>'
    : '';
  echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
     . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><title>Subscribed</title>'
     . $ga_script . '</head><body class="p-4">'
     . '<div class="container"><div class="alert alert-info">' . htmlspecialchars($msg) . '</div>'
     . '<a class="btn btn-primary" href="/omr-local-events/index.php">Back to Events</a></div></body></html>';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); endWith('Invalid request.'); }
$token = $_POST['csrf'] ?? '';
if (empty($_SESSION['events_newsletter_csrf']) || !hash_equals($_SESSION['events_newsletter_csrf'], (string)$token)) {
  endWith('Session expired. Please go back and try again.');
}
if (!empty($_POST['website'])) { endWith('Thanks!'); }

$email = trim((string)($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { endWith('Please enter a valid email.'); }

$dir = __DIR__ . '/../weblog';
@mkdir($dir, 0755, true);
$file = $dir . '/events-newsletter-signups.csv';
$row = date('c') . ',' . str_replace(["\n","\r",","], ' ', $email) . "\n";
@file_put_contents($file, $row, FILE_APPEND | LOCK_EX);

unset($_SESSION['events_newsletter_csrf']);
endWith(
  'Subscribed! You will receive weekly OMR events.',
  '(function(){if(typeof gtag==="function"){gtag("event","subscribe",{"conversion_type":"events_newsletter"});}})();'
);


