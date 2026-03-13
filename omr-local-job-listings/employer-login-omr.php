<?php
/**
 * Employer Login - Email-based session login
 * Uses site modular bootstrap (same as index.php) so nav and footer render correctly.
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/employer-auth.php';

// Modular bootstrap — same pattern as root index.php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'my-posted-jobs-omr.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (employerLogin($email)) {
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Login failed. Please try again.';
        }
    } else {
        $error = 'Please enter a valid email address.';
    }
}

// Page meta for modular meta.php and head-includes
$page_nav = 'main';
$page_title = 'Employer Login - MyOMR Job Portal';
$page_description = 'Sign in using your email to manage job postings on MyOMR.';
$canonical_url = 'https://myomr.in/omr-local-job-listings/employer-login-omr.php';
if (!empty($_GET['redirect'])) {
    $canonical_url .= '?redirect=' . rawurlencode($_GET['redirect']);
}
$og_type = 'website';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <!-- Job portal overrides and form styles (Bootstrap 5 + job CSS) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/post-job-form-modern.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
</head>
<body class="modern-page">

<?php omr_nav($page_nav); ?>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container">
        <div class="text-center text-white">
            <div class="mb-3">
                <div class="form-section-icon" style="margin: 0 auto;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <h1 class="hero-modern-title">Employer Login</h1>
            <p class="hero-modern-subtitle">Sign in using your email to manage job postings</p>
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

                        <form method="POST" novalidate class="needs-validation" id="employer-login-form">
                            <div class="form-group-modern mb-4">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required placeholder="your@email.com">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> We'll send application notifications to this email</div>
                            </div>

                            <button type="submit" class="btn-modern btn-modern-primary w-100">
                                <i class="fas fa-right-to-bracket"></i>
                                <span>Login</span>
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="mb-0 text-center text-muted">
                            New employer? Post a job directly and we'll create your employer profile.
                            <br>
                            <a href="post-job-omr.php" class="text-primary fw-semibold">Post a Job</a>
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
  document.getElementById('employer-login-form')?.addEventListener('submit', function(){
    const email = document.getElementById('email')?.value || '';
    gtagSend('employer_login_submit', { method: 'email', email_length: email.length });
  });
</script>
</body>
</html>
