<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /admin/login.php');
    exit;
}
require_once '../core/omr-connect.php';

$username = $_SESSION['admin_username'] ?? 'Admin';

/* ── Helper: safe query count ──────────────────────────────────── */
function qs(mysqli $db, string $sql): int {
    try {
        $r = $db->query($sql);
        return ($r && $r->num_rows > 0) ? (int)$r->fetch_row()[0] : 0;
    } catch (Throwable $e) { return -1; }
}
function qr(mysqli $db, string $sql): ?mysqli_result {
    try { $r = $db->query($sql); return ($r instanceof mysqli_result) ? $r : null; }
    catch (Throwable $e) { return null; }
}

/* ── Stats ─────────────────────────────────────────────────────── */
$stats = [
    'articles'     => qs($conn, "SELECT COUNT(*) FROM articles WHERE status='published'"),
    'art_draft'    => qs($conn, "SELECT COUNT(*) FROM articles WHERE status='draft'"),
    'events'       => qs($conn, "SELECT COUNT(*) FROM event_listings WHERE status='scheduled'"),
    'ev_pending'   => qs($conn, "SELECT COUNT(*) FROM event_listings WHERE status='pending'"),
    'jobs'         => qs($conn, "SELECT COUNT(*) FROM job_postings WHERE status='active'"),
    'jobs_pending' => qs($conn, "SELECT COUNT(*) FROM job_postings WHERE status='pending'"),
    'cowork'       => qs($conn, "SELECT COUNT(*) FROM coworking_spaces WHERE status='active'"),
    'cw_pending'   => qs($conn, "SELECT COUNT(*) FROM coworking_spaces WHERE status='pending'"),
    'hostels'      => qs($conn, "SELECT COUNT(*) FROM hostel_properties WHERE status='active'"),
    'h_pending'    => qs($conn, "SELECT COUNT(*) FROM hostel_properties WHERE status='pending'"),
    'restaurants'  => qs($conn, "SELECT COUNT(*) FROM omr_restaurants"),
    'schools'      => qs($conn, "SELECT COUNT(*) FROM omrschoolslist"),
    'hospitals'    => qs($conn, "SELECT COUNT(*) FROM omrhospitalslist"),
    'it_cos'       => qs($conn, "SELECT COUNT(*) FROM omr_it_companies"),
];

/* Total pending approvals badge */
$total_pending = max(0, $stats['ev_pending']) + max(0, $stats['jobs_pending'])
               + max(0, $stats['cw_pending']) + max(0, $stats['h_pending']);

/* ── Recent items ──────────────────────────────────────────────── */
$recent_articles = qr($conn, "SELECT id, title, published_date FROM articles WHERE status='published' ORDER BY published_date DESC LIMIT 5");
$recent_events   = qr($conn, "SELECT id, title, start_datetime, status FROM event_listings ORDER BY created_at DESC LIMIT 5");
$recent_jobs     = qr($conn, "SELECT j.id, j.title, j.status, e.company_name FROM job_postings j LEFT JOIN employers e ON j.employer_id = e.id ORDER BY j.created_at DESC LIMIT 5");
$pending_jobs    = qr($conn, "SELECT j.id, j.title, e.company_name, j.created_at FROM job_postings j LEFT JOIN employers e ON j.employer_id = e.id WHERE j.status='pending' ORDER BY j.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Admin Dashboard — MyOMR CMS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --brand: #14532d;
      --brand-light: #22c55e;
      --sidebar-w: 240px;
    }
    body { background: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }

    /* ── Sidebar ── */
    .sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: var(--sidebar-w); background: var(--brand);
      color: #fff; overflow-y: auto; z-index: 100;
      display: flex; flex-direction: column;
    }
    .sidebar-brand {
      padding: 1.2rem 1rem; font-weight: 700; font-size: 1.1rem;
      border-bottom: 1px solid rgba(255,255,255,.15);
      display: flex; align-items: center; gap: .5rem;
    }
    .nav-section { padding: .5rem 1rem .2rem; font-size: .68rem;
      text-transform: uppercase; letter-spacing: .08em;
      color: rgba(255,255,255,.45); margin-top: .5rem; }
    .sidebar a {
      display: flex; align-items: center; gap: .6rem;
      padding: .55rem 1rem; color: rgba(255,255,255,.85);
      text-decoration: none; font-size: .88rem; border-radius: 0;
      transition: background .15s;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,.12); color: #fff;
    }
    .sidebar a .fa-fw { width: 1.1em; text-align: center; }

    /* ── Main ── */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; }
    .topbar {
      background: #fff; border-bottom: 1px solid #e2e8f0;
      padding: .75rem 1.5rem;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
    }
    .content-area { padding: 1.5rem; }

    /* ── Stat cards ── */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1rem; }
    .stat-card {
      background: #fff; border-radius: 10px; padding: 1.2rem;
      display: flex; align-items: center; gap: 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,.08);
      transition: transform .15s, box-shadow .15s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.12); }
    .stat-icon {
      width: 46px; height: 46px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-num { font-size: 1.5rem; font-weight: 700; line-height: 1; color: #0f172a; }
    .stat-label { font-size: .78rem; color: #64748b; margin-top: .1rem; }
    .pending-badge {
      font-size: .68rem; background: #fef2f2; color: #dc2626;
      border-radius: 20px; padding: .1rem .45rem; margin-left: .3rem;
    }

    /* ── Tables ── */
    .panel { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    .panel-header {
      padding: .85rem 1.2rem; border-bottom: 1px solid #f1f5f9;
      display: flex; align-items: center; justify-content: space-between;
    }
    .panel-header h2 { font-size: 1rem; font-weight: 600; margin: 0; }
    .panel table td, .panel table th { padding: .55rem 1.2rem; font-size: .85rem; }
    .panel table th { background: #f8fafc; font-weight: 600; color: #475569; }

    /* ── Quick actions ── */
    .qa-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(145px, 1fr)); gap: .75rem; }
    .qa-btn {
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: .4rem; padding: 1rem .5rem; background: #fff; border-radius: 10px;
      text-decoration: none; color: #334155; font-size: .8rem; font-weight: 500;
      border: 2px solid transparent; transition: all .15s;
      box-shadow: 0 1px 3px rgba(0,0,0,.07);
    }
    .qa-btn:hover { border-color: var(--brand-light); color: var(--brand); }
    .qa-btn i { font-size: 1.4rem; }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-wrap { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- ── Sidebar ── -->
<nav class="sidebar">
  <div class="sidebar-brand">
    <i class="fas fa-leaf"></i> MyOMR Admin
  </div>

  <div class="nav-section">Overview</div>
  <a href="/admin/dashboard.php" class="active"><i class="fas fa-gauge-high fa-fw"></i> Dashboard</a>

  <div class="nav-section">Content</div>
  <a href="/admin/articles/index.php"><i class="fas fa-newspaper fa-fw"></i> Articles</a>
  <a href="/admin/news-list.php"><i class="fas fa-list fa-fw"></i> News Bulletin</a>
  <a href="/omr-local-events/admin/manage-events-omr.php"><i class="fas fa-calendar-alt fa-fw"></i> Events</a>

  <div class="nav-section">Jobs Portal</div>
  <a href="/omr-local-job-listings/admin/manage-jobs-omr.php"><i class="fas fa-briefcase fa-fw"></i> Manage Jobs
    <?php if ($stats['jobs_pending'] > 0): ?><span class="pending-badge"><?= $stats['jobs_pending'] ?></span><?php endif; ?>
  </a>
  <a href="/omr-local-job-listings/admin/verify-employers-omr.php"><i class="fas fa-building fa-fw"></i> Employers</a>
  <a href="/omr-local-job-listings/admin/view-all-applications-omr.php"><i class="fas fa-file-alt fa-fw"></i> Applications</a>

  <div class="nav-section">Spaces & Stays</div>
  <a href="/omr-coworking-spaces/admin/manage-spaces.php"><i class="fas fa-desk fa-fw"></i> Coworking
    <?php if ($stats['cw_pending'] > 0): ?><span class="pending-badge"><?= $stats['cw_pending'] ?></span><?php endif; ?>
  </a>
  <a href="/omr-hostels-pgs/admin/manage-properties.php"><i class="fas fa-bed fa-fw"></i> Hostels & PGs
    <?php if ($stats['h_pending'] > 0): ?><span class="pending-badge"><?= $stats['h_pending'] ?></span><?php endif; ?>
  </a>

  <div class="nav-section">Directory</div>
  <a href="/admin/schools-list.php"><i class="fas fa-school fa-fw"></i> Schools</a>
  <a href="/admin/hospitals-list.php"><i class="fas fa-hospital fa-fw"></i> Hospitals</a>
  <a href="/admin/banks-list.php"><i class="fas fa-landmark fa-fw"></i> Banks & ATMs</a>
  <a href="/admin/it-companies-list.php"><i class="fas fa-laptop-code fa-fw"></i> IT Companies</a>
  <a href="/admin/industries-list.php"><i class="fas fa-industry fa-fw"></i> Industries</a>
  <a href="/admin/parks-list.php"><i class="fas fa-tree fa-fw"></i> Parks</a>
  <a href="/admin/government-offices-list.php"><i class="fas fa-building-columns fa-fw"></i> Govt Offices</a>

  <div class="nav-section">System</div>
  <a href="/admin/change-password.php"><i class="fas fa-key fa-fw"></i> Change Password</a>
  <a href="/admin/logout.php"><i class="fas fa-right-from-bracket fa-fw"></i> Logout</a>
</nav>

<!-- ── Main ── -->
<div class="main-wrap">
  <div class="topbar">
    <div>
      <strong>Dashboard</strong>
      <span class="text-muted ms-2" style="font-size:.85rem;">Welcome back, <?= htmlspecialchars($username) ?>!</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <?php if ($total_pending > 0): ?>
      <span class="badge bg-danger rounded-pill"><i class="fas fa-bell me-1"></i><?= $total_pending ?> pending</span>
      <?php endif; ?>
      <a href="https://myomr.in" target="_blank" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-external-link-alt me-1"></i>View Site
      </a>
    </div>
  </div>

  <div class="content-area">

    <!-- ── Stats grid ── -->
    <div class="stat-grid mb-4">

      <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8"><i class="fas fa-newspaper"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['articles']) ?></div>
          <div class="stat-label">Published Articles
            <?php if ($stats['art_draft'] > 0): ?>
              <span class="pending-badge"><?= $stats['art_draft'] ?> draft</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#15803d"><i class="fas fa-calendar-check"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['events']) ?></div>
          <div class="stat-label">Live Events
            <?php if ($stats['ev_pending'] > 0): ?>
              <span class="pending-badge"><?= $stats['ev_pending'] ?> pending</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#a16207"><i class="fas fa-briefcase"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['jobs']) ?></div>
          <div class="stat-label">Active Jobs
            <?php if ($stats['jobs_pending'] > 0): ?>
              <span class="pending-badge"><?= $stats['jobs_pending'] ?> pending</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#6d28d9"><i class="fas fa-desk"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['cowork']) ?></div>
          <div class="stat-label">Coworking Spaces
            <?php if ($stats['cw_pending'] > 0): ?>
              <span class="pending-badge"><?= $stats['cw_pending'] ?> pending</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#be185d"><i class="fas fa-bed"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['hostels']) ?></div>
          <div class="stat-label">Hostels & PGs
            <?php if ($stats['h_pending'] > 0): ?>
              <span class="pending-badge"><?= $stats['h_pending'] ?> pending</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#ffedd5;color:#c2410c"><i class="fas fa-utensils"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['restaurants']) ?></div>
          <div class="stat-label">Restaurants</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe;color:#0369a1"><i class="fas fa-school"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['schools']) ?></div>
          <div class="stat-label">Schools</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#166534"><i class="fas fa-laptop-code"></i></div>
        <div>
          <div class="stat-num"><?= max(0, $stats['it_cos']) ?></div>
          <div class="stat-label">IT Companies</div>
        </div>
      </div>

    </div>

    <!-- ── Quick actions ── -->
    <h2 class="mb-3" style="font-size:1rem;font-weight:600;color:#374151;">Quick Actions</h2>
    <div class="qa-grid mb-4">
      <a href="/admin/articles/add.php" class="qa-btn"><i class="fas fa-plus-circle text-primary"></i>New Article</a>
      <a href="/omr-local-events/admin/manage-events-omr.php" class="qa-btn"><i class="fas fa-calendar-plus text-success"></i>Review Events</a>
      <a href="/omr-local-job-listings/admin/manage-jobs-omr.php" class="qa-btn"><i class="fas fa-briefcase text-warning"></i>Review Jobs</a>
      <a href="/omr-coworking-spaces/admin/manage-spaces.php" class="qa-btn"><i class="fas fa-desk text-purple" style="color:#7c3aed"></i>Review Spaces</a>
      <a href="/omr-hostels-pgs/admin/manage-properties.php" class="qa-btn"><i class="fas fa-bed" style="color:#be185d"></i>Review PGs</a>
      <a href="/omr-listings/get-listed.php" class="qa-btn" target="_blank"><i class="fas fa-building-circle-check text-info"></i>IT Submissions</a>
      <a href="https://analytics.google.com" target="_blank" class="qa-btn"><i class="fas fa-chart-line" style="color:#e25e3e"></i>GA4 Reports</a>
      <a href="https://search.google.com/search-console" target="_blank" class="qa-btn"><i class="fab fa-google" style="color:#4285F4"></i>Search Console</a>
    </div>

    <!-- ── Pending approvals ── -->
    <?php if ($pending_jobs && $pending_jobs->num_rows > 0): ?>
    <div class="panel mb-4">
      <div class="panel-header">
        <h2><i class="fas fa-clock text-warning me-2"></i>Pending Job Approvals</h2>
        <a href="/omr-local-job-listings/admin/manage-jobs-omr.php?status=pending" class="btn btn-sm btn-warning">View All</a>
      </div>
      <table class="table table-hover mb-0">
        <thead><tr><th>Job Title</th><th>Company</th><th>Submitted</th><th></th></tr></thead>
        <tbody>
          <?php while ($j = $pending_jobs->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($j['title']) ?></td>
            <td><?= htmlspecialchars($j['company_name'] ?? '—') ?></td>
            <td><?= date('M d', strtotime($j['created_at'])) ?></td>
            <td><a href="/omr-local-job-listings/admin/manage-jobs-omr.php?id=<?= (int)$j['id'] ?>" class="btn btn-xs btn-outline-warning" style="font-size:.75rem;padding:.2rem .5rem;">Review</a></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

    <!-- ── Two-column: recent articles + recent events ── -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="panel">
          <div class="panel-header">
            <h2><i class="fas fa-newspaper text-primary me-2"></i>Recent Articles</h2>
            <a href="/admin/articles/index.php" class="btn btn-sm btn-outline-primary">All</a>
          </div>
          <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Date</th></tr></thead>
            <tbody>
              <?php if ($recent_articles && $recent_articles->num_rows > 0):
                while ($a = $recent_articles->fetch_assoc()): ?>
              <tr>
                <td><a href="/admin/articles/edit.php?id=<?= (int)$a['id'] ?>"><?= htmlspecialchars(mb_strimwidth($a['title'], 0, 45, '…')) ?></a></td>
                <td style="white-space:nowrap"><?= date('M d, Y', strtotime($a['published_date'])) ?></td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="2" class="text-muted">No articles yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel">
          <div class="panel-header">
            <h2><i class="fas fa-calendar-alt text-success me-2"></i>Recent Events</h2>
            <a href="/omr-local-events/admin/manage-events-omr.php" class="btn btn-sm btn-outline-success">All</a>
          </div>
          <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
              <?php if ($recent_events && $recent_events->num_rows > 0):
                while ($ev = $recent_events->fetch_assoc()):
                  $badge = match($ev['status']) {
                    'scheduled' => 'success', 'pending' => 'warning',
                    'cancelled' => 'danger',  default   => 'secondary'
                  }; ?>
              <tr>
                <td><?= htmlspecialchars(mb_strimwidth($ev['title'], 0, 40, '…')) ?></td>
                <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($ev['status']) ?></span></td>
                <td style="white-space:nowrap"><?= date('M d', strtotime($ev['start_datetime'])) ?></td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="3" class="text-muted">No events yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ── Recent jobs ── -->
    <div class="panel mb-4">
      <div class="panel-header">
        <h2><i class="fas fa-briefcase text-warning me-2"></i>Recent Job Posts</h2>
        <a href="/omr-local-job-listings/admin/manage-jobs-omr.php" class="btn btn-sm btn-outline-warning">All</a>
      </div>
      <table class="table table-hover mb-0">
        <thead><tr><th>Title</th><th>Company</th><th>Status</th></tr></thead>
        <tbody>
          <?php if ($recent_jobs && $recent_jobs->num_rows > 0):
            while ($j = $recent_jobs->fetch_assoc()):
              $badge = match($j['status']) {
                'active' => 'success', 'pending' => 'warning',
                'expired' => 'secondary', 'rejected' => 'danger', default => 'light'
              }; ?>
          <tr>
            <td><a href="/omr-local-job-listings/job-detail-omr.php?id=<?= (int)$j['id'] ?>" target="_blank"><?= htmlspecialchars(mb_strimwidth($j['title'], 0, 50, '…')) ?></a></td>
            <td><?= htmlspecialchars($j['company_name'] ?? '—') ?></td>
            <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($j['status']) ?></span></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="3" class="text-muted">No jobs yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div><!-- /content-area -->
</div><!-- /main-wrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
<?php if (isset($conn)) $conn->close(); ?>
