<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (ca_user_id() !== null) {
    header('Location: /omr-classified-ads/');
    exit;
}

require_once ROOT_PATH . '/core/security-helpers.php';
require_once __DIR__ . '/includes/oauth-google.php';
ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$reg_prefill = $_SESSION['ca_reg_prefill'] ?? [];
unset($_SESSION['ca_reg_prefill']);

$page_title = 'Register | OMR Classified Ads | MyOMR';
$canonical_url = 'https://myomr.in/omr-classified-ads/register-omr.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="robots" content="noindex">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container ca-auth-wrap">
  <div class="ca-auth-card">
  <p class="ca-auth-kicker">OMR Classified Ads</p>
  <h1 class="ca-display mb-2 fs-3">Create account</h1>
  <p class="text-muted small mb-4">For posting on OMR Classified Ads. Already have one? <a href="/omr-classified-ads/login-omr.php">Log in</a></p>

  <?php if (isset($_GET['check_email'])): ?>
  <div class="alert alert-info">Check your email for a verification link (or use <code>MYOMR_CA_AUTO_VERIFY=1</code> / dev mode for instant verify).</div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['ca_reg_errors'])): $re = $_SESSION['ca_reg_errors']; unset($_SESSION['ca_reg_errors']); ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($re as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
  <?php endif; ?>

  <?php if (ca_google_oauth_config()): ?>
  <a href="/omr-classified-ads/auth/google-start-omr.php" class="btn btn-outline-dark w-100 mb-3 py-2"><i class="fab fa-google me-2" aria-hidden="true"></i>Continue with Google</a>
  <p class="ca-auth-divider">register with email</p>
  <?php endif; ?>

  <form method="post" action="process-register-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <div class="mb-3">
      <label class="form-label" for="display_name">Display name</label>
      <input type="text" name="display_name" id="display_name" class="form-control" required maxlength="120" value="<?= htmlspecialchars($reg_prefill['display_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($reg_prefill['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label" for="password">Password (min 8 characters)</label>
      <input type="password" name="password" id="password" class="form-control" required minlength="8" autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
  </form>
  </div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
