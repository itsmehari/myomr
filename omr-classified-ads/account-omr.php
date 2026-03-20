<?php
/**
 * Account — link Google (redirect) and phone (OTP).
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/oauth-google.php';
require_once ROOT_PATH . '/core/security-helpers.php';

ca_require_login();

ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$u = ca_current_user();
$google_on = ca_google_oauth_config() !== null;
$page_title = 'Your account | OMR Classified Ads | MyOMR';
$step2 = isset($_GET['phone_step']) && $_GET['phone_step'] === '2';
$pending = $_SESSION['ca_phone_otp_pending'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="robots" content="noindex">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container py-4" style="max-width:560px">
  <h1 class="h3 mb-4">Your account</h1>

  <?php if (isset($_GET['phone_ok'])): ?>
  <div class="alert alert-success">Phone number linked.</div>
  <?php endif; ?>
  <?php if (isset($_GET['phone_err'])): ?>
  <div class="alert alert-danger small"><?= htmlspecialchars($_GET['phone_err'] === 'taken' ? 'That number is already on another account.' : 'Something went wrong.') ?></div>
  <?php endif; ?>

  <ul class="list-group mb-4">
    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($u['email'] ?? '') ?></li>
    <li class="list-group-item"><strong>Display name:</strong> <?= htmlspecialchars($u['display_name'] ?? '') ?></li>
    <li class="list-group-item"><strong>Email verified:</strong> <?= !empty($u['email_verified']) ? 'Yes' : 'No' ?></li>
    <li class="list-group-item"><strong>Google:</strong> <?= !empty($u['google_sub']) ? 'Connected' : 'Not connected' ?></li>
    <li class="list-group-item"><strong>Phone:</strong> <?= !empty($u['phone_verified']) && !empty($u['phone_e164']) ? htmlspecialchars($u['phone_e164']) : 'Not linked' ?></li>
  </ul>

  <?php if ($google_on && empty($u['google_sub'])): ?>
  <p><a href="/omr-classified-ads/auth/google-start-omr.php" class="btn btn-outline-dark"><i class="fab fa-google me-2"></i>Connect Google</a></p>
  <?php elseif (!$google_on): ?>
  <p class="text-muted small">Google sign-in is not configured (set MYOMR_CLASSIFIED_GOOGLE_CLIENT_ID / SECRET on the server).</p>
  <?php endif; ?>

  <h2 class="h5 mt-4">Link phone (OTP)</h2>
  <?php if (!$step2 || $pending === ''): ?>
  <form method="post" action="/omr-classified-ads/send-phone-otp-omr.php" class="mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="intent" value="link">
    <div class="mb-2">
      <label class="form-label" for="phone">Mobile</label>
      <input type="tel" name="phone" id="phone" class="form-control" required placeholder="9876543210">
    </div>
    <button type="submit" class="btn btn-primary">Send OTP</button>
  </form>
  <?php else: ?>
  <p class="small text-muted">Code sent to <?= htmlspecialchars($pending) ?></p>
  <form method="post" action="/omr-classified-ads/verify-phone-otp-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <div class="mb-2">
      <label class="form-label" for="code">6-digit code</label>
      <input type="text" name="code" id="code" class="form-control" maxlength="6" pattern="[0-9]{6}" required>
    </div>
    <button type="submit" class="btn btn-primary">Verify</button>
    <a href="/omr-classified-ads/account-omr.php" class="btn btn-link">Cancel</a>
  </form>
  <?php endif; ?>

  <p class="mt-4"><a href="/omr-classified-ads/">Back to classifieds</a> · <a href="/omr-classified-ads/logout-omr.php">Log out</a></p>
</main>

<?php omr_footer(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
