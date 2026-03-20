<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/organizer-manage.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['manage_csrf'])) { $_SESSION['manage_csrf'] = bin2hex(random_bytes(16)); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$t = $_GET['t'] ?? '';
$error = '';
$ok = '';
$sub = null;

try {
  global $conn;
  if ($id > 0 && $conn && !$conn->connect_error) {
    $q = $conn->prepare("SELECT * FROM event_submissions WHERE id=? LIMIT 1");
    $q->bind_param('i', $id);
    $q->execute();
    $sub = $q->get_result()->fetch_assoc();
    if (!$sub) { $error = 'Submission not found.'; }
    else if (!eventsVerifyManageToken($id, (string)$sub['organizer_email'], (string)$t)) { $error = 'Invalid or expired link.'; }
  } else {
    $error = 'Invalid request.';
  }
} catch (Throwable $e) {
  $error = 'Could not load submission.';
}

// Handle updates only if pending
if (!$error && $sub && $sub['status'] === 'submitted' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!hash_equals($_SESSION['manage_csrf'], (string)$token)) {
    $error = 'Session expired. Please reload and try again.';
  } else {
    $title = sanitizeInput($_POST['title'] ?? $sub['title']);
    $location = sanitizeInput($_POST['location'] ?? $sub['location']);
    $locality = sanitizeInput($_POST['locality'] ?? $sub['locality']);
    $start = $_POST['start_datetime'] ?? $sub['start_datetime'];
    $end = $_POST['end_datetime'] ?? $sub['end_datetime'];
    $phone = sanitizeInput($_POST['organizer_phone'] ?? $sub['organizer_phone']);
    $tickets = sanitizeInput($_POST['tickets_url'] ?? $sub['tickets_url']);
    $desc = $_POST['description'] ?? $sub['description'];
    try {
      global $conn;
      $u = $conn->prepare("UPDATE event_submissions SET title=?, location=?, locality=?, start_datetime=?, end_datetime=?, organizer_phone=?, tickets_url=?, description=? WHERE id=?");
      $u->bind_param('ssssssssi', $title, $location, $locality, $start, $end, $phone, $tickets, $desc, $id);
      $u->execute();
      $ok = 'Updated successfully.';
      // refresh sub
      $q = $conn->prepare("SELECT * FROM event_submissions WHERE id=? LIMIT 1");
      $q->bind_param('i', $id);
      $q->execute();
      $sub = $q->get_result()->fetch_assoc();
    } catch (Throwable $e) {
      $error = 'Update failed.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Submission – MyOMR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/footer.css" />
</head>
<body class="modern-page">
<?php omr_nav(); ?>

<div class="container py-5">
  <h1 class="h3 mb-3">Manage Submission</h1>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php elseif (!$sub): ?>
    <div class="alert alert-warning">Not found.</div>
  <?php else: ?>
    <div class="mb-3"><span class="badge bg-secondary">Status: <?php echo htmlspecialchars($sub['status']); ?></span></div>
    <?php if ($ok): ?><div class="alert alert-success"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>

    <?php if ($sub['status'] !== 'submitted'): ?>
      <div class="alert alert-info">This submission is already processed. You can no longer edit it.</div>
      <div class="card"><div class="card-body">
        <h5><?php echo htmlspecialchars($sub['title']); ?></h5>
        <div><?php echo nl2br(htmlspecialchars(strip_tags($sub['description']))); ?></div>
      </div></div>
    <?php else: ?>
      <form method="post" class="card p-3">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['manage_csrf']); ?>">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($sub['title']); ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($sub['location']); ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Locality</label>
            <input type="text" class="form-control" name="locality" value="<?php echo htmlspecialchars($sub['locality']); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Start Date & Time</label>
            <input type="datetime-local" class="form-control" name="start_datetime" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $sub['start_datetime'])); ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">End Date & Time</label>
            <input type="datetime-local" class="form-control" name="end_datetime" value="<?php echo htmlspecialchars($sub['end_datetime'] ? str_replace(' ', 'T', $sub['end_datetime']) : ''); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Organizer Phone</label>
            <input type="text" class="form-control" name="organizer_phone" value="<?php echo htmlspecialchars($sub['organizer_phone']); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Tickets URL</label>
            <input type="url" class="form-control" name="tickets_url" value="<?php echo htmlspecialchars($sub['tickets_url']); ?>">
          </div>
          <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="6" name="description" required><?php echo htmlspecialchars($sub['description']); ?></textarea>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save Changes</button>
          <a class="btn btn-outline-secondary" href="/omr-local-events/">Back to Events</a>
        </div>
      </form>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


