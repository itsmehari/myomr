<?php
/**
 * GA4 summary via Data API (last 7 days) — requires service account + property id.
 */
require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__) . '/core/analytics-config.php';
require_once dirname(__DIR__) . '/core/ga4-data-api.php';

$propertyId = myomr_ga4_property_id();
$saResult = myomr_ga4_load_service_account();

$summary = null;
$topPages = null;
$configError = null;
$metricsError = null;
$topPagesError = null;

if (!$saResult['ok']) {
    $configError = $saResult['error'] ?? 'Could not load service account.';
} else {
    $sa = $saResult['data'];

    $r1 = myomr_ga4_run_report($sa, $propertyId, [
        'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
        'metrics' => [
            ['name' => 'sessions'],
            ['name' => 'activeUsers'],
            ['name' => 'screenPageViews'],
            ['name' => 'eventCount'],
        ],
    ]);
    if (!$r1['ok']) {
        $metricsError = $r1['error'] ?? 'runReport failed';
    } else {
        $labels = [];
        foreach ($r1['data']['metricHeaders'] ?? [] as $mh) {
            $labels[] = $mh['name'] ?? '?';
        }
        $summary = [
            'labels' => $labels,
            'values' => myomr_ga4_first_row_metrics($r1['data']),
        ];
    }

    if ($metricsError === null) {
        $r2 = myomr_ga4_run_report($sa, $propertyId, [
            'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'pagePath']],
            'metrics' => [['name' => 'screenPageViews']],
            'orderBys' => [
                ['desc' => true, 'metric' => ['metricName' => 'screenPageViews']],
            ],
            'limit' => 15,
        ]);
        if (!$r2['ok']) {
            $topPagesError = $r2['error'] ?? 'Top pages report failed';
        } else {
            $topPages = [];
            foreach ($r2['data']['rows'] ?? [] as $row) {
                $path = $row['dimensionValues'][0]['value'] ?? '';
                $views = $row['metricValues'][0]['value'] ?? '';
                $topPages[] = ['path' => $path, 'views' => $views];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>GA4 overview (API) — MyOMR Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background:#f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include __DIR__ . '/admin-header.php'; ?>
      <?php include __DIR__ . '/admin-breadcrumbs.php'; ?>

      <h2 class="mb-3"><i class="fab fa-google text-primary"></i> GA4 overview <small class="text-muted">(Data API)</small></h2>
      <p class="text-muted">Last 7 days · <code>7daysAgo</code> → <code>today</code></p>

      <?php if ($configError !== null): ?>
        <div class="alert alert-warning">
          <strong>Not configured.</strong> <?php echo htmlspecialchars($configError, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">One-time setup</h5>
            <ol class="mb-0">
              <li>Google Cloud: create/select a project → enable <strong>Google Analytics Data API</strong>.</li>
              <li>Create a <strong>service account</strong> → download JSON key. Store it <strong>outside</strong> <code>public_html</code> if possible.</li>
              <li>GA4 → <strong>Admin</strong> → <strong>Property access management</strong> → add the service account email with <strong>Viewer</strong> (or Analyst).</li>
              <li>Optional: set <code>MYOMR_GA_PROPERTY_ID</code> to override the default numeric Property ID from <code>core/analytics-config.php</code> (not the G-XXXXXXXX measurement id).</li>
              <li>Set <code>MYOMR_GA_SERVICE_ACCOUNT_PATH</code> to the absolute filesystem path of the JSON file (e.g. in cPanel: Environment Variables, or PHP ini, or a small env loader).</li>
            </ol>
          </div>
        </div>
      <?php else: ?>
        <?php if ($metricsError !== null): ?>
          <div class="alert alert-danger">
            <strong>Metrics request failed.</strong> <?php echo htmlspecialchars($metricsError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>
        <?php if ($summary !== null): ?>
          <div class="row mb-4">
            <?php
            $icons = ['fa-chart-line', 'fa-users', 'fa-file', 'fa-bolt'];
            for ($i = 0; $i < count($summary['labels']); $i++):
              $lab = $summary['labels'][$i] ?? '';
              $val = $summary['values'][$i] ?? '—';
              $ic = $icons[$i] ?? 'fa-circle';
            ?>
              <div class="col-sm-6 col-lg-3 mb-3">
                <div class="card h-100 shadow-sm">
                  <div class="card-body">
                    <div class="text-muted small text-uppercase"><i class="fas <?php echo htmlspecialchars($ic, ENT_QUOTES, 'UTF-8'); ?>"></i> <?php echo htmlspecialchars($lab, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="h4 mb-0 mt-1"><?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?></div>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
          </div>
        <?php endif; ?>

        <?php if ($topPagesError !== null): ?>
          <div class="alert alert-warning">
            <strong>Top pages could not be loaded.</strong> <?php echo htmlspecialchars($topPagesError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($topPages)): ?>
          <div class="card mb-5">
            <div class="card-header"><strong>Top page paths</strong> (screen page views)</div>
            <div class="table-responsive">
              <table class="table table-sm table-striped mb-0">
                <thead><tr><th>Path</th><th class="text-right">Views</th></tr></thead>
                <tbody>
                  <?php foreach ($topPages as $row): ?>
                    <tr>
                      <td><code><?php echo htmlspecialchars($row['path'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                      <td class="text-right"><?php echo htmlspecialchars($row['views'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <p class="small text-muted mb-5">
        Measurement ID for gtag remains in <code>components/analytics.php</code> / <code>core/analytics-config.php</code> (<code>G-…</code>). The Data API uses the numeric property id only.
      </p>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
