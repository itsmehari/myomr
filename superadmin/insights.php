<?php
declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__) . '/core/omr-connect.php';
require_once dirname(__DIR__) . '/core/superadmin-insights.php';

$moduleKey = trim((string) ($_GET['module'] ?? ''));
$module = sa_insight_get_module($moduleKey);

if ($module === null) {
    http_response_code(404);
    echo 'Module insight not found.';
    exit;
}

$username = $_SESSION['admin_username'] ?? 'Admin';
$stats = sa_dash_collect_metrics($conn);
$trend = sa_insight_series_from_sql($conn, $module['trend_sql']);
$statusRows = sa_insight_status_breakdown($conn, $module['status_sql']);
$listRows = sa_insight_list_rows($conn, $module);
$totalListed = count($listRows);
$statusTotal = array_sum(array_column($statusRows, 'count'));

$activeNav = 'dashboard';
$pageTitle = $module['name'] . ' Insights';
$extraScripts = true;

include __DIR__ . '/includes/dashboard-shell-open.php';
?>

<div class="sa-breadcrumb">
  <a href="/superadmin/index.php"><i class="fas fa-arrow-left"></i> Command Center</a>
  <span>/</span>
  <span><?= htmlspecialchars($module['name']) ?></span>
</div>

<section class="sa-insight-hero" style="--insight-color: <?= htmlspecialchars($module['color']) ?>">
  <div class="sa-insight-hero__icon" style="background:<?= htmlspecialchars($module['bg']) ?>;color:<?= htmlspecialchars($module['color']) ?>">
    <i class="fas <?= htmlspecialchars($module['icon']) ?>"></i>
  </div>
  <div>
    <h2><?= htmlspecialchars($module['name']) ?></h2>
    <p><?= htmlspecialchars($module['description']) ?></p>
  </div>
  <div class="sa-insight-hero__actions">
    <a href="<?= htmlspecialchars($module['open_href']) ?>" class="btn btn-success btn-sm">Open manager</a>
    <a href="<?= htmlspecialchars($module['manage_href']) ?>" class="btn btn-outline-light btn-sm">Quick manage</a>
  </div>
</section>

<div class="sa-insight-kpis">
  <div class="sa-insight-kpi">
    <span>Total records</span>
    <strong><?= number_format($statusTotal) ?></strong>
  </div>
  <div class="sa-insight-kpi">
    <span>Last 12 months</span>
    <strong><?= number_format(array_sum($trend['values'])) ?></strong>
  </div>
  <div class="sa-insight-kpi">
    <span>This month</span>
    <strong><?= number_format((int) ($trend['values'][count($trend['values']) - 1] ?? 0)) ?></strong>
  </div>
  <div class="sa-insight-kpi">
    <span>Showing in table</span>
    <strong><?= number_format($totalListed) ?></strong>
  </div>
</div>

<div class="sa-chart-grid sa-chart-grid--insight">
  <section class="sa-chart-card sa-chart-card--wide">
    <div class="sa-chart-card__head">
      <h3>12-month trend</h3>
      <span>New records per month</span>
    </div>
    <div class="sa-chart-wrap sa-chart-wrap--tall">
      <canvas id="saInsightTrendChart" aria-label="12 month trend chart"></canvas>
    </div>
  </section>

  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Status breakdown</h3>
      <span>Current distribution</span>
    </div>
    <div class="sa-chart-wrap">
      <canvas id="saInsightStatusChart" aria-label="Status breakdown chart"></canvas>
    </div>
    <ul class="sa-status-legend">
      <?php foreach ($statusRows as $row): ?>
        <li>
          <span class="sa-status <?= sa_status_class($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span>
          <strong><?= number_format($row['count']) ?></strong>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
</div>

<section class="sa-panel">
  <div class="sa-panel__head">
    <h3><i class="fas fa-table"></i> All records (latest 100)</h3>
    <a href="<?= htmlspecialchars($module['open_href']) ?>" class="btn btn-sm btn-outline-secondary">Full manager</a>
  </div>
  <?php if ($listRows !== []): ?>
  <div class="sa-table-scroll">
    <table>
      <thead>
        <tr>
          <?php foreach ($module['list_cols'] as $label): ?>
            <th><?= htmlspecialchars($label) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listRows as $row): ?>
        <tr>
          <?php foreach (array_keys($module['list_cols']) as $field): ?>
            <td>
              <?php if ($field === 'status'): ?>
                <span class="sa-status <?= sa_status_class((string) ($row[$field] ?? '')) ?>"><?= htmlspecialchars((string) ($row[$field] ?? '')) ?></span>
              <?php elseif ($field === 'title' && isset($row['id']) && $moduleKey === 'articles'): ?>
                <a href="/superadmin/articles/edit.php?id=<?= (int) $row['id'] ?>"><?= htmlspecialchars(sa_dash_trim((string) ($row[$field] ?? ''), 50)) ?></a>
              <?php elseif ($field === 'title' && isset($row['id']) && $moduleKey === 'jobs'): ?>
                <a href="/superadmin/jobs/manage-jobs-omr.php?id=<?= (int) $row['id'] ?>"><?= htmlspecialchars(sa_dash_trim((string) ($row[$field] ?? ''), 50)) ?></a>
              <?php else: ?>
                <?= htmlspecialchars(sa_insight_format_cell($field, $row[$field] ?? '')) ?>
              <?php endif; ?>
            </td>
          <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <div class="sa-empty">No records found for this module.</div>
  <?php endif; ?>
</section>

<script>
window.saInsightCharts = {
  trend: <?= json_encode($trend, JSON_UNESCAPED_UNICODE) ?>,
  trendColor: <?= json_encode($module['color']) ?>,
  status: {
    labels: <?= json_encode(array_column($statusRows, 'status'), JSON_UNESCAPED_UNICODE) ?>,
    values: <?= json_encode(array_column($statusRows, 'count'), JSON_UNESCAPED_UNICODE) ?>,
    colors: <?= json_encode(array_map(static fn ($r) => $module['color'], $statusRows)) ?>
  }
};
</script>

<?php
include __DIR__ . '/includes/dashboard-shell-close.php';
if (isset($conn)) {
    $conn->close();
}
