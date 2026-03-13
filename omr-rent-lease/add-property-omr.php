<?php
/**
 * MyOMR Rent & Lease - Add Property Form
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/property-functions.php';
if (!defined('ROOT_PATH')) define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

$prefill_type = $_GET['type'] ?? 'rent-house';
$localities = ['Perungudi','Sholinganallur','Thoraipakkam','Navalur','Kelambakkam','Siruseri','Karapakkam'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>List Property for Rent | OMR | MyOMR</title>
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="assets/rent-lease-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="rent-lease-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>
<main id="main-content">
<div class="container py-5">
  <h1 class="h2 mb-4">List Your Property</h1>
  <form method="post" action="process-property-omr.php">
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <div class="mb-3">
      <label class="form-label">Type</label>
      <select name="listing_type" class="form-select" required>
        <option value="rent-house" <?= $prefill_type === 'rent-house' ? 'selected' : '' ?>>House</option>
        <option value="rent-apartment" <?= $prefill_type === 'rent-apartment' ? 'selected' : '' ?>>Apartment</option>
        <option value="rent-land" <?= $prefill_type === 'rent-land' ? 'selected' : '' ?>>Land</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Locality</label>
      <select name="locality" class="form-select" required>
        <?php foreach ($localities as $l): ?>
        <option value="<?= htmlspecialchars($l) ?>"><?= htmlspecialchars($l) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Monthly Rent (Rs)</label>
      <input type="number" name="monthly_rent" class="form-control" min="0">
    </div>
    <div class="mb-3">
      <label class="form-label">Details</label>
      <textarea name="property_details" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Your Name</label>
      <input type="text" name="owner_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="tel" name="owner_phone" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="owner_email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
  </form>
</div>
</main>
<?php omr_footer(); ?>
</body>
</html>
