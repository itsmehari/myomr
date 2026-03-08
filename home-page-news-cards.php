<?php
/**
 * Homepage news cards - root-level copy for deployments where weblog/ may not exist.
 * Same logic as weblog/home-page-news-cards.php
 */
require_once __DIR__ . '/core/omr-connect.php';

$sql = "SELECT id, title, slug, summary, published_date, image_path 
        FROM articles 
        WHERE status = 'published' 
        AND slug NOT LIKE '%-tamil' 
        ORDER BY published_date DESC, id DESC 
        LIMIT 20";
$result = isset($conn) ? $conn->query($sql) : false;
?>
<div class="myomr-news-bulletin">
  <div class="news-grid">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="news-card">
          <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="news-date"><?php echo date('M d, Y', strtotime($row['published_date'])); ?></p>
          <p><?php echo nl2br(htmlspecialchars($row['summary'])); ?></p>
          <a href="/local-news/<?php echo htmlspecialchars($row['slug']); ?>" 
             class="news-read-more-link" 
             aria-label="Read more about <?php echo htmlspecialchars($row['title']); ?>">
            Read More
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No news available at the moment.</p>
    <?php endif; ?>
  </div>
</div>
<link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">
