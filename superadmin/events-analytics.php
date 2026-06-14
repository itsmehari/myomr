<?php
require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__) . '/core/analytics-config.php';

$title = 'Events Analytics';
$pageTitle = $title;
$lookerUrl = getenv('MYOMR_LOOKER_DASHBOARD_URL') ?: '';
$propertyId = myomr_ga4_property_id();

include __DIR__ . '/includes/admin-shell-open.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <h5>Tracked Events (GA4)</h5>
    <ul class="mb-0">
      <li><code>event_view</code>, <code>event_map</code>, <code>event_ticket</code>, <code>event_share</code></li>
      <li><code>event_submit_start</code>, <code>event_submit_success</code></li>
    </ul>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h5>Funnel Definition</h5>
    <p class="mb-0">View → Intent actions → Submission start → Submission success. Filter <code>event_category=Events</code>.</p>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h5>Looker Studio (optional)</h5>
    <?php if ($lookerUrl): ?>
      <div class="ratio ratio-16x9">
        <iframe src="<?= htmlspecialchars($lookerUrl, ENT_QUOTES, 'UTF-8') ?>" allowfullscreen></iframe>
      </div>
    <?php else: ?>
      <p class="text-muted mb-0">Set <code>MYOMR_LOOKER_DASHBOARD_URL</code> to embed a report.</p>
    <?php endif; ?>
    <?php if ($propertyId): ?>
      <p class="mt-2 mb-0 small text-muted">GA4 property id: <code><?= htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8') ?></code></p>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
