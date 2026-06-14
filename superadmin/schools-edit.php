<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
require_once '../core/security-helpers.php';

$slno = isset($_GET['slno']) ? (int)$_GET['slno'] : 0;
if ($slno <= 0) { header('Location: schools-list.php'); exit; }

// Fetch
$stmt = $conn->prepare('SELECT slno, schoolname, address, locality, about, services, careers_url FROM omrschoolslist WHERE slno = ?');
$stmt->bind_param('i', $slno);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();
if (!$row) { $_SESSION['flash_error'] = 'School not found.'; header('Location: schools-list.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: schools-edit.php?slno=' . $slno);
        exit;
    }
    $about = trim($_POST['about'] ?? '');
    $services = trim($_POST['services'] ?? '');
    $careers_url = trim($_POST['careers_url'] ?? '');
    if ($careers_url !== '' && !preg_match('#^https?://#i', $careers_url)) { $careers_url = 'https://' . $careers_url; }
    $upd = $conn->prepare('UPDATE omrschoolslist SET about = ?, services = ?, careers_url = ? WHERE slno = ?');
    $upd->bind_param('sssi', $about, $services, $careers_url, $slno);
    if ($upd->execute()) { $_SESSION['flash_success'] = 'Profile updated.'; } else { $_SESSION['flash_error'] = 'Update failed: ' . $conn->error; }
    $upd->close();
    header('Location: schools-edit.php?slno=' . $slno);
    exit;
}

$pageTitle = $pageTitle ?? $title ?? 'Admin';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3"><?php echo htmlspecialchars($row['schoolname']); ?></h5>
          <p class="text-muted mb-4">
            <strong>Locality:</strong> <?php echo htmlspecialchars($row['locality'] ?? ''); ?><br>
            <strong>Address:</strong> <?php echo htmlspecialchars($row['address'] ?? ''); ?>
          </p>
          <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="form-group">
              <label for="about">About</label>
              <textarea id="about" name="about" class="form-control" rows="5" maxlength="3000" placeholder="Overview, programs, facilities..."><?php echo htmlspecialchars($row['about'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
              <label for="services">Programs / Services</label>
              <textarea id="services" name="services" class="form-control" rows="4" maxlength="3000" placeholder="CBSE/ICSE/IB, co-curriculars, transport, etc."><?php echo htmlspecialchars($row['services'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
              <label for="careers_url">Careers URL</label>
              <input id="careers_url" type="url" name="careers_url" class="form-control" maxlength="255" placeholder="https://example.com/careers" value="<?php echo htmlspecialchars($row['careers_url'] ?? ''); ?>" />
            </div>
            <div class="d-flex justify-content-between">
              <a class="btn btn-secondary" href="schools-list.php">Back</a>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
