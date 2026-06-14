<?php
require_once __DIR__ . '/_bootstrap.php';
$title = 'Admin Security';
$pageTitle = $title;
$breadcrumbs = ['Admin Security' => null];
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="alert alert-info">
  Superadmin login uses hardcoded credentials in
  <code>superadmin/_auth.php</code>.
  Edit <code>SUPERADMIN_USERNAME</code> and <code>SUPERADMIN_PASSWORD</code> there, then redeploy.
</div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
