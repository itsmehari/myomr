<?php
/**
 * Admin - Featured Properties Management
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

$title = 'Featured Properties';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get featured properties
$query = "SELECT h.*, p.full_name as owner_name 
          FROM hostels_pgs h 
          LEFT JOIN property_owners p ON h.owner_id = p.id 
          WHERE h.featured = 1
          ORDER BY h.featured_start DESC";
$result = $conn->query($query);
$featured = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Get all active properties for "Add to Featured"
$allActiveQuery = "SELECT h.*, p.full_name as owner_name 
                   FROM hostels_pgs h 
                   LEFT JOIN property_owners p ON h.owner_id = p.id 
                   WHERE h.status = 'active' 
                   ORDER BY h.property_name";
$allActiveResult = $conn->query($allActiveQuery);
$all_active = $allActiveResult ? $allActiveResult->fetch_all(MYSQLI_ASSOC) : [];
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
      
      <h2 class="mb-4">Featured Properties Management</h2>
      
      <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>Featured properties appear first in search results and on the homepage. Featured listings can be rotated monthly.
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
        <div class="alert alert-warning">No properties are currently featured.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Property Name</th>
                <th>Type</th>
                <th>Location</th>
                <th>Owner</th>
                <th>Featured Since</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($featured as $prop): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($prop['property_name']); ?></strong></td>
                  <td><?php echo htmlspecialchars($prop['property_type']); ?></td>
                  <td><?php echo htmlspecialchars($prop['locality']); ?></td>
                  <td><?php echo htmlspecialchars($prop['owner_name'] ?? 'N/A'); ?></td>
                  <td><?php echo $prop['featured_start'] ? date('M j, Y', strtotime($prop['featured_start'])) : 'N/A'; ?></td>
                  <td>
                    <button class="btn btn-sm btn-danger" onclick="removeFeatured(<?php echo $prop['id']; ?>)">
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
              <h5 class="modal-title">Add Property to Featured</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Select</th>
                      <th>Property Name</th>
                      <th>Type</th>
                      <th>Location</th>
                      <th>Views</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($all_active as $prop): ?>
                      <tr>
                        <td>
                          <input type="checkbox" name="featured_properties" value="<?php echo $prop['id']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($prop['property_name']); ?></td>
                        <td><?php echo htmlspecialchars($prop['property_type']); ?></td>
                        <td><?php echo htmlspecialchars($prop['locality']); ?></td>
                        <td><?php echo number_format($prop['views_count'] ?? 0); ?></td>
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
      
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addFeatured() {
  const checkboxes = document.querySelectorAll('input[name="featured_properties"]:checked');
  const ids = Array.from(checkboxes).map(cb => cb.value);
  if (ids.length === 0) {
    alert('Please select at least one property.');
    return;
  }
  window.location.href = 'add-featured.php?ids=' + ids.join(',');
}

function removeFeatured(id) {
  if (confirm('Remove this property from featured listings?')) {
    window.location.href = 'remove-featured.php?id=' + id;
  }
}
</script>
</body>
</html>

