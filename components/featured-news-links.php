<?php
/**
 * Featured / spotlight internal links to key news articles.
 * Include on homepage and News Highlights page for internal linking and SEO.
 * Edit $featured below to add or change highlighted articles (slug => title).
 */
$featured = [
    'omr-apartment-community-water-bowls-for-birds-and-stray-animals.php' => 'Plaza Opulance Residents Set Up Water Bowls for Birds and Stray Animals',
    'malathi-helen-chengalpet-collector-sneha-kanchipuram-feb-2026' => 'Malathi Helen Chengalpet Collector, D. Sneha Kanchipuram',
    'omr-infrastructure-update-flyover-metro-road-repairs-feb-2025' => 'OMR Infrastructure: Flyover, Metro Impact & Road Repairs',
    'chennai-28-3-lakh-voters-final-electoral-roll-2026-assembly'    => '28.3 Lakh Voters in Chennai for 2026 Assembly Elections',
];
if (count($featured) === 0) return;
?>
<section class="featured-news-section" aria-label="Featured news">
  <h2 class="featured-news-section__header">
    <i class="fas fa-star" aria-hidden="true"></i>
    Editor&rsquo;s picks
  </h2>
  <div class="featured-news-section__grid">
    <?php foreach ($featured as $slug => $title): ?>
      <a href="/local-news/<?php echo htmlspecialchars($slug); ?>" class="featured-news-card" aria-label="Read: <?php echo htmlspecialchars($title); ?>">
        <?php echo htmlspecialchars($title); ?><span aria-hidden="true">&rarr;</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<link rel="stylesheet" href="/assets/css/featured-news-links.css">
