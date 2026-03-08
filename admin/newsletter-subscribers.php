<?php
/**
 * Admin — Newsletter Subscribers
 * Lists all omr_newsletter_subscribers with search, status filter, and CSV export.
 */
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../core/omr-connect.php';

$page_title = 'Newsletter Subscribers';

// ── CSV Export ────────────────────────────────────────────────────────────────
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $rows = $conn->query(
        "SELECT email, locality, source_page, status, created_at
           FROM omr_newsletter_subscribers
          ORDER BY created_at DESC"
    );
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="omr-newsletter-subscribers-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email', 'Locality', 'Source Page', 'Status', 'Subscribed At']);
    if ($rows) {
        while ($r = $rows->fetch_assoc()) {
            fputcsv($out, [$r['email'], $r['locality'], $r['source_page'], $r['status'], $r['created_at']]);
        }
    }
    fclose($out);
    exit;
}

// ── Handle unsubscribe action ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['email'])) {
    $act   = $_POST['action'];
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if ($email && in_array($act, ['unsubscribe', 'reactivate'], true)) {
        $new_status = ($act === 'unsubscribe') ? 'unsubscribed' : 'active';
        $stmt = $conn->prepare(
            "UPDATE omr_newsletter_subscribers SET status = ?, updated_at = NOW() WHERE email = ?"
        );
        if ($stmt) {
            $stmt->bind_param('ss', $new_status, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: newsletter-subscribers.php?done=1');
    exit;
}

// ── Filters ───────────────────────────────────────────────────────────────────
$filter_status = in_array($_GET['status'] ?? '', ['active', 'unsubscribed'], true)
               ? $_GET['status']
               : '';
$search        = htmlspecialchars(trim($_GET['q'] ?? ''), ENT_QUOTES, 'UTF-8');

// ── Count ─────────────────────────────────────────────────────────────────────
$per_page  = 50;
$cur_page  = max(1, (int)($_GET['page'] ?? 1));
$offset    = ($cur_page - 1) * $per_page;

$where_parts = ['1=1'];
$bind_types  = '';
$bind_vals   = [];

if ($filter_status !== '') {
    $where_parts[] = 'status = ?';
    $bind_types   .= 's';
    $bind_vals[]   = $filter_status;
}
if ($search !== '') {
    $where_parts[] = '(email LIKE ? OR locality LIKE ?)';
    $bind_types   .= 'ss';
    $like          = '%' . $search . '%';
    $bind_vals[]   = $like;
    $bind_vals[]   = $like;
}
$where = 'WHERE ' . implode(' AND ', $where_parts);

$total = 0;
$count_sql = "SELECT COUNT(*) AS c FROM omr_newsletter_subscribers $where";
if ($bind_vals) {
    $s = $conn->prepare($count_sql);
    $s->bind_param($bind_types, ...$bind_vals);
    $s->execute();
    $total = (int)$s->get_result()->fetch_assoc()['c'];
    $s->close();
} else {
    $r = $conn->query($count_sql);
    if ($r) $total = (int)$r->fetch_assoc()['c'];
}
$total_pages = max(1, (int)ceil($total / $per_page));
$cur_page    = min($cur_page, $total_pages);
$offset      = ($cur_page - 1) * $per_page;

// ── Fetch rows ────────────────────────────────────────────────────────────────
$rows = [];
$data_sql = "SELECT id, email, locality, source_page, status, created_at
               FROM omr_newsletter_subscribers $where
              ORDER BY created_at DESC
              LIMIT ? OFFSET ?";
$full_types  = $bind_types . 'ii';
$full_vals   = array_merge($bind_vals, [$per_page, $offset]);
$s2 = $conn->prepare($data_sql);
$s2->bind_param($full_types, ...$full_vals);
$s2->execute();
$res = $s2->get_result();
while ($row = $res->fetch_assoc()) $rows[] = $row;
$s2->close();

// ── Summary stats ─────────────────────────────────────────────────────────────
$stats_res = $conn->query(
    "SELECT status, COUNT(*) AS c FROM omr_newsletter_subscribers GROUP BY status"
);
$stats = ['active' => 0, 'unsubscribed' => 0];
if ($stats_res) {
    while ($sr = $stats_res->fetch_assoc()) {
        $stats[$sr['status']] = (int)$sr['c'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?> | MyOMR Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f8fafc; }
    .admin-header { background: #14532d; color: #fff; padding: 1rem 1.5rem; }
    .admin-header h1 { font-size: 1.25rem; font-weight: 600; margin: 0; }
    .stat-chip { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 20px; text-align: center; }
    .stat-chip .num { font-size: 1.75rem; font-weight: 700; color: #14532d; }
    .stat-chip .lbl { font-size: .8rem; color: #6b7280; }
    .badge-active   { background: #dcfce7; color: #14532d; }
    .badge-unsub    { background: #fee2e2; color: #991b1b; }
  </style>
</head>
<body>

<div class="admin-header d-flex align-items-center justify-content-between">
  <h1><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($page_title) ?></h1>
  <div class="d-flex gap-2">
    <a href="newsletter-subscribers.php?export=csv<?= $filter_status ? '&status=' . urlencode($filter_status) : '' ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
       class="btn btn-sm btn-light fw-semibold">
      <i class="fas fa-download me-1"></i>Export CSV
    </a>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">
      <i class="fas fa-arrow-left me-1"></i>Dashboard
    </a>
  </div>
</div>

<div class="container-fluid py-4">

  <?php if (isset($_GET['done'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      Action completed. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="row g-3 mb-4">
    <div class="col-sm-3">
      <div class="stat-chip">
        <div class="num"><?= number_format($stats['active'] + $stats['unsubscribed']) ?></div>
        <div class="lbl">Total Subscribers</div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="stat-chip">
        <div class="num" style="color:#14532d;"><?= number_format($stats['active']) ?></div>
        <div class="lbl">Active</div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="stat-chip">
        <div class="num" style="color:#dc2626;"><?= number_format($stats['unsubscribed']) ?></div>
        <div class="lbl">Unsubscribed</div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="stat-chip">
        <div class="num"><?= number_format($total) ?></div>
        <div class="lbl">Matching Filter</div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <form method="get" class="row g-2 mb-3">
    <div class="col-md-5">
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
             class="form-control" placeholder="Search email or locality…">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">All statuses</option>
        <option value="active"        <?= $filter_status === 'active'        ? 'selected' : '' ?>>Active</option>
        <option value="unsubscribed"  <?= $filter_status === 'unsubscribed'  ? 'selected' : '' ?>>Unsubscribed</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn w-100" style="background:#14532d;color:#fff;">
        <i class="fas fa-search me-1"></i>Filter
      </button>
    </div>
    <div class="col-md-2">
      <a href="newsletter-subscribers.php" class="btn btn-outline-secondary w-100">Clear</a>
    </div>
  </form>

  <!-- Table -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0" style="font-size:.88rem;">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Email</th>
            <th>Locality</th>
            <th>Source Page</th>
            <th>Status</th>
            <th>Subscribed</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No subscribers found.</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
            <tr>
              <td class="text-muted"><?= $offset + $i + 1 ?></td>
              <td><?= htmlspecialchars($r['email']) ?></td>
              <td><?= htmlspecialchars($r['locality'] ?? '—') ?></td>
              <td><small class="text-muted"><?= htmlspecialchars($r['source_page'] ?? '—') ?></small></td>
              <td>
                <?php if ($r['status'] === 'active'): ?>
                  <span class="badge badge-active px-2 py-1">Active</span>
                <?php else: ?>
                  <span class="badge badge-unsub px-2 py-1">Unsubscribed</span>
                <?php endif; ?>
              </td>
              <td><small><?= date('d M Y', strtotime($r['created_at'])) ?></small></td>
              <td>
                <form method="post" class="d-inline">
                  <input type="hidden" name="email" value="<?= htmlspecialchars($r['email']) ?>">
                  <?php if ($r['status'] === 'active'): ?>
                    <input type="hidden" name="action" value="unsubscribe">
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Mark as unsubscribed?')">
                      <i class="fas fa-ban me-1"></i>Unsub
                    </button>
                  <?php else: ?>
                    <input type="hidden" name="action" value="reactivate">
                    <button type="submit" class="btn btn-sm btn-outline-success">
                      <i class="fas fa-redo me-1"></i>Reactivate
                    </button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
  <nav class="mt-3">
    <ul class="pagination pagination-sm">
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <li class="page-item <?= $p === $cur_page ? 'active' : '' ?>">
          <a class="page-link"
             href="newsletter-subscribers.php?page=<?= $p ?><?= $filter_status ? '&status=' . urlencode($filter_status) : '' ?><?= $search ? '&q=' . urlencode($search) : '' ?>">
            <?= $p ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
