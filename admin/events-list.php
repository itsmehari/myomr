<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

include_once '../core/omr-connect.php';
$title = 'Manage Events';
$breadcrumbs = ['Events' => null];
$events = [];
$error = '';
$sql = "SELECT id, title, event_date, status FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
if ($result === false) {
    $error = 'Error loading events: ' . $conn->error;
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
if (isset($_GET['error'])) {
    $_SESSION['flash_error'] = htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <link rel="stylesheet" href="../events/Omr-Road-Events-and-Happenings.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Admin - Manage Events</h1>
      <a href="events-add.php" class="btn btn-success mb-3">Add Event</a>
      <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white" aria-label="Events List">
            <thead class="thead-dark">
                <tr><th>Title</th><th>Date</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($event['status']); ?></td>
                        <td>
                            <a href="events-edit.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary" aria-label="Edit event">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $event['id']; ?>" aria-label="Delete event">Delete</button>
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $event['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $event['id']; ?>" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $event['id']; ?>">Confirm Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure you want to delete this event?
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <a href="events-delete.php?id=<?php echo $event['id']; ?>" class="btn btn-danger">Delete</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No events found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
      </div>
      <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </main>
  </div>
</div>
<!-- Bootstrap JS for modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 