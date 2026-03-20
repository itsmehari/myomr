<?php
/**
 * OMR Classified Ads — admin menu
 */
require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OMR Classified Ads — Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4">OMR Classified Ads — Admin</h1>
  <ul class="list-unstyled">
    <li class="mb-2"><a href="manage-listings-omr.php" class="btn btn-outline-primary">Manage listings</a></li>
    <li class="mb-2"><a href="view-reports-omr.php" class="btn btn-outline-warning">View reports</a></li>
    <li class="mb-2"><a href="/admin/" class="btn btn-outline-secondary">Main admin</a></li>
  </ul>
</main>
</body>
</html>
