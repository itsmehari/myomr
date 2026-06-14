<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COMMUNITY_EVENTS_ADMIN_ROUTED', '/superadmin/community-events/view-listings.php');
require_once __DIR__ . '/_urls.php';
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['admin_csrf'])) { $_SESSION['admin_csrf'] = bin2hex(random_bytes(16)); }

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'feature') {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        $stmt = $conn->prepare('UPDATE event_listings SET featured = 1 - COALESCE(featured, 0) WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    $qs = array_diff_key($_GET, ['action' => '', 'id' => '']);
    header('Location: ' . COMMUNITY_EVENTS_ADMIN_LISTINGS_URL . ($qs ? '?' . http_build_query($qs) : ''));
    exit;
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
$allowed = ['scheduled','ongoing','ended','archived',''];
if (!in_array($status, $allowed, true)) { $status = ''; }

$rows = [];
try {
  global $conn;
  if ($conn && !$conn->connect_error) {
    if ($status !== '') {
      $stmt = $conn->prepare("SELECT id, title, slug, start_datetime, end_datetime, status, location, featured FROM event_listings WHERE status = ? ORDER BY start_datetime DESC LIMIT 200");
      $stmt->bind_param('s', $status);
      $stmt->execute();
      $res = $stmt->get_result();
    } else {
      $res = $conn->query("SELECT id, title, slug, start_datetime, end_datetime, status, location, featured FROM event_listings ORDER BY start_datetime DESC LIMIT 200");
    }
    while ($r = $res->fetch_assoc()) { $rows[] = $r; }
  }
} catch (Throwable $e) {
  error_log('Events Admin view listings error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approved Listings – Events Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Approved Listings</h1>
      <div>
        <a class="btn btn-outline-secondary btn-sm" href="<?= htmlspecialchars(COMMUNITY_EVENTS_ADMIN_INDEX_URL, ENT_QUOTES, 'UTF-8') ?>">Dashboard</a>
      </div>
    </div>

    <form class="mb-3" method="get">
      <div class="row g-2 align-items-end">
        <div class="col-auto">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="" <?php echo $status===''?'selected':''; ?>>All</option>
            <option value="scheduled" <?php echo $status==='scheduled'?'selected':''; ?>>Scheduled</option>
            <option value="ongoing" <?php echo $status==='ongoing'?'selected':''; ?>>Ongoing</option>
            <option value="ended" <?php echo $status==='ended'?'selected':''; ?>>Ended</option>
            <option value="archived" <?php echo $status==='archived'?'selected':''; ?>>Archived (Paused)</option>
          </select>
        </div>
        <div class="col-auto">
          <button class="btn btn-primary">Apply</button>
        </div>
      </div>
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Start</th>
              <th>Status</th>
              <th>Featured</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">No listings found</td></tr>
            <?php else: foreach ($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r['id']; ?></td>
                <td>
                  <div class="fw-semibold"><?php echo htmlspecialchars($r['title']); ?></div>
                  <div class="small text-muted">@ <?php echo htmlspecialchars($r['location']); ?></div>
                </td>
                <td><?php echo htmlspecialchars($r['start_datetime']); ?></td>
                <td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($r['status']); ?></span></td>
                <td><?php echo !empty($r['featured']) ? '<span class="badge bg-info"><i class="fas fa-star"></i> Featured</span>' : '—'; ?></td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-secondary" target="_blank" href="../event-detail-omr.php?slug=<?php echo urlencode($r['slug']); ?>">View</a>
                    <a class="btn btn-outline-warning" href="?action=feature&amp;id=<?php echo (int)$r['id']; ?><?php echo $status !== '' ? '&amp;status=' . urlencode($status) : ''; ?>" title="Toggle featured"><i class="fas fa-star"></i></a>
                    <?php if ($r['status'] !== 'archived'): ?>
                      <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-pause-listing.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline">
                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                        <input type="hidden" name="mode" value="pause">
                        <button class="btn btn-warning" type="submit">Pause</button>
                      </form>
                    <?php else: ?>
                      <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-pause-listing.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline">
                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                        <input type="hidden" name="mode" value="resume">
                        <button class="btn btn-success" type="submit">Resume</button>
                      </form>
                    <?php endif; ?>
                    <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-unapprove-listing.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline">
                      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                      <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                      <button class="btn btn-outline-primary" type="submit">Move to Unapproved</button>
                    </form>
                    <form method="post" action="<?= htmlspecialchars(community_events_admin_process_url('process-delete-listing.php'), ENT_QUOTES, 'UTF-8') ?>" class="d-inline" onsubmit="return confirm('Delete this listing?');">
                      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                      <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                      <button class="btn btn-outline-danger" type="submit">Delete</button>
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
</body>
</html>


