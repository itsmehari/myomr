<?php
/**
 * Admin: list job seeker profiles (résumé + contact) — CSV export
 */
require_once __DIR__ . '/_bootstrap.php';

require_once dirname(__DIR__) . '/core/omr-connect.php';

$table_ok = false;
$tc = $conn->query("SHOW TABLES LIKE 'job_seeker_profiles'");
if ($tc && $tc->num_rows > 0) {
    $table_ok = true;
}

if ($table_ok && isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="job-seeker-profiles-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
    $ccRes = $conn->query("SHOW COLUMNS FROM job_seeker_profiles LIKE 'consent_contact'");
    $hasConsent = $ccRes && $ccRes->num_rows > 0;
    if ($hasConsent) {
        fputcsv($out, ['id', 'full_name', 'email', 'phone', 'preferred_locality', 'experience_level', 'headline', 'consent_contact', 'consent_at', 'created_at', 'updated_at', 'resume_path']);
        $q = $conn->query('SELECT id, full_name, email, phone, preferred_locality, experience_level, headline, consent_contact, consent_at, created_at, updated_at, resume_path FROM job_seeker_profiles ORDER BY updated_at DESC');
    } else {
        fputcsv($out, ['id', 'full_name', 'email', 'phone', 'preferred_locality', 'experience_level', 'headline', 'created_at', 'updated_at', 'resume_path']);
        $q = $conn->query('SELECT id, full_name, email, phone, preferred_locality, experience_level, headline, created_at, updated_at, resume_path FROM job_seeker_profiles ORDER BY updated_at DESC');
    }
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            if ($hasConsent) {
                fputcsv($out, [
                    $r['id'], $r['full_name'], $r['email'], $r['phone'],
                    $r['preferred_locality'] ?? '', $r['experience_level'] ?? '', $r['headline'] ?? '',
                    $r['consent_contact'] ?? '', $r['consent_at'] ?? '',
                    $r['created_at'], $r['updated_at'], $r['resume_path'],
                ]);
            } else {
                fputcsv($out, [
                    $r['id'], $r['full_name'], $r['email'], $r['phone'],
                    $r['preferred_locality'] ?? '', $r['experience_level'] ?? '', $r['headline'] ?? '',
                    $r['created_at'], $r['updated_at'], $r['resume_path'],
                ]);
            }
        }
    }
    fclose($out);
    exit;
}

$rows = [];
$hasConsentCol = false;
if ($table_ok) {
    $cr0 = $conn->query("SHOW COLUMNS FROM job_seeker_profiles LIKE 'consent_contact'");
    $hasConsentCol = $cr0 && $cr0->num_rows > 0;
    $sql = $hasConsentCol
        ? 'SELECT id, full_name, email, phone, preferred_locality, experience_level, headline, consent_contact, created_at, updated_at, resume_path FROM job_seeker_profiles ORDER BY updated_at DESC LIMIT 500'
        : 'SELECT id, full_name, email, phone, preferred_locality, experience_level, headline, created_at, updated_at, resume_path FROM job_seeker_profiles ORDER BY updated_at DESC LIMIT 500';
    $q = $conn->query($sql);
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $rows[] = $r;
        }
    }
}

include __DIR__ . '/layout/header.php';
?>
  <h1 class="h3 mb-3">Job seeker profiles</h1>
  <p class="text-muted">Résumés uploaded via <code>candidate-profile-omr.php</code>. Download files individually; CSV export has metadata only.</p>
  <?php if (!$table_ok): ?>
    <div class="alert alert-warning">Table <code>job_seeker_profiles</code> not found. Run <code>dev-tools/migrations/2026-03-31-job-seeker-profiles.sql</code>.</div>
  <?php else: ?>
    <p><a href="?export=csv" class="btn btn-success btn-sm">Download CSV</a></p>
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Locality</th>
            <th>Outreach OK</th>
            <th>Updated</th>
            <th>Résumé</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['preferred_locality'] ?? '') ?></td>
            <td><?= ($hasConsentCol && !empty($r['consent_contact'])) ? 'Yes' : '—' ?></td>
            <td><?= htmlspecialchars($r['updated_at'] ?? '') ?></td>
            <td><a href="download-job-seeker-resume-omr.php?id=<?= (int)$r['id'] ?>">Download</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if (count($rows) >= 500): ?>
      <p class="small text-muted">Showing latest 500 rows.</p>
    <?php endif; ?>
  <?php endif; ?>
  <p class="mt-3"><a href="/admin/index.php">← Admin home</a></p>
<?php include __DIR__ . '/layout/footer.php'; ?>
