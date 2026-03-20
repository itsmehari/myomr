<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (ca_user_id() !== null) {
    header('Location: /omr-classified-ads/');
    exit;
}

require_once ROOT_PATH . '/core/security-helpers.php';
require_once __DIR__ . '/includes/oauth-google.php';
ca_session_start(); // before reading ca_login_redirect
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title = 'Log in | OMR Classified Ads | MyOMR';
$canonical_url = 'https://myomr.in/omr-classified-ads/login-omr.php';
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
  <h1 class="ca-display mb-2 fs-3">Log in</h1>
  <p class="text-muted small mb-4">New here? <a href="/omr-classified-ads/register-omr.php">Register</a></p>

  <?php
    $oauth_err = $_GET['oauth'] ?? '';
    $oauth_msgs = [
        'missing_config' => 'Google sign-in is not configured on this server yet.',
        'bad_state' => 'Sign-in session expired. Try again.',
        'token_fail' => 'Could not complete Google sign-in.',
        'no_email' => 'Google did not return an email. Use another method.',
        'email_linked_other' => 'This email is already linked to a different Google account.',
    ];
    if ($oauth_err && isset($oauth_msgs[$oauth_err])): ?>
  <div class="alert alert-warning small"><?= htmlspecialchars($oauth_msgs[$oauth_err]) ?></div>
  <?php endif; ?>

  <?php
    $pe = $_GET['phone_err'] ?? '';
    if ($pe === 'invalid') {
        echo '<div class="alert alert-danger small">Enter a valid mobile number.</div>';
    } elseif ($pe === 'rate') {
        echo '<div class="alert alert-danger small">Too many codes sent. Wait an hour or try later.</div>';
    } elseif ($pe === 'sms_config') {
        echo '<div class="alert alert-warning small">SMS is not configured (Twilio env vars). Ask the site admin.</div>';
    } elseif ($pe === 'session') {
        echo '<div class="alert alert-warning small">OTP session expired. Request a new code.</div>';
    } elseif ($pe === 'conflict') {
        echo '<div class="alert alert-danger small">Account conflict. Contact support.</div>';
    }
  ?>

  <?php if (isset($_GET['need_verify'])): ?>
  <div class="alert alert-warning">Please verify your email before posting. Check your inbox or register again.</div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['ca_login_error'])): $le = $_SESSION['ca_login_error']; unset($_SESSION['ca_login_error']); ?>
  <div class="alert alert-danger"><?= htmlspecialchars($le) ?></div>
  <?php endif; ?>

  <?php
    $login_redirect = $_GET['redirect'] ?? ($_SESSION['ca_login_redirect'] ?? '/omr-classified-ads/');
    if (!is_string($login_redirect) || strpos($login_redirect, '/') !== 0) {
        $login_redirect = '/omr-classified-ads/';
    }
    unset($_SESSION['ca_login_redirect']);
    $google_ok = ca_google_oauth_config() !== null;
  ?>

  <?php if ($google_ok): ?>
  <a href="/omr-classified-ads/auth/google-start-omr.php" class="btn btn-outline-dark w-100 mb-3 py-2"><i class="fab fa-google me-2" aria-hidden="true"></i>Continue with Google</a>
  <p class="ca-auth-divider">or</p>
  <?php endif; ?>

  <p class="mb-3"><a href="/omr-classified-ads/phone-login-omr.php" class="btn btn-outline-primary w-100 py-2">Log in with phone (OTP)</a></p>
  <p class="ca-auth-divider">email</p>

  <form method="post" action="process-login-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($login_redirect) ?>">
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email" name="email" id="email" class="form-control" required autocomplete="username">
    </div>
    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2">Log in</button>
  </form>
  </div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
