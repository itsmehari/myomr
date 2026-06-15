<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COMMUNITY_EVENTS_ADMIN_ROUTED', '/superadmin/community-events/manage-events-omr.php');
require_once __DIR__ . '/_urls.php';
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
// Simple admin guard placeholder (replace with session-based admin check)
// if (empty($_SESSION['admin_logged_in'])) { die('Unauthorized'); }

// Load pending submissions
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['admin_csrf'])) { $_SESSION['admin_csrf'] = bin2hex(random_bytes(16)); }

$pending = [];
try {
  global $conn;
  if ($conn && !$conn->connect_error) {
    $sql = "SELECT id, title, organizer_name, organizer_email, start_datetime, location, status, created_at FROM event_submissions WHERE status IN ('submitted','draft') ORDER BY created_at DESC LIMIT 100";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) { $pending[] = $row; }
  }
} catch (Throwable $e) {
  error_log('Events Admin: failed to load submissions: ' . $e->getMessage());
}

$__modulePageTitle = 'Manage Events – Admin';
$__moduleActiveNav = '/superadmin/community-events/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Manage Events – Admin</title></head>
<body>
<?php } ?>
<div class="container py-4">
  <h1 class="h3 mb-4">Manage Events – Submissions</h1>
  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Organizer</th>
            <th>Start</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pending)): ?>
            <tr><td colspan="7" class="text-center text-muted">No pending submissions</td></tr>
          <?php else: foreach ($pending as $p): ?>
            <tr>
              <td><?php echo (int)$p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['title']); ?></td>
              <td><?php echo htmlspecialchars($p['organizer_name'] ?: $p['organizer_email'] ?: '—'); ?></td>
              <td><?php echo htmlspecialchars($p['start_datetime']); ?></td>
              <td><?php echo htmlspecialchars($p['location']); ?></td>
              <td><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($p['status']); ?></span></td>
              <td>
                <div class="btn-group btn-group-sm">
                  <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-approve-event.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                    <button class="btn btn-success" title="Approve" type="submit">Approve</button>
                  </form>
                  <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-reject-event.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline ms-1">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                    <button class="btn btn-danger" title="Reject" type="submit">Reject</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
