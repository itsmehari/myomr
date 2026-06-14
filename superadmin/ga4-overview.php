<?php
/**
 * GA4 summary via Data API (last 7 days) — requires service account + property id.
 */
require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__) . '/core/analytics-config.php';
require_once dirname(__DIR__) . '/core/ga4-data-api.php';

$title = 'GA4 Overview';
$pageTitle = $title;

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

include __DIR__ . '/includes/admin-shell-open.php';
?>

<p class="text-muted mb-3">Last 7 days · <code>7daysAgo</code> → <code>today</code> (Data API)</p>

<?php if ($configError !== null): ?>
  <div class="alert alert-warning">
    <strong>Not configured.</strong> <?= htmlspecialchars($configError, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php else: ?>
  <?php if ($metricsError !== null): ?>
    <div class="alert alert-danger"><strong>Metrics request failed.</strong> <?= htmlspecialchars($metricsError, ENT_QUOTES, 'UTF-8') ?></div>
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
              <div class="text-muted small text-uppercase"><i class="fas <?= htmlspecialchars($ic, ENT_QUOTES, 'UTF-8') ?>"></i> <?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></div>
              <div class="h4 mb-0 mt-1"><?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
  <?php if ($topPagesError !== null): ?>
    <div class="alert alert-warning"><strong>Top pages could not be loaded.</strong> <?= htmlspecialchars($topPagesError, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($topPages)): ?>
    <div class="card mb-4">
      <div class="card-header"><strong>Top page paths</strong></div>
      <div class="table-responsive">
        <table class="table table-sm table-striped mb-0">
          <thead><tr><th>Path</th><th class="text-end">Views</th></tr></thead>
          <tbody>
            <?php foreach ($topPages as $row): ?>
              <tr>
                <td><code><?= htmlspecialchars($row['path'], ENT_QUOTES, 'UTF-8') ?></code></td>
                <td class="text-end"><?= htmlspecialchars($row['views'], ENT_QUOTES, 'UTF-8') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>

<p class="small text-muted">Measurement ID for gtag: <code>G-…</code> in analytics config. Data API uses numeric property id.</p>

<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
