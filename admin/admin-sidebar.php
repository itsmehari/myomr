<?php
$navigationConfig = require __DIR__ . '/config/navigation.php';

$navSections = array_map(function (array $section) {
    $links = [];
    foreach ($section['modules'] as $module) {
        $links[] = [
            'href' => $module['path'],
            'label' => $module['name'],
            'icon' => $module['icon'] ?? 'fa-circle',
            'is_action' => false,
        ];
        if (!empty($module['actions'])) {
            foreach ($module['actions'] as $action) {
                $links[] = [
                    'href' => $action['path'],
                    'label' => $action['label'],
                    'icon' => $action['icon'] ?? 'fa-circle-dot',
                    'is_action' => true,
                ];
            }
        }
    }

    return [
        'label' => $section['label'],
        'icon' => $section['icon'] ?? 'fa-circle',
        'links' => $links,
    ];
}, $navigationConfig);

$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
?>
<style>
  .admin-sidebar {
    background: linear-gradient(180deg, #0f3e28 0%, #0a2c1d 100%);
    color: #f3faf6;
    min-height: 100vh;
    padding: 0;
    position: relative;
    transition: transform 0.3s ease;
    box-shadow: 4px 0 18px rgba(12, 42, 28, 0.18);
    overflow: hidden;
  }
  .admin-sidebar.is-collapsed {
    transform: translateX(-100%);
  }
  .admin-sidebar__header {
    padding: 1.75rem 1.5rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .admin-sidebar__brand {
    font-weight: 700;
    font-size: 1.15rem;
    letter-spacing: 0.04em;
    color: #fefefe;
    margin: 0;
  }
  .admin-sidebar__toggle {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: inherit;
    border-radius: 8px;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
  }
  .admin-sidebar__toggle:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
  }
  .admin-sidebar__section {
    padding: 0.75rem 0;
  }
  .admin-sidebar__section-title {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin: 0;
    padding: 0.75rem 1.5rem 0.35rem;
    color: rgba(255, 255, 255, 0.55);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: none;
  }
  .admin-sidebar__section-title:hover {
    color: rgba(255, 255, 255, 0.75);
  }
  .admin-sidebar__section-title i {
    font-size: 0.85rem;
  }
  .admin-sidebar__section-title .collapse-indicator {
    transition: transform 0.2s ease;
  }
  .admin-sidebar__section-title.collapsed .collapse-indicator {
    transform: rotate(-180deg);
  }
  .admin-sidebar__nav {
    list-style: none;
    margin: 0;
    padding: 0.2rem 0;
  }
  .admin-sidebar__link {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 0.95rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    position: relative;
    transition: background 0.18s ease, color 0.18s ease, padding-left 0.18s ease;
  }
  .admin-sidebar__link:hover, .admin-sidebar__link:focus-visible {
    color: #ffffff;
    padding-left: 1.75rem;
    background: rgba(255, 255, 255, 0.1);
  }
  .admin-sidebar__link.is-sub {
    font-size: 0.85rem;
    padding-left: 2.1rem;
    color: rgba(255, 255, 255, 0.7);
  }
  .admin-sidebar__link.is-sub:hover,
  .admin-sidebar__link.is-sub:focus-visible {
    color: #ffffff;
    padding-left: 2.35rem;
  }
  .admin-sidebar__link i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
  }
  .admin-sidebar__link.is-active {
    background: rgba(34, 197, 94, 0.18);
    color: #ffffff;
    padding-left: 1.75rem;
    border-left: 3px solid #22c55e;
  }
  .admin-sidebar__footer {
    margin-top: auto;
    padding: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .admin-sidebar__logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    padding: 0.65rem 1rem;
    color: #fff;
    text-decoration: none;
    transition: background 0.18s ease, transform 0.18s ease;
  }
  .admin-sidebar__logout:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
  }
  .admin-sidebar__collapse-btn {
    display: none;
  }
  @media (max-width: 991.98px) {
    .admin-sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 280px;
      z-index: 1045;
      transform: translateX(-100%);
    }
    .admin-sidebar.is-open {
      transform: translateX(0);
    }
    .admin-sidebar__collapse-btn {
      display: inline-flex;
    }
    body.admin-sidebar-open {
      overflow: hidden;
    }
    .admin-sidebar__shade {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 23, 42, 0.45);
      z-index: 1040;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }
    .admin-sidebar__shade.is-visible {
      opacity: 1;
      pointer-events: all;
    }
  }
  .admin-sidebar .nav-tooltip {
    position: absolute;
    left: 100%;
  @media (max-width: 767.98px) {
    .admin-sidebar {
      display: none;
    }
    .admin-sidebar.is-open {
      display: flex;
    }
  }
  .admin-sidebar .btn-link {
    color: inherit;
  }
  .admin-sidebar .btn-link:hover,
  .admin-sidebar .btn-link:focus-visible {
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
  }
  .admin-sidebar__body {
    overflow-y: auto;
  }
    top: 50%;
    transform: translateY(-50%);
    background: rgba(15, 23, 42, 0.88);
    color: #fff;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.18s ease, transform 0.18s ease;
    margin-left: 0.5rem;
  }
  .admin-sidebar__link:hover .nav-tooltip {
    opacity: 1;
    transform: translate(8px, -50%);
  }
</style>

<div class="admin-sidebar__shade" id="adminSidebarShade"></div>
<nav class="col-md-2 col-lg-2 admin-sidebar d-flex flex-column" aria-label="Admin navigation">
  <header class="admin-sidebar__header">
    <h1 class="admin-sidebar__brand">MyOMR Admin</h1>
    <button class="admin-sidebar__toggle admin-sidebar__collapse-btn" type="button" id="adminSidebarClose" aria-label="Close sidebar">
      <i class="fas fa-xmark"></i>
    </button>
  </header>
  <div class="admin-sidebar__body d-flex flex-column flex-grow-1">
    <?php foreach ($navSections as $sectionIndex => $section): ?>
      <section class="admin-sidebar__section" data-section="<?php echo $sectionIndex; ?>">
        <button class="admin-sidebar__section-title btn btn-link text-start w-100 p-0 border-0 text-decoration-none section-toggle" type="button" aria-expanded="true">
          <i class="fas <?php echo htmlspecialchars($section['icon']); ?>"></i>
          <span><?php echo htmlspecialchars($section['label']); ?></span>
          <i class="fas fa-chevron-down ms-auto small collapse-indicator"></i>
        </button>
        <ul class="admin-sidebar__nav list-unstyled mb-0">
          <?php foreach ($section['links'] as $link): ?>
            <?php
              $linkHref = $link['href'];
              $isActive = $currentUri === $linkHref || (strpos($currentUri, $linkHref) === 0);
              $linkClasses = ['admin-sidebar__link'];
              if ($isActive) {
                  $linkClasses[] = 'is-active';
              }
              if (!empty($link['is_action'])) {
                  $linkClasses[] = 'is-sub';
              }
            ?>
            <li>
              <a class="<?php echo implode(' ', $linkClasses); ?>" href="<?php echo htmlspecialchars($linkHref); ?>">
                <i class="fas <?php echo htmlspecialchars($link['icon']); ?>"></i>
                <span><?php echo htmlspecialchars($link['label']); ?></span>
                <span class="nav-tooltip"><?php echo htmlspecialchars($link['label']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
    <?php endforeach; ?>
  </div>
  <footer class="admin-sidebar__footer">
    <a class="admin-sidebar__logout" href="/admin/logout.php">
      <i class="fas fa-arrow-right-from-bracket"></i>
      <span>Logout</span>
    </a>
  </footer>
</nav>
<button class="admin-sidebar__toggle d-md-none" type="button" id="adminSidebarOpen" aria-label="Open sidebar" style="position: fixed; top: 1rem; left: 1rem; z-index: 1046;">
  <i class="fas fa-bars"></i>
</button>
<script>
  (function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const openBtn = document.getElementById('adminSidebarOpen');
    const closeBtn = document.getElementById('adminSidebarClose');
    const shade = document.getElementById('adminSidebarShade');
    const toggleSidebar = (show) => {
      if (!sidebar) return;
      sidebar.classList.toggle('is-open', show);
      document.body.classList.toggle('admin-sidebar-open', show);
      if (shade) {
        shade.classList.toggle('is-visible', show);
      }
    };

    openBtn?.addEventListener('click', () => toggleSidebar(true));
    closeBtn?.addEventListener('click', () => toggleSidebar(false));
    shade?.addEventListener('click', () => toggleSidebar(false));

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        toggleSidebar(false);
      }
    });

    const sectionButtons = document.querySelectorAll('.admin-sidebar .section-toggle');
    sectionButtons.forEach((btn) => {
      btn.addEventListener('click', function() {
        const nav = this.nextElementSibling;
        const expanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', String(!expanded));
        this.classList.toggle('collapsed', expanded);
        nav?.classList.toggle('d-none', expanded);
      });
    });

    const activeLink = document.querySelector('.admin-sidebar__link.is-active');
    if (activeLink) {
      const parentSection = activeLink.closest('.admin-sidebar__section');
      const toggleBtn = parentSection?.querySelector('.section-toggle');
      const navList = parentSection?.querySelector('.admin-sidebar__nav');
      toggleBtn?.setAttribute('aria-expanded', 'true');
      navList?.classList.remove('d-none');
      toggleBtn?.classList.remove('collapsed');
    }
  })();
</script>