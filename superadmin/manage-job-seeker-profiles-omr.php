<?php
/**
 * Admin: list job seeker profiles (résumé + contact) — CSV export
 */
require_once __DIR__ . '/_bootstrap.php';

require_once dirname(__DIR__) . '/core/omr-connect.php';
require_once dirname(__DIR__) . '/core/security-helpers.php';

$table_ok = false;
$tc = $conn->query("SHOW TABLES LIKE 'job_seeker_profiles'");
if ($tc && $tc->num_rows > 0) {
    $table_ok = true;
}

function jsp_admin_has_column(mysqli $conn, string $column): bool
{
    $stmt = $conn->prepare(
        'SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
         LIMIT 1'
    );
    if (!$stmt) {
        return false;
    }
    $table = 'job_seeker_profiles';
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $ok = (bool) $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $ok;
}

$hasConsent = $table_ok && jsp_admin_has_column($conn, 'consent_contact');
$hasOnboarding = $table_ok && jsp_admin_has_column($conn, 'onboarding_status');
$hasHidden = $table_ok && jsp_admin_has_column($conn, 'is_hidden');
$canSoftHide = $hasHidden || $hasOnboarding;
$showHidden = isset($_GET['show_hidden']) && $_GET['show_hidden'] === '1';

if ($table_ok && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_action'], $_POST['profile_id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: manage-job-seeker-profiles-omr.php');
        exit;
    }
    $profileId = (int)$_POST['profile_id'];
    $profileAction = $_POST['profile_action'];
    if ($profileId > 0 && in_array($profileAction, ['hide', 'restore'], true) && $canSoftHide) {
        if ($hasHidden) {
            $hiddenVal = $profileAction === 'hide' ? 1 : 0;
            $stmt = $conn->prepare('UPDATE job_seeker_profiles SET is_hidden = ? WHERE id = ?');
            $stmt->bind_param('ii', $hiddenVal, $profileId);
            $stmt->execute();
            $stmt->close();
        } elseif ($hasOnboarding) {
            $statusVal = $profileAction === 'hide' ? 'archived' : 'registered';
            $stmt = $conn->prepare('UPDATE job_seeker_profiles SET onboarding_status = ? WHERE id = ?');
            $stmt->bind_param('si', $statusVal, $profileId);
            $stmt->execute();
            $stmt->close();
        }
        $_SESSION['flash_success'] = $profileAction === 'hide' ? 'Profile hidden from admin list.' : 'Profile restored.';
    }
    $redirect = 'manage-job-seeker-profiles-omr.php';
    if ($showHidden) {
        $redirect .= '?show_hidden=1';
    }
    header('Location: ' . $redirect);
    exit;
}

if ($table_ok && isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="job-seeker-profiles-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
    $headers = ['id', 'full_name', 'email', 'phone', 'preferred_locality', 'experience_level', 'headline'];
    if ($hasOnboarding) {
        $headers[] = 'onboarding_status';
        $headers[] = 'registered_at';
        $headers[] = 'last_login_at';
    }
    if ($hasConsent) {
        $headers[] = 'consent_contact';
        $headers[] = 'consent_at';
    }
    $headers = array_merge($headers, ['created_at', 'updated_at', 'resume_path']);
    fputcsv($out, $headers);

    $cols = 'id, full_name, email, phone, preferred_locality, experience_level, headline';
    if ($hasOnboarding) {
        $cols .= ', onboarding_status, registered_at, last_login_at';
    }
    if ($hasConsent) {
        $cols .= ', consent_contact, consent_at';
    }
    $cols .= ', created_at, updated_at, resume_path';
    $q = $conn->query("SELECT {$cols} FROM job_seeker_profiles ORDER BY updated_at DESC");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            fputcsv($out, array_values($r));
        }
    }
    fclose($out);
    exit;
}

$rows = [];
if ($table_ok) {
    $cols = 'id, full_name, email, phone, preferred_locality, experience_level, headline';
    if ($hasOnboarding) {
        $cols .= ', onboarding_status, registered_at, last_login_at';
    }
    if ($hasConsent) {
        $cols .= ', consent_contact';
    }
    if ($hasHidden) {
        $cols .= ', is_hidden';
    }
    $cols .= ', created_at, updated_at, resume_path';
    $where = '1=1';
    if (!$showHidden && $canSoftHide) {
        if ($hasHidden) {
            $where .= ' AND COALESCE(is_hidden, 0) = 0';
        } elseif ($hasOnboarding) {
            $where .= " AND COALESCE(onboarding_status, 'registered') != 'archived'";
        }
    }
    $q = $conn->query("SELECT {$cols} FROM job_seeker_profiles WHERE {$where} ORDER BY updated_at DESC LIMIT 500");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $rows[] = $r;
        }
    }
}

$pageTitle = 'Job seeker profiles';
$activeNav = '/superadmin/manage-job-seeker-profiles-omr.php';
include __DIR__ . '/includes/admin-shell-open.php';
?>
  <h1 class="h3 mb-3">Job seeker profiles</h1>
  <p class="text-muted">Profiles via join/login and <code>candidate-profile-omr.php</code>. CSV export has metadata only.</p>
  <?php if (!$table_ok): ?>
    <div class="alert alert-warning">Table <code>job_seeker_profiles</code> not found. Run <code>dev-tools/migrations/2026-03-31-job-seeker-profiles.sql</code>.</div>
  <?php else: ?>
    <p>
      <a href="?export=csv" class="btn btn-success btn-sm">Download CSV</a>
      <?php if ($canSoftHide): ?>
        <?php if ($showHidden): ?>
          <a href="manage-job-seeker-profiles-omr.php" class="btn btn-outline-secondary btn-sm ms-1">Hide archived</a>
        <?php else: ?>
          <a href="?show_hidden=1" class="btn btn-outline-secondary btn-sm ms-1">Show hidden</a>
        <?php endif; ?>
      <?php endif; ?>
    </p>
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Locality</th>
            <?php if ($hasOnboarding): ?><th>Status</th><th>Registered</th><th>Last login</th><?php endif; ?>
            <?php if ($hasConsent): ?><th>Outreach OK</th><?php endif; ?>
            <th>Updated</th>
            <th>Résumé</th>
            <?php if ($canSoftHide): ?><th>Actions</th><?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['full_name'] ?? '—') ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['preferred_locality'] ?? '') ?></td>
            <?php if ($hasOnboarding): ?>
            <td><span class="badge bg-<?= ($r['onboarding_status'] ?? '') === 'complete' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($r['onboarding_status'] ?? 'registered') ?></span></td>
            <td class="small"><?= htmlspecialchars($r['registered_at'] ?? '—') ?></td>
            <td class="small"><?= htmlspecialchars($r['last_login_at'] ?? '—') ?></td>
            <?php endif; ?>
            <?php if ($hasConsent): ?>
            <td><?= !empty($r['consent_contact']) ? 'Yes' : '—' ?></td>
            <?php endif; ?>
            <td><?= htmlspecialchars($r['updated_at'] ?? '') ?></td>
            <td><?php if (!empty($r['resume_path'])): ?><a href="download-job-seeker-resume-omr.php?id=<?= (int)$r['id'] ?>">Download</a><?php else: ?>—<?php endif; ?></td>
            <?php if ($canSoftHide): ?>
            <td>
              <?php
                $isHiddenRow = $hasHidden
                    ? !empty($r['is_hidden'])
                    : (($r['onboarding_status'] ?? '') === 'archived');
              ?>
              <form method="post" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                <input type="hidden" name="profile_id" value="<?= (int)$r['id'] ?>">
                <?php if ($isHiddenRow): ?>
                  <button type="submit" name="profile_action" value="restore" class="btn btn-sm btn-outline-success">Restore</button>
                <?php else: ?>
                  <button type="submit" name="profile_action" value="hide" class="btn btn-sm btn-outline-warning" onclick="return confirm('Hide this profile from the admin list?');">Hide</button>
                <?php endif; ?>
              </form>
            </td>
            <?php endif; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if (count($rows) >= 500): ?>
      <p class="small text-muted">Showing latest 500 rows.</p>
    <?php endif; ?>
  <?php endif; ?>
  <p class="mt-3"><a href="/superadmin/index.php">← Command Center</a></p>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
