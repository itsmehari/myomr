<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$page_title       = 'How to find jobs on OMR Chennai (Old Mahabalipuram Road) | MyOMR';
$page_description = 'Practical steps to search IT corridor jobs: use MyOMR filters, locality pages, fresher and IT hubs, and optional job seeker profile with CV upload.';
$canonical_url    = 'https://myomr.in/discover-myomr/omr-job-search-guide-myomr.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:Poppins,sans-serif;} .discover-hero{background:linear-gradient(135deg,#14532d,#166534);color:#fff;padding:2.5rem 0;}</style>
</head>
<body class="bg-light">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
  <section class="discover-hero">
    <div class="container">
      <h1 class="h2 mb-2">How to find jobs on OMR, Chennai</h1>
      <p class="mb-0 opacity-90">Old Mahabalipuram Road — from Perungudi to Kelambakkam — is dense with IT, BPO, teaching, healthcare, and field roles. Here is a simple path.</p>
    </div>
  </section>
  <div class="container py-5" style="max-width:720px;">
    <ol class="ps-3">
      <li class="mb-3"><strong>Start broad.</strong> Open the <a href="/omr-local-job-listings/">main job listings</a> or the <a href="/jobs-in-omr-chennai.php">OMR jobs hub</a>.</li>
      <li class="mb-3"><strong>Filter by locality.</strong> Try <a href="/jobs-in-perungudi-omr.php">Perungudi</a>, <a href="/jobs-in-sholinganallur-omr.php">Sholinganallur</a>, or <a href="/jobs-in-navalur-omr.php">Navalur</a> if you want a shorter commute.</li>
      <li class="mb-3"><strong>Use vertical pages.</strong> <a href="/it-jobs-omr-chennai.php">IT</a>, <a href="/fresher-jobs-omr-chennai.php">fresher</a>, and <a href="/part-time-jobs-omr-chennai.php">part-time</a> hubs speed up discovery.</li>
      <li class="mb-3"><strong>Apply with care.</strong> Each listing has an apply flow; your application goes to the employer. MyOMR does not charge applicants.</li>
      <li class="mb-3"><strong>Optional — save a profile.</strong> <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=job_search_guide&utm_medium=content&utm_campaign=job_seeker_profile">Upload your résumé and create a free job seeker profile</a> so your details stay on file for OMR opportunities.</li>
    </ol>
    <p class="text-muted small mb-0"><a href="/discover-myomr/getting-started.php">← Get started with MyOMR</a></p>
  </div>
</main>
<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
