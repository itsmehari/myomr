<?php
/**
 * OMR Buy-Sell Admin — Manage Categories
 */
require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';

$categories = [];
$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_categories'");
if ($check && $check->num_rows > 0) {
    $res = $conn->query("SELECT id, name, slug, parent_id, sort_order FROM omr_buy_sell_categories ORDER BY sort_order ASC, name ASC");
    $categories = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Categories | Buy & Sell Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4">Manage Categories</h1>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead>
        <tr><th>ID</th><th>Name</th><th>Slug</th><th>Sort</th></tr>
      </thead>
      <tbody>
      <?php foreach ($categories as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= htmlspecialchars($c['slug']) ?></td>
        <td><?= (int)$c['sort_order'] ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($categories)): ?>
  <p class="text-muted">No categories. Run migration to seed.</p>
  <?php endif; ?>
  <a href="index.php" class="btn btn-outline-secondary">Back</a>
</main>
</body>
</html>
