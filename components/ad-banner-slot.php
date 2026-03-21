<?php
/**
 * Ad banner slot component — displays one random ad in standard sizes.
 * Uses ad-registry.php for ad definitions. Monetization-ready.
 *
 * Usage: omr_ad_slot('article-top', '728x90');
 *
 * @param string $slot_id Slot identifier (e.g. article-top, content-top, homepage-top)
 * @param string $size Banner size: 728x90, 336x280, 300x250, or 320x50 (mobile)
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

if (!function_exists('omr_ad_slot')) {
    function omr_ad_slot($slot_id, $size = '336x280') {
        $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
        $registry_file = $base . '/core/ad-registry.php';
        if (!file_exists($registry_file)) {
            if (isset($_GET['omr_ad_debug']) && $_GET['omr_ad_debug'] === '1') {
                echo '<!-- omr_ad_slot: registry not found at ' . htmlspecialchars($registry_file, ENT_QUOTES, 'UTF-8') . ' -->';
            }
            return;
        }

        require_once $registry_file;
        $omr_ads = isset($omr_ads) ? $omr_ads : [];

        $eligible = array_filter($omr_ads, function ($a) use ($slot_id, $size) {
            return !empty($a['active'])
                && in_array($slot_id, $a['slot_ids'] ?? [], true)
                && in_array($size, $a['sizes'] ?? [], true);
        });

        $eligible = array_values($eligible);
        if (empty($eligible)) {
            if (isset($_GET['omr_ad_debug']) && $_GET['omr_ad_debug'] === '1') {
                echo '<!-- omr_ad_slot: no eligible ads for slot_id=' . htmlspecialchars($slot_id, ENT_QUOTES, 'UTF-8') . ' size=' . htmlspecialchars($size, ENT_QUOTES, 'UTF-8') . ' -->';
            }
            return;
        }

        $ad = $eligible[array_rand($eligible)];
        $url = $ad['url'] ?? '#';
        $advertiser = htmlspecialchars($ad['advertiser'] ?? 'Sponsor');
        $headline = htmlspecialchars($ad['headline'] ?? $advertiser);
        $tagline = htmlspecialchars($ad['tagline'] ?? '');
        $design = $ad['design'] ?? 'default';

        $size_class = 'omr-ad-banner--' . preg_replace('/[^a-z0-9x]/', '', strtolower($size));
        $design_class = 'omr-ad-banner--' . preg_replace('/[^a-z0-9]/', '', strtolower($design));

        $icon = _omr_ad_icon($design);

        if (!defined('OMR_AD_BANNERS_CSS_LOADED')) {
            define('OMR_AD_BANNERS_CSS_LOADED', true);
            echo '<link rel="stylesheet" href="/assets/css/ad-banners.css">';
        }
        ?>
        <div class="omr-ad-slot" role="complementary" aria-label="Sponsored content">
            <span class="omr-ad-slot__label">Ad</span>
            <a href="<?php echo htmlspecialchars($url); ?>"
               class="omr-ad-banner omr-ad-banner <?php echo htmlspecialchars($size_class . ' ' . $design_class, ENT_QUOTES, 'UTF-8'); ?>"
               target="_blank"
               rel="sponsored noopener noreferrer"
               aria-label="<?php echo htmlspecialchars($advertiser . ' - ' . $headline, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($size === '728x90'): ?>
                    <span class="omr-ad-icon"><?php echo $icon; ?></span>
                    <span class="omr-ad-copy">
                        <span class="omr-ad-headline"><?php echo $headline; ?></span>
                        <?php if ($tagline): ?>
                            <span class="omr-ad-tagline"><?php echo $tagline; ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="omr-ad-cta">Visit site</span>
                <?php elseif ($size === '320x50'): ?>
                    <span class="omr-ad-icon"><?php echo $icon; ?></span>
                    <span class="omr-ad-headline"><?php echo $headline; ?></span>
                    <span class="omr-ad-cta">Visit</span>
                <?php else: ?>
                    <span class="omr-ad-icon"><?php echo $icon; ?></span>
                    <span class="omr-ad-headline"><?php echo $headline; ?></span>
                    <?php if ($tagline): ?>
                        <span class="omr-ad-tagline"><?php echo $tagline; ?></span>
                    <?php endif; ?>
                    <span class="omr-ad-cta">Learn more</span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }

    function _omr_ad_icon($design) {
        switch ($design) {
            case 'resumedoctor':
            case 'resumebuilder':
                return '<i class="fas fa-file-alt" aria-hidden="true"></i>';
            case 'mycovai':
                return '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
            case 'colourchemist':
                return '<i class="fas fa-palette" aria-hidden="true"></i>';
            case 'bseri':
                return '<i class="fas fa-certificate" aria-hidden="true"></i>';
            default:
                return '<i class="fas fa-bullhorn" aria-hidden="true"></i>';
        }
    }
}

/**
 * Output a row of multiple ad slots (e.g. two side-by-side on desktop).
 * Uses 336x280 so two fit in a 2-column grid within max-width.
 *
 * @param string $slot_id Slot identifier (e.g. homepage-top)
 * @param int    $count   Number of slots to render (default 2)
 */
if (!function_exists('omr_ad_slot_row')) {
    function omr_ad_slot_row($slot_id, $count = 2) {
        if (!function_exists('omr_ad_slot')) {
            $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
            require_once $base . '/components/ad-banner-slot.php';
        }
        if (!defined('OMR_AD_BANNERS_CSS_LOADED')) {
            define('OMR_AD_BANNERS_CSS_LOADED', true);
            echo '<link rel="stylesheet" href="/assets/css/ad-banners.css">';
        }
        echo '<div class="omr-ad-zone omr-ad-zone--row">';
        for ($i = 0; $i < $count; $i++) {
            omr_ad_slot($slot_id, '336x280');
        }
        echo '</div>';
    }
}
