<?php
/**
 * Head Includes — modular head assets with per-page control.
 * Requires ROOT_PATH.
 *
 * Set flags BEFORE including:
 *
 *   CSS:
 *   $omr_css_megamenu = true;   // Megamenu (default: true)
 *   $omr_css_homepage = true;   // Homepage styles (default: false)
 *   $omr_css_core    = false;   // Legacy Bootstrap 4 stack (default: false)
 *
 *   Analytics:
 *   $page_analytics = true;     // Include GA4 (default: true)
 *   $ga_custom_params = [];     // Pass-through to analytics
 *   $ga_user_id      = 0;       // Pass-through
 *   $ga_user_properties = [];   // Pass-through
 *
 * Usage:
 *   <?php $omr_css_homepage = true; require_once ROOT_PATH . '/components/head-includes.php'; ?>
 *
 * @see docs/workflows-pipelines/MODULAR-INCLUDES.md
 */
if (!defined('ROOT_PATH')) {
    trigger_error('ROOT_PATH must be defined before head-includes.php', E_USER_WARNING);
}

// Apply defaults for CSS flags
$omr_css_megamenu = isset($omr_css_megamenu) ? (bool) $omr_css_megamenu : true;
$omr_css_homepage = isset($omr_css_homepage) ? (bool) $omr_css_homepage : false;
$omr_css_core     = isset($omr_css_core) ? (bool) $omr_css_core : false;
$page_analytics   = isset($page_analytics) ? (bool) $page_analytics : true;

require_once ROOT_PATH . '/components/css-includes.php';

if ($page_analytics) {
    require_once ROOT_PATH . '/components/analytics.php';
}
