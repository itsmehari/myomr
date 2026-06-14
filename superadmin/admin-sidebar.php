<?php
/**
 * Bootstrap grid sidebar fragment for module admin pages (hostels, coworking).
 * Superadmin CRUD pages use includes/admin-shell-open.php instead.
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/layout-helpers.php';

$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$navSections = sa_layout_nav_sections();
?>
<nav class="col-md-3 col-lg-2 d-md-block bg-white border-end sidebar min-vh-100 py-3 px-2">
  <div class="px-2 mb-3">
    <a href="/superadmin/index.php" class="text-decoration-none fw-semibold text-success">
      <i class="fas fa-leaf me-1"></i> Command Center
    </a>
  </div>
  <?php foreach ($navSections as $section): ?>
    <div class="text-uppercase text-muted small px-2 mt-3 mb-1"><?= htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php foreach ($section['links'] as $link): ?>
      <?php
        $isActive = sa_layout_nav_is_active($link['href'], $currentUri);
        $cls = 'nav-link py-1 px-2 rounded' . ($isActive ? ' bg-success text-white' : ' text-dark');
        if (!empty($link['is_action'])) {
            $cls .= ' ps-4 small';
        }
      ?>
      <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= $cls ?>">
        <i class="fas <?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?> me-1"></i>
        <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
      </a>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <div class="text-uppercase text-muted small px-2 mt-3 mb-1">Account</div>
  <a href="/superadmin/logout.php" class="nav-link py-1 px-2 rounded text-muted">
    <i class="fas fa-right-from-bracket me-1"></i> Logout
  </a>
</nav>
