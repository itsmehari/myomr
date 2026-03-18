<?php
/**
 * Component include helpers — use ROOT_PATH so includes work from any page depth.
 * Requires ROOT_PATH (from core/include-path.php).
 *
 * Usage:
 *   require_once ROOT_PATH . '/components/component-includes.php';
 *   omr_nav('homepage');
 *   omr_footer();
 *
 * @see docs/workflows-pipelines/ASSET-INCLUDES.md
 */

if (!defined('ROOT_PATH')) {
    trigger_error('ROOT_PATH must be defined before component-includes.php', E_USER_WARNING);
}

/**
 * Output navigation. Variant: 'homepage' | 'main' | 'megamenu'
 * Can use $page_nav (set before include) to control default: omr_nav();
 */
if (!function_exists('omr_nav')) {
    function omr_nav($variant = null) {
        $variant = $variant ?? ($GLOBALS['page_nav'] ?? 'main');
        $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
        if ($variant === 'homepage') {
            require_once $base . '/components/homepage-header.php';
        } elseif ($variant === 'megamenu') {
            $f = $base . '/components/megamenu-myomr.php';
            require_once file_exists($f) ? $f : $base . '/components/main-nav.php';
        } else {
            require_once $base . '/components/main-nav.php';
        }
    }
}

/**
 * Output ad banner slot. Loads ad-banner-slot component when first used.
 * Usage: omr_ad_slot('article-top', '728x90');
 */
if (!function_exists('omr_ad_slot')) {
    $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
    require_once $base . '/components/ad-banner-slot.php';
}

/**
 * Output footer. Skip if $page_footer === false (set before call).
 */
if (!function_exists('omr_footer')) {
    function omr_footer() {
        if (isset($GLOBALS['page_footer']) && $GLOBALS['page_footer'] === false) {
            return;
        }
        $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
        require_once $base . '/components/footer.php';
    }
}
