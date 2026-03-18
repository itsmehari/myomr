<?php
/**
 * Common CSS includes — root-relative URLs work from any page depth.
 * Requires ROOT_PATH (from core/include-path.php).
 *
 * Optional flags (set before include):
 *   $omr_css_megamenu = true;   // Megamenu styles (default: true)
 *   $omr_css_homepage = true;   // homepage-myomr.css (default: false)
 *   $omr_css_core    = true;    // Legacy: core, tokens, components (default: false)
 *
 * @see docs/workflows-pipelines/ASSET-INCLUDES.md
 */
$omr_css_megamenu = isset($omr_css_megamenu) ? (bool) $omr_css_megamenu : true;
$omr_css_homepage = isset($omr_css_homepage) ? (bool) $omr_css_homepage : false;
$omr_css_core     = isset($omr_css_core) ? (bool) $omr_css_core : false;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php if ($omr_css_megamenu): ?>
<link rel="stylesheet" href="/assets/css/megamenu-myomr.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php endif; ?>
<?php if ($omr_css_core): ?>
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/assets/css/components.css">
<?php endif; ?>
<link rel="stylesheet" href="/assets/css/main.css">
<?php if ($omr_css_homepage): ?>
<?php
  $homepage_css_path = ROOT_PATH . '/assets/css/homepage-myomr.css';
  $homepage_css_ver = file_exists($homepage_css_path) ? (string) filemtime($homepage_css_path) : '1';
?>
<link rel="stylesheet" href="/assets/css/homepage-myomr.css?v=<?php echo htmlspecialchars($homepage_css_ver, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
