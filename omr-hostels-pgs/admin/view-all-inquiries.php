<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('HOSTELS_ADMIN_ROUTED', '/superadmin/hostels/view-all-inquiries.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - View All Inquiries
 */

$title = 'All Inquiries';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get all inquiries
$query = "SELECT i.*, h.property_name, h.locality 
          FROM property_inquiries i
          INNER JOIN hostels_pgs h ON i.property_id = h.id
          ORDER BY i.created_at DESC";
$result = $conn->query($query);
$inquiries = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$__modulePageTitle = 'Hostels & PGs';
$__moduleActiveNav = '/superadmin/hostels/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Hostels & PGs</title></head>
<body>
<?php } ?>
<div class="container-fluid">
  <div class="row">
<main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">
<h2 class="mb-4">All Property Inquiries</h2>
      
      <?php if (empty($inquiries)): ?>
        <div class="alert alert-info">No inquiries found.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Property</th>
                <th>Location</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($inquiries as $inq): ?>
                <tr>
                  <td><?php echo $inq['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($inq['user_name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($inq['property_name']); ?></td>
                  <td><?php echo htmlspecialchars($inq['locality']); ?></td>
                  <td><?php echo htmlspecialchars($inq['user_phone']); ?></td>
                  <td>
                    <?php
                    $statusColors = ['new' => 'primary', 'contacted' => 'info', 'resolved' => 'success', 'archived' => 'secondary'];
                    $color = $statusColors[$inq['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $color; ?>"><?php echo ucfirst($inq['status']); ?></span>
                  </td>
                  <td><?php echo date('M j, Y', strtotime($inq['created_at'])); ?></td>
                  <td>
                    <a href="inquiry-detail.php?id=<?php echo $inq['id']; ?>" class="btn btn-sm btn-primary">
                      <i class="fas fa-eye"></i> View
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      
    </main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
