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

        // Rotate daily, while remaining stable for each article.
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
            echo '<style>
            .omr-affiliate-spotlight{margin:1.5rem 0;padding:1rem;border:1px solid #e5e7eb;border-radius:10px;background:#f8fafc}
            .omr-affiliate-badge{display:inline-block;font-size:.75rem;font-weight:700;color:#92400e;background:#fef3c7;border:1px solid #fde68a;border-radius:999px;padding:.15rem .6rem;margin-bottom:.6rem}
            .omr-affiliate-grid{display:grid;grid-template-columns:1fr;gap:.9rem}
            .omr-affiliate-card{display:grid;grid-template-columns:100px 1fr;gap:.85rem;align-items:start;padding:.75rem;background:#fff;border:1px solid #e5e7eb;border-radius:10px}
            .omr-affiliate-thumb{width:100px;height:100px;border-radius:8px;overflow:hidden;background:#fff;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center}
            .omr-affiliate-thumb img{max-width:100%;max-height:100%;display:block}
            .omr-affiliate-thumb i{font-size:1.25rem;color:#6b7280}
            .omr-affiliate-title{margin:0 0 .35rem 0;font-size:1.05rem;line-height:1.35;color:#14532d}
            .omr-affiliate-description{margin:0 0 .8rem 0;color:#4b5563;font-size:.93rem;line-height:1.5}
            .omr-affiliate-cta{display:inline-flex;align-items:center;gap:.45rem;background:#14532d;color:#fff;text-decoration:none;border-radius:8px;padding:.55rem .95rem;font-weight:600}
            .omr-affiliate-cta:hover{color:#fff;background:#166534}
            .omr-affiliate-disclosure{display:block;margin-top:.7rem;font-size:.8rem;color:#6b7280}
            @media (min-width:900px){.omr-affiliate-grid{grid-template-columns:1fr 1fr}}
            @media (max-width:576px){.omr-affiliate-card{grid-template-columns:72px 1fr}.omr-affiliate-thumb{width:72px;height:72px}}
            </style>';
        }
        ?>
        <aside class="omr-affiliate-spotlight" role="complementary" aria-label="Recommended product">
            <span class="omr-affiliate-badge">Affiliate</span>
            <div class="omr-affiliate-grid">
                <?php foreach ($selectedProducts as $position => $product): ?>
                    <?php
                    $network = isset($product['network']) ? (string)$product['network'] : 'amazon';
                    $benefit = isset($product['benefit']) ? (string)$product['benefit'] : '';
                    $imageUrl = isset($product['image_url']) ? (string)$product['image_url'] : '';
                    ?>
                    <div class="omr-affiliate-card">
                        <div class="omr-affiliate-thumb" aria-hidden="true">
                            <?php if ($imageUrl !== ''): ?>
                                <img src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                            <?php else: ?>
                                <i class="fas fa-box-open"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="omr-affiliate-title"><?php echo htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
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
                    </div>
                <?php endforeach; ?>
            </div>
            <small class="omr-affiliate-disclosure">
                We may earn a commission if you buy through these links, at no extra cost to you.
            </small>
        </aside>
        <?php
    }
}
