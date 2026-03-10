<?php
/**
 * MyOMR Job Portal - Edit Job Form
 * Employers can edit their existing job postings
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Require employer authentication
require_once __DIR__ . '/includes/employer-auth.php';
requireEmployerAuth();

// Include helper functions
require_once __DIR__ . '/includes/job-functions-omr.php';

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
    header('Location: my-posted-jobs-omr.php');
    exit;
}

// Get job details using direct query (like test-jobs.php)
$job = null;

// First try: Direct query with employer verification
$directQuery = "SELECT j.*, e.company_name, e.contact_person, e.email as employer_email, 
                       e.phone as employer_phone, e.address as company_address, e.website,
                       c.name as category_name
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
                                 e.phone as employer_phone, e.address as company_address, e.website,
                                 c.name as category_name
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

// Start session for CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get categories for dropdown
$categories = [];
try {
    $categories = getJobCategories();
    if (!is_array($categories)) {
        $categories = [];
    }
} catch (Exception $e) {
    error_log("Error getting job categories: " . $e->getMessage());
    $categories = [];
}

// SEO Meta
$page_title = "Edit Job Posting - " . htmlspecialchars($job['title']) . " | MyOMR";
$page_description = "Edit your job posting on MyOMR Job Portal.";
$canonical_url = "https://myomr.in/omr-local-job-listings/edit-job-omr.php?id=" . $job_id;

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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
    <link rel="stylesheet" href="assets/post-job-form-modern.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <style>
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .form-section h3 {
            color: #008552;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body class="post-job-page">

<!-- Navigation -->
<?php require_once '../components/main-nav.php'; ?>

<!-- Main Content -->
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="h3 mb-2"><i class="fas fa-edit me-2"></i>Edit Job Posting</h1>
                    <p class="mb-0">Update your job listing: <?php echo htmlspecialchars($job['title']); ?></p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="my-posted-jobs-omr.php" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            
            <form method="POST" action="process-job-omr.php" id="edit-job-form" class="needs-validation" novalidate>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                <input type="hidden" name="action" value="update">
                
                <!-- Company Information -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Company Information</h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="company_name" class="form-label-modern required-field">Company Name</label>
                                <input type="text" class="form-control-modern" id="company_name" name="company_name" required value="<?php echo htmlspecialchars($job['company_name'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="contact_person" class="form-label-modern required-field">Contact Person</label>
                                <input type="text" class="form-control-modern" id="contact_person" name="contact_person" required value="<?php echo htmlspecialchars($job['contact_person'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="phone" class="form-label-modern required-field">Phone Number</label>
                                <input type="tel" class="form-control-modern" id="phone" name="phone" required value="<?php echo htmlspecialchars($job['employer_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required value="<?php echo htmlspecialchars($job['employer_email'] ?? ''); ?>" readonly>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="website" class="form-label-modern">Website (Optional)</label>
                                <input type="url" class="form-control-modern" id="website" name="website" value="<?php echo htmlspecialchars($job['website'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="address" class="form-label-modern">Company Address (Optional)</label>
                                <textarea class="form-control-modern" id="address" name="address" rows="2"><?php echo htmlspecialchars($job['company_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Job Details -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>Job Details</h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="title" class="form-label-modern required-field">Job Title</label>
                                <input type="text" class="form-control-modern" id="title" name="title" required value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="category" class="form-label-modern required-field">Category</label>
                                <select class="form-select-modern" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['slug']); ?>" 
                                                <?php echo ($job['category'] === $category['slug']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="job_type" class="form-label-modern required-field">Job Type</label>
                                <select class="form-select-modern" id="job_type" name="job_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-time" <?php echo ($job['job_type'] === 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                                    <option value="Part-time" <?php echo ($job['job_type'] === 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                                    <option value="Contract" <?php echo ($job['job_type'] === 'Contract') ? 'selected' : ''; ?>>Contract</option>
                                    <option value="Internship" <?php echo ($job['job_type'] === 'Internship') ? 'selected' : ''; ?>>Internship</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="location" class="form-label-modern required-field">Location</label>
                                <input type="text" class="form-control-modern" id="location" name="location" required value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="salary_range" class="form-label-modern">Salary Range (Optional)</label>
                                <input type="text" class="form-control-modern" id="salary_range" name="salary_range" value="<?php echo htmlspecialchars($job['salary_range'] ?? 'Not Disclosed'); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="description" class="form-label-modern required-field">Job Description</label>
                                <textarea class="form-control-modern" id="description" name="description" rows="6" required><?php echo htmlspecialchars($job['description'] ?? ''); ?></textarea>
                                <span class="char-counter"><span id="desc-count"><?php echo strlen($job['description'] ?? ''); ?></span> chars</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="requirements" class="form-label-modern">Requirements (Optional)</label>
                                <textarea class="form-control-modern" id="requirements" name="requirements" rows="4"><?php echo htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="benefits" class="form-label-modern">Benefits & Perks (Optional)</label>
                                <textarea class="form-control-modern" id="benefits" name="benefits" rows="4"><?php echo htmlspecialchars($job['benefits'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="application_deadline" class="form-label-modern">Application Deadline (Optional)</label>
                                <input type="date" class="form-control-modern" id="application_deadline" name="application_deadline" 
                                       value="<?php echo $job['application_deadline'] ? date('Y-m-d', strtotime($job['application_deadline'])) : ''; ?>"
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="form-section">
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-save"></i>
                            <span>Update Job Posting</span>
                        </button>
                        <a href="my-posted-jobs-omr.php" class="btn-modern btn-modern-secondary">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </a>
                        <a href="job-detail-omr.php?id=<?php echo $job_id; ?>" class="btn-modern btn-modern-secondary" target="_blank">
                            <i class="fas fa-eye"></i>
                            <span>Preview</span>
                        </a>
                    </div>
                </div>
                
            </form>
            
        </div>
    </section>
    
</main>

<!-- Footer -->
<?php require_once '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.js"></script>

<!-- Form Validation -->
<script>
(function() {
    'use strict';
    const form = document.getElementById('edit-job-form');
    if (!form) return;
    
    const description = document.getElementById('description');
    const descCounter = document.getElementById('desc-count');
    
    if (description && descCounter) {
        description.addEventListener('input', function() {
            descCounter.textContent = this.value.length;
        });
    }
    
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
})();
</script>

</body>
</html>

