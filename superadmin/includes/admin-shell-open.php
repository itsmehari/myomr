<?php
/**
 * Unified superadmin layout — command center shell for all admin pages.
 * Set before include: $pageTitle or $title, optional $breadcrumbs, $activeNav, $stats, $extraScripts
 */
declare(strict_types=1);

require_once __DIR__ . '/layout-helpers.php';

$pageTitle = $pageTitle ?? $title ?? 'Superadmin';
$displayName = $_SESSION['admin_full_name'] ?? ($username ?? 'Admin');
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$navSections = sa_layout_nav_sections();
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — MyOMR Superadmin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="/superadmin/assets/superadmin-dashboard.css" rel="stylesheet">
</head>
<body class="sa-body">
<button type="button" class="sa-mobile-toggle" id="saSidebarToggle" aria-label="Toggle menu">
  <i class="fas fa-bars"></i>
</button>
<div class="sa-overlay" id="saOverlay"></div>

<aside class="sa-sidebar" id="saSidebar">
  <div class="sa-sidebar__brand">
    <span class="sa-sidebar__logo"><i class="fas fa-leaf"></i></span>
    <div>
      <strong>MyOMR</strong>
      <small>Superadmin</small>
    </div>
  </div>

  <nav class="sa-sidebar__nav">
    <?php foreach ($navSections as $section): ?>
      <div class="sa-nav-section"><?= htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php foreach ($section['links'] as $link): ?>
        <?php
          $isActive = sa_layout_nav_is_active($link['href'], $currentUri)
              || ($activeNav !== '' && str_contains($link['href'], $activeNav));
          $linkClass = 'sa-nav-link' . ($isActive ? ' is-active' : '') . (!empty($link['is_action']) ? ' sa-nav-link--sub' : '');
        ?>
        <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>" class="<?= $linkClass ?>">
          <i class="fas <?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
          <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <div class="sa-nav-section">Account</div>
    <a href="/superadmin/change-password.php" class="sa-nav-link<?= sa_layout_nav_is_active('/superadmin/change-password.php', $currentUri) ? ' is-active' : '' ?>">
      <i class="fas fa-key"></i> Admin Security
    </a>
    <a href="/superadmin/logout.php" class="sa-nav-link sa-nav-link--muted">
      <i class="fas fa-right-from-bracket"></i> Logout
    </a>
  </nav>
</aside>

<div class="sa-main">
  <header class="sa-topbar">
    <div class="sa-topbar__left">
      <h1 class="sa-topbar__title"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
    </div>
    <div class="sa-topbar__right">
      <?php if (!empty($stats['pending_total']) && (int) $stats['pending_total'] > 0): ?>
        <span class="sa-pill sa-pill--danger">
          <i class="fas fa-bell"></i> <?= (int) $stats['pending_total'] ?> pending
        </span>
      <?php endif; ?>
      <span class="sa-user-chip">
        <i class="fas fa-user-circle"></i>
        <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>
      </span>
      <a href="https://myomr.in" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">
        <i class="fas fa-external-link-alt me-1"></i> View site
      </a>
    </div>
  </header>
  <main class="sa-content">
    <?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
      <nav aria-label="Breadcrumb" class="sa-breadcrumbs mb-3">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="/superadmin/index.php">Command Center</a></li>
          <?php foreach ($breadcrumbs as $label => $href): ?>
            <?php if ($href === null || $href === ''): ?>
              <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') ?></li>
            <?php else: ?>
              <li class="breadcrumb-item"><a href="<?= htmlspecialchars((string) $href, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') ?></a></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>
    <?php include __DIR__ . '/../admin-flash.php'; ?>
