<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title       = 'Areas Covered by MyOMR | Old Mahabalipuram Road Communities';
$page_description = 'Discover all the neighborhoods and localities covered by MyOMR along the OMR corridor, from Perungudi to Kelambakkam, Chennai.';
$canonical_url    = 'https://myomr.in/discover-myomr/areas-covered.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.png';
$og_title         = $page_title;
$og_description   = $page_description;
$og_url           = $canonical_url;

$localities = [
    'Perungudi', 'Thuraipakkam', 'Karapakkam', 'Kandhanchavadi', 'Mettukuppam', 'Sholinganallur',
    'Dollar Stop', 'IT Corridor', 'Tidel Park', 'Madhya Kailash', 'Navalur', 'Thazhambur',
    'Kelambakkam', 'SRP Tools', 'Siruseri', 'Semmancheri', 'Egattur', 'Padur', 'Other OMR neighborhoods',
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
    :root { --brand: #14532d; --brand-mid: #166534; --brand-light: #f0fdf4; }
    .areas-panel {
      background: var(--brand-light); border-radius: 12px; border: 1px solid #bbf7d0;
    }
    .area-pill {
      display: block; background: #fff; border: 1px solid #d1fae5; color: var(--brand);
      font-size: .9rem; font-weight: 600; padding: .75rem; border-radius: 10px; text-align: center;
    }
    .section-heading { font-weight: 700; color: var(--brand); }
  </style>
</head>
<body class="bg-light">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content" class="container py-5">
  <section class="areas-panel p-4 p-md-5">
    <h1 class="section-heading h2 mb-3">Areas we cover</h1>
    <p class="text-muted mb-4">
      MyOMR serves the Old Mahabalipuram Road (OMR) corridor and its neighbourhoods. We connect residents, businesses, and communities across these areas:
    </p>
    <div class="row g-3">
      <?php foreach ($localities as $loc): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="area-pill"><?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="text-muted small mt-4 mb-0">
      If your area is not listed, <a href="/webmaster-contact-my-omr.php">contact us</a>. We are always expanding coverage.
    </p>
  </section>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
