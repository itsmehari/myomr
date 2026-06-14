<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
require_once '../core/security-helpers.php';

$slno = isset($_GET['slno']) ? (int)$_GET['slno'] : 0;
if ($slno <= 0) { header('Location: it-companies-list.php'); exit; }

// Fetch record
$stmt = $conn->prepare('SELECT slno, company_name, address, locality, industry_type, about, services, careers_url, verified FROM omr_it_companies WHERE slno = ?');
$stmt->bind_param('i', $slno);
$stmt->execute();
$res = $stmt->get_result();
$company = $res ? $res->fetch_assoc() : null;
$stmt->close();
if (!$company) { $_SESSION['flash_error'] = 'Company not found.'; header('Location: it-companies-list.php'); exit; }

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: it-companies-edit.php?slno=' . $slno);
        exit;
    }
    if (($_POST['action'] ?? '') === 'toggle_verified') {
        $upd = $conn->prepare('UPDATE omr_it_companies SET verified = 1 - COALESCE(verified, 0) WHERE slno = ?');
        $upd->bind_param('i', $slno);
        if ($upd->execute()) {
            $_SESSION['flash_success'] = 'Verified status updated.';
        } else {
            $_SESSION['flash_error'] = 'Could not update verified status.';
        }
        $upd->close();
        header('Location: it-companies-edit.php?slno=' . $slno);
        exit;
    }
    $about = trim($_POST['about'] ?? '');
    $services = trim($_POST['services'] ?? '');
    $careers_url = trim($_POST['careers_url'] ?? '');
    if ($careers_url !== '' && !preg_match('#^https?://#i', $careers_url)) {
        $careers_url = 'https://' . $careers_url;
    }
    $upd = $conn->prepare('UPDATE omr_it_companies SET about = ?, services = ?, careers_url = ? WHERE slno = ?');
    $upd->bind_param('sssi', $about, $services, $careers_url, $slno);
    if ($upd->execute()) {
        $_SESSION['flash_success'] = 'Profile updated.';
    } else {
        $_SESSION['flash_error'] = 'Update failed: ' . $conn->error;
    }
    $upd->close();
    header('Location: it-companies-edit.php?slno=' . $slno);
    exit;
}

$pageTitle = $pageTitle ?? $title ?? 'Admin';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3"><?php echo htmlspecialchars($company['company_name']); ?></h5>
          <p class="text-muted mb-4">
            <strong>Industry:</strong> <?php echo htmlspecialchars($company['industry_type']); ?><br>
            <strong>Address:</strong> <?php echo htmlspecialchars($company['address']); ?><br>
            <strong>Verified:</strong> <?php echo !empty($company['verified']) ? 'Yes' : 'No'; ?>
          </p>
          <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="form-group">
              <label for="about">About</label>
              <textarea id="about" name="about" class="form-control" rows="5" maxlength="3000" placeholder="Short overview, specialties, scale, highlights..."><?php echo htmlspecialchars($company['about'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
              <label for="services">Services</label>
              <textarea id="services" name="services" class="form-control" rows="4" maxlength="3000" placeholder="Comma-separated or paragraph description of services."><?php echo htmlspecialchars($company['services'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
              <label for="careers_url">Careers URL</label>
              <input id="careers_url" type="url" name="careers_url" class="form-control" maxlength="255" placeholder="https://example.com/careers" value="<?php echo htmlspecialchars($company['careers_url'] ?? ''); ?>" />
              <small class="form-text text-muted">If empty, the site will fallback to a Google Careers search.</small>
            </div>
            <div class="d-flex justify-content-between flex-wrap gap-2">
              <div class="d-flex gap-2">
                <a class="btn btn-secondary" href="it-companies-list.php">Back to list</a>
                <a class="btn btn-outline-primary" href="featured-it-list.php">Featured IT</a>
              </div>
              <div class="d-flex gap-2">
                <button type="submit" name="action" value="save" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </form>
          <form method="post" class="mt-3">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <button type="submit" name="action" value="toggle_verified" class="btn btn-sm btn-<?php echo !empty($company['verified']) ? 'warning' : 'success'; ?>">
              <?php echo !empty($company['verified']) ? 'Mark unverified' : 'Mark verified'; ?>
            </button>
          </form>
        </div>
      </div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
