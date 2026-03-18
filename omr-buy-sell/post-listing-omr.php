<?php
/**
 * OMR Buy-Sell — Post Listing Form
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/captcha-config.php';

require_once ROOT_PATH . '/core/omr-localities-buy-sell.php';
$categories = getBuySellCategories();
$localities = getBuySellLocalities();

$page_title = 'Post Your Ad | Buy & Sell OMR | MyOMR';
$canonical_url = 'https://myomr.in/omr-buy-sell/post-listing-omr.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="robots" content="noindex">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<script>if (typeof gtag === 'function') gtag('event', 'post_listing_start', { event_category: 'buy_sell' });</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/omr-buy-sell/assets/buy-sell-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="buy-sell-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-4">
  <h1 class="h2 mb-4">Post Your Ad</h1>
  <?php
  if (!empty($_SESSION['post_listing_errors'])):
    $errs = $_SESSION['post_listing_errors'];
    unset($_SESSION['post_listing_errors']);
  ?>
  <div class="alert alert-danger">
    <strong>Please fix the following:</strong>
    <ul class="mb-0 mt-1">
      <?php foreach ($errs as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>
  <?php
  $prefill = $_SESSION['post_listing_prefill'] ?? [];
  if (!empty($prefill)) unset($_SESSION['post_listing_prefill']);
  ?>
  <form method="post" action="process-listing-omr.php" enctype="multipart/form-data" class="needs-validation" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <div class="card mb-4">
      <div class="card-body">
        <h2 class="h6 mb-3">Listing Details</h2>
        <div class="mb-3">
          <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
          <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select category</option>
            <?php foreach ($categories as $c): $sel = (int)($prefill['category_id'] ?? 0) === (int)$c['id']; ?>
            <option value="<?= (int)$c['id'] ?>"<?= $sel ? ' selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" id="title" class="form-control" required minlength="5" maxlength="255" placeholder="e.g. iPhone 12, 64GB" value="<?= htmlspecialchars($prefill['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
          <textarea name="description" id="description" class="form-control" required minlength="20" rows="4" placeholder="Describe the item, condition, reason for selling..."><?= htmlspecialchars($prefill['description'] ?? '') ?></textarea>
          <small class="text-muted">At least 20 characters</small>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price (₹)</label>
            <input type="number" name="price" id="price" class="form-control" min="0" max="999999999" step="0.01" placeholder="Optional" value="<?= isset($prefill['price']) && $prefill['price'] !== '' ? htmlspecialchars($prefill['price']) : '' ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label d-block">Price negotiable?</label>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="price_negotiable" value="1" class="form-check-input" id="price_negotiable"<?= ($prefill['price_negotiable'] ?? 1) ? ' checked' : '' ?>>
              <label for="price_negotiable" class="form-check-label">Yes</label>
            </div>
          </div>
        </div>
        <div class="mb-3">
          <label for="locality" class="form-label">Locality <span class="text-danger">*</span></label>
          <select name="locality" id="locality" class="form-select" required>
            <option value="">Select locality</option>
            <?php foreach ($localities as $l): $sel = ($prefill['locality'] ?? '') === $l; ?>
            <option value="<?= htmlspecialchars($l) ?>"<?= $sel ? ' selected' : '' ?>><?= htmlspecialchars($l) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="images" class="form-label">Images (max 5, jpg/png/webp, 2MB each)</label>
          <input type="file" name="images[]" id="images" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <h2 class="h6 mb-3">Your Contact</h2>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="seller_name" class="form-label">Your Name <span class="text-danger">*</span></label>
            <input type="text" name="seller_name" id="seller_name" class="form-control" required value="<?= htmlspecialchars($prefill['seller_name'] ?? '') ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label for="seller_phone" class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="tel" name="seller_phone" id="seller_phone" class="form-control" required value="<?= htmlspecialchars($prefill['seller_phone'] ?? '') ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label for="seller_email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="seller_email" id="seller_email" class="form-control" required value="<?= htmlspecialchars($prefill['seller_email'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

    <?php if (isCaptchaEnabled()): ?>
    <div class="mb-3 g-recaptcha" data-sitekey="<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></div>
    <?php endif; ?>
    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-paper-plane me-2"></i>Submit</button>
    <a href="/omr-buy-sell/" class="btn btn-outline-secondary ms-2">Cancel</a>
    <small class="d-block mt-2"><a href="guidelines.php">Guidelines & Safety</a></small>
  </form>
</div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php if (isCaptchaEnabled()): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<script>
document.querySelector('form.needs-validation').addEventListener('submit', function(e) {
  if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
  this.classList.add('was-validated');
});
</script>
</body>
</html>
