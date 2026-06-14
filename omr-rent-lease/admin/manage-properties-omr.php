<?php

require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';

myomr_module_require_routed('RENT_LEASE_ADMIN_ROUTED', '/superadmin/rent-lease/manage-properties-omr.php');

require_once __DIR__ . '/_urls.php';

/**

 * Rent & Lease Admin - Manage Properties

 */

require_once __DIR__ . '/../../core/omr-connect.php';



if (isset($_GET['action'], $_GET['id'])) {

    $action = $_GET['action'];

    $id = (int) $_GET['id'];

    $allowed = ['approve', 'reject', 'feature', 'delete'];

    if (in_array($action, $allowed, true) && $id > 0) {

        if ($action === 'approve') {

            $stmt = $conn->prepare("UPDATE rent_lease_properties SET status = 'approved' WHERE id = ?");

            $stmt->bind_param('i', $id);

            $stmt->execute();

        } elseif ($action === 'reject') {

            $stmt = $conn->prepare("UPDATE rent_lease_properties SET status = 'rejected' WHERE id = ?");

            $stmt->bind_param('i', $id);

            $stmt->execute();

        } elseif ($action === 'feature') {

            $stmt = $conn->prepare("UPDATE rent_lease_properties SET featured = 1 - COALESCE(featured, 0) WHERE id = ? AND status = 'approved'");

            $stmt->bind_param('i', $id);

            $stmt->execute();

        } elseif ($action === 'delete') {

            $stmt = $conn->prepare('DELETE FROM rent_lease_properties WHERE id = ?');

            $stmt->bind_param('i', $id);

            $stmt->execute();

        }

        $qs = array_diff_key($_GET, ['action' => '', 'id' => '']);

        header('Location: ' . RENT_LEASE_ADMIN_MANAGE_URL . ($qs ? '?' . http_build_query($qs) : ''));

        exit;

    }

}



$status_filter = $_GET['status'] ?? '';

$props = [];

$where = '1=1';

$params = [];

$types = '';

if ($status_filter && in_array($status_filter, ['pending', 'approved', 'rejected'], true)) {

    $where = 'p.status = ?';

    $params[] = $status_filter;

    $types = 's';

}



$sql = "SELECT p.*, o.name as owner_name FROM rent_lease_properties p LEFT JOIN rent_lease_owners o ON p.owner_id = o.id WHERE $where ORDER BY p.created_at DESC LIMIT 100";

$stmt = $conn->prepare($sql);

if ($params) {

    $stmt->bind_param($types, ...$params);

}

$stmt->execute();

$props = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL) {
    $pageTitle = 'Rent & Lease - Manage Properties';
    $activeNav = '/superadmin/rent-lease/';
    include __DIR__ . '/../../superadmin/includes/admin-shell-open.php';
} else {
?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Properties | Rent & Lease Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>

<body>
<?php } ?>

<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>

<main id="main-content" class="container py-4">

  <h1 class="h3 mb-4">Manage Properties</h1>

  <div class="mb-3 d-flex flex-wrap gap-2">

    <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-secondary">All</a>

    <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>?status=pending" class="btn btn-sm btn-warning">Pending</a>

    <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>?status=approved" class="btn btn-sm btn-success">Approved</a>

    <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>?status=rejected" class="btn btn-sm btn-secondary">Rejected</a>

  </div>

  <div class="table-responsive">

    <table class="table table-sm align-middle">

      <thead><tr><th>ID</th><th>Title</th><th>Type</th><th>Locality</th><th>Status</th><th>Featured</th><th>Owner</th><th>Actions</th></tr></thead>

      <tbody>

      <?php foreach ($props as $p): ?>

      <tr>

        <td><?= (int)$p['id'] ?></td>

        <td><?= htmlspecialchars($p['title']) ?></td>

        <td><?= htmlspecialchars($p['listing_type']) ?></td>

        <td><?= htmlspecialchars($p['locality']) ?></td>

        <td><span class="badge bg-<?= $p['status'] === 'approved' ? 'success' : ($p['status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= htmlspecialchars($p['status']) ?></span></td>

        <td><?= !empty($p['featured']) ? '<span class="badge bg-info"><i class="fas fa-star"></i></span>' : '—' ?></td>

        <td><?= htmlspecialchars($p['owner_name'] ?? '') ?></td>

        <td>

          <div class="btn-group btn-group-sm">

            <?php if ($p['status'] === 'pending'): ?>

              <a class="btn btn-success" href="?action=approve&amp;id=<?= (int)$p['id'] ?><?= $status_filter !== '' ? '&amp;status=' . urlencode($status_filter) : '' ?>">Approve</a>

              <a class="btn btn-danger" href="?action=reject&amp;id=<?= (int)$p['id'] ?><?= $status_filter !== '' ? '&amp;status=' . urlencode($status_filter) : '' ?>">Reject</a>

            <?php endif; ?>

            <?php if ($p['status'] === 'approved'): ?>

              <a class="btn btn-outline-warning" href="?action=feature&amp;id=<?= (int)$p['id'] ?><?= $status_filter !== '' ? '&amp;status=' . urlencode($status_filter) : '' ?>" title="Toggle featured"><i class="fas fa-star"></i></a>

            <?php endif; ?>

            <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(rent_lease_admin_photos_url((int)$p['id']), ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-image"></i></a>

            <a class="btn btn-outline-danger" href="?action=delete&amp;id=<?= (int)$p['id'] ?>" onclick="return confirm('Delete this listing?');"><i class="fas fa-trash"></i></a>

          </div>

        </td>

      </tr>

      <?php endforeach; ?>

      </tbody>

    </table>

  </div>

  <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_INDEX_URL, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-secondary">Back</a>

</main>

<?php if (defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL) { ?>
<?php include __DIR__ . '/../../superadmin/includes/admin-shell-close.php'; ?>
<?php } else { ?>
</body>

</html>
<?php } ?>

