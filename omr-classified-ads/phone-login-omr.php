<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (ca_user_id() !== null) {
    header('Location: /omr-classified-ads/');
    exit;
}

require_once ROOT_PATH . '/core/security-helpers.php';
ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$step = isset($_GET['step']) && $_GET['step'] === '2' ? 2 : 1;
$pending = $_SESSION['ca_phone_otp_pending'] ?? '';

$page_title = 'Log in with phone | OMR Classified Ads | MyOMR';
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

<main id="main-content" class="container py-4" style="max-width:480px">
  <h1 class="h3 mb-3">Log in with phone</h1>
  <p class="text-muted small mb-4"><a href="/omr-classified-ads/login-omr.php">Email login</a> · <a href="/omr-classified-ads/register-omr.php">Register</a></p>

  <?php if (isset($_GET['phone_err'])): ?>
  <div class="alert alert-danger small">Could not verify code. Try again or request a new code.</div>
  <?php endif; ?>

  <?php if ($step === 2 && $pending === ''): ?>
  <div class="alert alert-warning">Session expired. Start again.</div>
  <?php $step = 1; endif; ?>

  <?php if ($step === 1): ?>
  <form method="post" action="/omr-classified-ads/send-phone-otp-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="intent" value="login">
    <div class="mb-3">
      <label class="form-label" for="phone">Mobile number</label>
      <input type="tel" name="phone" id="phone" class="form-control" required placeholder="9876543210 or +919876543210" autocomplete="tel">
    </div>
    <button type="submit" class="btn btn-primary w-100">Send OTP</button>
  </form>
  <?php else: ?>
  <p class="small text-muted mb-3">Code sent to <strong><?= htmlspecialchars($pending) ?></strong></p>
  <form method="post" action="/omr-classified-ads/verify-phone-otp-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <div class="mb-3">
      <label class="form-label" for="code">6-digit code</label>
      <input type="text" name="code" id="code" class="form-control" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code">
    </div>
    <button type="submit" class="btn btn-primary w-100">Verify &amp; log in</button>
  </form>
  <p class="mt-3 small"><a href="/omr-classified-ads/phone-login-omr.php">Start over</a></p>
  <?php endif; ?>
</main>

<?php omr_footer(); ?>
</body>
</html>
