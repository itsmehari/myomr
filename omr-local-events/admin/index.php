<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

$stats = [
  'pending' => 0,
  'approved_submissions' => 0,
  'scheduled' => 0,
  'ongoing' => 0,
];

try {
  global $conn;
  if ($conn && !$conn->connect_error) {
    $r = $conn->query("SELECT COUNT(*) c FROM event_submissions WHERE status IN ('submitted','draft')");
    $stats['pending'] = (int)($r->fetch_assoc()['c'] ?? 0);
    $r = $conn->query("SELECT COUNT(*) c FROM event_submissions WHERE status='approved'");
    $stats['approved_submissions'] = (int)($r->fetch_assoc()['c'] ?? 0);
    $r = $conn->query("SELECT COUNT(*) c FROM event_listings WHERE status='scheduled'");
    $stats['scheduled'] = (int)($r->fetch_assoc()['c'] ?? 0);
    $r = $conn->query("SELECT COUNT(*) c FROM event_listings WHERE status='ongoing'");
    $stats['ongoing'] = (int)($r->fetch_assoc()['c'] ?? 0);
  }
} catch (Throwable $e) {
  error_log('Events Admin Dashboard stats error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events Admin – Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/events-dashboard.css">
</head>
<body class="bg-light">
  <div class="dashboard-header">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <div class="mb-2 mb-md-0">
        <div class="title h1 mb-0">Events Admin</div>
        <div class="small opacity-90">Moderate submissions and manage published events</div>
      </div>
      <div class="dashboard-actions">
        <a href="manage-events-omr.php" class="btn-modern btn-modern-primary"><i class="fas fa-inbox"></i><span>Manage Submissions</span></a>
        <a href="../generate-events-sitemap.php" class="btn-modern btn-modern-secondary"><i class="fas fa-sitemap"></i><span>Generate Sitemap</span></a>
        <a href="../index.php" class="btn-modern btn-modern-secondary"><i class="fas fa-globe"></i><span>View Public Listing</span></a>
      </div>
    </div>
  </div>

  <main class="py-4">
    <div class="container">
      <div class="row g-3">
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <div class="h6 text-muted">Pending Submissions</div>
              <div class="display-6 fw-bold"><?php echo $stats['pending']; ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <div class="h6 text-muted">Approved Submissions</div>
              <div class="display-6 fw-bold"><?php echo $stats['approved_submissions']; ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <div class="h6 text-muted">Scheduled</div>
              <div class="display-6 fw-bold"><?php echo $stats['scheduled']; ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <div class="h6 text-muted">Ongoing</div>
              <div class="display-6 fw-bold"><?php echo $stats['ongoing']; ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mt-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Quick Links</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-3"><a class="btn btn-outline-primary w-100" href="manage-events-omr.php"><i class="fas fa-inbox me-2"></i>Manage Submissions</a></div>
            <div class="col-md-3"><a class="btn btn-outline-primary w-100" href="view-listings.php"><i class="fas fa-list me-2"></i>Approved Listings</a></div>
            <div class="col-md-3"><a class="btn btn-outline-primary w-100" href="calendar-export.php"><i class="far fa-calendar me-2"></i>Calendar & Export</a></div>
            <div class="col-md-3"><a class="btn btn-outline-secondary w-100" href="../generate-events-sitemap.php" target="_blank"><i class="fas fa-sitemap me-2"></i>Generate Sitemap</a></div>
            <div class="col-md-3"><a class="btn btn-outline-success w-100" href="share-playbook.php"><i class="fas fa-share me-2"></i>Friday Share Playbook</a></div>
            <div class="col-md-3"><a class="btn btn-outline-success w-100" href="email-digest.php"><i class="fas fa-envelope me-2"></i>Email Digest</a></div>
            <div class="col-md-3"><a class="btn btn-outline-secondary w-100" href="../index.php"><i class="fas fa-globe me-2"></i>Public Listing</a></div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


