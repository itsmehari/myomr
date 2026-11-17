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
        static $ads = null;
        if ($ads === null) {
            $ads = (isset($omr_ads) && is_array($omr_ads)) ? $omr_ads : [];
        }
        $omr_ads = $ads;

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
            omr_ad_maybe_render_wide_companion($slot_id, $size);
            return;
        }

        $ad = $eligible[array_rand($eligible)];
        $url = $ad['url'] ?? '#';
        $advertiser = htmlspecialchars($ad['advertiser'] ?? 'Sponsor');
        $headline = htmlspecialchars($ad['headline'] ?? $advertiser);
        $offer = htmlspecialchars($ad['offer'] ?? '');
        $price_hook = htmlspecialchars($ad['price_hook'] ?? '');
        $tagline = htmlspecialchars($ad['tagline'] ?? '');
        $design = $ad['design'] ?? 'default';
        $cta = trim((string) ($ad['cta'] ?? ''));
        $cta_short = trim((string) ($ad['cta_short'] ?? ''));
        $cta_wide = htmlspecialchars($cta !== '' ? $cta : 'Visit site', ENT_QUOTES, 'UTF-8');
        $cta_card = htmlspecialchars($cta !== '' ? $cta : 'Learn more', ENT_QUOTES, 'UTF-8');
        $cta_narrow = htmlspecialchars($cta_short !== '' ? $cta_short : ($cta !== '' ? $cta : 'Visit'), ENT_QUOTES, 'UTF-8');

        $size_class = 'omr-ad-banner--' . preg_replace('/[^a-z0-9x]/', '', strtolower($size));
        $design_class = 'omr-ad-banner--' . preg_replace('/[^a-z0-9]/', '', strtolower($design));

        $image = trim((string) ($ad['image'] ?? ''));
        $has_photo = $image !== '';
        $display = strtolower(trim((string) ($ad['display'] ?? '')));
        $is_full_image = $has_photo && ($display === 'full-image' || $display === 'image' || !empty($ad['full_image']));
        if ($is_full_image) {
            $design_class .= ' omr-ad-banner--full-image';
        } elseif ($has_photo) {
            $design_class .= ' omr-ad-banner--has-photo';
        }
        $icon = _omr_ad_icon($design);
        $img_w = (int) ($ad['image_width'] ?? 0);
        $img_h = (int) ($ad['image_height'] ?? 0);
        if ($img_w <= 0 || $img_h <= 0) {
            if (preg_match('/^(\d+)x(\d+)$/', (string) $size, $dim)) {
                $img_w = (int) $dim[1];
                $img_h = (int) $dim[2];
            } else {
                $img_w = 1400;
                $img_h = 788;
            }
        }
        if ($has_photo) {
            $photo_src = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
            $photo_alt = htmlspecialchars($ad['image_alt'] ?? ($ad['advertiser'] ?? 'Advertiser'), ENT_QUOTES, 'UTF-8');
            $loading = $is_full_image ? 'eager' : 'lazy';
            $media_html = '<span class="omr-ad-media"><img class="omr-ad-photo" src="' . $photo_src . '" alt="' . $photo_alt . '" width="' . $img_w . '" height="' . $img_h . '" loading="' . $loading . '" decoding="async"></span>';
        } else {
            $media_html = '<span class="omr-ad-icon">' . $icon . '</span>';
        }

        if (!function_exists('omr_enqueue_ad_banners_css')) {
            require_once $base . '/core/ad-banners-asset.php';
        }
        omr_enqueue_ad_banners_css();
        if ($has_photo && !$is_full_image && empty($GLOBALS['omr_ad_card_fonts'])) {
            $GLOBALS['omr_ad_card_fonts'] = true;
            echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@1,9..144,560;1,9..144,640&family=Outfit:wght@400;500;600&display=swap">' . "\n";
        }
        $offer_html = $offer !== '' ? '<span class="omr-ad-offer">' . $offer . '</span>' : '';
        $slot_mods = ($is_full_image || $size === '1024x576') ? ' omr-ad-slot--wide' : '';
        ?>
        <div class="omr-ad-slot<?php echo htmlspecialchars($slot_mods, ENT_QUOTES, 'UTF-8'); ?>" role="complementary" aria-label="Sponsored content">
            <span class="omr-ad-slot__label">Ad</span>
            <a href="<?php echo htmlspecialchars($url); ?>"
               class="omr-ad-banner omr-ad-banner <?php echo htmlspecialchars($size_class . ' ' . $design_class, ENT_QUOTES, 'UTF-8'); ?>"
               target="_blank"
               rel="sponsored noopener noreferrer"
               aria-label="<?php echo htmlspecialchars($advertiser . ' - ' . $headline, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($is_full_image): ?>
                    <?php echo $media_html; ?>
                <?php elseif ($has_photo && $size === '320x50'): ?>
                    <?php echo $media_html; ?>
                    <?php echo $offer_html; ?>
                    <span class="omr-ad-cta"><?php echo $cta_narrow; ?></span>
                <?php elseif ($has_photo): ?>
                    <?php echo $media_html; ?>
                    <span class="omr-ad-headline"><?php echo $headline; ?></span>
                    <span class="omr-ad-copy">
                        <?php echo $offer_html; ?>
                        <?php if ($price_hook): ?>
                            <span class="omr-ad-hook"><?php echo $price_hook; ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="omr-ad-cta"><?php echo $size === '728x90' ? $cta_wide : $cta_card; ?></span>
                <?php elseif ($size === '728x90'): ?>
                    <?php echo $media_html; ?>
                    <span class="omr-ad-copy">
                        <span class="omr-ad-headline"><?php echo $headline; ?></span>
                        <?php echo $offer_html; ?>
                        <?php if ($price_hook): ?>
                            <span class="omr-ad-hook"><?php echo $price_hook; ?></span>
                        <?php endif; ?>
                        <?php /* Tagline on leaderboard clips in 90px; show on rectangles only */ ?>
                        <?php if ($tagline && !$price_hook): ?>
                            <span class="omr-ad-tagline"><?php echo $tagline; ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="omr-ad-cta"><?php echo $cta_wide; ?></span>
                <?php elseif ($size === '320x50'): ?>
                    <?php echo $media_html; ?>
                    <?php echo $offer !== '' ? $offer_html : '<span class="omr-ad-headline">' . ($price_hook !== '' ? $price_hook : $headline) . '</span>'; ?>
                    <span class="omr-ad-cta"><?php echo $cta_narrow; ?></span>
                <?php else: ?>
                    <?php echo $media_html; ?>
                    <span class="omr-ad-headline"><?php echo $headline; ?></span>
                    <?php echo $offer_html; ?>
                    <?php if ($price_hook): ?>
                        <span class="omr-ad-hook"><?php echo $price_hook; ?></span>
                    <?php endif; ?>
                    <?php if ($tagline): ?>
                        <span class="omr-ad-tagline"><?php echo $tagline; ?></span>
                    <?php endif; ?>
                    <span class="omr-ad-cta"><?php echo $cta_card; ?></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
        if ($size === '1024x576' || $is_full_image) {
            $GLOBALS['omr_ad_wide_shown'] = true;
        }
        omr_ad_maybe_render_wide_companion($slot_id, $size);
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
            case 'colourchemist-print':
                return '<i class="fas fa-print" aria-hidden="true"></i>';
            case 'bseri':
                return '<i class="fas fa-certificate" aria-hidden="true"></i>';
            case 'agsunwin':
                return '<i class="fas fa-solar-panel" aria-hidden="true"></i>';
            default:
                return '<i class="fas fa-bullhorn" aria-hidden="true"></i>';
        }
    }
}

if (!function_exists('omr_ad_maybe_render_wide_companion')) {
    /**
     * Once per page, append a native 16:9 creative after a full-width slot.
     * Skips sidebar slots such as homepage-top (336x280 in the buy-sell row).
     */
    function omr_ad_maybe_render_wide_companion($slot_id, $size) {
        static $rendering = false;
        if (!empty($GLOBALS['omr_ad_wide_shown']) || $rendering || $size === '1024x576') {
            return;
        }
        $wide_after = [
            'article-top', 'article-mid',
            'homepage-mid',
            'events-index-top', 'events-index-mid',
            'classified-ads-index-top',
            'jobs-index-top', 'jobs-index-mid',
            'listing-top', 'content-top', 'global-top', 'discover-top',
            'buy-sell-index-top', 'hostels-index-top', 'rent-lease-index-top',
            'listings-index-top', 'elections-top',
        ];
        if (!in_array($slot_id, $wide_after, true)) {
            return;
        }
        $GLOBALS['omr_ad_wide_shown'] = true;
        $rendering = true;
        omr_ad_slot('content-wide', '1024x576');
        $rendering = false;
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
        $base = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
        if (!function_exists('omr_ad_slot')) {
            require_once $base . '/components/ad-banner-slot.php';
        }
        if (!function_exists('omr_enqueue_ad_banners_css')) {
            require_once $base . '/core/ad-banners-asset.php';
        }
        omr_enqueue_ad_banners_css();
        echo '<div class="omr-ad-zone omr-ad-zone--row">';
        for ($i = 0; $i < $count; $i++) {
            omr_ad_slot($slot_id, '336x280');
        }
        echo '</div>';
    }
}
