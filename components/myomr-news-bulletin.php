<?php
/**
 * MyOMR News Bulletin — Dynamic Livemint-style editorial layout
 * Hero + 2 medium cards + sidebar compact list. Data from articles table.
 */
if (!defined('MYOMR_NEWS_BULLETIN')) {
  define('MYOMR_NEWS_BULLETIN', true);
  echo '<link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">';
}

$core_file = __DIR__ . '/../core/omr-connect.php';
if (!file_exists($core_file)) {
  $core_file = $_SERVER['DOCUMENT_ROOT'] . '/core/omr-connect.php';
}
if (file_exists($core_file)) {
  require_once $core_file;
}
$conn = isset($conn) ? $conn : null;

$articles = [];
if ($conn && !$conn->connect_error) {
  $sql = "SELECT id, title, slug, summary, published_date, image_path, category, author 
          FROM articles 
          WHERE status = 'published' AND slug NOT LIKE '%-tamil' 
          ORDER BY published_date DESC, id DESC 
          LIMIT 12";
  $res = $conn->query($sql);
  if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $articles[] = $row;
    }
  }
}

function _badge_class($cat) {
  $c = strtolower(trim($cat ?? ''));
  if (strpos($c, 'event') !== false) return 'badge--events';
  if (strpos($c, 'infra') !== false || strpos($c, 'road') !== false) return 'badge--infra';
  if (strpos($c, 'community') !== false) return 'badge--community';
  if (strpos($c, 'business') !== false) return 'badge--business';
  if (strpos($c, 'alert') !== false) return 'badge--alert';
  return 'badge--local';
}

function _img_url($path) {
  if (empty($path)) return 'https://myomr.in/My-OMR-Logo.png';
  if (strpos($path, 'http') === 0) return $path;
  return (strpos($path, '/') === 0 ? '' : '/') . $path;
}

$hero = $articles[0] ?? null;
$medium = array_slice($articles, 1, 2);
$sidebar = array_slice($articles, 3, 9);
?>

<section class="omr-newsroom" aria-label="OMR Latest News">
  <div class="newsroom-header">
    <div class="newsroom-header__left">
      <span class="newsroom-header__eyebrow">Hyperlocal</span>
      <h2 class="newsroom-header__title">OMR News Bulletin</h2>
    </div>
    <a href="/local-news/" class="newsroom-viewall">
      View All Stories
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>

  <?php if (empty($articles)): ?>
    <p class="text-muted py-4">No news available. <a href="/local-news/">Browse news</a>.</p>
  <?php else: ?>
  <div class="newsroom-layout">
    <div class="newsroom-main">
      <?php if ($hero): ?>
      <article class="news-hero">
        <a href="/local-news/<?php echo htmlspecialchars($hero['slug']); ?>" class="news-hero__link">
          <div class="news-hero__image-wrap">
            <img src="<?php echo htmlspecialchars(_img_url($hero['image_path'])); ?>" alt="<?php echo htmlspecialchars($hero['title']); ?>" class="news-hero__img" loading="eager" fetchpriority="high">
            <span class="news-badge <?php echo _badge_class($hero['category']); ?>"><?php echo htmlspecialchars($hero['category'] ?: 'Local'); ?></span>
          </div>
          <div class="news-hero__body">
            <h3 class="news-hero__title"><?php echo htmlspecialchars($hero['title']); ?></h3>
            <p class="news-hero__excerpt"><?php echo htmlspecialchars(mb_substr($hero['summary'] ?? '', 0, 200)); ?><?php echo mb_strlen($hero['summary'] ?? '') > 200 ? '…' : ''; ?></p>
            <div class="news-meta">
              <span class="news-meta__source"><?php echo htmlspecialchars($hero['author'] ?: 'OMR News Desk'); ?></span>
              <span class="news-meta__sep" aria-hidden="true">·</span>
              <time class="news-meta__date" datetime="<?php echo date('Y-m-d', strtotime($hero['published_date'])); ?>"><?php echo date('M j, Y', strtotime($hero['published_date'])); ?></time>
            </div>
          </div>
        </a>
      </article>
      <?php endif; ?>

      <div class="newsroom-medium-row">
        <?php foreach ($medium as $m): ?>
        <article class="news-medium">
          <a href="/local-news/<?php echo htmlspecialchars($m['slug']); ?>" class="news-medium__link">
            <div class="news-medium__image-wrap">
              <img src="<?php echo htmlspecialchars(_img_url($m['image_path'])); ?>" alt="<?php echo htmlspecialchars($m['title']); ?>" class="news-medium__img" loading="lazy">
              <span class="news-badge <?php echo _badge_class($m['category']); ?>"><?php echo htmlspecialchars($m['category'] ?: 'Local'); ?></span>
            </div>
            <div class="news-medium__body">
              <h4 class="news-medium__title"><?php echo htmlspecialchars($m['title']); ?></h4>
              <div class="news-meta">
                <span class="news-meta__source"><?php echo htmlspecialchars($m['author'] ?: 'OMR News Desk'); ?></span>
                <span class="news-meta__sep" aria-hidden="true">·</span>
                <time class="news-meta__date" datetime="<?php echo date('Y-m-d', strtotime($m['published_date'])); ?>"><?php echo date('M j, Y', strtotime($m['published_date'])); ?></time>
              </div>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>

    <aside class="newsroom-sidebar" aria-label="More OMR stories">
      <div class="sidebar-more-label">More Stories</div>
      <?php foreach ($sidebar as $s): ?>
      <article class="news-compact">
        <a href="/local-news/<?php echo htmlspecialchars($s['slug']); ?>" class="news-compact__link">
          <div class="news-compact__thumb">
            <img src="<?php echo htmlspecialchars(_img_url($s['image_path'])); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" loading="lazy">
          </div>
          <div class="news-compact__body">
            <div class="news-compact__meta">
              <span class="news-badge <?php echo _badge_class($s['category']); ?>"><?php echo htmlspecialchars($s['category'] ?: 'Local'); ?></span>
              <time class="news-meta__date" datetime="<?php echo date('Y-m-d', strtotime($s['published_date'])); ?>"><?php echo date('M j, Y', strtotime($s['published_date'])); ?></time>
            </div>
            <h5 class="news-compact__title"><?php echo htmlspecialchars($s['title']); ?></h5>
          </div>
        </a>
      </article>
      <?php endforeach; ?>
    </aside>
  </div>

  <div class="newsroom-footer">
    <a href="/local-news/" class="newsroom-footer__link">
      View All OMR News Stories
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
  <?php endif; ?>
</section>
