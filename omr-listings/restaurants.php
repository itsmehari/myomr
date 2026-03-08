<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../core/omr-connect.php';

// Pagination settings
$restaurants_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $restaurants_per_page;

// Filter settings
$locality_filter = isset($_GET['locality']) ? $conn->real_escape_string($_GET['locality']) : '';
$cuisine_filter = isset($_GET['cuisine']) ? $conn->real_escape_string($_GET['cuisine']) : '';
$cost_filter = isset($_GET['cost']) ? (int)$_GET['cost'] : 0;
$rating_filter = isset($_GET['rating']) ? (float)$_GET['rating'] : 0;

// Build the SQL query with filters
$sql = "SELECT id, name, address, locality, cuisine, cost_for_two, rating, availability, accessibility, reviews, imagelocation, ST_X(geolocation) AS lon, ST_Y(geolocation) AS lat 
        FROM omr_restaurants WHERE 1=1";
if ($locality_filter) {
    $sql .= " AND locality = '$locality_filter'";
}
if ($cuisine_filter) {
    $sql .= " AND cuisine LIKE '%$cuisine_filter%'";
}
if ($cost_filter) {
    $sql .= " AND cost_for_two <= $cost_filter";
}
if ($rating_filter) {
    $sql .= " AND rating >= $rating_filter";
}
$sql .= " LIMIT $restaurants_per_page OFFSET $offset";

// Count total restaurants for pagination
$count_sql = "SELECT COUNT(*) as total FROM omr_restaurants WHERE 1=1";
if ($locality_filter) $count_sql .= " AND locality = '$locality_filter'";
if ($cuisine_filter) $count_sql .= " AND cuisine LIKE '%$cuisine_filter%'";
if ($cost_filter) $count_sql .= " AND cost_for_two <= $cost_filter";
if ($rating_filter) $count_sql .= " AND rating >= $rating_filter";
$count_result = $conn->query($count_sql);
$total_restaurants = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_restaurants / $restaurants_per_page);

// Fetch restaurant data
$result = $conn->query($sql);

// Fetch unique values for filters
$localities = $conn->query("SELECT DISTINCT locality FROM omr_restaurants ORDER BY locality");
$cuisines = $conn->query("SELECT DISTINCT cuisine FROM omr_restaurants ORDER BY cuisine");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Restaurants in OMR, Chennai | Dining Guide | MyOMR';
$page_description    = 'Explore restaurants along Old Mahabalipuram Road (OMR), Chennai. Find restaurant names, addresses, cuisines, ratings, and more.';
$canonical_url       = 'https://myomr.in/restaurants';
$og_title            = 'Restaurants in OMR, Chennai | Dining Guide | MyOMR';
$og_description      = 'Explore restaurants along Old Mahabalipuram Road (OMR), Chennai. Find restaurant names, addresses, cuisines, ratings, and more.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/restaurants';
$twitter_title       = 'Restaurants in OMR, Chennai | Dining Guide | MyOMR';
$twitter_description = 'Explore restaurants along Old Mahabalipuram Road (OMR), Chennai. Find restaurant names, addresses, cuisines, ratings, and more.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
    <meta name="robots" content="index, follow">
    <style>
        body { font-family: 'Poppins', sans-serif; }
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
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/main-nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container maxw-1280">
            <h1>Vegetarian Restaurants in OMR, Chennai</h1>
            <p class="lead">Discover the best vegetarian dining options along Old Mahabalipuram Road (OMR). From South Indian to North Indian cuisines, find your perfect meal today!</p>
            <a href="#filters" class="btn btn-lg btn-warning font-weight-bold mt-3">Explore Now</a>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="container maxw-1280 py-4" id="filters">
        <div class="filter-section">
            <h3 class="mb-4">Filter Restaurants</h3>
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="locality">Locality</label>
                        <select name="locality" id="locality" class="form-control">
                            <option value="">All Localities</option>
                            <?php while ($loc = $localities->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($loc['locality']); ?>" <?php echo $locality_filter == $loc['locality'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($loc['locality']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cuisine">Cuisine</label>
                        <select name="cuisine" id="cuisine" class="form-control">
                            <option value="">All Cuisines</option>
                            <?php while ($cui = $cuisines->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($cui['cuisine']); ?>" <?php echo $cuisine_filter == $cui['cuisine'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cui['cuisine']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cost">Max Cost for Two (₹)</label>
                        <select name="cost" id="cost" class="form-control">
                            <option value="0">Any</option>
                            <option value="300" <?php echo $cost_filter == 300 ? 'selected' : ''; ?>>Up to ₹300</option>
                            <option value="500" <?php echo $cost_filter == 500 ? 'selected' : ''; ?>>Up to ₹500</option>
                            <option value="800" <?php echo $cost_filter == 800 ? 'selected' : ''; ?>>Up to ₹800</option>
                            <option value="1000" <?php echo $cost_filter == 1000 ? 'selected' : ''; ?>>Up to ₹1000</option>
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
                <a href="restaurants.php" class="btn btn-secondary mt-2">Clear Filters</a>
            </form>
        </div>
    </section>

    <!-- Restaurant Cards -->
    <section class="container maxw-1280 py-4">
        <h2 class="text-center mb-4">Restaurants Along OMR Road</h2>
        <?php $itemList = []; $positionCounter = $offset + 1; ?>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $rSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $row['name']));
                    $rSlug = trim($rSlug, '-');
                    $rUrl  = 'https://myomr.in/restaurants/' . $rSlug . '-' . (int)$row['id'];
                    $itemList[] = ['@type' => 'ListItem', 'position' => $positionCounter++, 'url' => $rUrl, 'name' => $row['name']];
                ?>
                    <!-- Structured Data for SEO -->
                    <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "Restaurant",
                        "name": "<?php echo htmlspecialchars($row['name']); ?>",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "<?php echo htmlspecialchars($row['address']); ?>",
                            "addressLocality": "<?php echo htmlspecialchars($row['locality']); ?>",
                            "addressRegion": "Chennai",
                            "postalCode": "600097",
                            "addressCountry": "IN"
                        },
                        "geo": {
                            "@type": "GeoCoordinates",
                            "latitude": <?php echo $row['lat']; ?>,
                            "longitude": <?php echo $row['lon']; ?>
                        },
                        "servesCuisine": "<?php echo htmlspecialchars($row['cuisine']); ?>",
                        "priceRange": "₹<?php echo $row['cost_for_two']; ?> for two",
                        "aggregateRating": {
                            "@type": "AggregateRating",
                            "ratingValue": "<?php echo $row['rating']; ?>",
                            "reviewCount": "100"
                        },
                        "openingHours": "<?php echo htmlspecialchars($row['availability']); ?>",
                        "image": "<?php echo htmlspecialchars($row['imagelocation']); ?>",
                        "description": "<?php echo htmlspecialchars(substr($row['reviews'], 0, 200)); ?>..."
                    }
                    </script>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-success shadow-sm">
                            <img src="<?php echo htmlspecialchars($row['imagelocation']); ?>" class="card-img-top lazyload" alt="<?php echo htmlspecialchars($row['name']); ?>" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text"><strong>Locality:</strong> <?php echo htmlspecialchars($row['locality']); ?></p>
                                <p class="card-text"><strong>Cuisine:</strong> <?php echo htmlspecialchars($row['cuisine']); ?></p>
                                <p class="card-text"><strong>Cost for Two:</strong> ₹<?php echo $row['cost_for_two']; ?></p>
                                <p class="card-text"><strong>Rating:</strong> <?php echo $row['rating']; ?> <i class="fas fa-star text-warning"></i></p>
                                <p class="card-text"><strong>Availability:</strong> <?php echo htmlspecialchars($row['availability']); ?></p>
                                <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($row['reviews'], 0, 100)); ?>...</p>
                                <a href="#map" class="btn btn-outline-success mt-2">View on Map</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No restaurants found matching your criteria. Try adjusting the filters.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($itemList)): ?>
        <script type="application/ld+json">
        <?php echo json_encode(['@context'=>'https://schema.org','@type'=>'ItemList','name'=>'Restaurants on OMR, Chennai','itemListElement'=>$itemList], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
        </script>
        <?php endif; ?>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&locality=<?php echo urlencode($locality_filter); ?>&cuisine=<?php echo urlencode($cuisine_filter); ?>&cost=<?php echo $cost_filter; ?>&rating=<?php echo $rating_filter; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&locality=<?php echo urlencode($locality_filter); ?>&cuisine=<?php echo urlencode($cuisine_filter); ?>&cost=<?php echo $cost_filter; ?>&rating=<?php echo $rating_filter; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&locality=<?php echo urlencode($locality_filter); ?>&cuisine=<?php echo urlencode($cuisine_filter); ?>&cost=<?php echo $cost_filter; ?>&rating=<?php echo $rating_filter; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </section>

    <!-- Featured Restaurants -->
    <section class="container maxw-1280 py-5">
        <h2 class="text-center mb-4">Featured Restaurants</h2>
        <div class="row">
            <?php
            $featured_sql = "SELECT name, address, locality, cuisine, cost_for_two, rating, imagelocation 
                            FROM omr_restaurants 
                            WHERE rating >= 4.2 
                            LIMIT 3";
            $featured_result = $conn->query($featured_sql);
            while ($featured = $featured_result->fetch_assoc()):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-warning shadow-sm">
                        <img src="<?php echo htmlspecialchars($featured['imagelocation']); ?>" class="card-img-top lazyload" alt="<?php echo htmlspecialchars($featured['name']); ?>" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($featured['name']); ?></h5>
                            <p class="card-text"><strong>Locality:</strong> <?php echo htmlspecialchars($featured['locality']); ?></p>
                            <p class="card-text"><strong>Cuisine:</strong> <?php echo htmlspecialchars($featured['cuisine']); ?></p>
                            <p class="card-text"><strong>Rating:</strong> <?php echo $featured['rating']; ?> <i class="fas fa-star text-warning"></i></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Map Preview -->
    <section class="container maxw-1280 py-5" id="map">
        <h2 class="text-center mb-4">Find Restaurants on the Map</h2>
        <div class="map-preview">
            <img src="https://maps.googleapis.com/maps/api/staticmap?center=12.94,80.24&zoom=12&size=800x300&maptype=roadmap&markers=color:red%7C12.94,80.24&key=YOUR_GOOGLE_MAPS_API_KEY" alt="OMR Restaurants Map Preview" loading="lazy" decoding="async" width="800" height="300">
        </div>
        <div class="text-center mt-4">
            <a href="https://www.google.com/maps/search/vegetarian+restaurants/@12.94,80.24,12z" class="btn btn-primary" target="_blank">View Full Map</a>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="container maxw-1280 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-success shadow-sm text-center p-4" style="background: linear-gradient(90deg, #FDBB2D 0%, #3A1C71 100%); color: #fff;">
                    <h2 class="mb-3">Join the OMR Dining Community!</h2>
                    <p class="lead">Share your dining experiences, rate restaurants, or suggest new vegetarian spots along OMR.</p>
                    <p>Email us at <a href="mailto:myomrnews@gmail.com" class="text-white font-weight-bold">myomrnews@gmail.com</a></p>
                    <div class="mt-3">
                        <?php include $_SERVER['DOCUMENT_ROOT'].'/components/social-icons.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer.php'; ?>

    <!-- Bootstrap JS, jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lazy Load Images -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let lazyImages = [].slice.call(document.querySelectorAll("img.lazyload"));
            if ('IntersectionObserver' in window) {
                let imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src || lazyImage.src;
                            lazyImage.classList.remove("lazyload");
                            imageObserver.unobserve(lazyImage);
                        }
                    });
                });
                lazyImages.forEach((lazyImage) => imageObserver.observe(lazyImage));
            }
        });
    </script>
    
    <!-- UN SDG Floating Badges -->
    <?php include '../components/sdg-badge.php'; ?>
</body>
</html>
<?php $conn->close(); ?>
