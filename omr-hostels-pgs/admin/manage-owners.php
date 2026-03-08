<?php
/**
 * Admin - Manage Owners
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

$title = 'Manage Owners';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get all owners with property count
$query = "SELECT p.*, COUNT(h.id) as property_count
          FROM property_owners p 
          LEFT JOIN hostels_pgs h ON p.id = h.owner_id
          GROUP BY p.id
          ORDER BY p.created_at DESC";
$result = $conn->query($query);
$owners = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - MyOMR Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include '../../admin/admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">
      <?php include '../../admin/admin-header.php'; ?>
      <?php include '../../admin/admin-breadcrumbs.php'; ?>
      
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Operation completed successfully!</div>
      <?php endif; ?>
      
      <h2 class="mb-4">All Property Owners</h2>
      
      <?php if (empty($owners)): ?>
        <div class="alert alert-info">No owners found.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Properties</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($owners as $owner): ?>
                <tr>
                  <td><?php echo $owner['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($owner['full_name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($owner['email']); ?></td>
                  <td><?php echo htmlspecialchars($owner['phone'] ?? 'N/A'); ?></td>
                  <td><span class="badge bg-primary"><?php echo $owner['property_count']; ?></span></td>
                  <td>
                    <?php
                    $statusColors = ['verified' => 'success', 'pending' => 'warning', 'suspended' => 'danger'];
                    $color = $statusColors[$owner['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $color; ?>"><?php echo ucfirst($owner['status']); ?></span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <?php if ($owner['status'] !== 'verified'): ?>
                        <button class="btn btn-success" onclick="verifyOwner(<?php echo $owner['id']; ?>)" title="Verify">
                          <i class="fas fa-check"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($owner['status'] !== 'suspended'): ?>
                        <button class="btn btn-danger" onclick="suspendOwner(<?php echo $owner['id']; ?>)" title="Suspend">
                          <i class="fas fa-ban"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function verifyOwner(id) {
  if (confirm('Verify this owner?')) {
    window.location.href = 'verify-owner.php?id=' + id + '&action=verify';
  }
}
function suspendOwner(id) {
  if (confirm('Suspend this owner? They will not be able to access their account.')) {
    window.location.href = 'verify-owner.php?id=' + id + '&action=suspend';
  }
}
</script>
</body>
</html>

