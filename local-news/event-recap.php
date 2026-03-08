<?php
require_once __DIR__ . '/../omr-local-events/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../omr-local-events/includes/event-functions-omr.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$event = $slug ? getEventBySlug($slug) : null;
if (!$event) { http_response_code(404); die('Event not found'); }

$page_title = 'Recap: ' . $event['title'] . ' – Photos & Highlights';
$canonical_url = 'https://myomr.in/local-news/event-recap.php?slug=' . urlencode($slug);

// Look for images in /omr-local-events/uploads/recaps/{slug}/
$recapDir = __DIR__ . '/../omr-local-events/uploads/recaps/' . $slug;
$images = [];
if (is_dir($recapDir)) {
  foreach (scandir($recapDir) as $f) {
    if (preg_match('/\.(jpe?g|png|webp)$/i', $f)) {
      $images[] = '/omr-local-events/uploads/recaps/' . $slug . '/' . $f;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="description" content="Photo recap and highlights from <?php echo htmlspecialchars($event['title']); ?> on OMR." />
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <?php include __DIR__ . '/../components/organization-schema.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<header class="py-5" style="background:linear-gradient(90deg,#9ebd13 0%, #008552 100%); color:#fff;">
  <div class="container">
    <h1 class="display-6 mb-2">Recap: <?php echo htmlspecialchars($event['title']); ?></h1>
    <p class="mb-0"><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($event['start_datetime'])); ?> • <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?><?php echo $event['locality']?' • '.htmlspecialchars($event['locality']):''; ?></p>
  </div>
</header>

<main class="py-5">
  <div class="container">
    <div class="row g-4">
      <?php if (!empty($images)): foreach ($images as $img): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card border-0 shadow-sm h-100">
            <img class="card-img-top" src="<?php echo htmlspecialchars($img); ?>" alt="Recap photo">
          </div>
        </div>
      <?php endforeach; else: ?>
        <div class="col-12">
          <div class="alert alert-info">Photos for this recap are being processed. Check back soon.</div>
        </div>
      <?php endif; ?>
    </div>

    <hr class="my-5" />
    <div class="text-center">
      <a class="btn btn-outline-secondary" href="/omr-local-events/event-detail-omr.php?slug=<?php echo urlencode($slug); ?>">Back to event</a>
      <a class="btn btn-success" href="/omr-local-events/">Browse more events</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


