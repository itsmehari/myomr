<?php
/**
 * Landing Page Template
 * Reusable template for SEO-optimized landing pages
 */

// If template is included directly, set defaults
if (!isset($page_title)) {
    $page_title = "Jobs in OMR Chennai | MyOMR";
    $page_description = "Find jobs in OMR Chennai";
    $canonical_url = "https://myomr.in/jobs-in-omr-chennai.php";
}

// Get general stats
$total_jobs = 0;
$total_employers = 0;
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $jobsQuery = $conn->query("SELECT COUNT(*) as total FROM job_postings WHERE status = 'approved'");
    if ($jobsQuery) {
        $row = $jobsQuery->fetch_assoc();
        $total_jobs = (int)($row['total'] ?? 0);
    }
    $employersQuery = $conn->query("SELECT COUNT(DISTINCT id) as total FROM employers");
    if ($employersQuery) {
        $row = $employersQuery->fetch_assoc();
        $total_employers = (int)($row['total'] ?? 0);
    }
}

// Set default filter URL
$filter_url = "/omr-local-job-listings/";
if (isset($location)) {
    $filter_url .= "?location=" . urlencode($location);
} elseif (isset($category)) {
    $filter_url .= "?category=" . urlencode($category);
} elseif (isset($job_type)) {
    $filter_url .= "?job_type=" . urlencode($job_type);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords ?? 'jobs in OMR Chennai, OMR job portal, local jobs'); ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.jpg">
    
    <?php include __DIR__ . '/../../../components/analytics.php'; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <link rel="stylesheet" href="../assets/job-listings-omr.css">
    <link rel="stylesheet" href="../assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="../../../components/footer.css">
    
    <style>
        .hero-landing {
            background: linear-gradient(135deg, #008552 0%, #006b42 100%);
            color: white;
            padding: 80px 0 60px;
        }
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        .hero-search-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .stat-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #008552;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-weight: 500;
        }
        .featured-job-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .featured-job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .cta-section {
            background: linear-gradient(135deg, #008552 0%, #006b42 100%);
            color: white;
            padding: 60px 0;
        }
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MyOMR Job Portal",
        "url": "<?php echo $canonical_url; ?>",
        "description": "<?php echo htmlspecialchars($page_description); ?>",
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
            {"@type": "ListItem", "position": 2, "name": "Jobs in OMR", "item": "https://myomr.in/jobs-in-omr-chennai.php"},
            {"@type": "ListItem", "position": 3, "name": "<?php echo htmlspecialchars($breadcrumb_name ?? 'Jobs'); ?>", "item": "<?php echo $canonical_url; ?>"}
        ]
    }
    </script>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../../../components/main-nav.php'; ?>
<a href="#main-content" class="skip-link">Skip to main content</a>

<main id="main-content">
    
    <!-- Hero Section -->
    <section class="hero-landing">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="hero-title"><?php echo htmlspecialchars($hero_title ?? $page_title); ?></h1>
                    <p class="hero-subtitle"><?php echo htmlspecialchars($hero_subtitle ?? $page_description); ?> <strong>100% Free to Use.</strong></p>
                    
                    <div class="hero-search-form">
                        <form method="GET" action="/omr-local-job-listings/" role="search">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <input type="text" 
                                           id="search" 
                                           name="search" 
                                           class="form-control form-control-lg" 
                                           placeholder="Job title or keywords">
                                    <?php if (isset($location)): ?>
                                        <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" 
                                           id="location_field" 
                                           name="location" 
                                           class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($location ?? 'OMR'); ?>"
                                           placeholder="Location">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-search me-1"></i> Search Jobs
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?php echo $filter_url; ?>" class="btn btn-light btn-lg me-2">
                            <i class="fas fa-briefcase me-2"></i> View All Jobs
                        </a>
                        <a href="/omr-local-job-listings/employer-register-omr.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus me-2"></i> Post a Job (Free)
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo number_format($location_job_count ?? $total_jobs); ?>+</div>
                                <div class="stat-label"><?php echo isset($location) ? 'Jobs in ' . htmlspecialchars($location) : 'Active Jobs'; ?></div>
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
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Free to Use</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Available</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($location_description) || isset($content_section)): ?>
    <!-- Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <?php if (isset($location_description)): ?>
                        <h2 class="h3 mb-4">About <?php echo htmlspecialchars($location_name ?? $location ?? ''); ?></h2>
                        <p class="lead text-muted"><?php echo htmlspecialchars($location_description); ?></p>
                    <?php endif; ?>
                    <?php if (isset($content_section)): ?>
                        <?php echo $content_section; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Job Listings Section -->
    <?php if (!empty($location_jobs ?? [])): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="h3 mb-3">Latest Job Opportunities</h2>
                    <p class="text-muted">Browse recently posted jobs<?php echo isset($location) ? ' in ' . htmlspecialchars($location) : ''; ?></p>
                </div>
            </div>
            <div class="row g-4">
                <?php foreach (($location_jobs ?? []) as $job): ?>
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
                                    <?php 
                                    $salary = htmlspecialchars($job['salary_range']);
                                    echo (strpos($salary, '₹') === false ? '₹' : '') . $salary;
                                    ?>
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
                <a href="<?php echo $filter_url; ?>" class="btn btn-primary btn-lg">
                    View All Jobs <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Related Landing Pages Section -->
    <?php
    // Determine related pages based on current page type
    $related_pages = [];
    if (isset($location)) {
        $current_page_type = 'location';
    } elseif (isset($category)) {
        $current_page_type = 'industry';
    } elseif (isset($job_type)) {
        $current_page_type = 'type';
    } else {
        $current_page_type = 'default';
    }
    
    // Include related pages component
    if (file_exists(__DIR__ . '/../../../components/job-related-landing-pages.php')) {
        include __DIR__ . '/../../../components/job-related-landing-pages.php';
    }
    ?>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <h2 class="h2 mb-4">Ready to Find Your Dream Job<?php echo isset($location) ? ' in ' . htmlspecialchars($location) : ' in OMR'; ?>?</h2>
                    <p class="lead mb-4">Join <?php echo number_format($total_jobs); ?>+ job opportunities and <?php echo number_format($total_employers); ?>+ employers on MyOMR.in</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="<?php echo $filter_url; ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-search me-2"></i> Search Jobs Now
                        </a>
                        <a href="/omr-local-job-listings/employer-register-omr.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-briefcase me-2"></i> Post a Job (Free)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/../../../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Landing Page Analytics -->
<script src="/omr-local-job-listings/assets/landing-page-analytics.js"></script>

<?php include __DIR__ . '/../../../components/sdg-badge.php'; ?>

</body>
</html>

