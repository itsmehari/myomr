<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('BUY_SELL_ADMIN_ROUTED', '/superadmin/buy-sell/manage-categories-omr.php');
require_once __DIR__ . '/_urls.php';
/**
 * OMR Buy-Sell Admin — Manage Categories
 */
require_once __DIR__ . '/../../core/omr-connect.php';

$categories = [];
$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_categories'");
if ($check && $check->num_rows > 0) {
    $res = $conn->query("SELECT id, name, slug, parent_id, sort_order FROM omr_buy_sell_categories ORDER BY sort_order ASC, name ASC");
    $categories = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

$__modulePageTitle = 'Manage Categories | Buy & Sell Admin';
$__moduleActiveNav = '/superadmin/buy-sell/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Manage Categories | Buy & Sell Admin</title></head>
<body>
<?php } ?>
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

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
