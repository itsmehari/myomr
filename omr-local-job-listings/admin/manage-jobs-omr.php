<?php
/**
 * Admin - Manage Jobs (Approve/Reject)
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/email.php';

// CSRF token
if (empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(16));
}

// Handle approve/reject action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = (int)($_POST['job_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    
    // CSRF check
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['admin_csrf'], (string)$token)) {
        http_response_code(403);
        exit('Invalid session token. Please refresh the page and try again.');
    }

    // Bulk operation
    if ($action === 'bulk' && isset($_POST['bulk_action']) && is_array($_POST['ids'] ?? null)) {
        $bulkAction = $_POST['bulk_action'];
        if (in_array($bulkAction, ['approve', 'reject'], true)) {
            foreach ($_POST['ids'] as $rawId) {
                $jid = (int)$rawId;
                if ($jid <= 0) { continue; }
                $status = $bulkAction === 'approve' ? 'approved' : 'rejected';
                // fetch employer + title
                $infoStmt = $conn->prepare("SELECT j.title, e.email FROM job_postings j INNER JOIN employers e ON j.employer_id = e.id WHERE j.id = ? LIMIT 1");
                $infoStmt->bind_param("i", $jid);
                $infoStmt->execute();
                $infoRes = $infoStmt->get_result();
                $info = $infoRes->fetch_assoc();
                // update
                $u = $conn->prepare("UPDATE job_postings SET status = ? WHERE id = ?");
                $u->bind_param("si", $status, $jid);
                $u->execute();
                // audit
                $adminUser = $_SESSION['admin_role'] ?? 'admin';
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                $audit = $conn->prepare("INSERT INTO admin_audit_log (admin_user, action, entity_type, entity_id, old_value, new_value, ip_address) VALUES (?, 'job_status_update', 'job', ?, '', ?, ?)");
                $audit->bind_param("sisss", $adminUser, $jid, $status, $ip);
                $audit->execute();
                // email
                if ($info && !empty($info['email'])) {
                    $body = renderEmailTemplate('Job ' . ucfirst($status), '<h2>Your job was ' . ucfirst($status) . '</h2><p><strong>Job:</strong> ' . htmlspecialchars($info['title']) . '</p>');
                    @sendEmail($info['email'], 'Job ' . ucfirst($status) . ' - ' . $info['title'], $body);
                }
            }
            header('Location: manage-jobs-omr.php?success=1');
            exit;
        }
    }

    if ($job_id > 0 && in_array($action, ['approve', 'reject'])) {
        $status = $action === 'approve' ? 'approved' : 'rejected';

        // Fetch employer email + job title
        $infoStmt = $conn->prepare("SELECT j.title, e.email FROM job_postings j INNER JOIN employers e ON j.employer_id = e.id WHERE j.id = ? LIMIT 1");
        $infoStmt->bind_param("i", $job_id);
        $infoStmt->execute();
        $infoRes = $infoStmt->get_result();
        $info = $infoRes->fetch_assoc();

        $stmt = $conn->prepare("UPDATE job_postings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $job_id);
        $stmt->execute();

        // Audit log
        $adminUser = $_SESSION['admin_role'] ?? 'admin';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $audit = $conn->prepare("INSERT INTO admin_audit_log (admin_user, action, entity_type, entity_id, old_value, new_value, ip_address) VALUES (?, ?, 'job', ?, ?, ?, ?)");
        $oldVal = '';
        $newVal = $status;
        $act = 'job_status_update';
        $audit->bind_param("ssisss", $adminUser, $act, $job_id, $oldVal, $newVal, $ip);
        $audit->execute();

        if ($info && !empty($info['email'])) {
            $body = renderEmailTemplate(
                'Job ' . ucfirst($status),
                '<h2>Your job was ' . ucfirst($status) . '</h2>' .
                '<p><strong>Job:</strong> ' . htmlspecialchars($info['title']) . '</p>'
            );
            @sendEmail($info['email'], 'Job ' . ucfirst($status) . ' - ' . $info['title'], $body);
        }

        header('Location: manage-jobs-omr.php?success=1');
        exit;
    }
}

// Get all jobs
$result = $conn->query("SELECT j.*, e.company_name, e.email as employer_email 
                        FROM job_postings j 
                        JOIN employers e ON j.employer_id = e.id 
                        ORDER BY j.created_at DESC");
$jobs = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - Admin Dashboard | MyOMR</title>
    <?php include '../../components/analytics.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/job-listings-omr.css">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-tasks me-2"></i>Manage Job Postings</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Job status updated successfully!</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <form method="POST" class="mb-3" onsubmit="return confirm('Apply action to selected jobs?');">
                <input type="hidden" name="action" value="bulk">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <select name="bulk_action" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Bulk action</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                    </select>
                    <button class="btn btn-sm btn-primary" type="submit">Apply</button>
                </div>
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Applications</th>
                    <th>Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?php if ($job['status']==='pending'): ?><input type="checkbox" name="ids[]" value="<?php echo (int)$job['id']; ?>" class="row-check"><?php endif; ?></td>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                        <td>
                            <?php
                                $status = $job['status'];
                                $badge = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'closed' => 'dark'][$status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($status); ?></span>
                        </td>
                        <td><?php echo (int)$job['views']; ?></td>
                        <td><?php echo (int)$job['applications_count']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($job['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if ($job['status'] === 'pending'): ?>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Approve this job?');">
                                        <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Reject this job?');">
                                        <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i></button>
                                    </form>
                                <?php endif; ?>
                                <a href="../job-detail-omr.php?id=<?php echo (int)$job['id']; ?>" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                <a href="view-applications-omr.php?id=<?php echo (int)$job['id']; ?>" class="btn btn-outline-primary"><i class="fas fa-inbox"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function gtagSend(name, params){
  try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
}
// Individual approve/reject buttons
document.addEventListener('submit', function(e){
  const form = e.target;
  if (!(form instanceof HTMLFormElement)) return;
  const act = form.querySelector('input[name="action"]')?.value;
  const jobId = form.querySelector('input[name="job_id"]')?.value || '';
  if (act === 'approve' || act === 'reject') {
    gtagSend('admin_job_' + act, { job_id: jobId });
  }
}, true);
// Bulk apply form
document.addEventListener('DOMContentLoaded', function(){
  const bulkForm = document.querySelector('form input[name="action"][value="bulk"]')?.closest('form');
  if (bulkForm) {
    bulkForm.addEventListener('submit', function(){
      const bulkAction = bulkForm.querySelector('select[name="bulk_action"]').value || '';
      const ids = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
      if (bulkAction === 'approve' || bulkAction === 'reject') {
        gtagSend('admin_job_bulk_' + bulkAction, { count: ids.length });
      }
    });
  }
});
</script>
<script>
document.getElementById('checkAll')?.addEventListener('change', function(){
  document.querySelectorAll('.row-check').forEach(cb => { cb.checked = this.checked; });
});
</script>
</body>
</html>

