<?php
/**
 * IT Corridor Chennai — Jobs & companies on OMR
 * Single page with sections for OMR stretches and links to job landing pages.
 */
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title     = 'IT Corridor Chennai — Jobs & Companies on OMR | MyOMR';
$page_description = 'Old Mahabalipuram Road (OMR) is Chennai\'s IT corridor. Find jobs in Perungudi, Sholinganallur, Navalur, Thoraipakkam, Kelambakkam and browse companies hiring.';
$canonical_url  = 'https://myomr.in/discover-myomr/it-corridor-jobs-omr.php';
$og_image       = 'https://myomr.in/My-OMR-Logo.png';
$og_title       = $page_title;
$og_description = $page_description;
$og_url         = $canonical_url;
$breadcrumbs    = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-local-job-listings/', 'Jobs in OMR'],
    [$canonical_url, 'IT Corridor'],
];

$corridor_areas = [
    ['name' => 'Perungudi', 'slug' => 'perungudi', 'url' => '/jobs-in-perungudi-omr.php', 'blurb' => 'Major IT parks and offices near Perungudi. WTC, RMZ and many tech companies.'],
    ['name' => 'Sholinganallur', 'slug' => 'sholinganallur', 'url' => '/jobs-in-sholinganallur-omr.php', 'blurb' => 'Dense IT and residential corridor. Close to Tidel Park and SIPCOT.'],
    ['name' => 'Navalur', 'slug' => 'navalur', 'url' => '/jobs-in-navalur-omr.php', 'blurb' => 'Growing tech and retail hub. IT companies and startups.'],
    ['name' => 'Thoraipakkam', 'slug' => 'thoraipakkam', 'url' => '/jobs-in-thoraipakkam-omr.php', 'blurb' => 'Chennai One IT SEZ and major employers. Strong IT job market.'],
    ['name' => 'Kelambakkam', 'slug' => 'kelambakkam', 'url' => '/jobs-in-kelambakkam-omr.php', 'blurb' => 'Southern OMR. Educational institutions and IT offices.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; }
.maxw-1280 { max-width: 1280px; margin: 0 auto; }
.corridor-hero { background: linear-gradient(135deg, #14532d 0%, #166534 100%); color: #fff; padding: 3rem 0 2rem; }
.corridor-hero h1 { font-weight: 700; }
.area-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow 0.2s; }
.area-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.area-card h2 { font-size: 1.15rem; margin-bottom: 0.35rem; color: #14532d; }
.area-card p { font-size: 0.9rem; color: #6b7280; margin-bottom: 0.75rem; }
</style>
<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'IT Corridor Chennai — Jobs & Companies on OMR',
    'description' => $page_description,
    'url' => $canonical_url,
    'publisher' => ['@type' => 'Organization', 'name' => 'MyOMR.in'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => ['@id' => 'https://myomr.in/']],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs in OMR', 'item' => ['@id' => 'https://myomr.in/omr-local-job-listings/']],
        ['@type' => 'ListItem', 'position' => 3, 'name' => 'IT Corridor', 'item' => ['@id' => $canonical_url]],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
</head>
<body>
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="corridor-hero">
  <div class="container maxw-1280">
    <h1>IT Corridor Chennai — Jobs &amp; companies on OMR</h1>
    <p class="lead mb-0">Old Mahabalipuram Road (OMR) is Chennai&rsquo;s technology corridor. Find jobs by area and browse companies hiring.</p>
  </div>
</section>

<div class="container maxw-1280 py-4">
  <div class="row mb-4">
    <div class="col-md-8">
      <h2 class="h4 mb-3">Jobs by area on OMR</h2>
      <?php foreach ($corridor_areas as $a): ?>
        <div class="area-card">
          <h2><?= htmlspecialchars($a['name']) ?></h2>
          <p class="mb-0"><?= htmlspecialchars($a['blurb']) ?></p>
          <a href="<?= htmlspecialchars($a['url']) ?>" class="btn btn-success btn-sm">Jobs in <?= htmlspecialchars($a['name']) ?></a>
        </div>
      <?php endforeach; ?>
    </div>
    <aside class="col-md-4">
      <div class="p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
        <h3 class="h6 mb-2">Quick links</h3>
        <a href="/omr-local-job-listings/" class="d-block mb-2">All jobs in OMR</a>
        <a href="/it-jobs-omr-chennai.php" class="d-block mb-2">IT jobs in OMR</a>
        <a href="/omr-local-job-listings/companies-hiring-omr.php" class="d-block mb-2">Companies hiring on OMR</a>
        <a href="/discover-myomr/it-careers-omr.php" class="d-block mb-2">IT career articles</a>
        <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=it_corridor_hub&utm_medium=sidebar&utm_campaign=job_seeker_profile" class="d-block mb-2">Job seeker profile (CV)</a>
      </div>
    </aside>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
