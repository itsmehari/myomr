<?php
require_once __DIR__ . '/../_bootstrap.php';

include_once '../../core/omr-connect.php';
require_once __DIR__ . '/../../core/security-helpers.php';
$title = 'Manage Events';
$breadcrumbs = ['Events' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quick_action'], $_POST['event_id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: events-list.php');
        exit;
    }
    $eventId = (int)$_POST['event_id'];
    $quickAction = $_POST['quick_action'];
    if ($eventId > 0 && in_array($quickAction, ['approve', 'reject'], true)) {
        $newStatus = $quickAction === 'approve' ? 'published' : 'rejected';
        $stmt = $conn->prepare('UPDATE events SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $newStatus, $eventId);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = $quickAction === 'approve' ? 'Event published.' : 'Event rejected.';
        } else {
            $_SESSION['flash_error'] = 'Could not update event status.';
        }
        $stmt->close();
    }
    header('Location: events-list.php');
    exit;
}

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

$pageTitle = $pageTitle ?? $title ?? 'Manage Events';
include __DIR__ . '/../includes/admin-shell-open.php';
?>
<h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Admin - Manage Events</h1>
      <a href="events-add.php" class="btn btn-success mb-3">Add Event</a>
      <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white" aria-label="Events List">
            <thead class="table-dark">
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
                            <?php if (($event['status'] ?? '') === 'pending'): ?>
                            <form method="post" class="d-inline">
                              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                              <input type="hidden" name="event_id" value="<?php echo (int)$event['id']; ?>">
                              <button type="submit" name="quick_action" value="approve" class="btn btn-sm btn-success" title="Quick publish">Approve</button>
                              <button type="submit" name="quick_action" value="reject" class="btn btn-sm btn-outline-danger" title="Quick reject">Reject</button>
                            </form>
                            <?php endif; ?>
                            <a href="events-edit.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary" aria-label="Edit event">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $event['id']; ?>" aria-label="Delete event">Delete</button>
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $event['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $event['id']; ?>" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $event['id']; ?>">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure you want to delete this event?
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="post" action="events-delete.php" class="m-0">
                                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                                      <input type="hidden" name="action" value="delete">
                                      <input type="hidden" name="id" value="<?php echo (int)$event['id']; ?>">
                                      <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
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
      <a href="/superadmin/index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
<?php include __DIR__ . '/../includes/admin-shell-close.php'; ?>
