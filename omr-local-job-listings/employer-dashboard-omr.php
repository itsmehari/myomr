<?php
/**
 * MyOMR Job Portal - Employer Dashboard (Unified Applications View)
 * Modern employer dashboard with advanced filtering and bulk actions
 * 
 * @package MyOMR Job Portal
 * @version 2.0.0
 * @reference QuikrJobs-style employer dashboard
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Require employer authentication
require_once __DIR__ . '/includes/employer-auth.php';
requireEmployerAuth();

// Load database connection
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Verify connection
if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    header("HTTP/1.0 500 Internal Server Error");
    die("Database connection error. Please try again later.");
}

$employerId = (int)($_SESSION['employer_id'] ?? 0);
$employerEmail = $_SESSION['employer_email'] ?? '';

// Get employer profile
$employer = null;
if (!empty($employerEmail)) {
    $emailQuery = "SELECT * FROM employers WHERE email = '" . $conn->real_escape_string($employerEmail) . "'";
    $emailResult = $conn->query($emailQuery);
    if ($emailResult && $emailResult->num_rows > 0) {
        $employer = $emailResult->fetch_assoc();
        $correctId = (int)$employer['id'];
        if ($employerId !== $correctId) {
            $_SESSION['employer_id'] = $correctId;
            $employerId = $correctId;
        }
    }
}

// Get all jobs for this employer
$jobs = [];
if ($employerId > 0) {
    $jobsQuery = "SELECT id, title, status, applications_count, created_at 
                  FROM job_postings 
                  WHERE employer_id = {$employerId} 
                  ORDER BY created_at DESC";
    $jobsResult = $conn->query($jobsQuery);
    if ($jobsResult && $jobsResult->num_rows > 0) {
        $jobs = $jobsResult->fetch_all(MYSQLI_ASSOC);
    }
}

// Get filter parameters
$selectedJobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$statusFilter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$statusCategory = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : 'all';
$sortBy = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'recent';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;

// Get applications with filters
$applications = [];
$totalApplications = 0;

if ($employerId > 0) {
    // Build WHERE clause
    $whereConditions = ["j.employer_id = {$employerId}"];
    
    if ($selectedJobId > 0) {
        $whereConditions[] = "a.job_id = {$selectedJobId}";
    }
    
    if (!empty($statusFilter)) {
        $whereConditions[] = "a.status = '{$statusFilter}'";
    }
    
    // Status category filter
    if ($statusCategory === 'shortlisted') {
        $whereConditions[] = "a.status = 'shortlisted'";
    } elseif ($statusCategory === 'matching') {
        // Matching profiles: VIP or high experience or high salary
        $whereConditions[] = "(a.is_vip = 1 OR a.experience_years >= 3 OR a.applicant_current_salary > 25000)";
    }
    
    $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";
    
    // Count total applications
    $countQuery = "SELECT COUNT(DISTINCT a.id) as total
                   FROM job_applications a
                   INNER JOIN job_postings j ON a.job_id = j.id
                   {$whereClause}";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $countRow = $countResult->fetch_assoc();
        $totalApplications = (int)$countRow['total'];
    }
    
    // Build ORDER BY clause
    $orderBy = "a.applied_at DESC";
    if ($sortBy === 'vip') {
        $orderBy = "a.is_vip DESC, a.applied_at DESC";
    }
    
    // Calculate offset for pagination
    $offset = ($page - 1) * $perPage;
    
    // Get applications with job details
    $applicationsQuery = "SELECT a.*, 
                                 j.title as job_title,
                                 j.location as job_location,
                                 j.category as job_category,
                                 e.company_name
                          FROM job_applications a
                          INNER JOIN job_postings j ON a.job_id = j.id
                          LEFT JOIN employers e ON j.employer_id = e.id
                          {$whereClause}
                          ORDER BY {$orderBy}
                          LIMIT {$perPage} OFFSET {$offset}";
    
    $applicationsResult = $conn->query($applicationsQuery);
    if ($applicationsResult && $applicationsResult->num_rows > 0) {
        $applications = $applicationsResult->fetch_all(MYSQLI_ASSOC);
    }
}

// Get unique localities, education levels for filters
$localities = [];
$educationLevels = [];
if ($employerId > 0) {
    $localityQuery = "SELECT DISTINCT applicant_locality 
                      FROM job_applications a
                      INNER JOIN job_postings j ON a.job_id = j.id
                      WHERE j.employer_id = {$employerId} 
                        AND applicant_locality IS NOT NULL 
                        AND applicant_locality != ''
                      ORDER BY applicant_locality";
    $localityResult = $conn->query($localityQuery);
    if ($localityResult) {
        while ($row = $localityResult->fetch_assoc()) {
            $localities[] = $row['applicant_locality'];
        }
    }
    
    $educationQuery = "SELECT DISTINCT applicant_education 
                       FROM job_applications a
                       INNER JOIN job_postings j ON a.job_id = j.id
                       WHERE j.employer_id = {$employerId} 
                         AND applicant_education IS NOT NULL 
                         AND applicant_education != ''
                       ORDER BY applicant_education";
    $educationResult = $conn->query($educationQuery);
    if ($educationResult) {
        while ($row = $educationResult->fetch_assoc()) {
            $educationLevels[] = $row['applicant_education'];
        }
    }
}

// Calculate total pages
$totalPages = $totalApplications > 0 ? ceil($totalApplications / $perPage) : 1;

// SEO Meta
$page_title = "Employer Dashboard - Applications | MyOMR";
$page_description = "Manage job applications, filter candidates, and track your hiring pipeline on MyOMR Job Portal.";
$canonical_url = "https://myomr.in/omr-local-job-listings/employer-dashboard-omr.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Google Analytics -->
    <?php $ga_user_id = (int)($_SESSION['employer_id'] ?? 0); $ga_user_properties = ['user_type' => 'employer']; include '../components/analytics.php'; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Core Styles -->
    <link rel="stylesheet" href="/assets/css/core.css">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <!-- Dashboard Styles -->
    <link rel="stylesheet" href="assets/employer-dashboard.css">
    
</head>
<body class="modern-page">
<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<!-- Header Section -->
<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 text-white">
            <div>
                <h1 class="hero-modern-title mb-2">
                    <i class="fas fa-briefcase me-2"></i>Job Applications
                </h1>
                <p class="hero-modern-subtitle mb-0">
                    Manage and review applications for your job postings
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light" href="my-posted-jobs-omr.php">
                    <i class="fas fa-list me-2"></i>My Jobs
                </a>
                <a class="btn btn-outline-light" href="post-job-omr.php">
                    <i class="fas fa-plus me-2"></i>Post New Job
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="py-4">
    <div class="container">
        
        <!-- Job Selector & Applicant Count -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-modern p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div>
                                <label for="job-selector" class="form-label mb-0 fw-semibold">Job:</label>
                                <select id="job-selector" class="form-select form-select-sm" style="min-width: 250px;">
                                    <option value="0" <?php echo $selectedJobId === 0 ? 'selected' : ''; ?>>All Jobs</option>
                                    <?php foreach ($jobs as $job): ?>
                                        <option value="<?php echo (int)$job['id']; ?>" 
                                                <?php echo $selectedJobId === (int)$job['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($job['title']); ?>
                                            (<?php echo (int)$job['applications_count']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="fas fa-users me-2"></i><?php echo $totalApplications; ?> applicants
                                </span>
                            </div>
                        </div>
                        
                        <!-- Bulk Actions (hidden until selection) -->
                        <div id="bulk-actions-bar" class="d-none align-items-center gap-2">
                            <span class="text-muted" id="selected-count">0 selected</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="bulk-sms-btn">
                                <i class="fas fa-sms me-1"></i>SMS
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="bulk-email-btn">
                                <i class="fas fa-envelope me-1"></i>Email
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" id="bulk-download-btn">
                                <i class="fas fa-download me-1"></i>Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Sidebar Filters -->
            <aside class="col-lg-3 mb-4 mb-lg-0">
                <div class="dashboard-sidebar card-modern p-0">
                    
                    <!-- Status Categories Navigation -->
                    <div class="sidebar-section">
                        <div class="status-nav">
                            <a href="?job_id=<?php echo $selectedJobId; ?>&category=all" 
                               class="status-nav-item <?php echo $statusCategory === 'all' ? 'active' : ''; ?>"
                               data-category="all">
                                <i class="fas fa-users me-2"></i>Applicants
                            </a>
                            <a href="?job_id=<?php echo $selectedJobId; ?>&category=matching" 
                               class="status-nav-item <?php echo $statusCategory === 'matching' ? 'active' : ''; ?>"
                               data-category="matching">
                                <i class="fas fa-user-friends me-2"></i>Matching profiles
                            </a>
                            <a href="?job_id=<?php echo $selectedJobId; ?>&category=shortlisted" 
                               class="status-nav-item <?php echo $statusCategory === 'shortlisted' ? 'active' : ''; ?>"
                               data-category="shortlisted">
                                <i class="fas fa-star me-2"></i>Shortlisted Profiles
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filters Header -->
                    <div class="sidebar-section border-top">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <h6 class="mb-0 fw-semibold">Filter profiles</h6>
                            <a href="employer-dashboard-omr.php" class="text-muted small text-decoration-none">Clear all</a>
                        </div>
                    </div>
                    
                    <!-- Candidate Status Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-status">
                                <span>Candidate status</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse show" id="filter-status">
                                <div class="filter-content">
                                    <select class="form-select form-select-sm" id="filter-status-select">
                                        <option value="">All Status</option>
                                        <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="reviewed" <?php echo $statusFilter === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                        <option value="shortlisted" <?php echo $statusFilter === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                        <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Locality Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-locality">
                                <span>Locality</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse show" id="filter-locality">
                                <div class="filter-content">
                                    <input type="text" class="form-control form-control-sm mb-2" placeholder="Eg: MG Road" id="filter-locality-search">
                                    <div class="filter-checkboxes" id="locality-checkboxes">
                                        <?php foreach ($localities as $locality): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($locality); ?>" 
                                                       id="locality-<?php echo md5($locality); ?>">
                                                <label class="form-check-label" for="locality-<?php echo md5($locality); ?>">
                                                    <?php echo htmlspecialchars($locality); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (empty($localities)): ?>
                                            <p class="text-muted small mb-0">No localities available</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Education Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-education">
                                <span>Education</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse" id="filter-education">
                                <div class="filter-content">
                                    <div class="filter-checkboxes">
                                        <?php foreach ($educationLevels as $education): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($education); ?>" 
                                                       id="education-<?php echo md5($education); ?>">
                                                <label class="form-check-label" for="education-<?php echo md5($education); ?>">
                                                    <?php echo htmlspecialchars($education); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (empty($educationLevels)): ?>
                                            <p class="text-muted small mb-0">No education levels available</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gender Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-gender">
                                <span>Gender</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse" id="filter-gender">
                                <div class="filter-content">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Male" id="gender-male">
                                        <label class="form-check-label" for="gender-male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Female" id="gender-female">
                                        <label class="form-check-label" for="gender-female">Female</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Other" id="gender-other">
                                        <label class="form-check-label" for="gender-other">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Salary Range Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-salary">
                                <span>Salary range</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse" id="filter-salary">
                                <div class="filter-content">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" placeholder="Min" id="salary-min">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" placeholder="Max" id="salary-max">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Experience Filter -->
                    <div class="sidebar-section">
                        <div class="filter-group">
                            <button class="filter-header" type="button" data-bs-toggle="collapse" data-bs-target="#filter-experience">
                                <span>Experience (in years)</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="collapse" id="filter-experience">
                                <div class="filter-content">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" placeholder="Min" id="experience-min">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" placeholder="Max" id="experience-max">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </aside>
            
            <!-- Main Content Area -->
            <div class="col-lg-9">
                
                <!-- Sort & Results per Page -->
                <div class="card-modern p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <label class="mb-0 fw-semibold">Sort by:</label>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="sort-option" id="sort-vip" 
                                       value="vip" <?php echo $sortBy === 'vip' ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary btn-sm" for="sort-vip">VIP first</label>
                                
                                <input type="radio" class="btn-check" name="sort-option" id="sort-recent" 
                                       value="recent" <?php echo $sortBy === 'recent' ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary btn-sm" for="sort-recent">Recent first</label>
                            </div>
                        </div>
                        <div>
                            <label for="results-per-page" class="mb-0 me-2">Results per page:</label>
                            <select class="form-select form-select-sm d-inline-block" id="results-per-page" style="width: auto;">
                                <option value="10" <?php echo $perPage === 10 ? 'selected' : ''; ?>>10</option>
                                <option value="20" <?php echo $perPage === 20 ? 'selected' : ''; ?>>20</option>
                                <option value="50" <?php echo $perPage === 50 ? 'selected' : ''; ?>>50</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Applications List -->
                <div id="applications-list">
                    <?php if (!empty($applications)): ?>
                        <?php foreach ($applications as $app): ?>
                            <?php 
                            // Make $app available to included file
                            $applicationData = $app;
                            include __DIR__ . '/includes/employer-applicant-card.php';
                            unset($applicationData);
                            ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card-modern p-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-inbox fa-4x text-muted"></i>
                            </div>
                            <h3 class="h4 mb-3">No Applications Found</h3>
                            <p class="text-muted mb-4">
                                <?php if ($selectedJobId > 0): ?>
                                    No applications found for this job. Try adjusting your filters or select a different job.
                                <?php else: ?>
                                    You don't have any applications yet. Start by posting a job!
                                <?php endif; ?>
                            </p>
                            <a href="post-job-omr.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Post Your First Job
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Applications pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&job_id=<?php echo $selectedJobId; ?>&sort=<?php echo $sortBy; ?>&per_page=<?php echo $perPage; ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&job_id=<?php echo $selectedJobId; ?>&sort=<?php echo $sortBy; ?>&per_page=<?php echo $perPage; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&job_id=<?php echo $selectedJobId; ?>&sort=<?php echo $sortBy; ?>&per_page=<?php echo $perPage; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Dashboard JavaScript -->
<script src="assets/employer-dashboard.js"></script>

</body>
</html>

