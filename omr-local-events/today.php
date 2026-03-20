<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';
$today = date('Y-m-d');
$filters = [
  'search' => '',
  'category' => 0,
  'locality' => '',
  'is_free' => '',
  'date_from' => $today,
  'date_to' => $today
];

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 12; $offset = ($page - 1) * $per_page;
$events = getEvents($filters, $per_page, $offset);
$total = getEventCount($filters); $pages = max(1, (int)ceil($total / $per_page));

$page_title = 'Events Today in OMR Chennai | MyOMR';
$page_description = 'See what is happening today across Old Mahabalipuram Road (OMR): workshops, sports, community events and more.';
$canonical_url = 'https://myomr.in/omr-local-events/today';
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
<?php require_once __DIR__ . '/../components/skip-link.php'; ?>
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<div class="dashboard-header">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2 mb-md-0">
      <div class="title h1 mb-0">Events Today</div>
      <div class="small opacity-90">Happening today across OMR</div>
    </div>
    <div class="dashboard-actions">
      <a href="post-event-omr.php" class="btn-modern btn-modern-primary"><i class="fas fa-plus"></i><span>List an Event</span></a>
      <a href="index.php" class="btn-modern btn-modern-secondary"><i class="fas fa-globe"></i><span>All Events</span></a>
    </div>
  </div>
</div>

<main id="main-content" class="py-5">
  <div class="container">
    <div class="row">
      <?php if (count($events) === 0): ?>
        <div class="col-12"><div class="alert-modern">No events found for today.</div></div>
      <?php else: foreach ($events as $ev): $compact = false; ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <?php include __DIR__ . '/components/event-card.php'; ?>
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


