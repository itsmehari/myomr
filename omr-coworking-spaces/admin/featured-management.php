<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COWORKING_ADMIN_ROUTED', '/superadmin/coworking/featured-management.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Featured Coworking Spaces Management
 */

$title = 'Featured Coworking Spaces';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get featured spaces
$query = "SELECT h.*, p.full_name as owner_name 
          FROM coworking_spaces h 
          LEFT JOIN space_owners p ON h.owner_id = p.id 
          WHERE h.featured = 1
          ORDER BY h.featured_start DESC";
$result = $conn->query($query);
$featured = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Get all active spaces for "Add to Featured"
$allActiveQuery = "SELECT h.*, p.full_name as owner_name 
                   FROM coworking_spaces h 
                   LEFT JOIN space_owners p ON h.owner_id = p.id 
                   WHERE h.status = 'active' 
                   ORDER BY h.space_name";
$allActiveResult = $conn->query($allActiveQuery);
$all_active = $allActiveResult ? $allActiveResult->fetch_all(MYSQLI_ASSOC) : [];

$__modulePageTitle = 'Coworking';
$__moduleActiveNav = '/superadmin/coworking/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Coworking</title></head>
<body>
<?php } ?>
<div class="container-fluid">
  <div class="row">
<main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">
<h2 class="mb-4">Featured Coworking Spaces Management</h2>
      
      <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>Featured spaces appear first in search results and on the homepage. Featured listings can be rotated monthly.
      </div>
      
      <div class="mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFeaturedModal">
          <i class="fas fa-plus me-2"></i>Add to Featured
        </button>
      </div>
      
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Operation completed successfully!</div>
      <?php endif; ?>
      
      <?php if (empty($featured)): ?>
        <div class="alert alert-warning">No spaces are currently featured.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Space Name</th>
                <th>Type</th>
                <th>Location</th>
                <th>Owner</th>
                <th>Featured Since</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($featured as $space): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($space['space_name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($space['space_type']); ?></td>
                  <td><?php echo htmlspecialchars($space['locality']); ?></td>
                  <td><?php echo htmlspecialchars($space['owner_name'] ?? 'N/A'); ?></td>
                  <td><?php echo $space['featured_start'] ? date('M j, Y', strtotime($space['featured_start'])) : 'N/A'; ?></td>
                  <td>
                    <button class="btn btn-sm btn-danger" onclick="removeFeatured(<?php echo $space['id']; ?>)">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      
      <!-- Modal to Add to Featured -->
      <div class="modal fade" id="addFeaturedModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Space to Featured</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Select</th>
                      <th>Space Name</th>
                      <th>Type</th>
                      <th>Location</th>
                      <th>Views</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($all_active as $space): ?>
                      <tr>
                        <td>
                          <input type="checkbox" name="featured_spaces" value="<?php echo $space['id']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($space['space_name']); ?></td>
                        <td><?php echo htmlspecialchars($space['space_type']); ?></td>
                        <td><?php echo htmlspecialchars($space['locality']); ?></td>
                        <td><?php echo number_format($space['views_count'] ?? 0); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success" onclick="addFeatured()">
                <i class="fas fa-check me-2"></i>Add Selected
              </button>
            </div>
          </div>
        </div>
      </div>
      
    </main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addFeatured() {
  const checkboxes = document.querySelectorAll('input[name="featured_spaces"]:checked');
  const ids = Array.from(checkboxes).map(cb => cb.value);
  if (ids.length === 0) {
    alert('Please select at least one space.');
    return;
  }
  window.location.href = 'add-featured.php?ids=' + ids.join(',');
}

function removeFeatured(id) {
  if (confirm('Remove this space from featured listings?')) {
    window.location.href = 'remove-featured.php?id=' + id;
  }
}
</script>

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
