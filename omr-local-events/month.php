<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';
include __DIR__ . '/includes/dev-diagnostics.php';

$start = date('Y-m-01');
$end = date('Y-m-t');
$filters = [
  'search' => '', 'category' => 0, 'locality' => '', 'is_free' => '',
  'date_from' => $start, 'date_to' => $end
];

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 12; $offset = ($page - 1) * $per_page;
$events = getEvents($filters, $per_page, $offset);
$total = getEventCount($filters); $pages = max(1, (int)ceil($total / $per_page));

$page_title = 'This Month in OMR – Events | MyOMR';
$page_description = 'Plan ahead with events this month across OMR: community, arts, sports, workshops and more.';
$canonical_url = 'https://myomr.in/omr-local-events/month';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>" />
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
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
      <div class="title h1 mb-0">This Month</div>
      <div class="small opacity-90">Plan your month on OMR</div>
    </div>
    <div class="dashboard-actions">
      <a href="post-event-omr.php" class="btn-modern btn-modern-primary"><i class="fas fa-plus"></i><span>List an Event</span></a>
      <a href="index.php" class="btn-modern btn-modern-secondary"><i class="fas fa-globe"></i><span>All Events</span></a>
    </div>
  </div>
</div>

<main class="py-5">
  <div class="container">
    <div class="row">
      <?php if (count($events) === 0): ?>
        <div class="col-12"><div class="alert-modern">No events found for this month.</div></div>
      <?php else: foreach ($events as $ev): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="job-card-modern h-100 dashboard-card">
            <div class="job-header">
              <div class="job-title"><?php echo htmlspecialchars($ev['title']); ?></div>
              <div class="company-name"><?php echo htmlspecialchars($ev['location']); ?><?php echo $ev['locality'] ? ' • ' . htmlspecialchars($ev['locality']) : ''; ?></div>
              <div class="job-meta">
                <span class="badge-modern badge-modern-success"><i class="far fa-calendar"></i><?php echo date('M d, Y g:i a', strtotime($ev['start_datetime'])); ?></span>
                <?php if ($ev['is_free']): ?><span class="badge-modern badge-modern-primary">Free</span><?php endif; ?>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="event-detail-omr.php?slug=<?php echo urlencode($ev['slug']); ?>" class="btn-modern btn-modern-secondary"><i class="fas fa-eye"></i><span>View</span></a>
              <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($ev['location'] . ' ' . ($ev['locality'] ?? 'OMR Chennai')); ?>" target="_blank" class="btn-modern btn-modern-secondary"><i class="fas fa-map-marker-alt"></i><span>Map</span></a>
            </div>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>

    <?php if ($pages > 1): ?>
    <nav class="mt-4" aria-label="Pages">
      <ul class="pagination justify-content-center">
        <?php for ($p=1;$p<=$pages;$p++): ?>
          <li class="page-item <?php echo $p===$page?'active':''; ?>">
            <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


