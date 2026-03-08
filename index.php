<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
include 'weblog/log.php';
include 'core/omr-connect.php';
$home_cat_file = __DIR__ . '/core/homepage-categories.php';
if (file_exists($home_cat_file)) {
  include $home_cat_file;
} else {
  $home_categories = [
    ['label' => 'Schools', 'url' => '/omr-listings/schools.php', 'icon' => 'fas fa-graduation-cap', 'highlight' => false, 'count' => null],
    ['label' => 'Restaurants', 'url' => '/omr-listings/restaurants.php', 'icon' => 'fas fa-utensils', 'highlight' => false, 'count' => null],
    ['label' => 'Jobs', 'url' => '/omr-local-job-listings/', 'icon' => 'fas fa-briefcase', 'highlight' => true, 'count' => null],
    ['label' => 'Events', 'url' => '/omr-local-events/', 'icon' => 'fas fa-calendar-day', 'highlight' => true, 'count' => null],
    ['label' => 'Hostels & PGs', 'url' => '/omr-hostels-pgs/', 'icon' => 'fas fa-bed', 'highlight' => false, 'count' => null],
    ['label' => 'Coworking', 'url' => '/omr-coworking-spaces/', 'icon' => 'fas fa-building', 'highlight' => false, 'count' => null],
    ['label' => 'Hospitals', 'url' => '/omr-listings/hospitals.php', 'icon' => 'fas fa-hospital', 'highlight' => false, 'count' => null],
    ['label' => 'Banks', 'url' => '/omr-listings/banks.php', 'icon' => 'fas fa-university', 'highlight' => false, 'count' => null],
    ['label' => 'IT Parks', 'url' => '/omr-listings/it-parks-in-omr.php', 'icon' => 'fas fa-laptop-house', 'highlight' => false, 'count' => null],
  ];
}

$sql = "SELECT `Areas` FROM `List of Areas`";
$result = $conn->query($sql);
$canonical_url = 'https://myomr.in/';

// Recent jobs for scroll banner
$recent_jobs = [];
if (isset($conn) && $conn && !$conn->connect_error) {
  $chk = @$conn->query("SHOW TABLES LIKE 'job_postings'");
  if ($chk && $chk->num_rows > 0) {
    $r = @$conn->query("SELECT id, title, location FROM job_postings WHERE status = 'approved' ORDER BY created_at DESC LIMIT 15");
    if ($r && $r->num_rows > 0) {
      while ($row = $r->fetch_assoc()) {
        $recent_jobs[] = ['id' => $row['id'], 'title' => $row['title'], 'location' => $row['location'] ?? ''];
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title       = 'OMR Road News & Community | Local Businesses & Events | MyOMR.in';
$page_description = 'Stay updated with the latest news, events, businesses, and jobs along OMR, Chennai. Find local services and community updates at MyOMR.in.';
$og_title         = 'OMR Road News & Community | Local Businesses & Events | MyOMR.in';
$og_description   = 'Stay updated with the latest news, events, businesses, and jobs along OMR, Chennai. Find local services and community updates at MyOMR.in.';
$og_image         = 'https://myomr.in/My-OMR-Logo.jpg';
$og_url           = 'https://myomr.in/';
$twitter_title    = 'OMR Road News & Community | Local Businesses & Events | MyOMR.in';
$twitter_description = 'Stay updated with the latest news, events, businesses, and jobs along OMR, Chennai. Find local services and community updates at MyOMR.in.';
$twitter_image    = 'https://myomr.in/My-OMR-Logo.jpg';
?>
<?php include 'components/meta.php'; ?>
<?php include 'components/analytics.php'; ?>
    <meta name="google-site-verification" content="0Z9Td8zvnhZsgWaItiaCGgpQ3M3SsOr_oiAIkCcDmqE" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts: Fraunces (display) + Poppins (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;1,400&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- MyOMR CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/homepage-myomr.css">
    <link rel="stylesheet" href="/assets/css/megamenu-myomr.css">
    <link rel="icon" href="/My-OMR-Logo.jpg" type="image/jpeg">

    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "MyOMR",
  "url": "https://myomr.in",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "OMR Road",
    "addressLocality": "Chennai",
    "addressRegion": "TN",
    "postalCode": "600097",
    "addressCountry": "IN"
  },
  "telephone": "+91XXXXXXXXXX",
  "openingHours": "Mo-Sa 09:00-18:00"
}
</script>

    <meta property="og:type" content="article" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="Old Mahabalipuram Road news, Search, Events, Happenings, Photographs" />
    <meta property="og:description" content="Home page of Old Mahabalipuram Road, OMR website, which hosts several features for its user base, especially from Chennai, Tamilnadu. We offer special coverage on News, Happenings, Events, Businesses and people of OMR and its neighbouring Community" />
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg" />
    <meta property="og:url" content="https://myomr.in/" />
    <meta property="og:site_name" content="My OMR Old Mahabalipuram Road." />
    <meta property="og:locale" content="en_US" />
    <meta property="og:locale:alternate" content="ta_IN" />

    <meta name="twitter:title" content="My OMR - Old Mahabalipuram Road News, Events, Images, Happenings, Search, Business Website">
    <meta name="twitter:description" content="Find news, events, images, happenings, updates, local business information of OMR Road, Old Mahabalipuram Road and its Surroundings">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.jpg">
    <meta name="twitter:site" content="@MyomrNews">
    <meta name="twitter:creator" content="@MyomrNews">

    <script src="/components/myomr-news-bulletin.js" defer></script>
    <link rel="stylesheet" href="/core/modal.css">

    <style>
    img.lazyload { opacity: 0; transition: opacity 0.3s; }
    img.lazyload[src] { opacity: 1; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border-width: 0; }
    @media screen and (max-width: 600px) { .hide-on-mobile { display: none; } }
    </style>
</head>
<body>

<!-- Homepage Header (dedicated, fallback to main-nav) -->
<?php
$hp_header = __DIR__ . '/components/homepage-header.php';
if (file_exists($hp_header)) {
  include $hp_header;
} else {
  include __DIR__ . '/components/main-nav.php';
}
?>

<main id="main-content" class="homepage-main">

<!-- Hero Section -->
<section class="homepage-hero">
  <div class="hero-inner">
    <h1 class="hero-headline">Explore OMR</h1>
    <p class="hero-subtitle">Your digital community hub for Old Mahabalipuram Road, Chennai. Local news, events, businesses & jobs.</p>
    <form class="hero-search" action="/omr-local-job-listings/" method="get" role="search">
      <input type="search" name="search" placeholder="Search OMR…" aria-label="Search OMR">
      <input type="text" name="location" placeholder="Location" aria-label="Location">
      <select name="category" aria-label="Category">
        <option value="">All</option>
        <option value="jobs">Jobs</option>
        <option value="events">Events</option>
        <option value="places">Places</option>
      </select>
      <button type="submit">Search OMR</button>
    </form>
    <a href="#subscribe" class="hero-cta">Join the Community</a>
  </div>
</section>

<?php
$jobs_banner = __DIR__ . '/components/homepage-jobs-scroll-banner.php';
if (file_exists($jobs_banner)) include $jobs_banner;
?>

<!-- Category Grid -->
<section class="homepage-section">
  <span class="homepage-section-label">Browse</span>
  <h2 class="homepage-section-title">What Do You Need in OMR?</h2>
  <p class="homepage-section-subtitle">Schools, restaurants, jobs, events, hostels & more</p>
  <div class="homepage-categories">
    <?php foreach ($home_categories as $cat): ?>
    <a href="<?php echo htmlspecialchars($cat['url']); ?>" class="homepage-category-card<?php echo $cat['highlight'] ? ' highlight' : ''; ?>">
      <i class="<?php echo htmlspecialchars($cat['icon']); ?> icon" aria-hidden="true"></i>
      <span class="label"><?php echo htmlspecialchars($cat['label']); ?></span>
      <?php if (isset($cat['count']) && $cat['count'] !== null): ?>
      <span class="count"><?php echo (int)$cat['count']; ?> listings</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- Latest News (two-row scrollable, above events) -->
<section class="homepage-section homepage-news">
  <span class="homepage-section-label">Latest</span>
  <h2 class="homepage-section-title">Latest News</h2>
  <p class="homepage-section-subtitle">Local news & updates from OMR</p>
  <p class="homepage-news-scroll-hint" aria-hidden="true"><span class="homepage-news-scroll-hint-icon" aria-hidden="true"></span> Drag or use arrows to scroll</p>
  <div class="homepage-news-scroll-container">
    <button type="button" class="homepage-news-scroll-btn homepage-news-scroll-prev" aria-label="Scroll news left" title="Scroll left">
      <i class="fas fa-chevron-left" aria-hidden="true"></i>
    </button>
    <div class="homepage-news-scroll-wrap-wrapper">
      <div class="homepage-news-scroll-wrap" id="homepage-news-scroll" role="region" aria-label="Latest news carousel">
        <div class="homepage-news-scroll-inner">
      <?php
      $news_root = __DIR__ . '/home-page-news-cards.php';
      $news_weblog = __DIR__ . '/weblog/home-page-news-cards.php';
      if (file_exists($news_root)) {
        include $news_root;
      } elseif (file_exists($news_weblog)) {
        include $news_weblog;
      } else {
        echo '<p class="text-muted text-center py-4">Latest news will appear here. <a href="/local-news/news-highlights-from-omr-road.php">Browse news</a>.</p>';
      }
      ?>
        </div>
      </div>
    </div>
    <button type="button" class="homepage-news-scroll-btn homepage-news-scroll-next" aria-label="Scroll news right" title="Scroll right">
      <i class="fas fa-chevron-right" aria-hidden="true"></i>
    </button>
  </div>
  <?php include 'components/featured-news-links.php'; ?>
</section>

<!-- Featured Events -->
<section class="homepage-section alt-bg homepage-events">
  <span class="homepage-section-label">Upcoming</span>
  <h2 class="homepage-section-title">Featured Events</h2>
  <p class="homepage-section-subtitle">Community events along OMR</p>
  <?php include __DIR__ . '/omr-local-events/components/top-featured-events-widget.php'; ?>
  <div class="text-center mt-4">
    <a class="btn btn-success" href="/omr-local-events/">Browse all events</a>
  </div>
</section>

<!-- Subscribe (single block) -->
<section class="homepage-section alt-bg" id="subscribe">
  <div class="homepage-subscribe">
    <h2>Subscribe to Our Newsletter</h2>
    <p>Get the latest news, events, and updates from OMR delivered to your inbox.</p>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_email'): ?>
    <div class="alert alert-danger" role="alert" id="email-error" aria-live="polite">Please enter a valid email address.</div>
    <?php endif; ?>
    <form action="/core/subscribe.php" method="POST" class="d-flex flex-wrap gap-2 justify-content-center">
      <label for="email-subscribe" class="sr-only">Email Address</label>
      <input type="email" id="email-subscribe" name="email" class="form-control" style="max-width: 280px;" placeholder="you@email.com" required
             aria-describedby="<?php echo (isset($_GET['error']) && $_GET['error'] === 'invalid_email') ? 'email-error' : ''; ?>">
      <button type="submit" class="btn btn-subscribe">Subscribe</button>
    </form>
    <p class="subscribe-text mt-3 small text-muted mb-0">
      By subscribing you agree to MyOMR Privacy Policy. Unsubscribe anytime.
    </p>
  </div>
</section>

<!-- Footer -->
<?php include 'components/footer.php'; ?>

</main>

<?php
$float_btns = __DIR__ . '/components/homepage-floating-buttons.php';
if (file_exists($float_btns)) {
  include $float_btns;
} else {
  echo '<a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="floating-btn floating-whatsapp" style="position:fixed;bottom:1rem;right:1rem;width:48px;height:48px;border-radius:50%;background:#25d366;color:#fff;display:flex;align-items:center;justify-content:center;z-index:1000;" target="_blank" rel="noopener" aria-label="Join MyOMR WhatsApp"><i class="fab fa-whatsapp"></i></a>';
}
?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/assets/js/analytics-tracking.js"></script>
<script src="/assets/js/main.js"></script>

<!-- Modal Section -->
<div class="modal-overlay" id="modalOverlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-hidden="true" tabindex="-1">
  <div class="modal">
    <button type="button" class="close-btn" onclick="closeModal()" aria-label="Close modal">&times;</button>
    <h2 id="modalTitle">Unlock Exclusive Access to MyOMR</h2>
    <ul>
      <li>✔ Get the latest OMR news & updates</li>
      <li>✔ Find local job listings easily</li>
      <li>✔ Discover events & activities near you</li>
      <li>✔ Browse business directories & real estate listings</li>
      <li>✔ Boost your brand with targeted advertising</li>
      <li>✔ Connect with the OMR community in one platform</li>
    </ul>
    <button onclick="window.location.href='tel:+919884785845'" aria-label="Call us at +919884785845">Call Us for Discussion</button>
  </div>
</div>

<!-- Job Feature Promotion Modal -->
<div class="modal fade" id="jobPromoModal" tabindex="-1" aria-labelledby="jobPromoModalLabel" aria-describedby="jobPromoModalDescription" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="closeJobPromoModal()" aria-label="Close"></button>
      <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); padding: 2rem; text-align: center;">
        <div class="d-flex justify-content-center">
          <i class="fas fa-briefcase" style="font-size: 2.5rem; color: #008552;" aria-hidden="true"></i>
        </div>
      </div>
      <div class="modal-body text-center pt-4">
        <h2 class="h4 mb-3" id="jobPromoModalLabel">Discover Job Opportunities!</h2>
        <p id="jobPromoModalDescription" class="text-muted mb-4">Find your dream job or post job openings in OMR.</p>
        <div class="d-flex flex-column gap-2 mb-3">
          <a href="/omr-local-job-listings/" class="btn btn-primary" onclick="localStorage.setItem('myomr_job_promo_seen', 'true');">Browse Jobs</a>
          <a href="/omr-local-job-listings/employer-landing-omr.php" class="btn btn-outline-primary" onclick="localStorage.setItem('myomr_job_promo_seen', 'true');">Post a Job</a>
        </div>
        <button type="button" class="btn btn-link text-muted text-decoration-none" onclick="closeJobPromoModal()" aria-label="Skip">Skip</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  var scrollEl = document.getElementById('homepage-news-scroll');
  var wrapper = document.querySelector('.homepage-news-scroll-wrap-wrapper');
  var prevBtn = document.querySelector('.homepage-news-scroll-prev');
  var nextBtn = document.querySelector('.homepage-news-scroll-next');
  if (!scrollEl) return;

  var step = 320;
  if (prevBtn) prevBtn.addEventListener('click', function() { scrollEl.scrollBy({ left: -step, behavior: 'smooth' }); });
  if (nextBtn) nextBtn.addEventListener('click', function() { scrollEl.scrollBy({ left: step, behavior: 'smooth' }); });

  function updateScrollState() {
    if (!wrapper) return;
    var atStart = scrollEl.scrollLeft <= 2;
    var atEnd = scrollEl.scrollLeft >= scrollEl.scrollWidth - scrollEl.clientWidth - 2;
    wrapper.classList.toggle('at-start', atStart);
    wrapper.classList.toggle('at-end', atEnd);
  }
  scrollEl.addEventListener('scroll', updateScrollState);
  window.addEventListener('resize', updateScrollState);
  updateScrollState();

  var isDown = false, startX, scrollStart, didDrag = false;

  function pointerStart(e) {
    startX = (e.touches ? e.touches[0] : e).clientX;
    isDown = true;
    didDrag = false;
    scrollStart = scrollEl.scrollLeft;
  }
  function pointerMove(e) {
    if (!isDown) return;
    didDrag = true;
    e.preventDefault();
    var clientX = (e.touches ? e.touches[0] : e).clientX;
    scrollEl.scrollLeft = scrollStart - (clientX - startX);
  }
  function pointerEnd() {
    isDown = false;
    setTimeout(function() { didDrag = false; }, 100);
  }

  scrollEl.addEventListener('mousedown', function(e) {
    if (e.button !== 0) return;
    pointerStart(e);
  });
  scrollEl.addEventListener('mousemove', pointerMove, { passive: false });
  scrollEl.addEventListener('mouseleave', pointerEnd);
  scrollEl.addEventListener('mouseup', pointerEnd);
  scrollEl.addEventListener('touchstart', pointerStart, { passive: true });
  scrollEl.addEventListener('touchmove', pointerMove, { passive: false });
  scrollEl.addEventListener('touchend', pointerEnd);
  scrollEl.addEventListener('touchcancel', pointerEnd);
  scrollEl.addEventListener('click', function(e) {
    if (didDrag && (e.target.closest('a') || e.target.closest('button'))) {
      e.preventDefault();
      e.stopPropagation();
    }
    didDrag = false;
  }, true);
})();
</script>

<script>
window.addEventListener('load', function() {
  if (!localStorage.getItem('myomr_job_promo_seen')) {
    setTimeout(function() {
      var modal = new bootstrap.Modal(document.getElementById('jobPromoModal'));
      modal.show();
    }, 5000);
  }
});
function closeJobPromoModal() {
  var el = document.getElementById('jobPromoModal');
  if (el) {
    var m = bootstrap.Modal.getInstance(el);
    if (m) m.hide();
    el.classList.remove('show');
    el.setAttribute('aria-hidden', 'true');
  }
  try { localStorage.setItem('myomr_job_promo_seen', 'true'); } catch (e) {}
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeJobPromoModal();
});
</script>

<?php include 'components/sdg-badge.php'; ?>
<script src="/core/modal.js"></script>
</body>
</html>
