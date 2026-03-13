<?php
require_once __DIR__ . '/includes/error-reporting.php';
if (!defined('ROOT_PATH')) define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listing Submitted | MyOMR Rent & Lease</title>
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body>
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>
<main id="main-content">
<div class="container py-5 text-center">
  <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
  <h1 class="h2">Listing Submitted</h1>
  <p class="text-muted">Your property will go live after review. We'll contact you if needed.</p>
  <a href="/omr-rent-lease/" class="btn btn-success">Browse Listings</a>
</div>
</main>
<?php omr_footer(); ?>
</body>
</html>
