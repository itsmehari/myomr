<?php
require_once __DIR__ . '/../core/omr-connect.php';

// Fetch articles from the 'articles' table (exclude Tamil versions - slugs ending with -tamil)
// Order: newest published first; when same date, newest id first so latest-added appears first
$sql = "SELECT id, title, slug, summary, published_date, image_path 
        FROM articles 
        WHERE status = 'published' 
        AND slug NOT LIKE '%-tamil' 
        ORDER BY published_date DESC, id DESC 
        LIMIT 20";
$result = $conn->query($sql);
?>

<div class="myomr-news-bulletin">
  <div class="news-grid">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="news-card">
          <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="news-date"><?php echo date('M d, Y', strtotime($row['published_date'])); ?></p>
          <p><?php echo nl2br(htmlspecialchars($row['summary'])); ?></p>
          
          <!-- Link to the article (using clean URL format) -->
          <a href="/local-news/<?php echo htmlspecialchars($row['slug']); ?>" 
             class="news-read-more-link" 
             data-article-title="<?php echo htmlspecialchars($row['title']); ?>"
             data-article-slug="<?php echo htmlspecialchars($row['slug']); ?>"
             aria-label="Read more about <?php echo htmlspecialchars($row['title']); ?>"
             onclick="trackNewsReadMore(event, '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['slug']); ?>');">
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

