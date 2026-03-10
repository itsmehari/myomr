<?php
/**
 * MyOMR Coworking Spaces Portal - Main Space Listings Page
 * 
 * @package MyOMR Coworking Spaces
 * @version 1.0.0
 * 
 * Uses direct database queries for reliable space loading
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Include helper functions
require_once __DIR__ . '/includes/space-functions.php';

// Load database connection directly
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Get filters from URL parameters
$filters = [];
if (!empty($_GET['search'])) {
    $filters['search'] = sanitizeInput($_GET['search']);
}
if (!empty($_GET['locality'])) {
    $filters['locality'] = sanitizeInput($_GET['locality']);
}
if (!empty($_GET['price_max'])) {
    $filters['price_max'] = sanitizeInput($_GET['price_max']);
}

// Pagination
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$spaces_per_page = 20;
$offset = ($current_page - 1) * $spaces_per_page;

// Get spaces using direct query
$spaces = [];
$total_spaces = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    // Direct query to get active spaces
    $limitInt = (int)$spaces_per_page;
    $offsetInt = (int)$offset;
    
    // First get all active spaces
    $allSpacesQuery = "SELECT * FROM coworking_spaces WHERE status = 'active' ORDER BY featured DESC, created_at DESC";
    $allSpacesResult = $conn->query($allSpacesQuery);
    
    if ($allSpacesResult && $allSpacesResult->num_rows > 0) {
        $allSpaces = $allSpacesResult->fetch_all(MYSQLI_ASSOC);
        $total_spaces = count($allSpaces);
        
        // Apply filters if any
        if (!empty($filters)) {
            $filteredSpaces = [];
            foreach ($allSpaces as $space) {
                $match = true;
                
                if (!empty($filters['locality']) && stripos($space['locality'] ?? '', $filters['locality']) === false) {
                    $match = false;
                }
                if (!empty($filters['price_max']) && !empty($space['hot_desk_monthly']) && floatval($space['hot_desk_monthly']) > floatval($filters['price_max'])) {
                    $match = false;
                }
                if (!empty($filters['search'])) {
                    $searchMatch = (
                        stripos($space['space_name'] ?? '', $filters['search']) !== false ||
                        stripos($space['brief_overview'] ?? '', $filters['search']) !== false ||
                        stripos($space['full_description'] ?? '', $filters['search']) !== false
                    );
                    if (!$searchMatch) {
                        $match = false;
                    }
                }
                
                if ($match) {
                    $filteredSpaces[] = $space;
                }
            }
            $allSpaces = $filteredSpaces;
            $total_spaces = count($filteredSpaces);
        }
        
        // Apply pagination
        $spaces = array_slice($allSpaces, $offsetInt, $limitInt);
    } else {
        // Try fallback with LOWER/TRIM
        $fallbackQuery = "SELECT * FROM coworking_spaces WHERE LOWER(TRIM(status)) = 'active' ORDER BY featured DESC, created_at DESC";
        $fallbackResult = $conn->query($fallbackQuery);
        if ($fallbackResult && $fallbackResult->num_rows > 0) {
            $allSpaces = $fallbackResult->fetch_all(MYSQLI_ASSOC);
            $total_spaces = count($allSpaces);
            $spaces = array_slice($allSpaces, $offsetInt, $limitInt);
        }
    }
}

$total_pages = ceil($total_spaces / $spaces_per_page);

// Get OMR localities for filter dropdown
$localities = ['All of OMR', 'Navalur', 'Sholinganallur', 'Thoraipakkam', 'Kandhanchavadi', 'Perungudi', 'Okkiyam Thuraipakkam'];

// SEO Meta
$page_title = "Coworking Spaces in OMR Chennai - Find Flexible Workspaces | MyOMR";
$page_description = "Discover 50+ coworking spaces in OMR Chennai. Day pass, hot desk, dedicated desk options with high-speed WiFi, meeting rooms, and 24/7 access. Affordable workspaces for freelancers and startups.";
$canonical_url = "https://myomr.in/omr-coworking-spaces/";

// Build filter URL for pagination
$filter_params = http_build_query($filters);
$base_url = "/omr-coworking-spaces/";
if (!empty($filter_params)) {
    $base_url .= "?" . $filter_params;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="coworking OMR, shared office OMR Chennai, day pass coworking OMR, hot desk OMR, workspace OMR">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Performance: DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
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
    <?php include '../components/analytics.php'; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins + Core tokens -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>
    <link rel="stylesheet" href="/assets/css/core.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/coworking-spaces.css">
    <!-- Universal Footer Styles -->
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MyOMR Coworking Spaces",
        "url": "<?php echo $canonical_url; ?>",
        "description": "<?php echo $page_description; ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo $canonical_url; ?>?search={search_term_string}",
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
        {"@type": "ListItem", "position": 2, "name": "Coworking Spaces in OMR", "item": "https://myomr.in/omr-coworking-spaces/"}
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
<?php require_once '../components/main-nav.php'; ?>

<!-- Skip Link for Accessibility -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Main Content -->
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="hero-section hero-modern text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3 hero-modern-title">Find Your Perfect Workspace in OMR</h1>
                    <p class="lead mb-4 hero-modern-subtitle">Discover flexible, affordable coworking spaces for freelancers, startups, and remote professionals in the OMR corridor.</p>
                    
                    <!-- Search Form -->
                    <form method="GET" class="search-form" role="search" aria-label="Search coworking spaces">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label for="search" class="form-label visually-hidden">Search spaces</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       class="form-control form-control-lg" 
                                       placeholder="Space name or location"
                                       value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                       aria-describedby="search-help">
                                <div id="search-help" class="form-text text-white-50">e.g., Navalur, Day Pass, Hot Desk</div>
                            </div>
                            <div class="col-md-4">
                                <label for="locality" class="form-label visually-hidden">Locality</label>
                                <select id="locality" name="locality" class="form-select form-select-lg">
                                    <option value="">All Localities</option>
                                    <?php foreach ($localities as $loc): ?>
                                        <option value="<?php echo htmlspecialchars($loc); ?>" 
                                                <?php echo isset($filters['locality']) && $filters['locality'] === $loc ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($loc); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-light btn-lg w-100" aria-label="Search spaces">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <div class="card-modern text-dark bg-white bg-opacity-10 p-4 rounded h-100">
                        <h3 class="h2 mb-1 text-success"><?php echo number_format($total_spaces); ?>+</h3>
                        <p class="mb-1 text-dark fw-semibold">Verified Spaces</p>
                        <small class="text-muted">Flexible and affordable</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section py-4 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="h5 mb-0">Refine Your Search</h2>
                    <small class="text-muted">Showing <?php echo number_format($total_spaces); ?> spaces</small>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="advanced-filters d-flex gap-2 flex-wrap">
                        <!-- Preserve existing filters -->
                        <?php foreach ($filters as $key => $value): ?>
                            <?php if ($key !== 'price_max' && !empty($value)): ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max ₹/month" 
                               value="<?php echo htmlspecialchars($filters['price_max'] ?? ''); ?>" 
                               style="width: 150px;">
                        
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        
                        <?php if (!empty($filters)): ?>
                            <a href="/omr-coworking-spaces/" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Space Listings -->
    <section class="space-listings-section py-5">
        <div class="container">
            
            <?php if (!empty($spaces)): ?>
                
                <!-- Results Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h2 class="h4 mb-1">Available Workspaces</h2>
                        <p class="text-muted mb-0">
                            Showing <?php echo count($spaces); ?> of <?php echo number_format($total_spaces); ?> spaces
                            <?php if ($current_page > 1): ?>
                                - Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="btn-group" role="group" aria-label="View options">
                            <button type="button" class="btn btn-outline-primary btn-sm active" data-view="grid">
                                <i class="fas fa-th me-1"></i> Grid
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-view="list">
                                <i class="fas fa-list me-1"></i> List
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Space Cards -->
                <div class="row" id="spaces-container">
                    <?php foreach ($spaces as $space): ?>
                        <div class="col-lg-6 mb-4">
                            
                            <!-- Space Card -->
                            <article class="space-card card-modern h-100 position-relative">
                                
                                <!-- Featured Badge -->
                                <?php if (!empty($space['featured'])): ?>
                                    <span class="badge-verified position-absolute top-0 end-0 m-3">
                                        <i class="fas fa-star me-1"></i> Featured
                                    </span>
                                <?php endif; ?>
                                
                                <!-- Space Image -->
                                <div class="space-card-image-wrapper">
                                    <?php if (!empty($space['featured_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($space['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($space['space_name']); ?>" 
                                             class="space-card-image"
                                             loading="lazy"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="space-card-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-building fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Space Header -->
                                <header class="space-card-header">
                                    <h3 class="h5 mb-2">
                                        <a href="space-detail.php?id=<?php echo $space['id']; ?>" 
                                           class="text-decoration-none text-dark space-card-title">
                                            <?php echo htmlspecialchars($space['space_name']); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="space-card-location">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($space['locality'] ?? 'OMR'); ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mt-2">
                                        <?php if (!empty($space['verification_status']) && $space['verification_status'] === 'verified'): ?>
                                            <span class="badge-verified">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <!-- Space Body -->
                                <div class="space-card-body">
                                    <div class="space-card-overview mb-3">
                                        <p class="text-muted mb-2">
                                            <?php 
                                            $overview = $space['brief_overview'] ?? $space['full_description'] ?? '';
                                            echo htmlspecialchars(strlen($overview) > 120 ? substr($overview, 0, 120) . '...' : $overview);
                                            ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Key Amenities -->
                                    <?php if (!empty($space['amenities'])): ?>
                                        <?php 
                                        $amenities = json_decode($space['amenities'], true);
                                        if (is_array($amenities) && !empty($amenities)): 
                                        ?>
                                            <div class="space-card-amenities">
                                                <?php foreach (array_slice($amenities, 0, 4) as $amenity): ?>
                                                    <span class="amenity-badge">
                                                        <i class="fas fa-check me-1"></i><?php echo htmlspecialchars($amenity); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                                <?php if (count($amenities) > 4): ?>
                                                    <span class="amenity-badge">+<?php echo count($amenities) - 4; ?> more</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Space Footer -->
                                <footer class="space-card-footer">
                                    <div class="space-card-price">
                                        ₹<?php echo number_format($space['hot_desk_monthly'] ?? 0); ?>/month
                                        <small class="text-muted d-block">Starting from</small>
                                    </div>
                                    
                                    <div class="space-actions">
                                        <a href="space-detail.php?id=<?php echo $space['id']; ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </div>
                                </footer>
                                
                            </article>
                            
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="row mt-5">
                        <div class="col-12">
                            <?php echo generatePagination($current_page, $total_pages, $base_url); ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                
                <!-- No Results -->
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="no-results">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h3 class="h4 mb-3">No Spaces Found</h3>
                            <p class="text-muted mb-4">
                                <?php if (!empty($filters)): ?>
                                    Try adjusting your search criteria or <a href="/omr-coworking-spaces/">browse all spaces</a>.
                                <?php else: ?>
                                    No coworking spaces available at the moment. Check back soon for new listings!
                                <?php endif; ?>
                            </p>
                            <a href="/omr-coworking-spaces/" class="btn btn-primary">
                                <i class="fas fa-refresh me-1"></i> View All Spaces
                            </a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section bg-primary text-white py-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="h3 mb-3">Are You a Space Owner?</h2>
                    <p class="lead mb-4">List your coworking space and connect with thousands of freelancers, startups, and remote professionals in the OMR area.</p>
                    <a href="owner-register.php" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-plus me-1"></i> List Your Space
                    </a>
                    <a href="owner-login.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-1"></i> Owner Login
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Footer -->
<?php require_once '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/coworking-search.js"></script>

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>

</body>
</html>

