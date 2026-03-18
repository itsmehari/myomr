<?php
/**
 * Homepage Buy & Sell Section — Promotional block with recent listings or empty state.
 * Expects $recent_buy_sell (array), $buy_sell_count (int).
 * Requires getListingDetailPath from omr-buy-sell listing-functions (loaded by index when data exists).
 */
if (!function_exists('getListingDetailPath')) {
  require_once ROOT_PATH . '/omr-buy-sell/includes/listing-functions.php';
}
$has_listings = !empty($recent_buy_sell) && is_array($recent_buy_sell);
?>
<section class="buy-sell-homepage-section homepage-section" aria-labelledby="buy-sell-section-heading">
  <div class="buy-sell-homepage-section__inner">
    <header class="buy-sell-homepage-section__header">
      <h2 id="buy-sell-section-heading" class="buy-sell-homepage-section__title">Buy &amp; Sell in OMR</h2>
      <p class="buy-sell-homepage-section__tagline">Local classifieds for electronics, furniture, vehicles, books and more.</p>
      <div class="buy-sell-homepage-section__ctas">
        <a href="/omr-buy-sell/" class="buy-sell-homepage-section__btn buy-sell-homepage-section__btn--primary">Browse listings</a>
        <a href="/omr-buy-sell/post-listing-omr.php" class="buy-sell-homepage-section__btn buy-sell-homepage-section__btn--secondary">Post your ad</a>
      </div>
    </header>
    <?php if ($has_listings): ?>
    <div class="buy-sell-homepage-section__cards">
      <?php foreach ($recent_buy_sell as $item): ?>
      <?php
        $img = null;
        if (!empty($item['images'])) {
          $imgs = is_string($item['images']) ? json_decode($item['images'], true) : $item['images'];
          $img = is_array($imgs) && !empty($imgs) ? $imgs[0] : null;
        }
        $detailPath = getListingDetailPath((int)($item['id'] ?? 0), $item['title'] ?? null);
      ?>
      <a href="/omr-buy-sell/<?php echo htmlspecialchars($detailPath); ?>" class="buy-sell-homepage-section__card">
        <span class="buy-sell-homepage-section__card-img-wrap">
          <?php if ($img): ?>
          <img src="/omr-buy-sell/<?php echo htmlspecialchars($img); ?>" alt="" loading="lazy" class="buy-sell-homepage-section__card-img">
          <?php else: ?>
          <span class="buy-sell-homepage-section__card-placeholder"><i class="fas fa-image" aria-hidden="true"></i></span>
          <?php endif; ?>
        </span>
        <span class="buy-sell-homepage-section__card-body">
          <span class="buy-sell-homepage-section__card-title"><?php echo htmlspecialchars($item['title'] ?? 'Listing'); ?></span>
          <span class="buy-sell-homepage-section__card-meta">
            <?php if (!empty($item['locality'])): ?>
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($item['locality']); ?>
            <?php endif; ?>
          </span>
          <?php if (isset($item['price']) && $item['price'] > 0): ?>
          <span class="buy-sell-homepage-section__card-price">₹<?php echo number_format($item['price']); ?><?php echo !empty($item['price_negotiable']) ? ' (neg.)' : ''; ?></span>
          <?php else: ?>
          <span class="buy-sell-homepage-section__card-price buy-sell-homepage-section__card-price--muted">Price on request</span>
          <?php endif; ?>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="buy-sell-homepage-section__empty">
      <i class="fas fa-shopping-bag" aria-hidden="true"></i>
      <p>No listings yet. Be the first to post an ad.</p>
      <a href="/omr-buy-sell/post-listing-omr.php" class="buy-sell-homepage-section__btn buy-sell-homepage-section__btn--primary">Post your ad</a>
    </div>
    <?php endif; ?>
  </div>
</section>
