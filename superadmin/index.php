<?php
declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__) . '/core/omr-connect.php';
require_once dirname(__DIR__) . '/core/superadmin-dashboard-metrics.php';
require_once dirname(__DIR__) . '/core/superadmin-insights.php';

$username = $_SESSION['admin_username'] ?? 'Admin';
$stats = sa_dash_collect_metrics($conn);
$recent = sa_dash_collect_recent($conn);
$moduleCards = sa_dash_module_cards($stats);
$overviewCharts = sa_insight_overview_charts($conn);
$extraScripts = true;

$groups = [];
foreach ($moduleCards as $card) {
    $groups[$card['group']] = true;
}

include __DIR__ . '/includes/dashboard-shell-open.php';
?>

<section class="sa-hero">
  <h2>Welcome back, <?= htmlspecialchars($_SESSION['admin_full_name'] ?? $username) ?></h2>
  <p>Every listing, job, article, and directory on MyOMR — live counts from production database.</p>
  <div class="sa-hero-metrics">
    <div class="sa-hero-metric">
      <span>Total listings</span>
      <strong><?= number_format($stats['listings_total']) ?></strong>
    </div>
    <div class="sa-hero-metric">
      <span>Content pieces</span>
      <strong><?= number_format($stats['content_total']) ?></strong>
    </div>
    <div class="sa-hero-metric">
      <span>Directory entries</span>
      <strong><?= number_format($stats['directory_total']) ?></strong>
    </div>
    <div class="sa-hero-metric">
      <span>Job seekers</span>
      <strong><?= number_format($stats['job_seekers']) ?></strong>
    </div>
    <?php if ($stats['pending_total'] > 0): ?>
    <div class="sa-hero-metric">
      <span>Needs review</span>
      <strong><?= number_format($stats['pending_total']) ?></strong>
    </div>
    <?php endif; ?>
  </div>
</section>

<h2 class="sa-section-title">Growth trends <span class="sa-section-sub">Last 12 months</span></h2>
<div class="sa-chart-grid">
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Job posts</h3>
      <a href="/superadmin/insights.php?module=jobs">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartJobs" aria-label="Job posts trend"></canvas></div>
  </section>
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Job seeker signups</h3>
      <a href="/superadmin/insights.php?module=seekers">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartSeekers" aria-label="Job seeker signups trend"></canvas></div>
  </section>
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Articles published</h3>
      <a href="/superadmin/insights.php?module=articles">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartArticles" aria-label="Articles trend"></canvas></div>
  </section>
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Events added</h3>
      <a href="/superadmin/insights.php?module=events">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartEvents" aria-label="Events trend"></canvas></div>
  </section>
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Classified ads</h3>
      <a href="/superadmin/insights.php?module=classified">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartClassified" aria-label="Classified ads trend"></canvas></div>
  </section>
  <section class="sa-chart-card">
    <div class="sa-chart-card__head">
      <h3>Marketplace activity</h3>
      <a href="/superadmin/insights.php?module=rent">Details</a>
    </div>
    <div class="sa-chart-wrap"><canvas id="saChartMarketplace" aria-label="Marketplace trend"></canvas></div>
  </section>
</div>

<script>
window.saOverviewCharts = <?= json_encode($overviewCharts, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div class="sa-toolbar">
  <div class="sa-search">
    <i class="fas fa-search"></i>
    <input type="search" id="saModuleSearch" placeholder="Search jobs, events, classifieds…" aria-label="Search modules">
  </div>
  <div class="sa-chips" role="tablist">
    <button type="button" class="sa-chip is-active" data-filter="all">All</button>
    <?php foreach (array_keys($groups) as $group): ?>
      <button type="button" class="sa-chip" data-filter="<?= htmlspecialchars($group) ?>"><?= htmlspecialchars($group) ?></button>
    <?php endforeach; ?>
  </div>
</div>

<h2 class="sa-section-title">Platform modules</h2>
<div class="sa-module-grid">
  <?php foreach ($moduleCards as $card): ?>
    <article class="sa-module-card" data-group="<?= htmlspecialchars($card['group']) ?>">
      <div class="sa-module-card__head">
        <div class="sa-module-icon" style="background:<?= htmlspecialchars($card['bg']) ?>;color:<?= htmlspecialchars($card['color']) ?>">
          <i class="fas <?= htmlspecialchars($card['icon']) ?>"></i>
        </div>
        <div class="text-end">
          <div class="sa-module-count"><?= number_format((int) $card['total']) ?></div>
          <?php if (($card['pending'] ?? 0) > 0): ?>
            <span class="sa-badge-pending"><?= (int) $card['pending'] ?> pending</span>
          <?php endif; ?>
        </div>
      </div>
      <h3 class="sa-module-name">
        <a href="/superadmin/insights.php?module=<?= urlencode($card['key']) ?>" class="sa-module-name__link"><?= htmlspecialchars($card['name']) ?></a>
      </h3>
      <p class="sa-module-meta"><?= htmlspecialchars($card['meta']) ?></p>
      <div class="sa-module-actions">
        <a class="primary" href="/superadmin/insights.php?module=<?= urlencode($card['key']) ?>">Insights</a>
        <a href="<?= htmlspecialchars($card['href']) ?>">Open</a>
        <a href="<?= htmlspecialchars($card['manage']) ?>">Manage</a>
      </div>
    </article>
  <?php endforeach; ?>
</div>

<h2 class="sa-section-title">Recent activity</h2>
<div class="sa-panels">

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-briefcase text-warning"></i> Job posts</h3>
      <a href="/superadmin/jobs/manage-jobs-omr.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['jobs'] && $recent['jobs']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Company</th><th>Status</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['jobs']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 42)) ?></td>
          <td><?= htmlspecialchars(sa_dash_trim($row['company_name'] ?? '—', 24)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No job posts yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-user-graduate text-purple" style="color:#7c3aed"></i> Job seekers</h3>
      <a href="/superadmin/manage-job-seeker-profiles-omr.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['job_seekers'] && $recent['job_seekers']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Name</th><th>Locality</th><th>Level</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['job_seekers']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['full_name'] ?? '', 28)) ?></td>
          <td><?= htmlspecialchars(sa_dash_trim($row['preferred_locality'] ?? '—', 20)) ?></td>
          <td><?= htmlspecialchars(sa_dash_trim($row['experience_level'] ?? '—', 16)) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No job seeker profiles yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-newspaper text-primary"></i> Articles</h3>
      <a href="/superadmin/articles/index.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['articles'] && $recent['articles']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['articles']->fetch_assoc()): ?>
        <tr>
          <td><a href="/superadmin/articles/edit.php?id=<?= (int) $row['id'] ?>"><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 40)) ?></a></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
          <td><?= !empty($row['published_date']) ? date('M j', strtotime($row['published_date'])) : '—' ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No articles yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-calendar-days text-success"></i> Events</h3>
      <a href="/superadmin/community-events/view-listings.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['events'] && $recent['events']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Status</th><th>Starts</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['events']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 38)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
          <td><?= !empty($row['start_datetime']) ? date('M j', strtotime($row['start_datetime'])) : '—' ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No events yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-bullhorn" style="color:#c2410c"></i> Classified ads</h3>
      <a href="/superadmin/classified-ads/manage-listings-omr.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['classified'] && $recent['classified']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Status</th><th>Added</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['classified']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 40)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
          <td><?= !empty($row['created_at']) ? date('M j', strtotime($row['created_at'])) : '—' ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No classified listings yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-house" style="color:#0d7a42"></i> Rent &amp; lease</h3>
      <a href="/superadmin/rent-lease/manage-properties-omr.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['rent_lease'] && $recent['rent_lease']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Locality</th><th>Status</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['rent_lease']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 36)) ?></td>
          <td><?= htmlspecialchars(sa_dash_trim($row['locality'] ?? '—', 18)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No rent/lease listings yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-bed" style="color:#be185d"></i> Hostels &amp; PGs</h3>
      <a href="/superadmin/hostels/manage-properties.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['hostels'] && $recent['hostels']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Property</th><th>Locality</th><th>Status</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['hostels']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['property_name'] ?? '', 36)) ?></td>
          <td><?= htmlspecialchars(sa_dash_trim($row['locality'] ?? '—', 18)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No hostel/PG listings yet.</div>
    <?php endif; ?>
  </section>

  <section class="sa-panel">
    <div class="sa-panel__head">
      <h3><i class="fas fa-shopping-bag" style="color:#be185d"></i> Buy &amp; sell</h3>
      <a href="/superadmin/buy-sell/manage-listings-omr.php" class="btn btn-sm btn-outline-secondary">All</a>
    </div>
    <?php if ($recent['buy_sell'] && $recent['buy_sell']->num_rows > 0): ?>
    <table>
      <thead><tr><th>Title</th><th>Status</th><th>Added</th></tr></thead>
      <tbody>
        <?php while ($row = $recent['buy_sell']->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 40)) ?></td>
          <td><span class="sa-status <?= sa_status_class((string) ($row['status'] ?? '')) ?>"><?= htmlspecialchars($row['status'] ?? '') ?></span></td>
          <td><?= !empty($row['created_at']) ? date('M j', strtotime($row['created_at'])) : '—' ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="sa-empty">No buy/sell listings yet.</div>
    <?php endif; ?>
  </section>

</div>

<?php if ($recent['pending_jobs'] && $recent['pending_jobs']->num_rows > 0): ?>
<section class="sa-panel mt-4">
  <div class="sa-panel__head">
    <h3><i class="fas fa-clock text-warning"></i> Pending job approvals</h3>
    <a href="/superadmin/jobs/manage-jobs-omr.php?status=pending" class="btn btn-sm btn-warning">Review all</a>
  </div>
  <table>
    <thead><tr><th>Job</th><th>Company</th><th>Submitted</th></tr></thead>
    <tbody>
      <?php while ($row = $recent['pending_jobs']->fetch_assoc()): ?>
      <tr>
        <td><a href="/superadmin/jobs/manage-jobs-omr.php?id=<?= (int) $row['id'] ?>"><?= htmlspecialchars(sa_dash_trim($row['title'] ?? '', 50)) ?></a></td>
        <td><?= htmlspecialchars(sa_dash_trim($row['company_name'] ?? '—', 28)) ?></td>
        <td><?= !empty($row['created_at']) ? date('M j, Y', strtotime($row['created_at'])) : '—' ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>
<?php endif; ?>

<?php
include __DIR__ . '/includes/dashboard-shell-close.php';
if (isset($conn)) {
    $conn->close();
}
