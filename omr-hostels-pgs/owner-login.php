<?php
/**
 * Property Owner Login – uses module bootstrap and root layout.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/owner-auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'my-properties.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rate limiting: 5 attempts per hour per email
    $attemptKey = 'login_attempts_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $hourAgo = time() - 3600;
    
    if (!isset($_SESSION[$attemptKey])) {
        $_SESSION[$attemptKey] = [];
    }
    $recentAttempts = array_filter($_SESSION[$attemptKey], function($timestamp) use ($hourAgo) {
        return $timestamp > $hourAgo;
    });
    
    if (count($recentAttempts) >= 5) {
        $error = 'Too many login attempts. Please try again in an hour.';
    } else {
        $email = trim($_POST['email'] ?? '');
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION[$attemptKey][] = time();
            $_SESSION[$attemptKey] = array_slice($_SESSION[$attemptKey], -10); // Keep last 10
            
            if (ownerLogin($email)) {
                // Clear attempts on successful login
                unset($_SESSION[$attemptKey]);
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Login failed. Please try again.';
            }
        } else {
            $error = 'Please enter a valid email address.';
        }
    }
}
$page_nav = 'main';
$page_title = 'Property Owner Login - MyOMR Hostels & PGs';
$page_description = 'Sign in to manage your hostel or PG listings on MyOMR.';
$canonical_url = 'https://myomr.in/omr-hostels-pgs/owner-login.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-hostels-pgs/', 'Hostels & PGs in OMR'],
    [$canonical_url, 'Owner Login'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/omr-hostels-pgs/assets/hostels-pgs.css">
</head>
<body class="modern-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container">
        <div class="text-center text-white">
            <div class="mb-3">
                <div class="form-section-icon" style="margin: 0 auto;">
                    <i class="fas fa-home"></i>
                </div>
            </div>
            <h1 class="hero-modern-title">Property Owner Login</h1>
            <p class="hero-modern-subtitle">Sign in using your email to manage your properties</p>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card-modern">
                    <div class="p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" novalidate class="needs-validation" id="owner-login-form">
                            <div class="form-group-modern mb-4">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required placeholder="your@email.com">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> We'll send inquiry notifications to this email</div>
                            </div>

                            <button type="submit" class="btn-modern btn-modern-primary w-100">
                                <i class="fas fa-right-to-bracket"></i>
                                <span>Login</span>
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="mb-0 text-center text-muted">
                            New property owner? List a property directly and we'll create your profile.
                            <br>
                            <a href="add-property.php" class="text-primary fw-semibold">Add a Property</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function gtagSend(name, params){
    try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
  }
  document.getElementById('owner-login-form')?.addEventListener('submit', function(){
    const email = document.getElementById('email')?.value || '';
    gtagSend('owner_login_submit', { method: 'email', email_length: email.length });
  });
</script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>
</body>
</html>

