<?php
// Reusable widget: Top Featured Events (limit 3)
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/include-path.php';
require_once __DIR__ . '/../../core/omr-connect.php';

$items = [];
try {
  global $conn;
  if ($conn && !$conn->connect_error) {
    $res = $conn->query("SELECT title, slug, start_datetime, location FROM event_listings WHERE featured = 1 AND status IN ('scheduled','ongoing') ORDER BY start_datetime ASC LIMIT 3");
    while ($row = $res->fetch_assoc()) { $items[] = $row; }
  }
} catch (Throwable $e) {
  error_log('Events Widget: ' . $e->getMessage());
}

if (empty($items)) { return; }
?>
<div class="card-modern mb-4">
  <div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h3 class="h5 mb-0">Featured Events</h3>
      <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    <ul class="list-unstyled mb-0">
      <?php foreach ($items as $ev): ?>
        <li class="mb-2">
          <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/event/<?php echo urlencode($ev['slug']); ?>" class="text-decoration-none">
            <strong><?php echo htmlspecialchars($ev['title']); ?></strong>
          </a>
          <div class="small text-muted">
            <?php echo date('M d, Y g:i a', strtotime($ev['start_datetime'])); ?> •
            <?php echo htmlspecialchars($ev['location']); ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  </div>


