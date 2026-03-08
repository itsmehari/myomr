<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once '../core/omr-connect.php';
$title = 'Manage News Bulletin';
$breadcrumbs = ['News Bulletin' => null];
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del = $conn->prepare('DELETE FROM news_bulletin WHERE id = ?');
    $del->bind_param('i', $id);
    if ($del->execute()) {
        $_SESSION['flash_success'] = 'News item deleted.';
    } else {
        $_SESSION['flash_error'] = 'Error deleting news item.';
    }
    $del->close();
    header('Location: news-list.php');
    exit;
}
$result = $conn->query('SELECT * FROM news_bulletin ORDER BY date DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News Bulletin - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .sidebar { min-height: 100vh; background: #14532d; color: #fff; }
        .sidebar a { color: #fff; display: block; padding: 1rem; border-bottom: 1px solid #1e6b3a; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: #22c55e; color: #14532d; font-weight: bold; }
        .main-content { padding: 2rem; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <h2 class="mb-4">News Bulletin List</h2>
      <a href="news-add.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Add News</a>
      <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white" aria-label="News Bulletin List">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Date</th>
              <th>Tags</th>
              <th>Image</th>
              <th>URL</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo htmlspecialchars($row['title']); ?></td>
                  <td><?php echo htmlspecialchars($row['date']); ?></td>
                  <td><?php echo htmlspecialchars($row['tags']); ?></td>
                  <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="img" width="60"></td>
                  <td><a href="<?php echo htmlspecialchars($row['article_url']); ?>" target="_blank">View</a></td>
                  <td>
                    <a href="news-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" aria-label="Edit news item"><i class="fas fa-edit"></i> Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>" aria-label="Delete news item"><i class="fas fa-trash"></i> Delete</button>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Confirm Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            Are you sure you want to delete this news item?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <a href="news-list.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center">No news items found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<!-- Bootstrap JS for modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 