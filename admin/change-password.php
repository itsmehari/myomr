<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
$title = 'Change Password';
$breadcrumbs = ['Change Password' => null];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($current_password === '' || $new_password === '' || $confirm_password === '') {
        $error = 'Please fill in all fields.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New password and confirmation do not match.';
    } elseif (strlen($new_password) < 8) {
        $error = 'New password must be at least 8 characters long.';
    } else {
        // Verify current password
        $stmt = $conn->prepare('SELECT password FROM admin_users WHERE username = ? LIMIT 1');
        $username = 'admin'; // Assuming single admin for now
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($current_password, $row['password'])) {
                // Update with new hashed password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare('UPDATE admin_users SET password = ? WHERE username = ?');
                $update_stmt->bind_param('ss', $hashed_password, $username);
                if ($update_stmt->execute()) {
                    $success = 'Password updated successfully!';
                    session_unset();
                    session_destroy();
                    header('Location: /admin/login.php');
                    exit;
                } else {
                    $error = 'Error updating password.';
                }
                $update_stmt->close();
            } else {
                $error = 'Current password is incorrect.';
            }
        } else {
            $error = 'Admin user not found.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .sidebar { min-height: 100vh; background: #14532d; color: #fff; }
        .sidebar a { color: #fff; display: block; padding: 1rem; border-bottom: 1px solid #1e6b3a; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: #22c55e; color: #14532d; font-weight: bold; }
        .main-content { padding: 2rem; }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include __DIR__ . '/admin-header.php'; ?>
      <?php include __DIR__ . '/admin-breadcrumbs.php'; ?>
      <?php include __DIR__ . '/admin-flash.php'; ?>
      <h2 class="mb-4">Change Password</h2>
      <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
      <form method="POST" autocomplete="off" aria-label="Change Password">
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input type="password" class="form-control" id="current_password" name="current_password" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" class="form-control" id="new_password" name="new_password" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required aria-required="true">
        </div>
        <button type="submit" class="btn btn-success">Change Password</button>
      </form>
    </main>
  </div>
</div>
</body>
</html>
<?php $conn->close(); ?>