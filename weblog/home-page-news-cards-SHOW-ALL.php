<?php
require_once 'core/omr-connect.php';

// Fetch ALL articles from the 'articles' table (no status filter, increased limit to 20)
$sql = "SELECT id, title, slug, summary, published_date, image_path FROM articles ORDER BY published_date DESC LIMIT 20";
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
          
          <!-- The link now points to the new clean URL structure -->
          <a href="/local-news/<?php echo htmlspecialchars($row['slug']); ?>">Read More</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No news available at the moment.</p>
    <?php endif; ?>
  </div>
</div>
<link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">

