<?php
/**
 * MyOMR Hostels & PGs – Main listings page.
 * Modular layout following root index.php pattern: bootstrap, page vars, meta/head, omr_nav/omr_footer, section includes.
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/includes/bootstrap.php';

global $conn;

// Page context for nav/layout
$page_nav = 'main';
$page_title = 'Hostels & PGs in OMR Chennai - Find Safe Accommodation | MyOMR';
$page_description = 'Find 100+ hostels and paying guest accommodations in OMR Chennai. Boys PG, Girls Hostel, student-friendly options with verified properties. Safe and affordable living spaces.';
$page_keywords = 'PG in OMR, hostels in OMR Chennai, boys PG OMR, girls hostel OMR, student accommodation OMR, affordable hostel OMR';
$canonical_url = 'https://myomr.in/omr-hostels-pgs/';
$og_type = 'website';

// Filters from URL
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

$properties = [];
$total_properties = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $limitInt = (int) $properties_per_page;
    $offsetInt = (int) $offset;

    $allPropsQuery = "SELECT * FROM hostels_pgs WHERE status = 'active' ORDER BY featured DESC, created_at DESC";
    $allPropsResult = $conn->query($allPropsQuery);

    if ($allPropsResult && $allPropsResult->num_rows > 0) {
        $allProps = $allPropsResult->fetch_all(MYSQLI_ASSOC);
        $total_properties = count($allProps);

        if (!empty($filters)) {
            $filteredProps = [];
            foreach ($allProps as $prop) {
                $match = true;
                if (!empty($filters['property_type']) && ($prop['property_type'] ?? '') !== $filters['property_type']) {
                    $match = false;
                }
                if (!empty($filters['locality']) && stripos($prop['locality'] ?? '', $filters['locality']) === false) {
                    $match = false;
                }
                if (!empty($filters['gender']) && ($prop['gender_preference'] ?? '') !== $filters['gender']) {
                    $match = false;
                }
                if (!empty($filters['price_max']) && isset($prop['monthly_rent_single']) && $prop['monthly_rent_single'] !== '' && (float) $prop['monthly_rent_single'] > (float) $filters['price_max']) {
                    $match = false;
                }
                if (!empty($filters['search'])) {
                    $searchMatch = (
                        stripos($prop['property_name'] ?? '', $filters['search']) !== false
                        || stripos($prop['brief_overview'] ?? '', $filters['search']) !== false
                        || stripos($prop['full_description'] ?? '', $filters['search']) !== false
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

        $properties = array_slice($allProps, $offsetInt, $limitInt);
    } else {
        $fallbackQuery = "SELECT * FROM hostels_pgs WHERE LOWER(TRIM(status)) = 'active' ORDER BY featured DESC, created_at DESC";
        $fallbackResult = $conn->query($fallbackQuery);
        if ($fallbackResult && $fallbackResult->num_rows > 0) {
            $allProps = $fallbackResult->fetch_all(MYSQLI_ASSOC);
            $total_properties = count($allProps);
            $properties = array_slice($allProps, $offsetInt, $limitInt);
        }
    }
}

$total_pages = (int) ceil($total_properties / $properties_per_page);
$localities = ['All of OMR', 'Navalur', 'Sholinganallur', 'Thoraipakkam', 'Kandhanchavadi', 'Perungudi', 'Okkiyam Thuraipakkam'];

$filter_params = http_build_query($filters);
$base_url = HOSTELS_PGS_BASE_URL . '/';
if ($filter_params !== '') {
    $base_url .= '?' . $filter_params;
}

// Breadcrumbs for meta component
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-hostels-pgs/', 'Hostels & PGs in OMR'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/omr-hostels-pgs/assets/hostels-pgs.css">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MyOMR Hostels & PGs",
        "url": "<?php echo htmlspecialchars($canonical_url); ?>",
        "description": "<?php echo htmlspecialchars($page_description); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo htmlspecialchars($canonical_url); ?>?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>
<body class="modern-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>

<?php omr_nav(); ?>

<main id="main-content">
    <?php include HOSTELS_PGS_PATH . '/components/hero-hostels.php'; ?>
    <div class="container py-3"><?php omr_ad_slot('hostels-index-top', '728x90'); ?></div>
    <?php include HOSTELS_PGS_PATH . '/components/filters-bar.php'; ?>

    <section class="property-listings-section py-5">
        <div class="container">
            <?php include HOSTELS_PGS_PATH . '/components/property-cards.php'; ?>
        </div>
        <?php omr_ad_slot('hostels-index-mid', '336x280'); ?>
    </section>

    <?php include HOSTELS_PGS_PATH . '/components/cta-owner.php'; ?>
</main>

<?php omr_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/omr-hostels-pgs/assets/hostels-search.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>

</body>
</html>
