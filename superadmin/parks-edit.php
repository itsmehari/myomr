<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/security-helpers.php';

$slno = isset($_GET['slno']) ? (int)$_GET['slno'] : 0;
if ($slno <= 0) {
    header('Location: parks-list.php');
    exit;
}

$stmt = $conn->prepare('SELECT slno, parkname, location, features FROM omrparkslist WHERE slno = ?');
$stmt->bind_param('i', $slno);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) {
    $_SESSION['flash_error'] = 'Park not found.';
    header('Location: parks-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: parks-edit.php?slno=' . $slno);
        exit;
    }
    $parkname = trim($_POST['parkname'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $upd = $conn->prepare('UPDATE omrparkslist SET parkname = ?, location = ?, features = ? WHERE slno = ?');
    $upd->bind_param('sssi', $parkname, $location, $features, $slno);
    if ($upd->execute()) {
        $_SESSION['flash_success'] = 'Park updated.';
    } else {
        $_SESSION['flash_error'] = 'Update failed.';
    }
    $upd->close();
    header('Location: parks-edit.php?slno=' . $slno);
    exit;
}

$pageTitle = 'Edit Park';
$activeNav = '/superadmin/parks-list.php';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3"><?php echo htmlspecialchars($row['parkname']); ?></h5>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
      <div class="mb-3">
        <label class="form-label" for="parkname">Name</label>
        <input type="text" class="form-control" id="parkname" name="parkname" value="<?php echo htmlspecialchars($row['parkname']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="location">Location</label>
        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($row['location']); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label" for="features">Features</label>
        <textarea class="form-control" id="features" name="features" rows="4"><?php echo htmlspecialchars($row['features'] ?? ''); ?></textarea>
      </div>
      <button type="submit" class="btn btn-success">Save</button>
      <a href="parks-list.php" class="btn btn-secondary ms-2">Back to list</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
