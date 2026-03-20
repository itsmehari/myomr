<?php
/**
 * MyOMR Job Portal - Post Job Form
 * Employers can post job vacancies through this form
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Require employer authentication before posting
require_once __DIR__ . '/includes/employer-auth.php';
requireEmployerAuth();

// Bootstrap
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

// Include helper functions
require_once __DIR__ . '/includes/job-functions-omr.php';

// Start session for CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load DB connection and fetch employer profile + categories
require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

// Pre-fill form with employer's saved profile data (+ plan columns for usage line when on Employer Pack)
$employer_profile = [];
$post_job_plan_used = null;
$post_job_plan_cap = null;
if (!empty($_SESSION['employer_id']) && $conn instanceof mysqli) {
    $cols = "company_name, contact_person, email, phone, address, website";
    if (jobEmployersTableHasPlanColumns($conn)) {
        $cols .= ", plan_type, plan_end_date";
    }
    $empStmt = $conn->prepare("SELECT {$cols} FROM employers WHERE id = ? LIMIT 1");
    if ($empStmt) {
        $empStmt->bind_param('i', $_SESSION['employer_id']);
        $empStmt->execute();
        $employer_profile = $empStmt->get_result()->fetch_assoc() ?: [];
        if (jobEmployersTableHasPlanColumns($conn) && !empty($employer_profile) && isEmployerOnActivePlan($employer_profile)) {
            $post_job_plan_cap = getPlanCap($employer_profile['plan_type']);
            if ($post_job_plan_cap > 0) {
                $post_job_plan_used = countJobsThisMonthForEmployer($conn, (int)$_SESSION['employer_id']);
            }
        }
    }
}

// Helper: return pre-filled value or empty string
$pre = static function (string $field) use ($employer_profile): string {
    return htmlspecialchars($employer_profile[$field] ?? '', ENT_QUOTES, 'UTF-8');
};

$categories = getJobCategories();

// SEO Meta
$page_title = "Post Job Vacancy in OMR - Reach 1000+ Job Seekers | MyOMR";
$page_description = "Post your job vacancy on MyOMR. Reach qualified candidates in OMR Chennai. Free job listings for employers.";
$canonical_url = "https://myomr.in/omr-local-job-listings/post-job-omr.php";

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
    <meta name="keywords" content="post job OMR, job vacancy Chennai, hire employees OMR, job listing OMR, Old Mahabalipuram Road jobs">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- GEO (local SEO) -->
    <meta name="geo.region" content="IN-TN">
    <meta name="geo.placename" content="Chennai, Old Mahabalipuram Road, OMR">
    <meta name="geo.position" content="12.9064;80.2322">
    <meta name="ICBM" content="12.9064, 80.2322">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
    <!-- AEO: Structured data for AI/search engines -->
    <script type="application/ld+json">
    <?php
    $postJobSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $page_title,
        'description' => $page_description,
        'url' => $canonical_url,
        'inLanguage' => 'en-IN',
        'about' => [
            '@type' => 'Place',
            'name' => 'Old Mahabalipuram Road, Chennai',
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 12.9064, 'longitude' => 80.2322]
        ]
    ];
    echo json_encode($postJobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    ?>
    </script>
    <!-- Google Analytics -->
    <?php $ga_user_id = (int)($_SESSION['employer_id'] ?? 0); $ga_user_properties = ['user_type' => 'employer']; include ROOT_PATH . '/components/analytics.php'; ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins + Core tokens -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <!-- Modern Form CSS -->
    <link rel="stylesheet" href="assets/post-job-form-modern.css">
    <!-- Universal Footer Styles -->
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
        .help-text {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .form-section label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .progress-step {
            flex: 1;
            text-align: center;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            margin: 0 0.5rem;
            position: relative;
        }
        .progress-step.active {
            background: #e7f5e7;
            border: 2px solid #22c55e;
        }
        .progress-step.completed {
            background: #d1fae5;
            border: 2px solid #008552;
        }
        .progress-step::after {
            content: '→';
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: #9ca3af;
        }
        .progress-step:last-child::after {
            display: none;
        }
    </style>
</head>
<body class="post-job-page">

<!-- Navigation -->
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<!-- Skip Link -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Main Content -->
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="h3 mb-2"><i class="fas fa-briefcase me-2"></i>Post Your Job Opening</h1>
                    <p class="mb-0">Reach 1000+ qualified candidates in the OMR area</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="index.php" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Jobs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            
            <!-- Progress Indicator -->
            <div class="progress-indicator active-step-1">
                <div class="progress-step active">
                    <div class="progress-step-circle">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="progress-step-label">Step 1</div>
                    <div class="progress-step-subtitle">Basic Info</div>
                </div>
                <div class="progress-step">
                    <div class="progress-step-circle">
                        <i class="fas fa-file-text"></i>
                    </div>
                    <div class="progress-step-label">Step 2</div>
                    <div class="progress-step-subtitle">Details</div>
                </div>
                <div class="progress-step">
                    <div class="progress-step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="progress-step-label">Step 3</div>
                    <div class="progress-step-subtitle">Review</div>
                </div>
            </div>
            
            <?php if ($post_job_plan_cap !== null && $post_job_plan_used !== null): ?>
            <div class="card-modern p-3 mb-4">
                <span class="text-muted">Employer Pack usage this month:</span>
                <span class="stat-value" style="font-size:1rem;">Used <strong><?php echo (int)$post_job_plan_used; ?>/<?php echo (int)$post_job_plan_cap; ?></strong> job posts</span>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <?php if (empty($categories)): ?>
                <div class="alert alert-warning mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No job categories found. Please run <code>UPDATE job_categories SET is_active = 1;</code> in phpMyAdmin, then refresh.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="process-job-omr.php" id="post-job-form" class="needs-validation" novalidate>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Two-Column Layout: Company Information & Job Details -->
                <div class="form-sections-wrapper">
                    <!-- Company Information -->
                    <div class="form-section form-section-column">
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
                                <input type="text" class="form-control-modern" id="company_name" name="company_name" required
                                       value="<?php echo $pre('company_name'); ?>"
                                       placeholder="Enter your company name">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> Your company or organization name</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide your company name.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="contact_person" class="form-label-modern required-field">Contact Person</label>
                                <input type="text" class="form-control-modern" id="contact_person" name="contact_person" required
                                       value="<?php echo $pre('contact_person'); ?>"
                                       placeholder="Your name">
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide your name.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="phone" class="form-label-modern required-field">Phone Number</label>
                                <input type="tel" class="form-control-modern" id="phone" name="phone" required
                                       pattern="[\d\s\-\+\(\)]+" minlength="10" maxlength="15"
                                       value="<?php echo $pre('phone'); ?>"
                                       placeholder="+91 9876543210">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> Format: +91 9876543210 or 9876543210</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a valid phone number.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required
                                       value="<?php echo $pre('email'); ?>"
                                       placeholder="contact@company.com">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> We'll use this to contact you about applications</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a valid email address.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="website" class="form-label-modern">Website <span class="text-muted">(Optional)</span></label>
                                <input type="url" class="form-control-modern" id="website" name="website"
                                       value="<?php echo $pre('website'); ?>"
                                       placeholder="https://example.com">
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a valid URL.</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="address" class="form-label-modern">Company Address <span class="text-muted">(Optional)</span></label>
                                <textarea class="form-control-modern" id="address" name="address" rows="2"
                                          placeholder="Enter company address"><?php echo $pre('address'); ?></textarea>
                            </div>
                        </div>

                        <?php if (!empty($employer_profile)): ?>
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 px-3 small">
                                <i class="fas fa-magic me-1"></i>
                                Fields pre-filled from your employer profile.
                                <a href="edit-employer-profile-omr.php" class="alert-link ms-1">Update profile</a>
                            </div>
                        </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Job Details -->
                    <div class="form-section form-section-column">
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
                                <input type="text" class="form-control-modern" id="title" name="title" required placeholder="e.g., Software Developer, Marketing Manager">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> Be specific and clear about the role</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a job title.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="category" class="form-label-modern required-field">Category</label>
                                <select class="form-select-modern" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <?php if (empty($categories)): ?>
                                        <option value="" disabled>⚠️ No categories available - Please check database</option>
                                        <?php if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE): ?>
                                            <option value="" disabled>Debug: Categories array is empty. Check job_categories table.</option>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if (isset($category['slug']) && isset($category['name'])): ?>
                                                <option value="<?php echo htmlspecialchars($category['slug']); ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please select a category.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="job_type" class="form-label-modern required-field">Job Type</label>
                                <select class="form-select-modern" id="job_type" name="job_type" required>
                                    <option value="">Select Type</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="internship">Internship</option>
                                    <option value="walk-in">Walk-in</option>
                                </select>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please select a job type.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="work_segment" class="form-label-modern required-field">Job Segment</label>
                                <select class="form-select-modern" id="work_segment" name="work_segment" required>
                                    <option value="">Select Segment</option>
                                    <option value="office">Office / White-collar</option>
                                    <option value="manpower">Manpower / Blue-collar</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> Helps job seekers find the right kind of work faster.</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please select a job segment.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="location" class="form-label-modern required-field">Location</label>
                                <input type="text" class="form-control-modern" id="location" name="location" required placeholder="e.g., Sholinganallur, OMR">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> Specific location in OMR area</div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a location.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="salary_range" class="form-label-modern">Salary Range <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control-modern" id="salary_range" name="salary_range" placeholder="e.g., ₹30,000 - ₹50,000">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> You can leave this as "Not Disclosed"</div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label for="description" class="form-label-modern required-field">Job Description</label>
                                <textarea class="form-control-modern" id="description" name="description" rows="6" required placeholder="Describe the role, responsibilities, and what makes it great"></textarea>
                                <div class="help-text-modern">
                                    <i class="fas fa-info-circle"></i> Describe the role, responsibilities, and what makes it great (minimum 50 characters)
                                    <span class="char-counter"><span id="desc-count">0</span> chars</span>
                                </div>
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a job description (at least 50 characters).</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="requirements" class="form-label-modern">Requirements <span class="text-muted">(Optional)</span></label>
                                <textarea class="form-control-modern" id="requirements" name="requirements" rows="4" placeholder="e.g., 2+ years experience, Bachelor's degree, etc."></textarea>
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> List required skills, qualifications, and experience</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="benefits" class="form-label-modern">Benefits & Perks <span class="text-muted">(Optional)</span></label>
                                <textarea class="form-control-modern" id="benefits" name="benefits" rows="4" placeholder="e.g., Health insurance, Flexible hours, Training"></textarea>
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> What benefits do you offer employees?</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="application_deadline" class="form-label-modern">Application Deadline <span class="text-muted">(Optional)</span></label>
                                <input type="date" class="form-control-modern" id="application_deadline" name="application_deadline" min="<?php echo date('Y-m-d'); ?>">
                                <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> Please provide a valid future date.</div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- End Two-Column Layout -->
                
                <!-- Terms & Submit -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3>Review & Submit</h3>
                    </div>
                    
                    <div class="alert-modern">
                        <h5><i class="fas fa-info-circle"></i>Important Information</h5>
                        <ul>
                            <li>Your job posting will be reviewed by our team before being published</li>
                            <li>Review typically takes 24-48 hours</li>
                            <li>You'll receive an email notification once your job is approved</li>
                            <li>False or misleading job postings will be rejected</li>
                        </ul>
                    </div>
                    
                    <div class="form-group-modern">
                        <div class="form-check-modern d-flex align-items-start">
                            <input class="form-check-input-modern" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label-modern" for="terms">
                                I agree to the <a href="/terms-and-conditions-my-omr.php" target="_blank" class="text-primary">Terms and Conditions</a> and confirm that this job posting is accurate
                            </label>
                            <div class="invalid-feedback-modern"><i class="fas fa-exclamation-circle"></i> You must agree to the terms to submit.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit Job Posting</span>
                        </button>
                        <a href="index.php" class="btn-modern btn-modern-secondary">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <div class="security-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>Your information is secure and will not be shared with third parties</span>
                        </div>
                    </div>
                </div>
                
            </form>
            
        </div>
    </section>
    
</main>

<!-- Footer -->
<?php omr_footer(); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Analytics Events -->
<script>
  function gtagSend(name, params){
    try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
  }

  // form_start — fires once when user first interacts with any field
  (function() {
    var started = false;
    var form = document.getElementById('post-job-form');
    if (!form) return;
    form.addEventListener('focusin', function() {
      if (started) return;
      started = true;
      gtagSend('form_start', { 'form_name': 'post_job' });
    });
  })();

  document.getElementById('post-job-form')?.addEventListener('submit', function(){
    const title = document.getElementById('title')?.value || '';
    const category = document.getElementById('category')?.value || '';
    const workSegment = document.getElementById('work_segment')?.value || '';
    gtagSend('job_post_submit', { title_length: title.length, category: category, work_segment: workSegment });
  });
</script>

<!-- Form Validation -->
<script>
(function() {
    'use strict';
    
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');
    
    // Real-time validation function - hide errors when fields become valid
    function validateField(field, form) {
        const feedback = field.parentElement ? field.parentElement.querySelector('.invalid-feedback-modern') : null;
        
        if (field.checkValidity()) {
            // Field is valid - hide error message
            if (feedback) {
                feedback.style.display = 'none';
            }
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            // Field is invalid - only show error if form was already validated
            if (form && form.classList.contains('was-validated') && feedback) {
                feedback.style.display = 'flex';
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
            }
        }
    }
    
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        // Add real-time validation to all inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            // Validate on blur (when user leaves field)
            input.addEventListener('blur', function() {
                validateField(this, form);
            });
            
            // Also validate on input (real-time) after first validation
            input.addEventListener('input', function() {
                validateField(this, form);
            });
        });
        
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Show all validation errors
                form.classList.add('was-validated');
                
                // Validate all fields to show/hide errors
                inputs.forEach(input => {
                    validateField(input, form);
                });
                
                // Focus on first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    setTimeout(() => {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                }
            } else {
                form.classList.add('was-validated');
            }
        }, false);
    });
    
    // Character count for description with modern UI
    const description = document.getElementById('description');
    const descCounter = document.getElementById('desc-count');
    if (description && descCounter) {
        description.addEventListener('input', function() {
            const count = this.value.length;
            const minLength = 50;
            
            // Update counter
            descCounter.textContent = count;
            const counterEl = descCounter.closest('.char-counter');
            
            if (count < minLength) {
                counterEl.className = 'char-counter danger';
                this.setCustomValidity('Description must be at least 50 characters.');
            } else if (count < minLength * 1.2) {
                counterEl.className = 'char-counter warning';
                this.setCustomValidity('');
            } else {
                counterEl.className = 'char-counter success';
                this.setCustomValidity('');
            }

            // Trigger field re-validation to hide/show feedback immediately
            const form = this.closest('form');
            if (form) { validateField(this, form); }
        });
        
        // Initialize counter
        descCounter.textContent = description.value.length;
    } else if (description) {
        // Fallback if counter element not found
        description.addEventListener('input', function() {
            const count = this.value.length;
            const minLength = 50;
            
            if (count < minLength) {
                this.setCustomValidity('Description must be at least 50 characters.');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Progress indicator update on scroll
    const progressIndicator = document.querySelector('.progress-indicator');
    const formSections = document.querySelectorAll('.form-section');
    
    function updateProgressIndicator() {
        const scrollPos = window.scrollY + window.innerHeight / 3;
        let activeSection = 1;
        
        formSections.forEach((section, index) => {
            const sectionTop = section.offsetTop;
            if (scrollPos >= sectionTop) {
                activeSection = index + 1;
            }
        });
        
        progressIndicator.className = `progress-indicator active-step-${activeSection}`;
        progressIndicator.querySelectorAll('.progress-step').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index < activeSection - 1) {
                step.classList.add('completed');
            } else if (index === activeSection - 1) {
                step.classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', updateProgressIndicator);
    updateProgressIndicator();
    
    // Smooth scroll to sections
    document.querySelectorAll('.progress-step').forEach((step, index) => {
        step.addEventListener('click', () => {
            if (formSections[index]) {
                formSections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // Phone number validation
    const phone = document.getElementById('phone');
    if (phone) {
        phone.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d\s\-\+\(\)]/g, '');
        });
    }

    // Auto-suggest segment from category (still editable by employer)
    const categorySelect = document.getElementById('category');
    const segmentSelect = document.getElementById('work_segment');
    if (categorySelect && segmentSelect) {
        const manpowerCategories = ['hospitality', 'construction', 'logistics', 'housekeeping', 'security'];
        categorySelect.addEventListener('change', function() {
            if (segmentSelect.value) return;
            segmentSelect.value = manpowerCategories.includes(this.value) ? 'manpower' : 'office';
        });
    }
    
})();
</script>

</body>
</html>

