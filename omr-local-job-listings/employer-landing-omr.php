<?php
/**
 * Employer Landing Page - Options after clicking "List a job"
 * Shows: Post New Job, Go to Dashboard options
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/employer-auth.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

// Check if employer is logged in
$isLoggedIn = isEmployerLoggedIn();

// SEO Meta
$page_title = "Hire Local Talent in OMR — Post Jobs Free | MyOMR Employer Portal";
$page_description = "Post job vacancies on OMR's #1 local job board. Reach 1000+ job seekers in Perungudi, Sholinganallur, Navalur, Thoraipakkam &amp; across the OMR corridor. Free for employers.";
$page_keywords = "post job OMR, hire talent OMR Chennai, job vacancy Old Mahabalipuram Road, employer OMR, local hiring Chennai, jobs Perungudi Sholinganallur Navalur";
$canonical_url = "https://myomr.in/omr-local-job-listings/employer-landing-omr.php";
$og_image = "https://myomr.in/My-OMR-Logo.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- GEO (local SEO) -->
    <meta name="geo.region" content="IN-TN">
    <meta name="geo.placename" content="Chennai, Old Mahabalipuram Road, OMR">
    <meta name="geo.position" content="12.9064;80.2322">
    <meta name="ICBM" content="12.9064, 80.2322">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="MyOMR">
    <meta property="og:locale" content="en_IN">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <!-- AEO: Structured data for AI/search engines -->
    <script type="application/ld+json">
    <?php
    $webPageSchema = [
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
    echo json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    ?>
    </script>
    <script type="application/ld+json">
    <?php
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'MyOMR',
        'url' => 'https://myomr.in/',
        'logo' => 'https://myomr.in/My-OMR-Logo.png'
    ];
    echo json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    ?>
    </script>
    <!-- Google Analytics 4 + Microsoft Clarity -->
    <?php
    $ga_user_id = (int)($_SESSION['employer_id'] ?? 0);
    $ga_user_properties = ['user_type' => 'employer'];
    $ga_content_group = 'Job Listings';
    include ROOT_PATH . '/components/analytics.php';
    ?>
    
    <style>
        .option-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            height: 100%;
        }
        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        .option-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="modern-page">

<?php omr_nav('main'); ?>

<section class="hero-modern py-5">
    <div class="container">
        <div class="text-center text-white">
            <?php if ($isLoggedIn): ?>
              <h1 class="hero-modern-title mb-3">Welcome back, <?php echo htmlspecialchars($_SESSION['employer_company'] ?? 'Employer'); ?>!</h1>
              <p class="hero-modern-subtitle mb-0">Manage your job listings, review applications, and find your next hire.</p>
            <?php else: ?>
              <h1 class="hero-modern-title mb-3">Hire Local Talent in OMR — Post Jobs Free</h1>
              <p class="hero-modern-subtitle mb-0">Reach qualified professionals in Perungudi, Sholinganallur, Navalur &amp; across the OMR corridor.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        
        <?php if ($isLoggedIn): ?>
            <!-- Logged In: Show Options -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="post-job-omr.php" class="text-decoration-none">
                        <div class="card-modern p-5 text-center option-card h-100">
                            <div class="option-icon" style="color:#14532d;">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3 class="h4 mb-3">Post a New Job</h3>
                            <p class="text-muted mb-0">Create a new job listing and reach qualified candidates in OMR</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="my-posted-jobs-omr.php" class="text-decoration-none">
                        <div class="card-modern p-5 text-center option-card h-100">
                            <div class="option-icon" style="color:#14532d;">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3 class="h4 mb-3">My Posted Jobs</h3>
                            <p class="text-muted mb-0">View your posted jobs and manage your listings</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="employer-dashboard-omr.php" class="text-decoration-none">
                        <div class="card-modern p-5 text-center option-card h-100">
                            <div class="option-icon" style="color:#14532d;">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="h4 mb-3">View Applications</h3>
                            <p class="text-muted mb-0">Manage all job applications with advanced filtering and bulk actions</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <?php
            $employerId = (int)($_SESSION['employer_id'] ?? 0);
            $statsQuery = $conn->query("SELECT 
                COUNT(*) as total_jobs,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_jobs,
                SUM(applications_count) as total_applications,
                SUM(views) as total_views
                FROM job_postings WHERE employer_id = {$employerId}");
            $stats = $statsQuery ? $statsQuery->fetch_assoc() : null;
            ?>
            
            <?php if ($stats && $stats['total_jobs'] > 0): ?>
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card-modern p-4 text-center">
                        <div class="h2 mb-1" style="color:#14532d;"><?php echo $stats['total_jobs']; ?></div>
                        <small class="text-muted">Total Jobs</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card-modern p-4 text-center">
                        <div class="h2 mb-1" style="color:#22c55e;"><?php echo $stats['approved_jobs']; ?></div>
                        <small class="text-muted">Active Jobs</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card-modern p-4 text-center">
                        <div class="h2 mb-1" style="color:#0891b2;"><?php echo $stats['total_applications'] ?? 0; ?></div>
                        <small class="text-muted">Applications</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card-modern p-4 text-center">
                        <div class="h2 mb-1" style="color:#d97706;"><?php echo $stats['total_views'] ?? 0; ?></div>
                        <small class="text-muted">Total Views</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Not Logged In: Full employer pitch -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-rupee-sign"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">100% Free to Post</h4>
                        <p class="text-muted mb-0">List unlimited job openings at no cost, ever. No hidden fees, no per-post charges.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-map-marker-alt"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">OMR-Specific Candidates</h4>
                        <p class="text-muted mb-0">Reach job seekers who live and work along OMR — Perungudi, Sholinganallur, Navalur, Kelambakkam, and more.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-bolt"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">Live in Under 5 Minutes</h4>
                        <p class="text-muted mb-0">Register, post your job, and start receiving applications the same day. No lengthy approval waits.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-filter"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">Filter Applications Easily</h4>
                        <p class="text-muted mb-0">Review all applications in your dashboard. Filter by experience level, job type, and more.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-bullhorn"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">Promoted to 10K+ Visitors</h4>
                        <p class="text-muted mb-0">Your job listing is visible to 10,000+ monthly visitors browsing MyOMR — OMR's most engaged local audience.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-modern p-4 h-100">
                        <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-mobile-alt"></i></div>
                        <h4 class="h5 mb-2" style="color:#14532d;">Mobile-Friendly Applications</h4>
                        <p class="text-muted mb-0">Candidates can apply directly from their phones. Higher application completion rate compared to complex job portals.</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="card-modern p-5 d-inline-block" style="max-width:480px;width:100%;">
                    <div class="mb-3" style="color:#14532d;font-size:2.5rem;"><i class="fas fa-user-tie"></i></div>
                    <h2 class="h4 mb-3">Start Hiring in OMR Today</h2>
                    <p class="text-muted mb-4">Join hundreds of OMR businesses already using MyOMR to hire local talent.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="employer-register-omr.php" class="btn btn-lg fw-semibold"
                           style="background:#14532d;color:#fff;border-radius:8px;padding:12px 28px;">
                            <i class="fas fa-user-plus me-2"></i>Register Free
                        </a>
                        <a href="employer-login-omr.php?redirect=employer-landing-omr.php"
                           class="btn btn-outline-secondary btn-lg fw-semibold" style="border-radius:8px;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

