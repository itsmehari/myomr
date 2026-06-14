<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/security-helpers.php';

$slno = isset($_GET['slno']) ? (int)$_GET['slno'] : 0;
if ($slno <= 0) {
    header('Location: atms-list.php');
    exit;
}

$stmt = $conn->prepare('SELECT slno, bankname, address FROM omr_atms WHERE slno = ?');
$stmt->bind_param('i', $slno);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) {
    $_SESSION['flash_error'] = 'ATM not found.';
    header('Location: atms-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: atms-edit.php?slno=' . $slno);
        exit;
    }
    $bankname = trim($_POST['bankname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $upd = $conn->prepare('UPDATE omr_atms SET bankname = ?, address = ? WHERE slno = ?');
    $upd->bind_param('ssi', $bankname, $address, $slno);
    if ($upd->execute()) {
        $_SESSION['flash_success'] = 'ATM updated.';
    } else {
        $_SESSION['flash_error'] = 'Update failed.';
    }
    $upd->close();
    header('Location: atms-edit.php?slno=' . $slno);
    exit;
}

$pageTitle = 'Edit ATM';
$activeNav = '/superadmin/atms-list.php';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3"><?php echo htmlspecialchars($row['bankname']); ?></h5>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
      <div class="mb-3">
        <label class="form-label" for="bankname">Bank</label>
        <input type="text" class="form-control" id="bankname" name="bankname" value="<?php echo htmlspecialchars($row['bankname']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($row['address']); ?></textarea>
      </div>
      <button type="submit" class="btn btn-success">Save</button>
      <a href="atms-list.php" class="btn btn-secondary ms-2">Back to list</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
