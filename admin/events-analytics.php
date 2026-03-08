<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$lookerUrl = getenv('MYOMR_LOOKER_DASHBOARD_URL') ?: '';
$propertyId = getenv('MYOMR_GA_PROPERTY_ID') ?: '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events Analytics Dashboard - MyOMR</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body style="background:#f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>

      <h2 class="mb-4">Events Analytics Dashboard</h2>

      <div class="card mb-4">
        <div class="card-body">
          <h5>Tracked Events (GA4)</h5>
          <p class="mb-2">These events are already instrumented on public events pages:</p>
          <ul class="mb-0">
            <li><code>event_view</code> (detail page engagement)</li>
            <li><code>event_map</code>, <code>event_ticket</code>, <code>event_share</code>, <code>event_calendar_add</code>, <code>event_ics_download</code></li>
            <li><code>events_filter</code> (list filters used)</li>
            <li><code>event_submit_start</code>, <code>event_submit_attempt</code>, <code>event_submit_success</code> (submission funnel)</li>
          </ul>
          <small class="text-muted">Custom params used: <code>event_category</code>, <code>event_label</code>, <code>value</code> (id for submit_success)</small>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5>GA4 Setup (one-time)</h5>
          <ol class="mb-0">
            <li>In GA4 Admin → Custom definitions → Create custom dimensions for <code>event_category</code> and <code>event_label</code> (scope: Event).</li>
            <li>Optional: Create a custom metric for <code>value</code> if you want to count submission IDs.</li>
            <li>Wait for GA4 to process (up to 24h) for reporting availability.</li>
          </ol>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5>Funnel Definition</h5>
          <p class="mb-1">Recommended steps for the Events funnel:</p>
          <ol>
            <li><strong>View</strong>: <code>event_view</code></li>
            <li><strong>Intent</strong>: any of <code>event_map</code>, <code>event_ticket</code>, <code>event_share</code>, <code>event_calendar_add</code>, <code>event_ics_download</code></li>
            <li><strong>Submission start</strong>: <code>event_submit_start</code></li>
            <li><strong>Submission success</strong>: <code>event_submit_success</code></li>
          </ol>
          <p class="mb-0">Segment by <code>event_category=Events</code>, breakdown by <code>event_label</code> (slug/channel), date range: last 28 days.</p>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5>Open GA4 Explorations</h5>
          <p class="mb-2">Use GA4 Explorations → Funnel exploration. Add the four steps above and apply a filter <code>event_category exactly matches Events</code>.</p>
          <?php if ($propertyId): ?>
            <p class="mb-0"><small class="text-muted">Property ID: <code><?php echo htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8'); ?></code> (for reference)</small></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-5">
        <div class="card-body">
          <h5>Looker Studio (optional)</h5>
          <p class="mb-2">If you have a Looker Studio report URL, set env <code>MYOMR_LOOKER_DASHBOARD_URL</code> and it will appear below.</p>
          <?php if ($lookerUrl): ?>
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="<?php echo htmlspecialchars($lookerUrl, ENT_QUOTES, 'UTF-8'); ?>" allowfullscreen></iframe>
            </div>
          <?php else: ?>
            <p class="text-muted mb-0">No Looker report URL configured yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-5">
        <div class="card-body">
          <h5>BigQuery (optional)</h5>
          <p class="mb-2">If GA4 BigQuery export is enabled, use this starter query (replace project/dataset/table):</p>
<pre class="mb-0"><code>SELECT event_name, event_params.key AS param, value.string_value AS val, COUNT(*) AS cnt
FROM `project.dataset.events_*`, UNNEST(event_params) AS event_params
WHERE _TABLE_SUFFIX BETWEEN '20251001' AND '20251031'
  AND event_name IN ('event_view','event_map','event_ticket','event_share','event_submit_start','event_submit_success')
GROUP BY 1,2,3
ORDER BY cnt DESC;</code></pre>
        </div>
      </div>

    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


