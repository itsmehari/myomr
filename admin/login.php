<?php
require_once __DIR__ . '/../core/admin-auth.php';

$error = '';

// CSRF token
if (empty($_SESSION['admin_login_csrf'])) {
    $_SESSION['admin_login_csrf'] = bin2hex(random_bytes(16));
}

// Simple rate limiting (per session)
$limitWindow = 600; // 10 minutes
$maxAttempts = 5;

if (!isset($_SESSION['admin_login_attempts'])) {
    $_SESSION['admin_login_attempts'] = [];
}

// Remove stale attempts
$_SESSION['admin_login_attempts'] = array_filter(
    $_SESSION['admin_login_attempts'],
    function ($ts) use ($limitWindow) { return (time() - $ts) <= $limitWindow; }
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['admin_login_csrf'], (string)$token)) {
        $error = 'Session expired. Please reload and try again.';
    } else {
        if (count($_SESSION['admin_login_attempts']) >= $maxAttempts) {
            $error = 'Too many attempts. Please wait and try again.';
        } else {
            $username = trim((string)($_POST['username'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            if (attemptAdminLogin($username, $password)) {
                // reset attempts
                $_SESSION['admin_login_attempts'] = [];
                unset($_SESSION['admin_login_csrf']);
                $to = isset($_GET['redirect']) ? $_GET['redirect'] : '/admin/index.php';
                header('Location: ' . $to);
                exit;
            } else {
                $error = 'Invalid credentials. Please try again.';
                $_SESSION['admin_login_attempts'][] = time();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login – MyOMR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="h4 mb-3 text-center">MyOMR Admin Login</h1>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <form method="post" autocomplete="off">
              <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_login_csrf']); ?>">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required autofocus>
              </div>
              <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

