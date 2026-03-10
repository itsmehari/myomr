<?php
/**
 * MyOMR Megamenu – responsive navigation with menu, submenu, list items
 * Requires: core/megamenu-config.php (fallback if missing)
 */
if (!isset($megamenu)) {
  $config_file = __DIR__ . '/../core/megamenu-config.php';
  if (file_exists($config_file)) {
    include $config_file;
  }
}
if (empty($megamenu) || !is_array($megamenu)) {
  $megamenu = [
    ['label' => 'Home', 'url' => '/', 'icon' => 'fas fa-home', 'active' => true],
    ['label' => 'Jobs', 'url' => '/omr-local-job-listings/', 'icon' => 'fas fa-briefcase'],
    ['label' => 'Events', 'url' => '/omr-local-events/', 'icon' => 'fas fa-calendar-alt'],
    ['label' => 'News', 'url' => '/local-news/news-highlights-from-omr-road.php', 'icon' => 'fas fa-newspaper'],
    ['label' => 'Places', 'url' => '/omr-listings/', 'icon' => 'fas fa-map-marker-alt'],
    ['label' => 'About', 'url' => '/about-myomr-omr-community-portal.php', 'icon' => 'fas fa-info-circle'],
    ['label' => 'Contact', 'url' => '/contact-my-omr-team.php', 'icon' => 'fas fa-envelope'],
  ];
}
?>
<header class="megamenu-wrap" role="banner">
  <div class="megamenu-bar">
    <a href="/" class="megamenu-logo" aria-label="MyOMR.in Home">
      <img src="/My-OMR-Logo.png" alt="MyOMR - Old Mahabalipuram Road Community Portal" width="40" height="40">
      <div class="megamenu-logo-text">
        <span class="megamenu-site-name">MyOMR.in</span>
        <span class="megamenu-tagline">Explore OMR • Old Mahabalipuram Road</span>
      </div>
    </a>

    <button class="megamenu-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="megamenu-nav" id="megamenu-toggle-btn">
      <span class="megamenu-toggle-icon"></span>
    </button>

    <nav class="megamenu-nav" id="megamenu-nav" role="navigation" aria-label="Main">
      <ul class="megamenu-list">
        <?php foreach ($megamenu as $i => $item): ?>
        <li class="megamenu-item<?php echo !empty($item['mega']) ? ' megamenu-has-mega' : ''; ?><?php echo !empty($item['active']) ? ' megamenu-active' : ''; ?>">
          <?php if (!empty($item['mega']) && !empty($item['columns'])): ?>
          <a href="<?php echo htmlspecialchars($item['url']); ?>" class="megamenu-link megamenu-link-parent" aria-haspopup="true" aria-expanded="false" aria-controls="megamenu-panel-<?php echo $i; ?>" data-panel="megamenu-panel-<?php echo $i; ?>">
            <i class="<?php echo htmlspecialchars($item['icon'] ?? 'fas fa-circle'); ?>" aria-hidden="true"></i>
            <span><?php echo htmlspecialchars($item['label']); ?></span>
            <i class="fas fa-chevron-down megamenu-chevron" aria-hidden="true"></i>
          </a>
          <div class="megamenu-panel" id="megamenu-panel-<?php echo $i; ?>" role="menu" aria-label="<?php echo htmlspecialchars($item['label']); ?> submenu">
            <div class="megamenu-panel-inner">
              <?php foreach ($item['columns'] as $col): ?>
              <div class="megamenu-column">
                <div class="megamenu-column-title"><?php echo htmlspecialchars($col['title']); ?></div>
                <ul class="megamenu-sublist">
                  <?php foreach ($col['items'] as $sub): ?>
                  <li><a href="<?php echo htmlspecialchars($sub['url']); ?>" class="megamenu-sublink"><?php echo htmlspecialchars($sub['label']); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php else: ?>
          <a href="<?php echo htmlspecialchars($item['url']); ?>" class="megamenu-link<?php echo !empty($item['active']) ? ' megamenu-active-link' : ''; ?>"<?php echo !empty($item['active']) ? ' aria-current="page"' : ''; ?>>
            <i class="<?php echo htmlspecialchars($item['icon'] ?? 'fas fa-circle'); ?>" aria-hidden="true"></i>
            <span><?php echo htmlspecialchars($item['label']); ?></span>
          </a>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <a href="/omr-local-job-listings/post-job-omr.php" class="megamenu-cta">Add Listing</a>
    </nav>
  </div>
</header>

<script>
(function() {
  var toggle = document.getElementById('megamenu-toggle-btn');
  var nav = document.getElementById('megamenu-nav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', function() {
    var open = nav.classList.toggle('megamenu-open');
    toggle.setAttribute('aria-expanded', open);
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  });

  document.querySelectorAll('.megamenu-link-parent').forEach(function(link) {
    link.addEventListener('click', function(e) {
      if (window.innerWidth <= 992) {
        e.preventDefault();
        var item = link.closest('.megamenu-item');
        var panel = document.getElementById(link.getAttribute('data-panel'));
        if (item && panel) {
          item.classList.toggle('megamenu-open');
          link.setAttribute('aria-expanded', item.classList.contains('megamenu-open'));
        }
      }
    });
  });

  document.addEventListener('click', function(e) {
    if (window.innerWidth <= 992 && nav.classList.contains('megamenu-open') && !nav.contains(e.target) && !toggle.contains(e.target)) {
      nav.classList.remove('megamenu-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open menu');
    }
  });
})();
</script>
