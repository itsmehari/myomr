<?php
require_once __DIR__ . '/_auth.php';

if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

if (isAdminLoggedIn()) {
    superadmin_redirect(superadmin_dashboard_url());
}

$error = '';
$username_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $username_value = $username;

    if (attemptAdminLogin($username, $password)) {
        $redirect = (string) ($_GET['redirect'] ?? '');
        superadmin_redirect(superadmin_normalize_redirect($redirect));
    }

    $error = 'Invalid credentials. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin Login – MyOMR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="h4 mb-3 text-center">MyOMR Superadmin</h1>
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($username_value, ENT_QUOTES, 'UTF-8') ?>" required autocomplete="username">
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required autocomplete="current-password">
              </div>
              <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
