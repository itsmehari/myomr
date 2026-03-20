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
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container ca-auth-wrap ca-auth-wrap--wide">
  <div class="ca-auth-card">
  <p class="ca-auth-kicker">OMR Classified Ads</p>
  <h1 class="ca-display mb-4 fs-3">Your account</h1>

  <?php if (isset($_GET['phone_ok'])): ?>
  <div class="alert alert-success">Phone number linked.</div>
  <?php endif; ?>
  <?php if (isset($_GET['phone_err'])): ?>
  <div class="alert alert-danger small"><?= htmlspecialchars($_GET['phone_err'] === 'taken' ? 'That number is already on another account.' : 'Something went wrong.') ?></div>
  <?php endif; ?>

  <ul class="list-group list-group-flush mb-4 rounded-3 border ca-account-list">
    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-1"><span class="text-muted small">Email</span><span class="fw-semibold text-break"><?= htmlspecialchars($u['email'] ?? '') ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-1"><span class="text-muted small">Display name</span><span class="fw-semibold"><?= htmlspecialchars($u['display_name'] ?? '') ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-1"><span class="text-muted small">Email verified</span><span class="fw-semibold"><?= !empty($u['email_verified']) ? 'Yes' : 'No' ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-1"><span class="text-muted small">Google</span><span class="fw-semibold"><?= !empty($u['google_sub']) ? 'Connected' : 'Not connected' ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-1"><span class="text-muted small">Phone</span><span class="fw-semibold text-break"><?= !empty($u['phone_verified']) && !empty($u['phone_e164']) ? htmlspecialchars($u['phone_e164']) : 'Not linked' ?></span></li>
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

  <p class="mt-4 small"><a href="/omr-classified-ads/">Back to classifieds</a> · <a href="/omr-classified-ads/logout-omr.php">Log out</a></p>
  </div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
