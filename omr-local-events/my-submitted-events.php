<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['organizer_csrf'])) { $_SESSION['organizer_csrf'] = bin2hex(random_bytes(16)); }

$submissions = [];
$emailInput = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!hash_equals($_SESSION['organizer_csrf'], (string)$token)) {
    $errorMsg = 'Session expired. Please reload and try again.';
  } else if (!empty($_POST['website'])) {
    // Honeypot
    $errorMsg = 'Invalid request.';
  } else {
    $emailInput = trim((string)($_POST['email'] ?? ''));
    if (!filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
      $errorMsg = 'Please enter a valid email.';
    } else {
      try {
        global $conn;
        if ($conn && !$conn->connect_error) {
          $stmt = $conn->prepare("SELECT id, title, status, created_at FROM event_submissions WHERE organizer_email = ? ORDER BY created_at DESC LIMIT 100");
          $stmt->bind_param('s', $emailInput);
          $stmt->execute();
          $res = $stmt->get_result();
          while ($row = $res->fetch_assoc()) { $submissions[] = $row; }
        }
      } catch (Throwable $e) {
        error_log('My Submissions error: ' . $e->getMessage());
        $errorMsg = 'Could not load submissions right now.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Submitted Events – MyOMR</title>
  <meta name="description" content="Check the status of events you submitted to MyOMR." />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/omr-jobs-unified-design.css" />
  <link rel="stylesheet" href="assets/events-dashboard.css" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <?php include __DIR__ . '/../components/organization-schema.php'; ?>
</head>
<body class="modern-page">
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<div class="dashboard-header">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2 mb-md-0">
      <div class="title h1 mb-0">My Submitted Events</div>
      <div class="small opacity-90">View the status of events you submitted</div>
    </div>
    <div class="dashboard-actions">
      <a href="post-event-omr.php" class="btn-modern btn-modern-primary"><i class="fas fa-plus"></i><span>List an Event</span></a>
      <a href="index.php" class="btn-modern btn-modern-secondary"><i class="fas fa-globe"></i><span>All Events</span></a>
    </div>
  </div>
</div>

<main class="py-5">
  <div class="container">
    <div class="card-modern mb-4">
      <div class="p-4">
        <form method="post" class="row g-3">
          <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['organizer_csrf']); ?>" />
          <div style="position:absolute;left:-9999px;top:auto;">
            <input type="text" name="website" tabindex="-1" autocomplete="off" />
          </div>
          <div class="col-md-8">
            <label class="form-label-modern">Organizer Email</label>
            <input type="email" class="form-control-modern" name="email" required placeholder="you@email.com" value="<?php echo htmlspecialchars($emailInput); ?>" />
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn-modern btn-modern-primary" type="submit"><i class="fas fa-search"></i><span>Find Submissions</span></button>
          </div>
          <?php if ($errorMsg): ?>
            <div class="col-12"><div class="alert alert-danger mb-0"><?php echo htmlspecialchars($errorMsg); ?></div></div>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errorMsg)): ?>
      <div class="card-modern">
        <div class="p-4">
          <?php if (empty($submissions)): ?>
            <div class="alert-modern">No submissions found for <strong><?php echo htmlspecialchars($emailInput); ?></strong>.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th><th>Title</th><th>Status</th><th>Submitted</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($submissions as $s): ?>
                    <tr>
                      <td><?php echo (int)$s['id']; ?></td>
                      <td><?php echo htmlspecialchars($s['title']); ?></td>
                      <td><span class="badge bg-secondary"><?php echo htmlspecialchars($s['status']); ?></span></td>
                      <td><?php echo htmlspecialchars($s['created_at'] ?? ''); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


