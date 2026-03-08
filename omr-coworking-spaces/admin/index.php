<?php
/**
 * Admin Dashboard - Coworking Spaces Module
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

$title = 'Coworking Spaces Dashboard';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Fetch summary stats (with error handling)
$total_spaces = 0;
$active_spaces = 0;
$pending_owners = 0;
$total_inquiries = 0;
$total_owners = 0;

$total_spaces_res = $conn->query("SELECT COUNT(*) as count FROM coworking_spaces");
if ($total_spaces_res) {
    $total_spaces = $total_spaces_res->fetch_assoc()['count'];
}

$active_spaces_res = $conn->query("SELECT COUNT(*) as count FROM coworking_spaces WHERE status = 'active'");
if ($active_spaces_res) {
    $active_spaces = $active_spaces_res->fetch_assoc()['count'];
}

$pending_owners_res = $conn->query("SELECT COUNT(*) as count FROM space_owners WHERE status = 'pending'");
if ($pending_owners_res) {
    $pending_owners = $pending_owners_res->fetch_assoc()['count'];
}

$total_inquiries_res = $conn->query("SELECT COUNT(*) as count FROM space_inquiries");
if ($total_inquiries_res) {
    $total_inquiries = $total_inquiries_res->fetch_assoc()['count'];
}

$total_owners_res = $conn->query("SELECT COUNT(*) as count FROM space_owners");
if ($total_owners_res) {
    $total_owners = $total_owners_res->fetch_assoc()['count'];
}

// Recent activity
$recent_spaces = $conn->query("SELECT id, space_name, created_at FROM coworking_spaces ORDER BY created_at DESC LIMIT 5");
$recent_inquiries = $conn->query("SELECT id, user_name, space_id, created_at FROM space_inquiries ORDER BY created_at DESC LIMIT 5");
$recent_owners = $conn->query("SELECT id, full_name, email, created_at FROM space_owners ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .stat-card { background: #fff; border-radius: 8px; padding: 1.5rem; text-align: center; transition: transform 0.2s; }
        .stat-card:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .stat-card i { font-size: 2.5rem; }
        .stat-card h3 { font-size: 2rem; font-weight: 700; }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include '../../admin/admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4" aria-label="Main content">
      <?php include '../../admin/admin-header.php'; ?>
      <?php include '../../admin/admin-breadcrumbs.php'; ?>
      <?php include '../../admin/admin-flash.php'; ?>
      
      <div class="alert alert-success mt-3">
        <strong>Coworking Spaces Module</strong> - Manage workspaces, owners, and inquiries
      </div>

      <!-- Summary Section -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="stat-card shadow-sm">
            <i class="fas fa-building text-primary"></i>
            <h3><?php echo $total_spaces; ?></h3>
            <p class="text-muted mb-0">Total Spaces</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card shadow-sm">
            <i class="fas fa-check-circle text-success"></i>
            <h3><?php echo $active_spaces; ?></h3>
            <p class="text-muted mb-0">Active Listings</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card shadow-sm">
            <i class="fas fa-users text-warning"></i>
            <h3><?php echo $pending_owners; ?></h3>
            <p class="text-muted mb-0">Pending Owners</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card shadow-sm">
            <i class="fas fa-envelope text-info"></i>
            <h3><?php echo $total_inquiries; ?></h3>
            <p class="text-muted mb-0">Total Inquiries</p>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <h3 class="mb-3">Recent Activity</h3>
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-building me-2"></i>Recent Spaces</h5>
              <?php if ($recent_spaces->num_rows > 0): ?>
                <ul class="list-unstyled mb-0">
                  <?php while ($space = $recent_spaces->fetch_assoc()): ?>
                    <li class="py-2 border-bottom">
                      <strong><?php echo htmlspecialchars($space['space_name']); ?></strong>
                      <br><small class="text-muted"><?php echo date('M d, Y', strtotime($space['created_at'])); ?></small>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                <p class="text-muted">No spaces yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-envelope me-2"></i>Recent Inquiries</h5>
              <?php if ($recent_inquiries->num_rows > 0): ?>
                <ul class="list-unstyled mb-0">
                  <?php while ($inq = $recent_inquiries->fetch_assoc()): ?>
                    <li class="py-2 border-bottom">
                      <strong><?php echo htmlspecialchars($inq['user_name']); ?></strong>
                      <br><small class="text-muted"><?php echo date('M d, Y', strtotime($inq['created_at'])); ?></small>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                <p class="text-muted">No inquiries yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-user me-2"></i>Recent Owners</h5>
              <?php if ($recent_owners->num_rows > 0): ?>
                <ul class="list-unstyled mb-0">
                  <?php while ($owner = $recent_owners->fetch_assoc()): ?>
                    <li class="py-2 border-bottom">
                      <strong><?php echo htmlspecialchars($owner['full_name']); ?></strong>
                      <br><small class="text-muted"><?php echo htmlspecialchars($owner['email']); ?></small>
                      <br><small class="text-muted"><?php echo date('M d, Y', strtotime($owner['created_at'])); ?></small>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                <p class="text-muted">No owners yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <h3 class="mb-3">Quick Actions</h3>
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <i class="fas fa-building fa-3x text-primary mb-3"></i>
              <h5 class="card-title">Manage Spaces</h5>
              <a href="manage-spaces.php" class="btn btn-primary">View All</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <i class="fas fa-users fa-3x text-warning mb-3"></i>
              <h5 class="card-title">Manage Owners</h5>
              <a href="manage-owners.php" class="btn btn-warning">View All</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <i class="fas fa-envelope fa-3x text-info mb-3"></i>
              <h5 class="card-title">All Inquiries</h5>
              <a href="view-all-inquiries.php" class="btn btn-info">View All</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 mb-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <i class="fas fa-star fa-3x text-success mb-3"></i>
              <h5 class="card-title">Featured</h5>
              <a href="featured-management.php" class="btn btn-success">Manage</a>
            </div>
          </div>
        </div>
      </div>
      
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

