<?php
require_once __DIR__ . '/includes/bootstrap.php';
$sat = date('Y-m-d', strtotime('saturday this week'));
$sun = date('Y-m-d', strtotime('sunday this week'));
$filters = [
  'search' => '', 'category' => 0, 'locality' => '', 'is_free' => '',
  'date_from' => $sat, 'date_to' => $sun
];

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 12; $offset = ($page - 1) * $per_page;
$events = getEvents($filters, $per_page, $offset);
$total = getEventCount($filters); $pages = max(1, (int)ceil($total / $per_page));

$page_title = 'This Weekend in OMR – Events | MyOMR';
$page_description = 'Top events this weekend across OMR: workshops, sports, community meetups and more.';
$canonical_url = 'https://myomr.in/omr-local-events/weekend';
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
  <link rel="stylesheet" href="/assets/css/footer.css" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <?php include __DIR__ . '/../components/organization-schema.php'; ?>
</head>
<body class="modern-page">
<?php require_once __DIR__ . '/../components/skip-link.php'; ?>
<?php omr_nav(); ?>

<div class="dashboard-header">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2 mb-md-0">
      <div class="title h1 mb-0">This Weekend</div>
      <div class="small opacity-90">Events for Saturday & Sunday on OMR</div>
      <div class="small mt-1">
        <a href="/local-news/this-weekend-in-omr.php?utm_source=events&utm_medium=internal&utm_campaign=weekend_roundup_link" class="text-decoration-underline">Read our Weekend Roundup</a>
      </div>
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
        <div class="col-12"><div class="alert-modern">No events found for this weekend.</div></div>
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

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


