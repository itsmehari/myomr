<?php
/**
 * Space Owner Login - Email-based session login
 */
// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/includes/owner-auth.php';

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'add-space.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Space Owner Login - MyOMR Coworking Spaces</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/coworking-spaces.css">
    
    <!-- Google Analytics -->
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container">
        <div class="text-center text-white">
            <div class="mb-3">
                <div class="form-section-icon" style="margin: 0 auto;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <h1 class="hero-modern-title">Space Owner Login</h1>
            <p class="hero-modern-subtitle">Sign in using your email to manage your workspaces</p>
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
                            New space owner? List a workspace directly and we'll create your profile.
                            <br>
                            <a href="add-space.php" class="text-primary fw-semibold">Add a Workspace</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
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
</body>
</html>

