<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('RENT_LEASE_ADMIN_ROUTED', '/superadmin/rent-lease/upload-property-images-omr.php');
require_once __DIR__ . '/_urls.php';
/**
 * Rent & Lease Admin — upload photos to an existing property (e.g. seeded listings).
 */
require_once __DIR__ . '/../includes/bootstrap.php';

global $conn;

$property_id = max(0, (int) ($_GET['id'] ?? $_POST['property_id'] ?? 0));
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } elseif ($property_id <= 0) {
        $error = 'Invalid property ID.';
    } else {
        $paths = isset($_FILES['images']) ? rentLeaseProcessImageUploads($_FILES['images']) : [];
        if ($paths === []) {
            $error = 'No valid images uploaded (JPG/PNG/WebP, max 2MB each, up to 8 files).';
        } elseif (rentLeaseAppendPropertyImages($property_id, $paths)) {
            $message = count($paths) . ' photo(s) added to property #' . $property_id . '.';
        } else {
            $error = 'Could not save images for property #' . $property_id . '.';
        }
    }
}

$property = null;
if ($property_id > 0) {
    $stmt = $conn->prepare(
        'SELECT p.*, o.name AS owner_name FROM rent_lease_properties p
         LEFT JOIN rent_lease_owners o ON p.owner_id = o.id WHERE p.id = ? LIMIT 1'
    );
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $rows = rentLeaseStmtFetchAllAssoc($stmt);
    $stmt->close();
    $property = $rows[0] ?? null;
}

$existing_images = $property ? rentLeaseDecodeImages($property) : [];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL) {
    $pageTitle = 'Rent & Lease - Upload Photos';
    $activeNav = '/superadmin/rent-lease/';
    include __DIR__ . '/../../superadmin/includes/admin-shell-open.php';
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Property Photos | Rent & Lease Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php } ?>
<main class="container py-4">
  <h1 class="h3 mb-3">Upload property photos</h1>
  <p class="text-muted">Add photos to listings posted without images (public form or seed scripts).</p>

  <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="get" class="row g-2 mb-4">
    <div class="col-auto">
      <input type="number" name="id" class="form-control" placeholder="Property ID" value="<?= $property_id > 0 ? $property_id : '' ?>" min="1" required>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-secondary">Load</button>
    </div>
  </form>

  <?php if ($property): ?>
  <div class="card mb-4">
    <div class="card-body">
      <h2 class="h6"><?= htmlspecialchars($property['title']) ?></h2>
      <p class="small text-muted mb-2">#<?= (int) $property['id'] ?> · <?= htmlspecialchars($property['locality']) ?> · <span class="badge bg-secondary"><?= htmlspecialchars($property['status']) ?></span></p>
      <?php if ($existing_images): ?>
      <p class="small fw-semibold mb-2">Current photos (<?= count($existing_images) ?>)</p>
      <div class="d-flex flex-wrap gap-2 mb-2">
        <?php foreach ($existing_images as $img): ?>
        <img src="<?= htmlspecialchars(rentLeaseImageUrl($img), ENT_QUOTES, 'UTF-8') ?>" alt="" width="120" height="90" style="object-fit:cover;border-radius:6px" loading="lazy">
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="small text-muted mb-0">No photos yet.</p>
      <?php endif; ?>
      <a class="btn btn-sm btn-outline-success mt-2" href="/omr-rent-lease/property-detail-omr.php?id=<?= (int) $property['id'] ?>" target="_blank" rel="noopener">View public page</a>
    </div>
  </div>

  <form method="post" enctype="multipart/form-data" class="card">
    <div class="card-body">
      <input type="hidden" name="property_id" value="<?= (int) $property['id'] ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
      <div class="mb-3">
        <label class="form-label" for="images">Add photos</label>
        <input type="file" name="images[]" id="images" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple required>
        <div class="form-text">Up to 8 files, 2MB each. New uploads are appended to existing photos.</div>
      </div>
      <button type="submit" class="btn btn-success">Upload</button>
    </div>
  </form>
  <?php elseif ($property_id > 0): ?>
  <div class="alert alert-warning">Property #<?= $property_id ?> not found.</div>
  <?php endif; ?>

  <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-secondary mt-3">Back to manage</a>
</main>

<?php if (defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL) { ?>
<?php include __DIR__ . '/../../superadmin/includes/admin-shell-close.php'; ?>
<?php } else { ?>
</body>
</html>
<?php } ?>
