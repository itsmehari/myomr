<?php
/**
 * MyOMR Hostels & PGs Portal - Main Property Listings Page
 * 
 * @package MyOMR Hostels & PGs
 * @version 1.0.0
 * 
 * Uses direct database queries for reliable property loading
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Include helper functions
require_once __DIR__ . '/includes/property-functions.php';

// Load database connection directly
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Get filters from URL parameters
$filters = [];
if (!empty($_GET['search'])) {
    $filters['search'] = sanitizeInput($_GET['search']);
}
if (!empty($_GET['property_type'])) {
    $filters['property_type'] = sanitizeInput($_GET['property_type']);
}
if (!empty($_GET['locality'])) {
    $filters['locality'] = sanitizeInput($_GET['locality']);
}
if (!empty($_GET['gender'])) {
    $filters['gender'] = sanitizeInput($_GET['gender']);
}
if (!empty($_GET['price_max'])) {
    $filters['price_max'] = sanitizeInput($_GET['price_max']);
}

// Pagination
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$properties_per_page = 20;
$offset = ($current_page - 1) * $properties_per_page;

// Get properties using direct query
$properties = [];
$total_properties = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    // Direct query to get active properties
    $limitInt = (int)$properties_per_page;
    $offsetInt = (int)$offset;
    
    // First get all active properties
    $allPropsQuery = "SELECT * FROM hostels_pgs WHERE status = 'active' ORDER BY featured DESC, created_at DESC";
    $allPropsResult = $conn->query($allPropsQuery);
    
    if ($allPropsResult && $allPropsResult->num_rows > 0) {
        $allProps = $allPropsResult->fetch_all(MYSQLI_ASSOC);
        $total_properties = count($allProps);
        
        // Apply filters if any
        if (!empty($filters)) {
            $filteredProps = [];
            foreach ($allProps as $prop) {
                $match = true;
                
                if (!empty($filters['property_type']) && $prop['property_type'] !== $filters['property_type']) {
                    $match = false;
                }
                if (!empty($filters['locality']) && stripos($prop['locality'] ?? '', $filters['locality']) === false) {
                    $match = false;
                }
                if (!empty($filters['gender']) && $prop['gender_preference'] !== $filters['gender']) {
                    $match = false;
                }
                if (!empty($filters['price_max']) && !empty($prop['monthly_rent_single']) && floatval($prop['monthly_rent_single']) > floatval($filters['price_max'])) {
                    $match = false;
                }
                if (!empty($filters['search'])) {
                    $searchMatch = (
                        stripos($prop['property_name'] ?? '', $filters['search']) !== false ||
                        stripos($prop['brief_overview'] ?? '', $filters['search']) !== false ||
                        stripos($prop['full_description'] ?? '', $filters['search']) !== false
                    );
                    if (!$searchMatch) {
                        $match = false;
                    }
                }
                
                if ($match) {
                    $filteredProps[] = $prop;
                }
            }
            $allProps = $filteredProps;
            $total_properties = count($filteredProps);
        }
        
        // Apply pagination
        $properties = array_slice($allProps, $offsetInt, $limitInt);
    } else {
        // Try fallback with LOWER/TRIM
        $fallbackQuery = "SELECT * FROM hostels_pgs WHERE LOWER(TRIM(status)) = 'active' ORDER BY featured DESC, created_at DESC";
        $fallbackResult = $conn->query($fallbackQuery);
        if ($fallbackResult && $fallbackResult->num_rows > 0) {
            $allProps = $fallbackResult->fetch_all(MYSQLI_ASSOC);
            $total_properties = count($allProps);
            $properties = array_slice($allProps, $offsetInt, $limitInt);
        }
    }
}

$total_pages = ceil($total_properties / $properties_per_page);

// Get OMR localities for filter dropdown
$localities = ['All of OMR', 'Navalur', 'Sholinganallur', 'Thoraipakkam', 'Kandhanchavadi', 'Perungudi', 'Okkiyam Thuraipakkam'];

// SEO Meta
$page_title = "Hostels & PGs in OMR Chennai - Find Safe Accommodation | MyOMR";
$page_description = "Find 100+ hostels and paying guest accommodations in OMR Chennai. Boys PG, Girls Hostel, student-friendly options with verified properties. Safe and affordable living spaces.";
$canonical_url = "https://myomr.in/omr-hostels-pgs/";

// Build filter URL for pagination
$filter_params = http_build_query($filters);
$base_url = "/omr-hostels-pgs/";
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
    <meta name="keywords" content="PG in OMR, hostels in OMR Chennai, boys PG OMR, girls hostel OMR, student accommodation OMR, affordable hostel OMR">
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
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.jpg">
    
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
    <link rel="stylesheet" href="assets/hostels-pgs.css">
    <!-- Universal Footer Styles -->
    <link rel="stylesheet" href="../components/footer.css">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MyOMR Hostels & PGs",
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
        {"@type": "ListItem", "position": 2, "name": "Hostels & PGs in OMR", "item": "https://myomr.in/omr-hostels-pgs/"}
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "My OMR",
      "url": "https://myomr.in/",
      "logo": "https://myomr.in/My-OMR-Logo.jpg",
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
                    <h1 class="display-4 fw-bold mb-3 hero-modern-title">Find Your Perfect Home in OMR</h1>
                    <p class="lead mb-4 hero-modern-subtitle">Discover safe, comfortable, and affordable hostels & PG accommodations for students and professionals in the OMR corridor.</p>
                    
                    <!-- Search Form -->
                    <form method="GET" class="search-form" role="search" aria-label="Search hostels and PGs">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label for="search" class="form-label visually-hidden">Search properties</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       class="form-control form-control-lg" 
                                       placeholder="Property name or location"
                                       value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                       aria-describedby="search-help">
                                <div id="search-help" class="form-text text-white-50">e.g., Navalur, Boys PG, Girls Hostel</div>
                            </div>
                            <div class="col-md-3">
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
                                <label for="property_type" class="form-label visually-hidden">Property Type</label>
                                <select id="property_type" name="property_type" class="form-select form-select-lg">
                                    <option value="">All Types</option>
                                    <option value="PG" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'PG' ? 'selected' : ''; ?>>PG</option>
                                    <option value="Hostel" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'Hostel' ? 'selected' : ''; ?>>Hostel</option>
                                    <option value="Co-living" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'Co-living' ? 'selected' : ''; ?>>Co-living</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-light btn-lg w-100" aria-label="Search properties">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <div class="card-modern text-dark bg-white bg-opacity-10 p-4 rounded h-100">
                        <h3 class="h2 mb-1 text-success"><?php echo number_format($total_properties); ?>+</h3>
                        <p class="mb-1 text-dark fw-semibold">Verified Properties</p>
                        <small class="text-muted">Safe and affordable options</small>
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
                    <small class="text-muted">Showing <?php echo number_format($total_properties); ?> properties</small>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="advanced-filters d-flex gap-2 flex-wrap">
                        <!-- Preserve existing filters -->
                        <?php foreach ($filters as $key => $value): ?>
                            <?php if ($key !== 'gender' && !empty($value)): ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <select name="gender" class="form-select form-select-sm" aria-label="Gender preference">
                            <option value="">All</option>
                            <option value="Boys Only" <?php echo isset($filters['gender']) && $filters['gender'] === 'Boys Only' ? 'selected' : ''; ?>>Boys Only</option>
                            <option value="Girls Only" <?php echo isset($filters['gender']) && $filters['gender'] === 'Girls Only' ? 'selected' : ''; ?>>Girls Only</option>
                            <option value="Co-living" <?php echo isset($filters['gender']) && $filters['gender'] === 'Co-living' ? 'selected' : ''; ?>>Co-living</option>
                        </select>
                        
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max ₹/month" 
                               value="<?php echo htmlspecialchars($filters['price_max'] ?? ''); ?>" 
                               style="width: 150px;">
                        
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        
                        <?php if (!empty($filters)): ?>
                            <a href="/omr-hostels-pgs/" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Listings -->
    <section class="property-listings-section py-5">
        <div class="container">
            
            <?php if (!empty($properties)): ?>
                
                <!-- Results Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h2 class="h4 mb-1">Available Properties</h2>
                        <p class="text-muted mb-0">
                            Showing <?php echo count($properties); ?> of <?php echo number_format($total_properties); ?> properties
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

                <!-- Property Cards -->
                <div class="row" id="properties-container">
                    <?php foreach ($properties as $property): ?>
                        <div class="col-lg-6 mb-4">
                            
                            <!-- Property Card -->
                            <article class="property-card card-modern h-100 position-relative">
                                
                                <!-- Featured Badge -->
                                <?php if (!empty($property['featured'])): ?>
                                    <span class="badge-verified position-absolute top-0 end-0 m-3">
                                        <i class="fas fa-star me-1"></i> Featured
                                    </span>
                                <?php endif; ?>
                                
                                <!-- Property Image -->
                                <div class="property-card-image-wrapper">
                                    <?php if (!empty($property['featured_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($property['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($property['property_name']); ?>" 
                                             class="property-card-image"
                                             loading="lazy"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="property-card-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-home fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Property Header -->
                                <header class="property-card-header">
                                    <h3 class="h5 mb-2">
                                        <a href="property-detail.php?id=<?php echo $property['id']; ?>" 
                                           class="text-decoration-none text-dark property-card-title">
                                            <?php echo htmlspecialchars($property['property_name']); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="property-card-location">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($property['locality'] ?? 'OMR'); ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mt-2">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($property['property_type']); ?></span>
                                        <?php if (!empty($property['verification_status']) && $property['verification_status'] === 'verified'): ?>
                                            <span class="badge-verified">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($property['is_student_friendly'])): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-graduation-cap me-1"></i> Student-Friendly
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <!-- Property Body -->
                                <div class="property-card-body">
                                    <div class="property-card-overview mb-3">
                                        <p class="text-muted mb-2">
                                            <?php 
                                            $overview = $property['brief_overview'] ?? $property['full_description'] ?? '';
                                            echo htmlspecialchars(strlen($overview) > 120 ? substr($overview, 0, 120) . '...' : $overview);
                                            ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Key Facilities -->
                                    <?php if (!empty($property['facilities'])): ?>
                                        <?php 
                                        $facilities = json_decode($property['facilities'], true);
                                        if (is_array($facilities) && !empty($facilities)): 
                                        ?>
                                            <div class="property-card-amenities">
                                                <?php foreach (array_slice($facilities, 0, 4) as $facility): ?>
                                                    <span class="amenity-badge">
                                                        <i class="fas fa-check me-1"></i><?php echo htmlspecialchars($facility); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                                <?php if (count($facilities) > 4): ?>
                                                    <span class="amenity-badge">+<?php echo count($facilities) - 4; ?> more</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Property Footer -->
                                <footer class="property-card-footer">
                                    <div class="property-card-price">
                                        ₹<?php echo number_format($property['monthly_rent_single'] ?? 0); ?>/month
                                        <small class="text-muted d-block">Starting from</small>
                                    </div>
                                    
                                    <div class="property-actions">
                                        <a href="/omr-hostels-pgs/property-detail.php?id=<?php echo $property['id']; ?>" 
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
                            <h3 class="h4 mb-3">No Properties Found</h3>
                            <p class="text-muted mb-4">
                                <?php if (!empty($filters)): ?>
                                    Try adjusting your search criteria or <a href="/omr-hostels-pgs/">browse all properties</a>.
                                <?php else: ?>
                                    No properties available at the moment. Check back soon for new listings!
                                <?php endif; ?>
                            </p>
                            <a href="/omr-hostels-pgs/" class="btn btn-primary">
                                <i class="fas fa-refresh me-1"></i> View All Properties
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
                    <h2 class="h3 mb-3">Are You a Property Owner?</h2>
                    <p class="lead mb-4">List your hostel or PG and connect with thousands of students and professionals in the OMR area.</p>
                    <a href="owner-register.php" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-plus me-1"></i> List Your Property
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
<script src="assets/hostels-search.js"></script>

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>

</body>
</html>

