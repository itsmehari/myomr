<?php
/**
 * MyOMR Job Portal - Primary Landing Page
 * Jobs in OMR Chennai - Main Landing Page
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 * Phase 1: Primary Landing Page
 */

// Enable error reporting for development
require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';

// Include helper functions
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';

// Bootstrap: central path + component helpers (omr_nav, omr_footer)
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

// Load database connection
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

// Get statistics
$total_jobs = 0;
$total_employers = 0;
$total_applications = 0;
$featured_jobs = [];

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    // Get total approved jobs
    $jobsQuery = $conn->query("SELECT COUNT(*) as total FROM job_postings WHERE status = 'approved'");
    if ($jobsQuery) {
        $row = $jobsQuery->fetch_assoc();
        $total_jobs = (int)($row['total'] ?? 0);
    }
    
    // Get total employers
    $employersQuery = $conn->query("SELECT COUNT(DISTINCT id) as total FROM employers");
    if ($employersQuery) {
        $row = $employersQuery->fetch_assoc();
        $total_employers = (int)($row['total'] ?? 0);
    }
    
    // Get total applications
    $applicationsQuery = $conn->query("SELECT COUNT(*) as total FROM job_applications");
    if ($applicationsQuery) {
        $row = $applicationsQuery->fetch_assoc();
        $total_applications = (int)($row['total'] ?? 0);
    }
    
    // Get featured jobs (6 latest approved jobs)
    $featuredQuery = "SELECT j.*, e.company_name, e.contact_person
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved'
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 6";
    $featuredResult = $conn->query($featuredQuery);
    if ($featuredResult && $featuredResult->num_rows > 0) {
        $featured_jobs = $featuredResult->fetch_all(MYSQLI_ASSOC);
    }
}

// Get categories for quick links
$categories = getJobCategories();

// SEO Meta
$page_title = "Jobs in OMR Chennai - Find Your Dream Job | MyOMR";
$page_description = "Find 500+ jobs in OMR Chennai. IT, Teaching, Healthcare & more. Free job listings. Apply directly to employers. OMR's local job portal - Perungudi, Sholinganallur, Navalur.";
$canonical_url = "https://myomr.in/jobs-in-omr-chennai.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="jobs in OMR Chennai, jobs in OMR, IT jobs OMR, teaching jobs OMR, healthcare jobs OMR, fresher jobs OMR, part time jobs OMR, local job portal, OMR job portal">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.png">
    
    <!-- Google Analytics -->
    <?php include 'components/analytics.php'; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <!-- Job Portal Styles -->
    <link rel="stylesheet" href="omr-local-job-listings/assets/job-listings-omr.css">
    <link rel="stylesheet" href="omr-local-job-listings/assets/omr-jobs-unified-design.css">
    <!-- Footer Styles -->
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <!-- Landing page styles are in job-listings-omr.css (extracted from inline) -->
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MyOMR Job Portal",
        "url": "<?php echo $canonical_url; ?>",
        "description": "<?php echo $page_description; ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://myomr.in/omr-local-job-listings/?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://myomr.in/"},
            {"@type": "ListItem", "position": 2, "name": "Jobs in OMR", "item": "<?php echo $canonical_url; ?>"}
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "My OMR",
        "url": "https://myomr.in/",
        "logo": "https://myomr.in/My-OMR-Logo.png",
        "sameAs": [
            "https://www.facebook.com/MyOMR.in",
            "https://www.instagram.com/myomr.in",
            "https://x.com/MyomrNews"
        ]
    }
    </script>
</head>
<body class="modern-page">

<!-- Navigation -->
<?php omr_nav('main'); ?>

<!-- Skip Link -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Main Content -->
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="hero-landing">
        <div class="container">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="hero-title">Find Your Dream Job in OMR Chennai</h1>
                        <p class="hero-subtitle">Connect with top employers in Old Mahabalipuram Road. Browse <?php echo number_format($total_jobs); ?>+ job opportunities across IT, Teaching, Healthcare, and more. <strong>100% Free to Use.</strong></p>
                        
                        <!-- Quick Search Form -->
                        <div class="hero-search-form">
                            <form method="GET" action="/omr-local-job-listings/" role="search">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <label for="search" class="form-label visually-hidden">Search jobs</label>
                                        <input type="text" 
                                               id="search" 
                                               name="search" 
                                               class="form-control form-control-lg" 
                                               placeholder="Job title, company, or keywords">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="location" class="form-label visually-hidden">Location</label>
                                        <input type="text" 
                                               id="location" 
                                               name="location" 
                                               class="form-control form-control-lg" 
                                               placeholder="Location (OMR, Chennai)">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-search me-1"></i> Search Jobs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a href="/omr-local-job-listings/?job_type=full-time" class="btn btn-outline-light btn-sm">Full-Time Jobs</a>
                            <a href="/omr-local-job-listings/?job_type=part-time" class="btn btn-outline-light btn-sm">Part-Time Jobs</a>
                            <a href="/omr-local-job-listings/?category=it" class="btn btn-outline-light btn-sm">IT Jobs</a>
                            <a href="/omr-local-job-listings/?category=teaching" class="btn btn-outline-light btn-sm">Teaching Jobs</a>
                            <a href="/omr-local-job-listings/" class="btn btn-outline-light btn-sm">View All Jobs</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <!-- Statistics -->
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number"><?php echo number_format($total_jobs); ?>+</div>
                                    <div class="stat-label">Active Jobs</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number"><?php echo number_format($total_employers); ?>+</div>
                                    <div class="stat-label">Employers</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number"><?php echo number_format($total_applications); ?>+</div>
                                    <div class="stat-label">Applications</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-number">100%</div>
                                    <div class="stat-label">Free to Use</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section (Job Seekers) -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="h3 mb-3">Struggling to Find Jobs in OMR?</h2>
                    <p class="text-muted">We understand the challenges job seekers face. Here's how we help:</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="problem-card">
                        <h5 class="mb-2"><i class="fas fa-car text-danger me-2"></i>Long Commute to City Jobs</h5>
                        <p class="mb-0 text-muted">Spending 2-3 hours daily traveling to Chennai city? Find jobs in OMR area - close to home, better work-life balance.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="problem-card">
                        <h5 class="mb-2"><i class="fas fa-search text-danger me-2"></i>Limited Local Opportunities</h5>
                        <p class="mb-0 text-muted">Major job portals show jobs across entire Chennai. We focus only on OMR - Perungudi, Sholinganallur, Navalur, and more.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="problem-card">
                        <h5 class="mb-2"><i class="fas fa-envelope text-danger me-2"></i>No Response from Employers</h5>
                        <p class="mb-0 text-muted">Applying to jobs but getting no response? Connect directly with OMR employers through our platform.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="problem-card">
                        <h5 class="mb-2"><i class="fas fa-user-graduate text-danger me-2"></i>Difficulty Finding Fresher Jobs</h5>
                        <p class="mb-0 text-muted">Most portals prioritize experienced candidates. We have entry-level opportunities for freshers and recent graduates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="h3 mb-3">Why Use MyOMR.in Job Portal?</h2>
                    <p class="text-muted">OMR's local job portal - built for job seekers and employers in Old Mahabalipuram Road.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-map-marker-alt text-success me-2"></i>OMR-Specific Jobs</h5>
                        <p class="mb-0 text-muted">Find jobs specifically in OMR area - Perungudi, Sholinganallur, Navalur, Thoraipakkam, and more. No need to commute to city.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-gift text-success me-2"></i>100% Free to Use</h5>
                        <p class="mb-0 text-muted">Free job search, free applications, free job alerts. No hidden fees, no premium subscriptions required.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-handshake text-success me-2"></i>Direct Employer Contact</h5>
                        <p class="mb-0 text-muted">Connect directly with employers in OMR. No middlemen, faster response times, better communication.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-clock text-success me-2"></i>Fresh Job Listings</h5>
                        <p class="mb-0 text-muted">Get daily updates with new job opportunities. Never miss a chance with our real-time job alerts.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-mobile-alt text-success me-2"></i>Mobile-Friendly</h5>
                        <p class="mb-0 text-muted">Search and apply for jobs on any device. Responsive design works perfectly on mobile, tablet, and desktop.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="benefit-card">
                        <h5 class="mb-2"><i class="fas fa-user-friends text-success me-2"></i>Local Community</h5>
                        <p class="mb-0 text-muted">Part of MyOMR.in community platform. Trusted by OMR residents and businesses. Local support, local expertise.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <?php if (!empty($featured_jobs)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="h3 mb-3">Latest Job Opportunities</h2>
                    <p class="text-muted">Browse recently posted jobs in OMR Chennai</p>
                </div>
            </div>
            <div class="row g-4">
                <?php foreach ($featured_jobs as $job): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="featured-job-card">
                        <?php if (!empty($job['featured'])): ?>
                            <span class="badge bg-warning text-dark mb-2">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                        <?php endif; ?>
                        <h5 class="mb-2">
                            <a href="/omr-local-job-listings/job-detail-omr.php?id=<?php echo $job['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($job['title']); ?>
                            </a>
                        </h5>
                        <p class="text-primary mb-2 fw-semibold">
                            <?php echo htmlspecialchars($job['company_name'] ?? 'Company'); ?>
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?php echo htmlspecialchars($job['location'] ?? 'OMR'); ?>
                        </p>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1">
                                <i class="fas fa-briefcase me-1"></i>
                                <?php echo ucfirst(str_replace('-', ' ', $job['job_type'] ?? 'Full-time')); ?>
                            </span>
                            <?php if (!empty($job['salary_range']) && $job['salary_range'] !== 'Not Disclosed'): ?>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-rupee-sign me-1"></i>
                                    <?php echo formatSalary($job['salary_range']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="/omr-local-job-listings/job-detail-omr.php?id=<?php echo $job['id']; ?>" class="btn btn-success btn-sm w-100">
                            View Details & Apply
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="/omr-local-job-listings/" class="btn btn-primary btn-lg">
                    View All Jobs <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="h3 mb-3">How It Works</h2>
                    <p class="text-muted">Find and apply for jobs in OMR in 4 simple steps</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="h4 mb-0">1</span>
                        </div>
                        <h5 class="mb-2">Search Jobs</h5>
                        <p class="text-muted small">Browse jobs by category, location, or keyword. Filter by job type, salary, and experience level.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="h4 mb-0">2</span>
                        </div>
                        <h5 class="mb-2">View Details</h5>
                        <p class="text-muted small">Read job descriptions, requirements, salary, and company information. Learn about the role and employer.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="h4 mb-0">3</span>
                        </div>
                        <h5 class="mb-2">Apply Online</h5>
                        <p class="text-muted small">Submit your application with resume and cover letter. Quick and easy application process.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="h4 mb-0">4</span>
                        </div>
                        <h5 class="mb-2">Get Hired</h5>
                        <p class="text-muted small">Connect directly with employers. Track your application status and get hired faster.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="text-center mb-5">
                        <h2 class="h3 mb-3">Frequently Asked Questions</h2>
                        <p class="text-muted">Everything you need to know about finding jobs in OMR</p>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I apply for jobs?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            Browse our job listings, click on a job that interests you, and click "Apply Now". Fill in your details, upload your resume, and submit. It's that simple!
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Is it free to use?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            Yes, 100% free! Job search, applications, and job alerts are completely free. No hidden fees, no premium subscriptions required.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What types of jobs are available?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            We have jobs across IT, Teaching, Healthcare, Retail, Hospitality, Sales, and more. Full-time, part-time, contract, and internship positions available.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Are there jobs for freshers?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            Yes! We have many entry-level and fresher job opportunities. Filter jobs by experience level to find positions suitable for recent graduates.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I create a job alert?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            After searching for jobs, you can set up job alerts to get notified when new jobs matching your criteria are posted. Just enter your email and job preferences.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can employers post jobs for free?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            Yes! Employers can post jobs for free on MyOMR.in. Simply register as an employer and start posting job openings to reach 1000+ job seekers in OMR.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What areas in OMR are covered?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            We cover all areas in OMR including Perungudi, Sholinganallur, Navalur, Thoraipakkam, Kelambakkam, Medavakkam, and more. All jobs are in the OMR area.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does it take to get a response?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            Response times vary by employer. Most employers respond within 1-2 weeks. You can track your application status through your profile.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Landing Pages Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="h4 mb-4 text-center">Explore More Job Opportunities</h3>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="/it-jobs-omr-chennai.php" class="card card-hover text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-laptop-code fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-0">IT Jobs in OMR</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="/jobs-in-sholinganallur-omr.php" class="card card-hover text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-0">Jobs in Sholinganallur</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="/fresher-jobs-omr-chennai.php" class="card card-hover text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-0">Fresher Jobs</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="/part-time-jobs-omr-chennai.php" class="card card-hover text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-0">Part-Time Jobs</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <h2 class="h2 mb-4">Ready to Find Your Dream Job in OMR?</h2>
                    <p class="lead mb-4">Join <?php echo number_format($total_jobs); ?>+ job opportunities and <?php echo number_format($total_employers); ?>+ employers on MyOMR.in</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="/omr-local-job-listings/" class="btn btn-light btn-lg">
                            <i class="fas fa-search me-2"></i> Search Jobs Now
                        </a>
                        <a href="/omr-local-job-listings/employer-register-omr.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-briefcase me-2"></i> Post a Job (Free)
                        </a>
                    </div>
                    <p class="mt-4 mb-0">
                        <small>
                            <i class="fas fa-shield-alt me-1"></i> 100% Free • 
                            <i class="fas fa-map-marker-alt me-1"></i> OMR Local • 
                            <i class="fas fa-users me-1"></i> Trusted by Community
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #dee2e6;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-decoration: none;
}
</style>

<!-- Footer -->
<?php omr_footer(); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FAQ Accordion Script -->
<script>
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', function() {
        const faqItem = this.closest('.faq-item');
        const isActive = faqItem.classList.contains('active');
        
        // Close all FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!isActive) {
            faqItem.classList.add('active');
        }
    });
});
</script>

<!-- Landing Page Analytics -->
<script src="/omr-local-job-listings/assets/landing-page-analytics.js"></script>

<!-- UN SDG Floating Badges -->
<?php include 'components/sdg-badge.php'; ?>

</body>
</html>

