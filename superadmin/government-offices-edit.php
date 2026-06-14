<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/security-helpers.php';

$slno = isset($_GET['slno']) ? (int)$_GET['slno'] : 0;
if ($slno <= 0) {
    header('Location: government-offices-list.php');
    exit;
}

$stmt = $conn->prepare('SELECT slno, office_name, address, contact, landmark FROM omr_gov_offices WHERE slno = ?');
$stmt->bind_param('i', $slno);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) {
    $_SESSION['flash_error'] = 'Government office not found.';
    header('Location: government-offices-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: government-offices-edit.php?slno=' . $slno);
        exit;
    }
    $action = $_POST['action'] ?? 'save';
    if ($action === 'delete') {
        $del = $conn->prepare('DELETE FROM omr_gov_offices WHERE slno = ?');
        $del->bind_param('i', $slno);
        if ($del->execute()) {
            $_SESSION['flash_success'] = 'Government office deleted.';
        } else {
            $_SESSION['flash_error'] = 'Delete failed.';
        }
        $del->close();
        header('Location: government-offices-list.php');
        exit;
    }
    $office_name = trim($_POST['office_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $landmark = trim($_POST['landmark'] ?? '');
    if ($office_name === '') {
        $_SESSION['flash_error'] = 'Office name is required.';
        header('Location: government-offices-edit.php?slno=' . $slno);
        exit;
    }
    $upd = $conn->prepare('UPDATE omr_gov_offices SET office_name = ?, address = ?, contact = ?, landmark = ? WHERE slno = ?');
    $upd->bind_param('ssssi', $office_name, $address, $contact, $landmark, $slno);
    if ($upd->execute()) {
        $_SESSION['flash_success'] = 'Government office updated.';
    } else {
        $_SESSION['flash_error'] = 'Update failed.';
    }
    $upd->close();
    header('Location: government-offices-edit.php?slno=' . $slno);
    exit;
}

$pageTitle = 'Edit Government Office';
$activeNav = '/superadmin/government-offices-list.php';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Edit government office</h5>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
      <input type="hidden" name="action" value="save">
      <div class="mb-3">
        <label class="form-label" for="office_name">Office name</label>
        <input type="text" class="form-control" id="office_name" name="office_name" value="<?php echo htmlspecialchars($row['office_name']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($row['address'] ?? ''); ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="contact">Contact</label>
        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($row['contact'] ?? ''); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label" for="landmark">Landmark</label>
        <input type="text" class="form-control" id="landmark" name="landmark" value="<?php echo htmlspecialchars($row['landmark'] ?? ''); ?>">
      </div>
      <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="government-offices-list.php" class="btn btn-secondary">Back to list</a>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </div>
    </form>
    <form method="post" class="mt-3" onsubmit="return confirm('Delete this government office?');">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
      <input type="hidden" name="action" value="delete">
      <button type="submit" class="btn btn-outline-danger btn-sm">Delete office</button>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
