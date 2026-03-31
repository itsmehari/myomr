<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$page_title       = 'Step-by-step: MyOMR job seeker profile & CV upload | OMR Chennai';
$page_description = 'Walkthrough of the free MyOMR job seeker profile: what you submit, privacy and consent, updating later with the same email.';
$canonical_url    = 'https://myomr.in/discover-myomr/job-seeker-profile-walkthrough-myomr.php';
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
      <h1 class="h2 mb-2">MyOMR job seeker profile — how it works</h1>
      <p class="mb-0 opacity-90">Free optional profile for people job-hunting along Old Mahabalipuram Road.</p>
    </div>
  </section>
  <div class="container py-5" style="max-width:720px;">
    <ol class="ps-3">
      <li class="mb-3">Open <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=walkthrough&utm_medium=content&utm_campaign=job_seeker_profile">Create your job seeker profile</a>.</li>
      <li class="mb-3">Enter name, email, and phone. Use a real email — you will reuse it to update your profile later.</li>
      <li class="mb-3">Add optional locality (e.g. Navalur), experience band, headline, and skills.</li>
      <li class="mb-3">Upload PDF or Word résumé (max 2 MB). First time it is required; later you can skip the file to keep the old CV.</li>
      <li class="mb-3">Accept the <a href="/info/website-privacy-policy-of-my-omr.php">Privacy Policy</a>. Optionally opt in to outreach from MyOMR about relevant OMR roles.</li>
    </ol>
    <p class="small text-muted">Browsing and applying to individual jobs stays separate — you can still apply from each listing. After you save a profile, we pre-fill your name, email, and phone on the apply form when you browse in the same browser session.</p>
    <p class="mt-3 mb-0"><a class="btn btn-success" href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=walkthrough&utm_medium=bottom_cta&utm_campaign=job_seeker_profile">Go to the form</a></p>
  </div>
</main>
<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
