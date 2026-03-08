<?php
/**
 * Admin Dashboard
 * Overview of all job postings and applications
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

// Get statistics
$stats = [
    'pending_jobs' => 0,
    'approved_jobs' => 0,
    'total_applications' => 0,
    'total_employers' => 0
];

$result = $conn->query("SELECT status, COUNT(*) as count FROM job_postings GROUP BY status");
while ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'pending') $stats['pending_jobs'] = $row['count'];
    if ($row['status'] === 'approved') $stats['approved_jobs'] = $row['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM job_applications")->fetch_assoc();
$stats['total_applications'] = $result['count'] ?? 0;

$result = $conn->query("SELECT COUNT(*) as count FROM employers")->fetch_assoc();
$stats['total_employers'] = $result['count'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyOMR Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/job-listings-omr.css">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-chart-line me-2"></i>Admin Dashboard</h1>
        <a href="/admin/logout.php" class="btn btn-outline-secondary">Logout</a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-clock me-2"></i>Pending Jobs</h5>
                    <h2><?php echo $stats['pending_jobs']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-check me-2"></i>Approved Jobs</h5>
                    <h2><?php echo $stats['approved_jobs']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-briefcase me-2"></i>Total Applications</h5>
                    <h2><?php echo $stats['total_applications']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-building me-2"></i>Employers</h5>
                    <h2><?php echo $stats['total_employers']; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-tasks me-2"></i>Manage Jobs</h5>
                    <p class="card-text">Approve, reject, or edit job postings</p>
                    <a href="manage-jobs-omr.php" class="btn btn-primary">Manage Jobs</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i>View All Applications</h5>
                    <p class="card-text">Browse all job applications</p>
                    <a href="view-all-applications-omr.php" class="btn btn-primary">View Applications</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-check me-2"></i>Verify Employers</h5>
                    <p class="card-text">Approve, set pending, or suspend employer accounts</p>
                    <a href="verify-employers-omr.php" class="btn btn-primary">Verify Employers</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

