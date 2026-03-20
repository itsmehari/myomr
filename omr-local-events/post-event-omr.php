<?php
require_once __DIR__ . '/includes/bootstrap.php';
// Simple CSRF token
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$categories = getEventCategories();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$page_title          = 'List Your Event on OMR | MyOMR';
$page_description    = 'Submit your local event to reach OMR residents. Community, workshops, sports, arts, business and more.';
$canonical_url       = 'https://myomr.in/omr-local-events/post-event-omr.php';
$og_title            = 'List Your Event on OMR | MyOMR';
$og_description      = 'Submit your local event to reach OMR residents. Community, workshops, sports, arts, business and more.';
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = 'https://myomr.in/omr-local-events/post-event-omr.php';
?>
<head>
  <?php $breadcrumbs = [
    ['https://myomr.in/','Home'],
    ['https://myomr.in/omr-local-events/','Events'],
    ['https://myomr.in/omr-local-events/post-event-omr.php','List an Event']
  ]; ?>
  <?php include __DIR__ . '/../components/meta.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/post-job-form-modern.css" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/omr-jobs-unified-design.css" />
  <link rel="stylesheet" href="assets/events-dashboard.css" />
  <link rel="stylesheet" href="/assets/css/footer.css" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body class="modern-page">
<?php omr_nav(); ?>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2 mb-md-0">
      <div class="title h1 mb-0">List an Event</div>
      <div class="small opacity-90">Share your event with the OMR community</div>
    </div>
    <div class="dashboard-actions">
      <a href="index.php" class="btn-modern btn-modern-secondary">
        <i class="fas fa-calendar"></i><span>Browse Events</span>
      </a>
    </div>
  </div>
</div>

<main class="py-5">
  <div class="container">
    <form method="POST" action="process-event-omr.php" class="needs-validation dashboard-card" novalidate enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" />
      <!-- Honeypot field -->
      <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;">
        <label>Leave this field empty</label>
        <input type="text" name="website" tabindex="-1" autocomplete="off" />
      </div>

      <div class="form-sections-wrapper">
        <!-- Event Basics -->
        <div class="form-section form-section-column">
          <div class="form-section-header">
            <div class="form-section-icon"><i class="fas fa-calendar"></i></div>
            <h3>Event Basics</h3>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Event Title</label>
                <input type="text" class="form-control-modern" name="title" required placeholder="e.g., Weekend Community Clean-up" />
                <div class="invalid-feedback-modern">Please enter an event title.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Category</label>
                <select name="category_id" class="form-select-modern" required>
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback-modern">Please select a category.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Location</label>
                <input type="text" class="form-control-modern" name="location" required placeholder="Venue / Address" />
                <div class="invalid-feedback-modern">Please provide a location.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Locality (Optional)</label>
                <input type="text" class="form-control-modern" name="locality" placeholder="e.g., Sholinganallur" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Start Date & Time</label>
                <input type="datetime-local" class="form-control-modern" name="start_datetime" required />
                <div class="invalid-feedback-modern">Please provide a start date and time.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">End Date & Time</label>
                <input type="datetime-local" class="form-control-modern" name="end_datetime" />
              </div>
            </div>
          </div>
        </div>

        <!-- Organizer & Details -->
        <div class="form-section form-section-column">
          <div class="form-section-header">
            <div class="form-section-icon"><i class="fas fa-user"></i></div>
            <h3>Organizer & Details</h3>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Organizer Name</label>
                <input type="text" class="form-control-modern" name="organizer_name" placeholder="Your name / organization" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Organizer Email</label>
                <input type="email" class="form-control-modern" name="organizer_email" placeholder="contact@email.com" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Organizer Phone</label>
                <input type="tel" class="form-control-modern" name="organizer_phone" placeholder="+91 98765 43210" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Tickets URL (Optional)</label>
                <input type="url" class="form-control-modern" name="tickets_url" placeholder="https://..." />
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Event Description</label>
                <textarea class="form-control-modern" name="description" rows="6" required placeholder="Describe the event, who should attend, and what to expect"></textarea>
                <div class="invalid-feedback-modern">Please add a description (min 30 characters).</div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group-modern">
                <label class="form-label-modern">Poster Image (Optional)</label>
                <input type="file" class="form-control-modern" name="poster" accept="image/jpeg,image/png,image/webp" />
                <div class="help-text-modern"><i class="fas fa-info-circle"></i> JPG/PNG/WebP up to 2MB.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern required-field">Cost</label>
                <select class="form-select-modern" name="is_free" required>
                  <option value="1">Free</option>
                  <option value="0">Paid</option>
                </select>
                <div class="invalid-feedback-modern">Please select Free or Paid.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">Price (if Paid)</label>
                <input type="text" class="form-control-modern" name="price" placeholder="e.g., ₹199" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Review & Submit -->
      <div class="form-section">
        <div class="form-section-header">
          <div class="form-section-icon"><i class="fas fa-file-contract"></i></div>
          <h3>Review & Submit</h3>
        </div>
        <div class="alert-modern">
          <h5><i class="fas fa-info-circle"></i> Guidelines</h5>
          <ul>
            <li>Submissions are reviewed within 24–48 hours.</li>
            <li>Only OMR-relevant events are approved.</li>
            <li>We may edit titles/descriptions for clarity and SEO.</li>
          </ul>
        </div>
        <div class="form-check-modern d-flex align-items-start mb-3">
          <input class="form-check-input-modern" type="checkbox" name="terms" required />
          <label class="form-check-label-modern">I agree to the terms and confirm the information is accurate.</label>
          <div class="invalid-feedback-modern">You must agree to submit.</div>
        </div>
        <div class="d-flex gap-3 flex-wrap">
          <button type="submit" class="btn-modern btn-modern-primary"><i class="fas fa-paper-plane"></i><span>Submit Event</span></button>
          <a href="index.php" class="btn-modern btn-modern-secondary"><i class="fas fa-times"></i><span>Cancel</span></a>
        </div>
      </div>
    </form>
  </div>
</main>

<?php omr_footer(); ?>
<script src="assets/events-analytics.js"></script>
<script>
(function(){
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');

  // form_start — fires once on first real user interaction
  var formStarted = false;
  Array.from(forms).forEach(function(form) {
    form.addEventListener('focusin', function() {
      if (formStarted) return;
      formStarted = true;
      if (typeof gtag === 'function') { gtag('event', 'form_start', { 'form_name': 'post_event' }); }
      if (window.MyOMREventsAnalytics) { window.MyOMREventsAnalytics.submissionStart(); }
    });
  });

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      form.classList.add('was-validated');
      if (window.MyOMREventsAnalytics) { window.MyOMREventsAnalytics.submissionSubmit(); }
    });
  });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


