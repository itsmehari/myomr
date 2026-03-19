<?php
/**
 * Explore OMR — category grid for homepage (MyCovai-style).
 * Expects $home_categories in scope (from core/homepage-categories.php).
 */
if (empty($home_categories) || !is_array($home_categories)) {
  return;
}
?>
<section class="homepage-explore-omr" aria-labelledby="explore-omr-heading">
  <div class="homepage-explore-omr__inner">
    <h2 id="explore-omr-heading" class="homepage-explore-omr__label">Explore OMR</h2>
    <div class="homepage-explore-omr__grid" role="list">
      <?php foreach ($home_categories as $cat): ?>
        <?php
        $url = isset($cat['url']) ? $cat['url'] : '#';
        $label = isset($cat['label']) ? $cat['label'] : '';
        $icon = isset($cat['icon']) ? $cat['icon'] : 'fas fa-circle';
        $highlight = !empty($cat['highlight']);
        $count = isset($cat['count']) ? $cat['count'] : null;
        $card_class = 'homepage-explore-omr__card';
        if ($highlight) {
          $card_class .= ' homepage-explore-omr__card--highlight';
        }
        $subtitle = '';
        if ($count !== null && $count !== '') {
          $subtitle = (int) $count . ' LISTINGS';
        } else {
          $subtitle = 'Explore';
        }
        ?>
        <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo htmlspecialchars($card_class, ENT_QUOTES, 'UTF-8'); ?>" role="listitem">
          <span class="homepage-explore-omr__card-icon" aria-hidden="true"><i class="<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></span>
          <span class="homepage-explore-omr__card-title"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="homepage-explore-omr__card-subtitle"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
