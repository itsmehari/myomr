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
<?php include __DIR__ . '/../includes/admin-head-assets.php'; ?>
</head>
<body class="classified-ads-admin">
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <header class="ca-admin-header">
    <h1>OMR Classified Ads — Admin</h1>
  </header>
  <ul class="list-unstyled d-flex flex-column gap-2">
    <li><a href="manage-listings-omr.php" class="btn btn-dark">Manage listings</a></li>
    <li><a href="view-reports-omr.php" class="btn btn-outline-dark">View reports</a></li>
    <li><a href="/admin/" class="btn btn-outline-secondary">Main admin</a></li>
  </ul>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
