<?php
require_once __DIR__ . '/_bootstrap.php';
requireAdmin();

$navSections = require __DIR__ . '/config/navigation.php';
$modules = require __DIR__ . '/modules.php';

$totalModules = count($modules);
$sectionCount = count($navSections);
$actionCount = array_reduce($navSections, function ($carry, $section) {
    foreach ($section['modules'] as $module) {
        $carry += !empty($module['actions']) ? count($module['actions']) : 0;
    }
    return $carry;
}, 0);

include __DIR__ . '/layout/header.php';
?>

<style>
  .admin-dashboard-shell {
    display: flex;
    flex-direction: column;
    gap: 1.75rem;
  }
  .admin-dashboard-hero {
    background: linear-gradient(135deg, #0d7a42, #0b4d2c);
    color: #f2fff5;
    border-radius: 16px;
    padding: 2.5rem 2rem;
    position: relative;
    overflow: hidden;
  }
  .admin-dashboard-hero::after {
    content: '';
    position: absolute;
    inset: -20% 40% auto -10%;
    height: 160%;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.07);
    filter: blur(0);
  }
  .admin-dashboard-hero h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }
  .admin-dashboard-hero p {
    margin: 0;
    max-width: 640px;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
  }
  .admin-dashboard-metrics {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.75rem;
  }
  .admin-dashboard-metric {
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    min-width: 160px;
  }
  .admin-dashboard-metric span {
    display: block;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.7);
  }
  .admin-dashboard-metric strong {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
  }
  .admin-dashboard-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
  }
  .admin-dashboard-controls .form-control {
    max-width: 280px;
  }
  .admin-dashboard-chips {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }
  .admin-chip {
    border: 1px solid #e0e7ef;
    border-radius: 999px;
    padding: 0.4rem 0.85rem;
    font-size: 0.85rem;
    background: #f9fafb;
    color: #1f2937;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .admin-chip.active,
  .admin-chip:hover {
    background: #0d7a42;
    color: #ffffff;
    border-color: #0d7a42;
  }
  .admin-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
  }
  .admin-section-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  .admin-section-header h2 i {
    color: #0d7a42;
    font-size: 1.1rem;
  }
  .admin-section-summary {
    font-size: 0.9rem;
    color: #64748b;
  }
  .admin-module-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    transition: transform 0.18s ease, box-shadow 0.18s ease;
  }
  .admin-module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
  }
  .admin-module-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(13, 122, 66, 0.08);
    color: #0d7a42;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 1rem;
  }
  .admin-module-card h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #102a43;
  }
  .admin-module-card p {
    color: #475569;
    font-size: 0.92rem;
    flex-grow: 1;
  }
  .admin-module-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
  }
  .admin-module-actions .btn {
    font-size: 0.85rem;
    border-radius: 999px;
  }
  .admin-module-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
  }
  .admin-module-meta span {
    font-size: 0.8rem;
    color: #64748b;
  }
  @media (max-width: 767.98px) {
    .admin-dashboard-hero {
      padding: 2rem 1.5rem;
    }
    .admin-dashboard-controls {
      flex-direction: column;
      align-items: stretch;
    }
    .admin-dashboard-controls .form-control {
      max-width: 100%;
    }
  }
</style>

<div class="admin-dashboard-shell">
  <section class="admin-dashboard-hero">
    <h1>Manage the MyOMR Platform</h1>
    <p>Access every CMS module, directory, and operational tool in one command center. Use filters to jump straight to the area you need.</p>
    <div class="admin-dashboard-metrics">
      <div class="admin-dashboard-metric">
        <span>Total Modules</span>
        <strong><?php echo $totalModules; ?></strong>
      </div>
      <div class="admin-dashboard-metric">
        <span>Navigation Groups</span>
        <strong><?php echo $sectionCount; ?></strong>
      </div>
      <div class="admin-dashboard-metric">
        <span>Quick Actions</span>
        <strong><?php echo $actionCount; ?></strong>
      </div>
    </div>
  </section>

  <section class="admin-dashboard-controls">
    <input type="search" class="form-control" id="moduleSearch" placeholder="Search modules, tags, or tools" aria-label="Search modules">
    <div class="admin-dashboard-chips" role="tablist">
      <button class="admin-chip active" data-filter="all" type="button">All Modules</button>
      <?php foreach ($navSections as $section): ?>
        <button class="admin-chip" data-filter="<?php echo htmlspecialchars($section['key']); ?>" type="button">
          <?php echo htmlspecialchars($section['label']); ?>
        </button>
      <?php endforeach; ?>
    </div>
  </section>

  <?php foreach ($navSections as $section): ?>
    <section class="admin-section" data-section="<?php echo htmlspecialchars($section['key']); ?>">
      <div class="admin-section-header">
        <h2><i class="fas <?php echo htmlspecialchars($section['icon']); ?>"></i><?php echo htmlspecialchars($section['label']); ?></h2>
        <span class="admin-section-summary"><?php echo count($section['modules']); ?> modules</span>
      </div>
      <div class="row g-3 module-group">
        <?php foreach ($section['modules'] as $module): ?>
          <?php
            $tags = implode(' ', array_map('strtolower', $module['tags'] ?? []));
          ?>
          <div class="col-md-6 col-lg-4 module-card" data-tags="<?php echo htmlspecialchars($tags); ?>" data-section="<?php echo htmlspecialchars($section['key']); ?>">
            <article class="admin-module-card">
              <div class="admin-module-icon">
                <i class="fas <?php echo htmlspecialchars($module['icon'] ?? 'fa-circle'); ?>"></i>
              </div>
              <h3><?php echo htmlspecialchars($module['name']); ?></h3>
              <p><?php echo htmlspecialchars($module['description']); ?></p>
              <div class="admin-module-meta">
                <span><?php echo htmlspecialchars($section['label']); ?></span>
                <a class="btn btn-sm btn-success" href="<?php echo htmlspecialchars($module['path']); ?>">Open</a>
              </div>
              <?php if (!empty($module['actions'])): ?>
                <div class="admin-module-actions">
                  <?php foreach ($module['actions'] as $action): ?>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo htmlspecialchars($action['path']); ?>"><?php echo htmlspecialchars($action['label']); ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
</div>

<script>
  (function() {
    const searchInput = document.getElementById('moduleSearch');
    const chips = document.querySelectorAll('.admin-chip');
    const modules = document.querySelectorAll('.module-card');
    const sections = document.querySelectorAll('.admin-section');

    let activeFilter = 'all';

    const applyFilters = () => {
      const query = (searchInput.value || '').trim().toLowerCase();

      modules.forEach((card) => {
        const section = card.dataset.section;
        const tags = card.dataset.tags || '';
        const content = (card.innerText || '').toLowerCase();

        let visible = true;

        if (activeFilter !== 'all' && section !== activeFilter) {
          visible = false;
        }

        if (visible && query) {
          visible = content.includes(query) || tags.includes(query);
        }

        card.classList.toggle('d-none', !visible);
      });

      sections.forEach((section) => {
        const visibleCards = section.querySelectorAll('.module-card:not(.d-none)');
        section.classList.toggle('d-none', visibleCards.length === 0);
      });
    };

    searchInput?.addEventListener('input', applyFilters);
    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        chips.forEach((c) => c.classList.remove('active'));
        chip.classList.add('active');
        activeFilter = chip.dataset.filter;
        applyFilters();
      });
    });

    applyFilters();
  })();
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
