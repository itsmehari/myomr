<?php
/**
 * Homepage OMR Classified Ads — expects $recent_classified_ads (array), $classified_ads_count (int).
 */
if (!function_exists('getClassifiedAdsDetailPath')) {
    require_once ROOT_PATH . '/omr-classified-ads/includes/listing-functions.php';
}
$has_ca = !empty($recent_classified_ads) && is_array($recent_classified_ads);
?>
<section class="classified-ads-homepage-section homepage-section" aria-labelledby="ca-section-heading">
  <div class="classified-ads-homepage-section__inner">
    <header class="classified-ads-homepage-section__header">
      <h2 id="ca-section-heading" class="classified-ads-homepage-section__title">OMR Classified Ads</h2>
      <p class="classified-ads-homepage-section__tagline">Have something to offer or need? Post in 2 minutes. <span lang="ta">உள்ளூர் விளம்பரங்கள்</span> — services, wanted, community notices.</p>
      <div class="classified-ads-homepage-section__ctas">
        <a href="/omr-classified-ads/" class="classified-ads-homepage-section__btn classified-ads-homepage-section__btn--primary">Browse ads</a>
        <a href="/omr-classified-ads/guidelines.php" class="classified-ads-homepage-section__btn classified-ads-homepage-section__btn--secondary">Guidelines</a>
        <a href="/omr-classified-ads/register-omr.php" class="classified-ads-homepage-section__btn classified-ads-homepage-section__btn--secondary">Register to post</a>
      </div>
    </header>
    <?php if ($has_ca): ?>
    <div class="classified-ads-homepage-section__cards">
      <?php foreach ($recent_classified_ads as $item): ?>
      <?php
        $detailPath = getClassifiedAdsDetailPath((int) ($item['id'] ?? 0), $item['title'] ?? null);
      ?>
      <a href="/omr-classified-ads/<?php echo htmlspecialchars($detailPath); ?>" class="classified-ads-homepage-section__card">
        <span class="classified-ads-homepage-section__card-icon" aria-hidden="true"><i class="fas fa-bullhorn"></i></span>
        <span class="classified-ads-homepage-section__card-body">
          <span class="classified-ads-homepage-section__card-title"><?php echo htmlspecialchars($item['title'] ?? 'Listing'); ?></span>
          <span class="classified-ads-homepage-section__card-meta">
            <?php if (!empty($item['locality'])): ?>
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($item['locality']); ?>
            <?php endif; ?>
          </span>
          <?php if (isset($item['price']) && $item['price'] > 0): ?>
          <span class="classified-ads-homepage-section__card-price">₹<?php echo number_format($item['price']); ?></span>
          <?php else: ?>
          <span class="classified-ads-homepage-section__card-price classified-ads-homepage-section__card-price--muted">Contact for details</span>
          <?php endif; ?>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="classified-ads-homepage-section__empty">
      <i class="fas fa-newspaper" aria-hidden="true"></i>
      <p>No public ads yet — be the first after we launch.</p>
      <a href="/omr-classified-ads/register-omr.php" class="classified-ads-homepage-section__btn classified-ads-homepage-section__btn--primary">Register &amp; post</a>
    </div>
    <?php endif; ?>
  </div>
</section>
