<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

$page_title = 'Explore Places in OMR Road Chennai | Schools, IT Companies, Banks, Hospitals | MyOMR';
$page_description = 'Explore OMR Road listings in one place: schools, IT companies, banks, hospitals, restaurants, ATMs, parks and more across Old Mahabalipuram Road, Chennai.';
$canonical_url = 'https://myomr.in/omr-listings/index.php';
$og_title = $page_title;
$og_description = $page_description;
$og_image = 'https://myomr.in/My-OMR-Logo.png';
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$twitter_image = $og_image;
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-listings/index.php', 'Explore Places']
];

$cards = [
    ['url' => '/it-parks', 'icon' => 'fas fa-building', 'title' => 'IT Parks', 'desc' => 'Major IT parks and SEZ campuses along OMR Road.'],
    ['url' => '/omr-listings/schools.php', 'icon' => 'fas fa-school', 'title' => 'Schools', 'desc' => 'Comprehensive list of schools in the OMR corridor.'],
    ['url' => '/omr-listings/best-schools.php', 'icon' => 'fas fa-star', 'title' => 'Best Schools', 'desc' => 'Top-rated schools for quality education in OMR.'],
    ['url' => '/omr-listings/it-companies.php', 'icon' => 'fas fa-laptop-code', 'title' => 'IT Companies', 'desc' => 'Major IT and tech companies located on OMR Road.'],
    ['url' => '/omr-listings/industries.php', 'icon' => 'fas fa-industry', 'title' => 'Industries', 'desc' => 'Key industries and manufacturing units in OMR Road.'],
    ['url' => '/omr-listings/restaurants.php', 'icon' => 'fas fa-utensils', 'title' => 'Restaurants', 'desc' => 'Popular restaurants and eateries along OMR.'],
    ['url' => '/omr-listings/government-offices.php', 'icon' => 'fas fa-landmark', 'title' => 'Government Offices', 'desc' => 'Important public offices and services in OMR localities.'],
    ['url' => '/omr-listings/atms.php', 'icon' => 'fas fa-credit-card', 'title' => 'ATMs', 'desc' => 'ATM locations from major banks across OMR.'],
    ['url' => '/omr-listings/parks.php', 'icon' => 'fas fa-tree', 'title' => 'Parks', 'desc' => 'Green spaces and parks for recreation in OMR.'],
    ['url' => '/omr-listings/banks.php', 'icon' => 'fas fa-university', 'title' => 'Banks', 'desc' => 'Bank branches and key banking services on OMR Road.'],
    ['url' => '/omr-listings/hospitals.php', 'icon' => 'fas fa-hospital', 'title' => 'Hospitals', 'desc' => 'Hospitals and healthcare centers in the OMR region.'],
    ['url' => '/omr-hostels-pgs/', 'icon' => 'fas fa-bed', 'title' => 'Hostels & PGs', 'desc' => 'Student and professional accommodation options near OMR.'],
    ['url' => '/omr-coworking-spaces/', 'icon' => 'fas fa-briefcase', 'title' => 'Coworking Spaces', 'desc' => 'Workspaces, hot desks and meeting rooms across OMR.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include ROOT_PATH . '/components/meta.php'; ?>
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<?php include ROOT_PATH . '/components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f8fafc; }
.listings-hero { background: linear-gradient(135deg, #14532d 0%, #166534 100%); color: #fff; border-radius: 14px; padding: 2.25rem; }
.listings-grid .card { border: 1px solid #e5e7eb; border-radius: 14px; transition: transform .2s ease, box-shadow .2s ease; }
.listings-grid .card:hover { transform: translateY(-3px); box-shadow: 0 10px 22px rgba(2, 6, 23, 0.08); }
.listings-grid .icon-wrap { width: 54px; height: 54px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #dcfce7; color: #166534; font-size: 1.35rem; }
</style>
</head>
<body>
<?php omr_nav('main'); ?>

<main class="container py-4" style="max-width:1280px;">
    <section class="listings-hero mb-4">
        <h1 class="h2 mb-2">Explore Places in OMR Road</h1>
        <p class="mb-0">Find schools, IT companies, banks, hospitals, restaurants, hostels and more across Old Mahabalipuram Road, Chennai.</p>
    </section>

    <section class="row g-3 listings-grid">
        <?php foreach ($cards as $card): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo htmlspecialchars($card['url'], ENT_QUOTES, 'UTF-8'); ?>" class="card h-100 p-3 text-decoration-none">
                    <div class="icon-wrap mb-3"><i class="<?php echo htmlspecialchars($card['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></div>
                    <h2 class="h5 text-dark mb-2"><?php echo htmlspecialchars($card['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="text-muted mb-0"><?php echo htmlspecialchars($card['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </section>

    <?php include ROOT_PATH . '/components/omr-topic-hubs.php'; ?>
</main>

<?php omr_footer(); ?>
</body>
</html>