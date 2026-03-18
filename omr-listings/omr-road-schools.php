```php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}
require ROOT_PATH . '/core/omr-connect.php';

// Pagination settings
$schools_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $schools_per_page;

// Filter settings
$area_filter = isset($_GET['area']) ? $conn->real_escape_string($_GET['area']) : '';
$curriculum_filter = isset($_GET['curriculum']) ? $conn->real_escape_string($_GET['curriculum']) : '';
$fee_filter = isset($_GET['fee']) ? $conn->real_escape_string($_GET['fee']) : '';
$rating_filter = isset($_GET['rating']) ? (float)$_GET['rating'] : 0;

// Build the SQL query with filters
$sql = "SELECT school_id, school_name, address, area, curriculum, fee_range, rating, notable_features, logo_url, latitude, longitude 
        FROM schools WHERE status = 'Active'";
if ($area_filter) {
    $sql .= " AND area = '$area_filter'";
}
if ($curriculum_filter) {
    $sql .= " AND curriculum LIKE '%$curriculum_filter%'";
}
if ($fee_filter) {
    $sql .= " AND fee_range = '$fee_filter'";
}
if ($rating_filter) {
    $sql .= " AND rating >= $rating_filter";
}
$sql .= " LIMIT $schools_per_page OFFSET $offset";

// Count total schools for pagination
$count_sql = "SELECT COUNT(*) as total FROM schools WHERE status = 'Active'";
if ($area_filter) $count_sql .= " AND area = '$area_filter'";
if ($curriculum_filter) $count_sql .= " AND curriculum LIKE '%$curriculum_filter%'";
if ($fee_filter) $count_sql .= " AND fee_range = '$fee_filter'";
if ($rating_filter) $count_sql .= " AND rating >= $rating_filter";
$count_result = $conn->query($count_sql);
$total_schools = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_schools / $schools_per_page);

// Fetch school data
$result = $conn->query($sql);

// Fetch unique values for filters
$areas = $conn->query("SELECT DISTINCT area FROM schools WHERE status = 'Active' ORDER BY area");
$curricula = $conn->query("SELECT DISTINCT curriculum FROM schools WHERE status = 'Active' ORDER BY curriculum");
$fees = $conn->query("SELECT DISTINCT fee_range FROM schools WHERE status = 'Active' AND fee_range IS NOT NULL ORDER BY fee_range");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Schools on OMR Road, Chennai | Complete List | MyOMR';
$page_description    = 'Find all schools on Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks.';
$canonical_url       = 'https://myomr.in/schools';
$og_title            = 'Schools on OMR Road, Chennai | Complete List | MyOMR';
$og_description      = 'Find all schools on Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks.';
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = 'https://myomr.in/schools';
$twitter_title       = 'Schools on OMR Road, Chennai | Complete List | MyOMR';
$twitter_description = 'Find all schools on Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks.';
$twitter_image       = 'https://myomr.in/My-OMR-Logo.png';
?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body { font-family: 'Josefin Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; color: #008552; }
        .hero-section { background: linear-gradient(90deg, #9ebd13 0%, #008552 100%); color: #fff; padding: 50px 0; text-align: center; }
        .card { transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .card-img-top { height: 200px; object-fit: cover; }
        .filter-section { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .pagination { justify-content: center; margin-top: 20px; }
        .map-preview img { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; }
        .float { position: fixed; width: 60px; height: 60px; bottom: 40px; right: 40px; background-color: #25d366; color: #FFF; border-radius: 50px; text-align: center; font-size: 30px; box-shadow: 2px 2px 3px #999; z-index: 100; animation: myAnim 2s ease 0s 1 normal forwards; }
        .my-float { margin-top: 16px; }
        @keyframes myAnim {
            0% { animation-timing-function: ease-in; opacity: 1; transform: translateY(-45px); }
            24% { opacity: 1; }
            40% { animation-timing-function: ease-in; transform: translateY(-24px); }
            65% { animation-timing-function: ease-in; transform: translateY(-12px); }
            82% { animation-timing-function: ease-in; transform: translateY(-6px); }
            93% { animation-timing-function: ease-in; transform: translateY(-4px); }
            25%, 55%, 75%, 87% { animation-timing-function: ease-out; transform: translateY(0px); }
            100% { animation-timing-function: ease-out; opacity: 1; transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <!-- WhatsApp Floating Button -->
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <!-- Navbar -->
    <?php include ROOT_PATH . '/components/main-nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Schools in OMR, Chennai</h1>
            <p class="lead">Discover top schools along Old Mahabalipuram Road (OMR). From CBSE to IB, find the perfect school for your child today!</p>
            <a href="#filters" class="btn btn-lg btn-warning font-weight-bold mt-3">Explore Now</a>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="container py-4" id="filters">
        <div class="filter-section">
            <h3 class="mb-4">Filter Schools</h3>
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="area">Area</label>
                        <select name="area" id="area" class="form-control">
                            <option value="">All Areas</option>
                            <?php while ($ar = $areas->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($ar['area']); ?>" <?php echo $area_filter == $ar['area'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ar['area']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="curriculum">Curriculum</label>
                        <select name="curriculum" id="curriculum" class="form-control">
                            <option value="">All Curricula</option>
                            <?php while ($cur = $curricula->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($cur['curriculum']); ?>" <?php echo $curriculum_filter == $cur['curriculum'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cur['curriculum']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="fee">Fee Range (Annual)</label>
                        <select name="fee" id="fee" class="form-control">
                            <option value="">Any</option>
                            <?php while ($fee = $fees->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($fee['fee_range']); ?>" <?php echo $fee_filter == $fee['fee_range'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fee['fee_range']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="rating">Minimum Rating</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="0">Any</option>
                            <option value="3.5" <?php echo $rating_filter == 3.5 ? 'selected' : ''; ?>>3.5+</option>
                            <option value="4.0" <?php echo $rating_filter == 4.0 ? 'selected' : ''; ?>>4.0+</option>
                            <option value="4.5" <?php echo $rating_filter == 4.5 ? 'selected' : ''; ?>>4.5+</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Apply Filters</button>
                <a href="schools-list.php" class="btn btn-secondary mt-2">Clear Filters</a>
            </form>
        </div>
    </section>

    <!-- School Cards -->
    <section class="container py-4">
        <h2 class="text-center mb-4">Schools Along OMR Road</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Structured Data for SEO -->
                    <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "School",
                        "name": "<?php echo htmlspecialchars($row['school_name']); ?>",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "<?php echo htmlspecialchars($row['address']); ?>",
                            "addressLocality": "<?php echo htmlspecialchars($row['area']); ?>",
                            "addressRegion": "Chennai",
                            "postalCode": "<?php echo htmlspecialchars($row['pin_code'] ?? '600097'); ?>",
                            "addressCountry": "IN"
                        },
                        "geo": {
                            "@type": "GeoCoordinates",
                            "latitude": <?php echo $row['latitude'] ?? '12.94'; ?>,
                            "longitude": <?php echo $row['longitude'] ?? '80.24'; ?>
                        },
                        "curriculum": "<?php echo htmlspecialchars($row['curriculum'] ?? 'N/A'); ?>",
                        "priceRange": "<?php echo htmlspecialchars($row['fee_range'] ?? 'N/A'); ?>",
                        "aggregateRating": {
                            "@type": "AggregateRating",
                            "ratingValue": "<?php echo $row['rating'] ?? '0'; ?>",
                            "reviewCount": "<?php echo $row['rating_count'] ?? '0'; ?>"
                        },
                        "image": "<?php echo htmlspecialchars($row['logo_url'] ?? 'https://myomr.in/images/schools/default.jpg'); ?>",
                        "description": "<?php echo htmlspecialchars(substr($row['notable_features'] ?? 'N/A', 0, 200)); ?>..."
                    }
                    </script>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-success shadow-sm">
                            <img src="<?php echo htmlspecialchars($row['logo_url'] ?? 'https://myomr.in/images/schools/default.jpg'); ?>" class="card-img-top lazyload" alt="<?php echo htmlspecialchars($row['school_name']); ?>" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['school_name']); ?></h5>
                                <p class="card-text"><strong>Area:</strong> <?php echo htmlspecialchars($row['area']); ?></p>
                                <p class="card-text"><strong>Curriculum:</strong> <?php echo htmlspecialchars($row['curriculum'] ?? 'N/A'); ?></p>
                                <p class="card-text"><strong>Fee Range:</strong> <?php echo htmlspecialchars($row['fee_range'] ?? 'N/A'); ?></p>
                                <p class="card-text"><strong>Rating:</strong> <?php echo $row['rating'] ? $row['rating'] : 'N/A'; ?> <i class="fas fa-star text-warning"></i></p>
                                <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($row['notable_features'] ?? 'N/A', 0, 100)); ?>...</p>
                                <a href="#map" class="btn btn-outline-success mt-2">View on Map</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No schools found matching your criteria. Try adjusting the filters.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&area=<?php echo urlencode($area_filter); ?>&curriculum=<?php echo urlencode($curriculum_filter); ?>&fee=<?php echo urlencode($fee_filter); ?>&rating=<?php echo $rating_filter; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&area=<?php echo urlencode($area_filter); ?>&curriculum=<?php echo urlencode($curriculum_filter); ?>&fee=<?php echo urlencode($fee_filter); ?>&rating=<?php echo $rating_filter; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&area=<?php echo urlencode($area_filter); ?>&curriculum=<?php echo urlencode($curriculum_filter); ?>&fee=<?php echo urlencode($fee_filter); ?>&rating=<?php echo $rating_filter; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </section>

    <!-- Featured Schools -->
    <section class="container py-5">
        <h2 class="text-center mb-4">Featured Schools</h2>
        <div class="row">
            <?php
            $featured_sql = "SELECT school_name, address, area, curriculum, rating, logo_url 
                            FROM schools 
                            WHERE rating >= 4.2 AND status = 'Active' 
                            LIMIT 3";
            $featured_result = $conn->query($featured_sql);
            while ($featured = $featured_result->fetch_assoc()):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-warning shadow-sm">
                        <img src="<?php echo htmlspecialchars($featured['logo_url'] ?? 'https://myomr.in/images/schools/default.jpg'); ?>" class="card-img-top lazyload" alt="<?php echo htmlspecialchars($featured['school_name']); ?>" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($featured['school_name']); ?></h5>
                            <p class="card-text"><strong>Area:</strong> <?php echo htmlspecialchars($featured['area']); ?></p>
                            <p class="card-text"><strong>Curriculum:</strong> <?php echo htmlspecialchars($featured['curriculum'] ?? 'N/A'); ?></p>
                            <p class="card-text"><strong>Rating:</strong> <?php echo $featured['rating'] ?? 'N/A'; ?> <i class="fas fa-star text-warning"></i></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Map Preview -->
    <section class="container py-5" id="map">
        <h2 class="text-center mb-4">Find Schools on the Map</h2>
        <div class="map-preview
