<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
  if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { $_SESSION['flash_error'] = 'Invalid CSRF token'; header('Location: manage-atms.php'); exit; }
  $id = (int)($_POST['id'] ?? 0);
  $del = $conn->prepare('DELETE FROM omr_atms WHERE slno = ?');
  $del->bind_param('i', $id);
  $del->execute();
  $del->close();
  $_SESSION['flash_success'] = 'ATM deleted';
  header('Location: manage-atms.php'); exit;
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = 'SELECT slno, bankname, address, contact, landmark FROM omr_atms';
if ($q !== '') { $esc = '%' . $conn->real_escape_string($q) . '%'; $sql .= " WHERE bankname LIKE '" . $esc . "' OR address LIKE '" . $esc . "'"; }
$sql .= ' ORDER BY bankname ASC LIMIT 200';
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage ATMs - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background:#f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <h2>ATMs</h2>
        <form class="form-inline" method="get">
          <input type="text" name="q" class="form-control mr-2" placeholder="Search" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
          <button class="btn btn-primary">Search</button>
        </form>
      </div>
      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark"><tr><th>#</th><th>Bank</th><th>Address</th><th>Contact</th><th>Landmark</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if ($res && $res->num_rows > 0): while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?php echo (int)$row['slno']; ?></td>
              <td><?php echo htmlspecialchars($row['bankname']); ?></td>
              <td><?php echo htmlspecialchars($row['address']); ?></td>
              <td><?php echo htmlspecialchars($row['contact']); ?></td>
              <td><?php echo htmlspecialchars($row['landmark']); ?></td>
              <td>
                <form method="post" onsubmit="return confirm('Delete this ATM?');" class="d-inline">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int)$row['slno']; ?>">
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="6" class="text-center">No results</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
</body>
</html>


