<?php
/**
 * MyOMR Job Portal - View Applications
 * Employers can view applications for their job postings
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Require employer authentication
require_once __DIR__ . '/includes/employer-auth.php';
requireEmployerAuth();

// Load database connection directly (like test-jobs.php)
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Verify connection
if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    header("HTTP/1.0 500 Internal Server Error");
    die("Database connection error. Please try again later.");
}

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$employerId = (int)($_SESSION['employer_id'] ?? 0);
$employerEmail = $_SESSION['employer_email'] ?? '';

if ($job_id <= 0) {
    header('Location: my-posted-jobs-omr.php?error=invalid_job');
    exit;
}

// Get job details using direct query (like test-jobs.php) and verify ownership
$job = null;

// First try: Direct query with employer verification
$directQuery = "SELECT j.*, e.company_name, e.contact_person, e.email as employer_email, 
                       e.phone as employer_phone, c.name as category_name
                FROM job_postings j
                LEFT JOIN employers e ON j.employer_id = e.id
                LEFT JOIN job_categories c ON j.category = c.slug
                WHERE j.id = {$job_id} AND j.employer_id = {$employerId}";

$directResult = $conn->query($directQuery);

if ($directResult && $directResult->num_rows > 0) {
    $job = $directResult->fetch_assoc();
} else {
    // Fallback: Try by email if employer_id doesn't match
    if (!empty($employerEmail)) {
        $fallbackQuery = "SELECT j.*, e.company_name, e.contact_person, e.email as employer_email, 
                                 e.phone as employer_phone, c.name as category_name
                          FROM job_postings j
                          LEFT JOIN employers e ON j.employer_id = e.id
                          LEFT JOIN job_categories c ON j.category = c.slug
                          WHERE j.id = {$job_id} AND e.email = '" . $conn->real_escape_string($employerEmail) . "'";
        
        $fallbackResult = $conn->query($fallbackQuery);
        if ($fallbackResult && $fallbackResult->num_rows > 0) {
            $job = $fallbackResult->fetch_assoc();
        }
    }
}

// If job not found or doesn't belong to this employer, redirect
if (!$job) {
    header('Location: my-posted-jobs-omr.php?error=not_found');
    exit;
}

// Verify ownership again
if ($job['employer_id'] != $employerId) {
    // Double check by email
    if (!empty($employerEmail) && $job['employer_email'] !== $employerEmail) {
        header('Location: my-posted-jobs-omr.php?error=unauthorized');
        exit;
    }
}

// Get applications for this job using direct query
$applications = [];

$applicationsQuery = "SELECT * FROM job_applications WHERE job_id = {$job_id} ORDER BY applied_at DESC";
$applicationsResult = $conn->query($applicationsQuery);

if ($applicationsResult && $applicationsResult->num_rows > 0) {
    $applications = $applicationsResult->fetch_all(MYSQLI_ASSOC);
}

// SEO Meta
$page_title = "Applications for " . htmlspecialchars($job['title']) . " | MyOMR";
$page_description = "View applications for your job posting on MyOMR Job Portal.";
$canonical_url = "https://myomr.in/omr-local-job-listings/view-applications-omr.php?id=" . $job_id;
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
    <!-- Poppins + Core tokens -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <style>
        .application-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .applicant-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #008552 0%, #006d42 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-right: 1rem;
        }
        .application-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .application-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }
        .meta-item i {
            color: #008552;
        }
        .cover-letter-section {
            background: #f9fafb;
            border-left: 4px solid #008552;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
        }
    </style>
</head>
<body class="modern-page">

<!-- Navigation -->
<?php require_once '../components/main-nav.php'; ?>

<!-- Hero Section -->
<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 text-white">
            <div>
                <h1 class="hero-modern-title mb-2">Job Applications</h1>
                <p class="hero-modern-subtitle mb-0">Applications for: <?php echo htmlspecialchars($job['title']); ?></p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light" href="employer-dashboard-omr.php?job_id=<?php echo $job_id; ?>">
                    <i class="fas fa-briefcase me-2"></i>Unified Dashboard
                </a>
                <a class="btn btn-outline-light" href="my-posted-jobs-omr.php">
                    <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                </a>
                <a class="btn btn-outline-light" href="job-detail-omr.php?id=<?php echo $job_id; ?>" target="_blank">
                    <i class="fas fa-eye me-2"></i>View Job Posting
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="py-5">
    <div class="container">
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>Application status updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php
                $errorMsg = 'An error occurred.';
                if ($_GET['error'] === 'invalid') $errorMsg = 'Invalid request.';
                elseif ($_GET['error'] === 'unauthorized') $errorMsg = 'You do not have permission to perform this action.';
                elseif ($_GET['error'] === 'update_failed') $errorMsg = 'Failed to update application status.';
                echo htmlspecialchars($errorMsg);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Job Info Card -->
        <div class="card-modern p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="h5 mb-2"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p class="text-muted mb-2">
                        <i class="fas fa-building me-2"></i><?php echo htmlspecialchars($job['company_name']); ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($job['location']); ?>
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($job['category_name'] ?? $job['category']); ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-briefcase me-2"></i><?php echo htmlspecialchars($job['job_type']); ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stat-card p-3">
                        <small>Total Applications</small>
                        <div class="stat-value"><?php echo count($applications); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Applications List -->
        <?php if (!empty($applications)): ?>
            <div class="mb-4">
                <h2 class="h4 mb-3">
                    <i class="fas fa-users me-2"></i>Applications (<?php echo count($applications); ?>)
                </h2>
                
                <?php foreach ($applications as $app): ?>
                    <div class="application-card">
                        <div class="application-header">
                            <div class="applicant-avatar">
                                <?php echo strtoupper(substr($app['applicant_name'], 0, 1)); ?>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="h5 mb-1"><?php echo htmlspecialchars($app['applicant_name']); ?></h4>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($app['applicant_email']); ?>
                                </p>
                            </div>
                            <div>
                                <?php
                                $status = strtolower($app['status'] ?? 'pending');
                                $badgeClass = 'secondary';
                                if ($status === 'shortlisted') $badgeClass = 'success';
                                elseif ($status === 'reviewed') $badgeClass = 'info';
                                elseif ($status === 'rejected') $badgeClass = 'danger';
                                elseif ($status === 'pending') $badgeClass = 'warning';
                                ?>
                                <span class="badge bg-<?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                            </div>
                        </div>
                        
                        <div class="application-meta">
                            <div class="meta-item">
                                <i class="fas fa-phone"></i>
                                <span><?php echo htmlspecialchars($app['applicant_phone']); ?></span>
                            </div>
                            <?php if (!empty($app['experience_years'])): ?>
                            <div class="meta-item">
                                <i class="fas fa-briefcase"></i>
                                <span><?php echo (int)$app['experience_years']; ?> years experience</span>
                            </div>
                            <?php endif; ?>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Applied <?php echo date('M j, Y g:i A', strtotime($app['applied_at'])); ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($app['cover_letter'])): ?>
                        <div class="cover-letter-section">
                            <h5 class="h6 mb-2"><i class="fas fa-file-alt me-2"></i>Cover Letter</h5>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-3 d-flex gap-2">
                            <a href="mailto:<?php echo htmlspecialchars($app['applicant_email']); ?>?subject=Re: Application for <?php echo urlencode($job['title']); ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-envelope me-1"></i>Email Candidate
                            </a>
                            <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $app['applicant_phone'])); ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-phone me-1"></i>Call Candidate
                            </a>
                            <?php if ($status === 'pending'): ?>
                            <form method="POST" action="update-application-status-omr.php" class="d-inline" onsubmit="return confirm('Mark this application as reviewed?');">
                                <input type="hidden" name="application_id" value="<?php echo (int)$app['id']; ?>">
                                <input type="hidden" name="status" value="reviewed">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-check me-1"></i>Mark as Reviewed
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Applications -->
            <div class="card-modern p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-inbox fa-4x text-muted"></i>
                </div>
                <h3 class="h4 mb-3">No Applications Yet</h3>
                <p class="text-muted mb-4">
                    You haven't received any applications for this job posting yet. Share your job posting to reach more candidates!
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="job-detail-omr.php?id=<?php echo $job_id; ?>" class="btn btn-primary" target="_blank">
                        <i class="fas fa-share-alt me-2"></i>Share Job Posting
                    </a>
                    <a href="my-posted-jobs-omr.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<!-- Footer -->
<?php require_once '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

