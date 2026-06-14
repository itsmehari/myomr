<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
require_once '../core/security-helpers.php';
$title = 'Manage Restaurants';
$breadcrumbs = ['Restaurants' => null];

// Pagination settings
$per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: restaurants-list.php?page=' . $page . ($search ? '&search=' . urlencode($search) : ''));
        exit;
    }
    $id = intval($_POST['id'] ?? 0);
    $del = $conn->prepare('DELETE FROM omr_restaurants WHERE id = ?');
    $del->bind_param('i', $id);
    if ($del->execute()) {
        $_SESSION['flash_success'] = 'Restaurant deleted.';
    } else {
        $_SESSION['flash_error'] = 'Error deleting restaurant.';
    }
    $del->close();
    header('Location: restaurants-list.php?page=' . $page . ($search ? '&search=' . urlencode($search) : ''));
    exit;
}

// Count total records
$count_sql = 'SELECT COUNT(*) FROM omr_restaurants WHERE 1=1';
if ($search) {
    $count_sql .= " AND (name LIKE '%$search%' OR locality LIKE '%$search%' OR cuisine LIKE '%$search%')";
}
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_row()[0];
$total_pages = ceil($total_records / $per_page);

// Fetch records for current page
$sql = "SELECT id, name, address, locality, cuisine, cost_for_two, rating, availability, imagelocation FROM omr_restaurants WHERE 1=1";
if ($search) {
    $sql .= " AND (name LIKE '%$search%' OR locality LIKE '%$search%' OR cuisine LIKE '%$search%')";
}
$sql .= " ORDER BY name ASC LIMIT $per_page OFFSET $offset";
$result = $conn->query($sql);


$pageTitle = $pageTitle ?? $title ?? 'Manage Restaurants';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<div class="mb-3">
        <a href="restaurants-add.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Restaurant</a>
      </div>
      <form method="GET" class="mb-3">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="Search by name, locality, or cuisine" value="<?php echo htmlspecialchars($search); ?>">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Search</button>
            <a href="restaurants-list.php" class="btn btn-secondary ms-2">Clear</a>
          </div>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white" aria-label="Restaurants List">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Address</th>
              <th>Locality</th>
              <th>Cuisine</th>
              <th>Cost for Two</th>
              <th>Rating</th>
              <th>Availability</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td><?php echo htmlspecialchars($row['address']); ?></td>
                  <td><?php echo htmlspecialchars($row['locality']); ?></td>
                  <td><?php echo htmlspecialchars($row['cuisine']); ?></td>
                  <td>₹<?php echo $row['cost_for_two']; ?></td>
                  <td><?php echo $row['rating']; ?></td>
                  <td><?php echo htmlspecialchars($row['availability']); ?></td>
                  <td><img src="<?php echo htmlspecialchars($row['imagelocation']); ?>" alt="Restaurant Image" width="60"></td>
                  <td>
                    <a href="restaurants-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" aria-label="Edit restaurant"><i class="fas fa-edit"></i> Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>" aria-label="Delete restaurant"><i class="fas fa-trash"></i> Delete</button>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            Are you sure you want to delete this restaurant?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="post" class="m-0">
                              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                              <input type="hidden" name="action" value="delete">
                              <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="10" class="text-center">No restaurants found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="restaurants-list.php?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="restaurants-list.php?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
              <a class="page-link" href="restaurants-list.php?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
