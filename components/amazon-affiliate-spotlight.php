<?php
/**
 * Amazon affiliate spotlight component.
 * Renders two rotated affiliate recommendation cards (primary + secondary).
 *
 * Usage:
 *   omr_amazon_affiliate_spotlight([
 *     'seed' => $article['slug'] ?? '',
 *     'article_title' => $article['title'] ?? '',
 *   ]);
 */
if (!function_exists('omr_affiliate_normalize_image_url')) {
    /**
     * Amazon image paths often include "+" in the filename; must be %2B in the request URL.
     */
    function omr_affiliate_normalize_image_url($url) {
        $url = trim((string)$url);
        if ($url === '') {
            return '';
        }
        return str_replace('+', '%2B', $url);
    }
}

if (!function_exists('omr_amazon_affiliate_spotlight')) {
    function omr_amazon_affiliate_spotlight(array $context = []) {
        $seed = isset($context['seed']) ? (string)$context['seed'] : '';
        $articleTitle = isset($context['article_title']) ? (string)$context['article_title'] : 'news-article';
        $articleCategory = isset($context['article_category']) ? (string)$context['article_category'] : '';
        $articleTags = isset($context['article_tags']) ? (string)$context['article_tags'] : '';
        $articleText = strtolower(trim($articleTitle . ' ' . $articleCategory . ' ' . $articleTags));

        $registryFile = ROOT_PATH . '/core/affiliate-registry.php';
        if (!file_exists($registryFile)) {
            return;
        }
        require $registryFile;

        $products = isset($omr_affiliate_products) && is_array($omr_affiliate_products) ? $omr_affiliate_products : [];
        $targeting = isset($omr_affiliate_targeting) && is_array($omr_affiliate_targeting) ? $omr_affiliate_targeting : [];

        if (empty($products) || empty($targeting)) {
            return;
        }

        $activeById = [];
        foreach ($products as $p) {
            if (!empty($p['active']) && !empty($p['id'])) {
                $activeById[(string)$p['id']] = $p;
            }
        }
        if (empty($activeById)) {
            return;
        }

        $selectedPoolIds = [];
        foreach ($targeting as $segment => $rule) {
            if ($segment === 'default') {
                continue;
            }
            $keywords = isset($rule['match']) && is_array($rule['match']) ? $rule['match'] : [];
            $matched = false;
            foreach ($keywords as $kw) {
                if ($kw !== '' && strpos($articleText, strtolower((string)$kw)) !== false) {
                    $matched = true;
                    break;
                }
            }
            if ($matched && isset($rule['product_ids']) && is_array($rule['product_ids'])) {
                $selectedPoolIds = array_merge($selectedPoolIds, $rule['product_ids']);
            }
        }

        if (empty($selectedPoolIds) && isset($targeting['default']['product_ids']) && is_array($targeting['default']['product_ids'])) {
            $selectedPoolIds = $targeting['default']['product_ids'];
        }

        $pool = [];
        foreach ($selectedPoolIds as $pid) {
            $pid = (string)$pid;
            if (isset($activeById[$pid])) {
                $pool[] = $activeById[$pid];
            }
        }

        if (empty($pool)) {
            $pool = array_values($activeById);
        }

        $rotationSeed = $seed . '|' . date('Y-m-d');
        $index = abs((int)crc32($rotationSeed)) % count($pool);
        $selectedProducts = [$pool[$index]];
        if (count($pool) > 1) {
            $secondIndex = ($index + 1) % count($pool);
            if ($secondIndex !== $index) {
                $selectedProducts[] = $pool[$secondIndex];
            }
        }

        if (!defined('OMR_AFFILIATE_SPOTLIGHT_CSS_LOADED')) {
            define('OMR_AFFILIATE_SPOTLIGHT_CSS_LOADED', true);
            echo '<link rel="stylesheet" href="/assets/css/amazon-affiliate-spotlight.css">';
        }
        ?>
        <aside class="omr-affiliate-spotlight" role="complementary" aria-label="Recommended on Amazon">
            <div class="omr-affiliate-spotlight__head">
                <div class="omr-affiliate-spotlight__head-icon" aria-hidden="true">
                    <i class="fab fa-amazon"></i>
                </div>
                <div class="omr-affiliate-spotlight__head-text">
                    <p class="omr-affiliate-spotlight__kicker">Readers also shop</p>
                    <p class="omr-affiliate-spotlight__title">Hand-picked picks</p>
                </div>
                <span class="omr-affiliate-badge">Affiliate</span>
            </div>
            <div class="omr-affiliate-grid">
                <?php foreach ($selectedProducts as $position => $product): ?>
                    <?php
                    $network = isset($product['network']) ? (string)$product['network'] : 'amazon';
                    $benefit = isset($product['benefit']) ? (string)$product['benefit'] : '';
                    $rawImageUrl = isset($product['image_url']) ? (string)$product['image_url'] : '';
                    $imageUrl = omr_affiliate_normalize_image_url($rawImageUrl);
                    $imgAlt = htmlspecialchars($product['title'] ?? 'Product', ENT_QUOTES, 'UTF-8');
                    ?>
                    <article class="omr-affiliate-card">
                        <div class="omr-affiliate-thumb<?php echo $imageUrl === '' ? ' omr-affiliate-thumb--fallback' : ''; ?>">
                            <?php if ($imageUrl !== ''): ?>
                                <img src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                     alt="<?php echo $imgAlt; ?>"
                                     width="500"
                                     height="375"
                                     loading="lazy"
                                     decoding="async"
                                     referrerpolicy="no-referrer-when-downgrade"
                                     onerror="this.closest('.omr-affiliate-thumb').classList.add('omr-affiliate-thumb--fallback');">
                            <?php endif; ?>
                            <div class="omr-affiliate-thumb__placeholder">
                                <i class="fab fa-amazon" aria-hidden="true"></i>
                                <span>Amazon</span>
                            </div>
                        </div>
                        <div class="omr-affiliate-card__body">
                            <h3 class="omr-affiliate-product-title"><?php echo htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="omr-affiliate-description"><?php echo htmlspecialchars($benefit, ENT_QUOTES, 'UTF-8'); ?></p>
                            <a href="<?php echo htmlspecialchars($product['url'], ENT_QUOTES, 'UTF-8'); ?>"
                               class="omr-affiliate-cta js-affiliate-click"
                               target="_blank"
                               rel="sponsored nofollow noopener noreferrer"
                               data-affiliate-network="<?php echo htmlspecialchars($network, ENT_QUOTES, 'UTF-8'); ?>"
                               data-affiliate-id="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>"
                               data-affiliate-position="<?php echo (int)($position + 1); ?>"
                               data-affiliate-article="<?php echo htmlspecialchars($articleTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                View on Amazon
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="omr-affiliate-disclosure">
                We may earn a commission if you buy through these links, at no extra cost to you.
            </p>
        </aside>
        <?php
    }
}
