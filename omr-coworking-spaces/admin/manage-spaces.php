<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COWORKING_ADMIN_ROUTED', '/superadmin/coworking/manage-spaces.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Manage Coworking Spaces
 */

$title = 'Manage Coworking Spaces';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get all spaces
$query = "SELECT h.*, p.full_name as owner_name 
          FROM coworking_spaces h 
          LEFT JOIN space_owners p ON h.owner_id = p.id 
          ORDER BY h.created_at DESC";
$result = $conn->query($query);
$spaces = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Get stats
$total = count($spaces);
$active = count(array_filter($spaces, fn($s) => $s['status'] === 'active'));
$pending = count(array_filter($spaces, fn($s) => $s['status'] === 'pending'));
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
<main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">
<?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Operation completed successfully!</div>
      <?php endif; ?>
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Coworking Spaces</h2>
        <div class="btn-group">
          <span class="badge bg-success ms-2"><?php echo $active; ?> Active</span>
          <span class="badge bg-warning ms-2"><?php echo $pending; ?> Pending</span>
          <span class="badge bg-secondary ms-2"><?php echo $total; ?> Total</span>
        </div>
      </div>
      
      <?php if (empty($spaces)): ?>
        <div class="alert alert-info">No spaces found.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Space Name</th>
                <th>Type</th>
                <th>Owner</th>
                <th>Location</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($spaces as $space): ?>
                <tr>
                  <td><?php echo $space['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($space['space_name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($space['space_type']); ?></td>
                  <td><?php echo htmlspecialchars($space['owner_name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($space['locality'] ?? 'OMR'); ?></td>
                  <td>
                    <?php
                    $statusColors = ['active' => 'success', 'pending' => 'warning', 'inactive' => 'secondary', 'flagged' => 'danger'];
                    $color = $statusColors[$space['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $color; ?>"><?php echo ucfirst($space['status']); ?></span>
                  </td>
                  <td>
                    <?php if ($space['featured']): ?>
                      <i class="fas fa-star text-warning"></i>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="../../omr-coworking-spaces/space-detail.php?id=<?php echo $space['id']; ?>" class="btn btn-primary" title="View">
                        <i class="fas fa-eye"></i>
                      </a>
                      <?php if ($space['status'] === 'pending'): ?>
                        <button class="btn btn-success" onclick="approveSpace(<?php echo $space['id']; ?>)" title="Approve">
                          <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-danger" onclick="rejectSpace(<?php echo $space['id']; ?>)" title="Reject">
                          <i class="fas fa-times"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($space['verification_status'] !== 'verified'): ?>
                        <button class="btn btn-info" onclick="verifySpace(<?php echo $space['id']; ?>)" title="Verify">
                          <i class="fas fa-certificate"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($space['status'] === 'active'): ?>
                        <button class="btn btn-warning" onclick="unlistSpace(<?php echo $space['id']; ?>)" title="Unlist">
                          <i class="fas fa-ban"></i>
                        </button>
                      <?php else: ?>
                        <button class="btn btn-secondary" onclick="approveSpace(<?php echo $space['id']; ?>)" title="Activate">
                          <i class="fas fa-toggle-on"></i>
                        </button>
                      <?php endif; ?>
                      <button class="btn btn-danger" onclick="deleteSpace(<?php echo $space['id']; ?>)" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      
    </main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const coworkingApproveUrl = <?php echo json_encode(coworking_admin_action_url('approve-space.php')); ?>;
const coworkingRejectUrl = <?php echo json_encode(coworking_admin_action_url('reject-space.php')); ?>;
const coworkingVerifyUrl = <?php echo json_encode(coworking_admin_action_url('verify-space.php')); ?>;
const coworkingUnlistUrl = <?php echo json_encode(coworking_admin_action_url('unlist-space.php')); ?>;
const coworkingDeleteUrl = <?php echo json_encode(coworking_admin_action_url('delete-space.php')); ?>;

function approveSpace(id) {
  if (confirm('Approve this space?')) {
    window.location.href = coworkingApproveUrl + '?id=' + id;
  }
}
function rejectSpace(id) {
  if (confirm('Reject this pending space? It will be marked inactive.')) {
    window.location.href = coworkingRejectUrl + '?id=' + id;
  }
}
function verifySpace(id) {
  if (confirm('Verify this space?')) {
    window.location.href = coworkingVerifyUrl + '?id=' + id;
  }
}
function unlistSpace(id) {
  if (confirm('Unlist this space? It will no longer appear in public listings.')) {
    window.location.href = coworkingUnlistUrl + '?id=' + id;
  }
}
function deleteSpace(id) {
  if (confirm('Permanently delete this space listing? This cannot be undone.')) {
    window.location.href = coworkingDeleteUrl + '?id=' + id;
  }
}
</script>
</body>
</html>

