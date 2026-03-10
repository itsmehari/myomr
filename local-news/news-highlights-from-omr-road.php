<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php 
include '../weblog/log.php';
include '../core/omr-connect.php';

// Check if businesses table exists
$table_check = $conn->query("SHOW TABLES LIKE 'businesses'");
$businesses_exists = ($table_check && $table_check->num_rows > 0);

// Check if gallery table exists
$gallery_check = $conn->query("SHOW TABLES LIKE 'gallery'");
$gallery_exists = ($gallery_check && $gallery_check->num_rows > 0);

$sql = "SELECT `Areas` FROM `List of Areas`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'OMR Road News & Community | Local Businesses & Events | MyOMR.in';
$page_description    = 'Stay updated with the latest news, events, businesses, and jobs along OMR, Chennai. Find local services and community updates at MyOMR.in.';
$canonical_url       = 'https://myomr.in/local-news/news-highlights-from-omr-road.php';
$og_title            = 'OMR Road News & Community | Local Businesses & Events | MyOMR.in';
$og_description      = 'Stay updated with the latest news, events, businesses, and jobs along OMR, Chennai.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/local-news/news-highlights-from-omr-road.php';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/local-news/news-highlights-from-omr-road.php','News Highlights']
]; ?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <!-- Favicon -->
    <link rel="icon" href="/My-OMR-Logo.png" type="image/jpeg">
    
    <!-- Other scripts and styles -->
    <link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">
    <script src="/components/myomr-news-bulletin.js" defer></script>
    <script src="core/modal.js"></script>
    <link rel="stylesheet" href="core/modal.css">
    <link rel="stylesheet" href="core/subscribe.css">
</head>

<body>
<?php include '../components/main-nav.php'; ?>

<!-- Latest News & Highlights -->
<section class="container py-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">MyOMR News Bulletin</h2>
  <?php include __DIR__ . '/../components/myomr-news-bulletin.php'; ?>
  <?php include __DIR__ . '/../components/featured-news-links.php'; ?>
</section>

<!-- Weekend Roundup CTA -->
<section class="container py-4">
  <div class="alert alert-success d-flex justify-content-between align-items-center">
    <div>
      <strong>This Weekend in OMR:</strong> See our curated roundup of the best events.
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-success" href="/local-news/this-weekend-in-omr.php?utm_source=news&utm_medium=internal&utm_campaign=weekend_roundup">Read Roundup</a>
      <a class="btn btn-outline-success" href="/omr-local-events/post-event-omr.php?utm_source=news&utm_medium=internal&utm_campaign=list_event">List your event</a>
    </div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-sm btn-outline-secondary" target="_blank" href="https://wa.me/?text=<?php echo urlencode('This Weekend in OMR – curated roundup: https://myomr.in/local-news/this-weekend-in-omr.php?utm_source=whatsapp&utm_medium=social&utm_campaign=weekend_roundup'); ?>">Share on WhatsApp</a>
    <a class="btn btn-sm btn-outline-secondary" target="_blank" href="https://t.me/share/url?url=<?php echo urlencode('https://myomr.in/local-news/this-weekend-in-omr.php?utm_source=telegram&utm_medium=social&utm_campaign=weekend_roundup'); ?>&text=<?php echo urlencode('This Weekend in OMR – curated roundup'); ?>">Share on Telegram</a>
  </div>
</section>

<!-- Featured Events on OMR -->
<section class="container py-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Featured Events on OMR</h2>
  <?php include __DIR__ . '/../omr-local-events/components/top-featured-events-widget.php'; ?>
  <div class="text-center mt-3">
    <a class="btn btn-success" href="/omr-local-events/">Browse all events</a>
    <a class="btn btn-outline-success" href="/omr-local-events/post-event-omr.php">List your event</a>
  </div>
</section>

<?php if ($businesses_exists): ?>
<!-- Featured Businesses & Services -->
<section class="container py-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Featured Businesses & Services</h2>
  <div class="row">
    <?php
    $biz_sql = "SELECT id, name, category, description, contact_url FROM businesses WHERE featured = 1 LIMIT 6";
    $biz_result = $conn->query($biz_sql);
    if ($biz_result && $biz_result->num_rows > 0) {
      while ($biz = $biz_result->fetch_assoc()) {
    ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-info shadow-sm">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title" style="font-family: 'Playfair Display', serif; color: #008552;"><?php echo htmlspecialchars($biz['name']); ?></h5>
            <p class="card-text mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($biz['category']); ?></p>
            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($biz['description']); ?></p>
            <a href="<?php echo htmlspecialchars($biz['contact_url']); ?>" class="btn btn-outline-info mt-2" target="_blank">View</a>
          </div>
        </div>
      </div>
    <?php }
    } else { ?>
      <div class="col-12 text-center">
        <p>No featured businesses at the moment.</p>
      </div>
    <?php } ?>
  </div>
  <div class="text-center mt-4">
    <a href="omr-road-database-list.php" class="btn btn-primary">View All Businesses</a>
  </div>
</section>
<?php endif; ?>

<?php if ($gallery_exists): ?>
<!-- Community Photo Gallery -->
<section class="container py-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Community Photo Gallery</h2>
  <div class="row">
    <?php
    $gallery_sql = "SELECT id, image_url, caption FROM gallery ORDER BY id DESC LIMIT 8";
    $gallery_result = $conn->query($gallery_sql);
    if ($gallery_result && $gallery_result->num_rows > 0) {
      while ($img = $gallery_result->fetch_assoc()) {
    ?>
      <div class="col-6 col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?php echo htmlspecialchars($img['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($img['caption']); ?>">
          <?php if (!empty($img['caption'])): ?>
            <div class="card-body p-2">
              <p class="card-text small text-center"><?php echo htmlspecialchars($img['caption']); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php }
    } else { ?>
      <div class="col-12 text-center">
        <p>No photos available at the moment.</p>
      </div>
    <?php } ?>
  </div>
  <div class="text-center mt-4">
    <a href="happy-streets-omr-photo-gallery/" class="btn btn-primary">View Full Gallery</a>
  </div>
</section>
<?php endif; ?>

<!-- Subscribe Section -->
<?php include '../components/subscribe.php'; ?>

<!-- Footer Section -->
<?php include '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>

<!-- Modal Section -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Unlock Exclusive Access to MyOMR</h2>
        <ul>
            <li>✔ Get the latest OMR news & updates</li>
            <li>✔ Find local job listings easily</li>
            <li>✔ Discover events & activities near you</li>
            <li>✔ Browse business directories & real estate listings</li>
            <li>✔ Boost your brand with targeted advertising</li>
            <li>✔ Connect with the OMR community in one platform</li>
        </ul>
        <button onclick="window.location.href='tel:+919884785845'">Call Us for Discussion</button>
    </div>
</div>

</body>
</html>
