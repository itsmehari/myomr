<?php
require_once __DIR__ . '/includes/bootstrap.php';
$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$t = isset($_GET['t']) ? $_GET['t'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Event Submitted – MyOMR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/omr-jobs-unified-design.css" />
  <link rel="stylesheet" href="/assets/css/footer.css" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body class="modern-page">
<?php omr_nav(); ?>

<div class="hero-modern">
  <div class="container">
    <div class="text-center text-white">
      <div class="success-icon-modern"><i class="fas fa-check"></i></div>
      <h1 class="hero-modern-title">Event Submitted</h1>
      <p class="hero-modern-subtitle">Thank you! Your event is pending review.</p>
    </div>
  </div>
</div>

<main class="py-5">
  <div class="container">
    <div class="success-card-modern">
      <p class="lead">Your submission ID: <strong><?php echo $submission_id; ?></strong></p>
      <p>We typically review events within 24–48 hours. You'll be notified if provided an email.</p>
      <div class="d-flex gap-3 justify-content-center mt-3 flex-wrap">
        <a href="index.php" class="btn-modern btn-modern-primary"><i class="fas fa-calendar"></i><span>Browse Events</span></a>
        <a href="post-event-omr.php" class="btn-modern btn-modern-secondary"><i class="fas fa-plus"></i><span>Submit Another</span></a>
        <a href="my-submitted-events.php" class="btn-modern btn-modern-secondary"><i class="fas fa-user"></i><span>My Submitted Events</span></a>
        <?php if ($t && $submission_id): ?>
          <a href="manage-submission.php?id=<?php echo (int)$submission_id; ?>&t=<?php echo urlencode($t); ?>" class="btn-modern btn-modern-secondary"><i class="fas fa-edit"></i><span>Manage this Submission</span></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php omr_footer(); ?>
<script src="assets/events-analytics.js"></script>
<script>
  (function(){
    if (window.MyOMREventsAnalytics) { window.MyOMREventsAnalytics.submissionSuccess(<?php echo (int)$submission_id; ?>); }
  })();
</script>

<script>
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'generate_lead', {
    'conversion_type': 'event_submission',
    'submission_id':   <?= (int)$submission_id ?>
  });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


