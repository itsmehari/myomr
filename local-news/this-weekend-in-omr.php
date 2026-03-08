<?php
require_once __DIR__ . '/../omr-local-events/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../omr-local-events/includes/event-functions-omr.php';

$sat = date('Y-m-d', strtotime('saturday this week'));
$sun = date('Y-m-d', strtotime('sunday this week'));
$filters = [
  'search' => '', 'category' => 0, 'locality' => '', 'is_free' => '',
  'date_from' => $sat, 'date_to' => $sun
];
$events = getEvents($filters, 100, 0);

$page_title = 'This Weekend in OMR – Top Events, Timings, Venues';
$page_description = 'Curated roundup of the best events this weekend on Old Mahabalipuram Road (OMR): timings, venues, and quick links.';
$canonical_url = 'https://myomr.in/local-news/this-weekend-in-omr.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>" />
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <?php include __DIR__ . '/../components/organization-schema.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<header class="py-5" style="background:linear-gradient(90deg,#9ebd13 0%, #008552 100%); color:#fff;">
  <div class="container">
    <h1 class="display-5 mb-2">This Weekend in OMR</h1>
    <p class="lead">Your curated shortlist for Saturday and Sunday on the IT Corridor.</p>
    <div><a class="btn btn-light btn-sm" href="/omr-local-events/weekend.php">Browse all weekend events</a></div>
    <div class="mt-2 d-flex gap-2 flex-wrap">
      <?php $roundupUrl = 'https://myomr.in/local-news/this-weekend-in-omr.php'; ?>
      <a class="btn btn-sm btn-outline-light" target="_blank" href="https://wa.me/?text=<?php echo urlencode('This Weekend in OMR – curated roundup: ' . $roundupUrl . '?utm_source=whatsapp&utm_medium=social&utm_campaign=weekend_roundup'); ?>">Share on WhatsApp</a>
      <a class="btn btn-sm btn-outline-light" target="_blank" href="https://t.me/share/url?url=<?php echo urlencode($roundupUrl . '?utm_source=telegram&utm_medium=social&utm_campaign=weekend_roundup'); ?>&text=<?php echo urlencode('This Weekend in OMR – curated roundup'); ?>">Share on Telegram</a>
    </div>
  </div>
</header>

<main class="py-5">
  <div class="container">
    <?php if (empty($events)): ?>
      <div class="alert alert-info">We’re compiling this weekend’s picks. Check again soon or <a href="/omr-local-events/post-event-omr.php">list your event</a>.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($events as $ev): ?>
          <div class="col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h3 class="h5 mb-1"><a class="text-decoration-none" href="/omr-local-events/event-detail-omr.php?slug=<?php echo urlencode($ev['slug']); ?>"><?php echo htmlspecialchars($ev['title']); ?></a></h3>
                <div class="text-muted mb-2">
                  <i class="far fa-calendar"></i>
                  <?php echo date('D, M d, g:i a', strtotime($ev['start_datetime'])); ?>
                  <?php if (!empty($ev['end_datetime'])): ?> – <?php echo date('g:i a', strtotime($ev['end_datetime'])); ?><?php endif; ?>
                </div>
                <div class="mb-2"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($ev['location']); ?><?php echo $ev['locality'] ? ' • ' . htmlspecialchars($ev['locality']) : ''; ?></div>
                <div class="d-flex gap-2 flex-wrap">
                  <a class="btn btn-sm btn-outline-secondary" href="/omr-local-events/event-detail-omr.php?slug=<?php echo urlencode($ev['slug']); ?>">View details</a>
                  <a class="btn btn-sm btn-outline-secondary" target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(($ev['location'] ?: '').' '.($ev['locality'] ?: 'OMR Chennai')); ?>">Map</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <hr class="my-5" />
    <div class="text-center">
      <p class="mb-2">Are you hosting something this weekend?</p>
      <a class="btn btn-success" href="/omr-local-events/post-event-omr.php">List your event (Free)</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


