<?php
/**
 * Admin - View All Coworking Space Inquiries
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

$title = 'All Coworking Space Inquiries';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get all inquiries
$query = "SELECT i.*, h.space_name, h.locality 
          FROM space_inquiries i
          INNER JOIN coworking_spaces h ON i.space_id = h.id
          ORDER BY i.created_at DESC";
$result = $conn->query($query);
$inquiries = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
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
      
      <h2 class="mb-4">All Coworking Space Inquiries</h2>
      
      <?php if (empty($inquiries)): ?>
        <div class="alert alert-info">No inquiries found.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Space</th>
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
                  <td><?php echo htmlspecialchars($inq['space_name']); ?></td>
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
      
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

