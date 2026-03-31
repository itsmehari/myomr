<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$page_title       = 'Fresher résumé checklist for OMR & Chennai IT corridor | MyOMR';
$page_description = 'Quick checklist for fresh graduates applying along OMR: format, skills, projects, and where to upload your CV on MyOMR.';
$canonical_url    = 'https://myomr.in/discover-myomr/fresher-resume-checklist-omr-chennai-myomr.php';
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
      <h1 class="h2 mb-2">Fresher résumé checklist (OMR / Chennai)</h1>
      <p class="mb-0 opacity-90">Before you hit “Apply” on local listings, tighten your CV so employers along the IT corridor can scan it fast.</p>
    </div>
  </section>
  <div class="container py-5" style="max-width:720px;">
    <ul class="ps-3">
      <li class="mb-2"><strong>One page</strong> unless you have strong projects or internships worth two.</li>
      <li class="mb-2"><strong>Clear headline</strong> — degree, branch, and target role (e.g. “B.E. CSE — seeking Java/backend trainee”).</li>
      <li class="mb-2"><strong>Skills row</strong> — languages, tools, databases; match words you see in <a href="/fresher-jobs-omr-chennai.php">fresher jobs on OMR</a>.</li>
      <li class="mb-2"><strong>Projects</strong> — 2–3 bullets each: what you built, stack, outcome.</li>
      <li class="mb-2"><strong>PDF export</strong> — Word is fine on MyOMR too, but PDF avoids layout breaks.</li>
    </ul>
    <div class="p-4 rounded border mt-4" style="background:#f0fdf4;border-color:#bbf7d0!important;">
      <h2 class="h5 text-success">Put it on MyOMR</h2>
      <p class="mb-2 small">Create a free profile and attach your résumé — optional, but it saves time when you apply to multiple OMR roles.</p>
      <a class="btn btn-success" href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=fresher_resume_guide&utm_medium=cta&utm_campaign=job_seeker_profile">Upload résumé &amp; profile</a>
    </div>
    <p class="text-muted small mt-4 mb-0"><a href="/discover-myomr/it-careers-omr.php">IT careers hub</a> · <a href="/omr-local-job-listings/">All jobs</a></p>
  </div>
</main>
<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
