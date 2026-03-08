<?php
/**
 * Employer Dashboard - My Posted Jobs
 */
// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/includes/employer-auth.php';
require_once __DIR__ . '/includes/job-functions-omr.php';
requireEmployerAuth();

$employerId = (int)($_SESSION['employer_id'] ?? 0);
$employerEmail = $_SESSION['employer_email'] ?? '';

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Verify connection
if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    die("Database connection error. Please try again later.");
}

// Get employer profile information using direct query (like test-jobs.php)
// First try by email (most reliable since that's what we login with)
$employer = null;
if (!empty($employerEmail)) {
    $emailQuery = "SELECT * FROM employers WHERE email = '" . $conn->real_escape_string($employerEmail) . "'";
    $emailResult = $conn->query($emailQuery);
    if ($emailResult && $emailResult->num_rows > 0) {
        $employer = $emailResult->fetch_assoc();
        // Update session with correct ID if different
        $correctId = (int)$employer['id'];
        if ($employerId !== $correctId) {
            $_SESSION['employer_id'] = $correctId;
            $employerId = $correctId;
            error_log("my-posted-jobs-omr.php: Updated employer_id from session to {$correctId} based on email");
        }
    }
}

// If still not found, try by ID
if (!$employer && $employerId > 0) {
    $employerQuery = "SELECT * FROM employers WHERE id = {$employerId}";
    $employerResult = $conn->query($employerQuery);
    $employer = $employerResult ? $employerResult->fetch_assoc() : null;
}

// Final check - if we have employer, use their ID
if ($employer && isset($employer['id'])) {
    $employerId = (int)$employer['id'];
}

// Get employer's jobs using direct query (like test-jobs.php approach)
$jobs = [];


if ($employerId > 0) {
    // Try multiple approaches to find jobs
    
    // Approach 1: Direct query by employer_id
    $jobsQuery = "SELECT * FROM job_postings WHERE employer_id = {$employerId} ORDER BY created_at DESC";
    error_log("my-posted-jobs-omr.php: Executing query: " . $jobsQuery);
    $jobsResult = $conn->query($jobsQuery);
    
    if ($jobsResult) {
        if ($jobsResult->num_rows > 0) {
            $jobs = $jobsResult->fetch_all(MYSQLI_ASSOC);
            error_log("my-posted-jobs-omr.php: Found " . count($jobs) . " jobs for employer_id = {$employerId}");
        } else {
            error_log("my-posted-jobs-omr.php: Query returned 0 rows for employer_id = {$employerId}");
        }
    } else {
        error_log("my-posted-jobs-omr.php: Query failed: " . $conn->error);
    }
    
    // Approach 2: If no jobs found, try by email join (fallback)
    if (empty($jobs) && !empty($employerEmail)) {
        $checkQuery = "SELECT j.* FROM job_postings j 
                      INNER JOIN employers e ON j.employer_id = e.id 
                      WHERE e.email = '" . $conn->real_escape_string($employerEmail) . "' 
                      ORDER BY j.created_at DESC";
        error_log("my-posted-jobs-omr.php: Trying fallback query by email");
        $checkResult = $conn->query($checkQuery);
        if ($checkResult && $checkResult->num_rows > 0) {
            $jobs = $checkResult->fetch_all(MYSQLI_ASSOC);
            error_log("my-posted-jobs-omr.php: Found " . count($jobs) . " jobs by email lookup");
        } else {
            error_log("my-posted-jobs-omr.php: Fallback query also returned 0 rows");
        }
    }
    
    // Approach 3: If still no jobs, check if maybe employer_id is stored as string or has whitespace
    if (empty($jobs)) {
        $flexibleQuery = "SELECT * FROM job_postings WHERE CAST(employer_id AS CHAR) = '{$employerId}' ORDER BY created_at DESC";
        $flexibleResult = $conn->query($flexibleQuery);
        if ($flexibleResult && $flexibleResult->num_rows > 0) {
            $jobs = $flexibleResult->fetch_all(MYSQLI_ASSOC);
            error_log("my-posted-jobs-omr.php: Found " . count($jobs) . " jobs using flexible query");
        }
    }
} else {
    error_log("my-posted-jobs-omr.php: Invalid employer_id = {$employerId}");
}

$status_totals = [
    'approved' => 0,
    'pending' => 0,
    'rejected' => 0,
    'closed' => 0,
];
$total_applications = 0;
$total_views = 0;
$next_deadline = null;

foreach ($jobs as $job) {
    $status_key = strtolower($job['status'] ?? '');
    if (array_key_exists($status_key, $status_totals)) {
        $status_totals[$status_key]++;
    }

    $total_applications += (int)($job['applications_count'] ?? 0);
    $total_views += (int)($job['views'] ?? 0);

    if (!empty($job['application_deadline'])) {
        $deadline = strtotime($job['application_deadline']);
        if ($deadline !== false && ($deadline >= time())) {
            if ($next_deadline === null || $deadline < $next_deadline) {
                $next_deadline = $deadline;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posted Jobs - Employer Dashboard | MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">

    <style>
        .dashboard-actions .btn {
            min-width: 44px;
        }
        .dashboard-table thead {
            background-color: #f9fafb;
        }
        .dashboard-table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .stat-card small {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            color: #64748b;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
    </style>
    
    <!-- Google Analytics -->
    <?php $ga_user_id = (int)($_SESSION['employer_id'] ?? 0); $ga_user_properties = ['user_type' => 'employer']; include '../components/analytics.php'; ?>
</head>
<body class="modern-page">
<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 text-white">
            <div>
                <h1 class="hero-modern-title mb-2">My Posted Jobs</h1>
                <p class="hero-modern-subtitle mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['employer_company'] ?? 'Employer'); ?>. Manage your listings and track performance.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light" href="employer-dashboard-omr.php"><i class="fas fa-users me-2"></i>View All Applications</a>
                <a class="btn btn-outline-light" href="post-job-omr.php"><i class="fas fa-plus me-2"></i>Post New Job</a>
                <a class="btn btn-outline-light" href="employer-logout-omr.php"><i class="fas fa-right-from-bracket me-2"></i>Logout</a>
            </div>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        
        <!-- Employer Profile Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-modern p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1"><?php echo htmlspecialchars($employer['company_name'] ?? $_SESSION['employer_company'] ?? 'Employer'); ?></h3>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($employer['email'] ?? $_SESSION['employer_email'] ?? 'N/A'); ?>
                                    <?php if (!empty($employer['phone'])): ?>
                                        <span class="ms-3"><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($employer['phone']); ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($employer['address'])): ?>
                                    <p class="text-muted mb-0 small">
                                        <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($employer['address']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="post-job-omr.php" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Post New Job
                            </a>
                            <a href="edit-employer-profile-omr.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                    
                    <?php if (!empty($employer['contact_person'])): ?>
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">Contact Person:</small>
                        <span class="fw-semibold"><?php echo htmlspecialchars($employer['contact_person']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($employer['status'])): ?>
                    <div class="mt-2">
                        <small class="text-muted">Account Status:</small>
                        <?php
                        $statusColor = 'secondary';
                        if ($employer['status'] === 'verified') $statusColor = 'success';
                        elseif ($employer['status'] === 'pending') $statusColor = 'warning';
                        elseif ($employer['status'] === 'suspended') $statusColor = 'danger';
                        ?>
                        <span class="badge bg-<?php echo $statusColor; ?>"><?php echo ucfirst(htmlspecialchars($employer['status'])); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if (!empty($jobs)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Active Jobs</small>
                    <div class="stat-value"><?php echo $status_totals['approved']; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Pending Review</small>
                    <div class="stat-value"><?php echo $status_totals['pending']; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Total Applications</small>
                    <div class="stat-value"><?php echo $total_applications; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Total Views</small>
                    <div class="stat-value"><?php echo $total_views; ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($next_deadline): ?>
        <div class="card-modern p-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="badge-modern badge-modern-warning"><i class="fas fa-hourglass-half me-2"></i>Upcoming Deadline</span>
                <span class="fw-semibold text-dark">Applications close on <?php echo date('F j, Y', $next_deadline); ?></span>
            </div>
            <a href="#job-dashboard-table" class="btn btn-outline-success btn-sm">Review jobs</a>
        </div>
        <?php endif; ?>

        <?php if (empty($jobs)): ?>
            <div class="card-modern p-5 text-center">
                <div class="mb-3">
                    <i class="fas fa-briefcase fa-3x text-success"></i>
                </div>
                <h2 class="h4 mb-2">You haven't posted any jobs yet</h2>
                <p class="text-muted mb-4">Start reaching candidates in OMR by creating your first job listing.</p>
                <a href="post-job-omr.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Post your first job</a>
            </div>
        <?php else: ?>
            <div class="card-modern p-0" id="job-dashboard-table">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 dashboard-table">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Applications</th>
                                <th scope="col">Views</th>
                                <th scope="col">Posted</th>
                                <th scope="col">Deadline</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                                <?php
                                    $statusValue = strtolower((string)($job['status'] ?? ''));
                                    $badge = 'secondary';
                                    if ($statusValue === 'approved') {
                                        $badge = 'success';
                                    } elseif ($statusValue === 'pending') {
                                        $badge = 'warning';
                                    } elseif ($statusValue === 'rejected') {
                                        $badge = 'danger';
                                    } elseif ($statusValue === 'closed') {
                                        $badge = 'dark';
                                    }
                                ?>
                                <?php
                                    $updatedTimestamp = !empty($job['updated_at']) ? strtotime($job['updated_at']) : null;
                                    $createdTimestamp = !empty($job['created_at']) ? strtotime($job['created_at']) : null;
                                    $deadlineTimestamp = !empty($job['application_deadline']) ? strtotime($job['application_deadline']) : null;
                                    $updatedLabel = $updatedTimestamp ? date('M j, Y', $updatedTimestamp) : ($createdTimestamp ? date('M j, Y', $createdTimestamp) : 'N/A');
                                    $postedLabel = $createdTimestamp ? date('M j, Y', $createdTimestamp) : 'N/A';
                                    $deadlineLabel = $deadlineTimestamp ? date('M j, Y', $deadlineTimestamp) : null;
                                ?>
                                <tr>
                                    <td>
                                        <a href="job-detail-omr.php?id=<?php echo (int)$job['id']; ?>" class="fw-semibold text-decoration-none" target="_blank">
                                            <?php echo htmlspecialchars($job['title']); ?>
                                        </a>
                                        <div class="text-muted small">Updated <?php echo htmlspecialchars($updatedLabel); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $badge; ?> text-uppercase px-3 py-2">
                                            <?php echo htmlspecialchars($job['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo (int)($job['applications_count'] ?? 0); ?></td>
                                    <td><?php echo (int)($job['views'] ?? 0); ?></td>
                                    <td><?php echo htmlspecialchars($postedLabel); ?></td>
                                    <td>
                                        <?php if ($deadlineLabel): ?>
                                            <?php echo htmlspecialchars($deadlineLabel); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end dashboard-actions">
                                        <div class="btn-group" role="group" aria-label="Manage job">
                                            <a href="edit-job-omr.php?id=<?php echo (int)$job['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit job">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="view-applications-omr.php?id=<?php echo (int)$job['id']; ?>" class="btn btn-sm btn-outline-secondary" title="View applications">
                                                <i class="fas fa-inbox"></i>
                                            </a>
                                            <a href="job-detail-omr.php?id=<?php echo (int)$job['id']; ?>" class="btn btn-sm btn-outline-success" title="Preview listing" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if (!empty($_GET['registered'])): ?>
<script>
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'sign_up', {
    'method': 'employer_registration'
  });
})();
</script>
<?php endif; ?>

</body>
</html>
