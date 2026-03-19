<?php
/**
 * Section-by-section rebuild. Current: Navigation + Hero + News + Subscribe + Footer.
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$recent_jobs = [];
$recent_events = [];
$recent_buy_sell = [];
$buy_sell_count = 0;
$total_jobs_home = 0;
$total_employers_home = 0;
$subscribed = isset($_GET['subscribed']);
$sub_error = isset($_GET['subscribe_error']);
$page_nav = 'homepage';
$omr_css_homepage = true;
$page_title = 'MyOMR.in – OMR Road News & Community';
$page_description = 'Chennai\'s IT corridor – jobs first. News, events, jobs, and community for Old Mahabalipuram Road (OMR). Find businesses, hostels, coworking spaces, and local happenings.';
$canonical_url = 'https://myomr.in/';
$og_type = 'website';
$core_file = __DIR__ . '/core/omr-connect.php';
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
if (file_exists($core_file)) {
  require_once $core_file;
  if (isset($conn) && $conn && !$conn->connect_error) {
    $job_res = $conn->query("SELECT id, title, location FROM job_postings WHERE status = 'approved' ORDER BY created_at DESC LIMIT 12");
    if ($job_res && $job_res->num_rows > 0) {
      while ($row = $job_res->fetch_assoc()) $recent_jobs[] = $row;
    }
    $ev_res = @$conn->query("SELECT id, title, slug, start_datetime, location, locality FROM event_listings WHERE status IN ('scheduled','ongoing') ORDER BY start_datetime ASC LIMIT 6");
    if ($ev_res && $ev_res->num_rows > 0) {
      while ($row = $ev_res->fetch_assoc()) $recent_events[] = $row;
    }
    require_once ROOT_PATH . '/omr-buy-sell/includes/listing-functions.php';
    $buy_sell_count = getBuySellCount([]);
    $recent_buy_sell = getBuySellListings([], 6, 0);
    $jr = $conn->query("SELECT COUNT(*) AS c FROM job_postings WHERE status = 'approved'");
    if ($jr && $row = $jr->fetch_assoc()) $total_jobs_home = (int)$row['c'];
    $er = $conn->query("SELECT COUNT(DISTINCT id) AS c FROM employers");
    if ($er && $row = $er->fetch_assoc()) $total_employers_home = (int)$row['c'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT_PATH . '/components/meta.php'; ?>
  <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
  <style>
    /* Typography: Manrope (headlines) + Source Sans 3 (body) */
    body { font-family: 'Source Sans 3', sans-serif; }

    /* Hero: rotating banner + trends */
    .homepage-hero {
      position: relative;
      overflow: hidden;
      padding: 4rem 1.5rem 2.5rem;
    }
    .hero-bg-slider {
      position: absolute;
      inset: 0;
      z-index: 0;
    }
    .hero-bg-slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      background: center center / cover no-repeat;
      transition: opacity 0.8s ease;
    }
    .hero-bg-slide.active { opacity: 1; z-index: 1; }
    .hero-bg-slide:nth-child(1) { background-image: url('/myomr-banner-image.png'); }
    .hero-bg-slide:nth-child(2) { background-image: url('/myomr-banner-image.png'); background-position: 30% center; }
    .hero-bg-slide:nth-child(3) { background-image: url('/myomr-banner-image.png'); background-position: 70% center; }
    .hero-bg-slide:nth-child(4) { background-image: url('/myomr-banner-image.png'); background-position: 50% 80%; }
    .hero-overlay {
      position: absolute;
      inset: 0;
      z-index: 1;
      background: linear-gradient(135deg, rgba(20,83,45,0.75) 0%, rgba(0,133,82,0.7) 50%, rgba(158,189,19,0.65) 100%);
      pointer-events: none;
    }
    .homepage-hero::before, .homepage-hero::after { display: none; }

    .hero-inner { position: relative; z-index: 2; }

    /* Trust badge above headline */
    .hero-badge {
      font-family: 'Source Sans 3', sans-serif;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.35rem 1rem;
      background: rgba(255,255,255,0.25);
      backdrop-filter: blur(8px);
      color: #fff;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,0.4);
      margin-bottom: 1rem;
      opacity: 0;
      animation: heroFadeUp 0.6s ease 0.1s forwards;
    }
    .hero-badge i { font-size: 0.75rem; }

    /* Rotating text slides */
    .hero-text-slider {
      position: relative;
      min-height: 140px;
      margin-bottom: 1rem;
    }
    .hero-slide {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      text-align: center;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.6s ease, visibility 0.6s;
    }
    .hero-slide.active { opacity: 1; visibility: visible; }
    .hero-headline {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2rem, 5vw, 3.25rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.15;
      text-shadow: 0 2px 24px rgba(0,0,0,0.25);
      margin-bottom: 0.5rem;
    }
    .hero-subtitle {
      font-family: 'Source Sans 3', sans-serif;
      font-size: 1.1rem;
      font-weight: 500;
      max-width: 560px;
      margin: 0 auto;
      line-height: 1.65;
      letter-spacing: 0.01em;
      text-shadow: 0 1px 12px rgba(0,0,0,0.2);
    }

    /* Glassmorphism search + gradient border */
    .hero-search-wrap {
      max-width: 720px;
      margin: 0 auto 1rem;
      padding: 3px;
      background: linear-gradient(135deg, rgba(255,255,255,0.5), rgba(255,255,255,0.2));
      border-radius: 14px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }
    .hero-search {
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      padding: 1rem;
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.4);
    }
    .hero-search input, .hero-search select, .hero-search button {
      font-family: 'Source Sans 3', sans-serif !important;
    }
    .hero-search input, .hero-search select {
      background: rgba(255,255,255,0.9) !important;
    }

    /* Stats row */
    .hero-stats {
      font-family: 'Source Sans 3', sans-serif;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1.5rem 2rem;
      margin-bottom: 1.25rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: rgba(255,255,255,0.95);
      text-shadow: 0 1px 8px rgba(0,0,0,0.2);
      opacity: 0;
      animation: heroFadeUp 0.6s ease 0.4s forwards;
    }
    .hero-stats span { display: inline-flex; align-items: center; gap: 0.35rem; }
    .hero-stats i { opacity: 0.9; }

    .hero-quick-links {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: center;
      margin-top: 1rem;
      opacity: 0;
      animation: heroFadeUp 0.6s ease 0.5s forwards;
    }
    .hero-quick-links a {
      font-family: 'Source Sans 3', sans-serif;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.5rem 1rem;
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(6px);
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,0.35);
      transition: background 0.2s, transform 0.2s;
    }
    .hero-quick-links a:hover {
      background: rgba(255,255,255,0.35);
      transform: translateY(-2px);
    }

    /* Carousel dots */
    .hero-dots {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1.25rem;
      opacity: 0;
      animation: heroFadeUp 0.6s ease 0.55s forwards;
    }
    .hero-dots button {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,0.8);
      background: transparent;
      cursor: pointer;
      padding: 0;
      transition: all 0.3s ease;
    }
    .hero-dots button:hover { background: rgba(255,255,255,0.4); }
    .hero-dots button.active { background: #fff; transform: scale(1.2); }

    .hero-cta-wrap {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.75rem;
      opacity: 0;
      animation: heroFadeUp 0.6s ease 0.6s forwards;
    }
    .homepage-hero .hero-cta {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }
    .homepage-hero .hero-cta-wa {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.6rem 1.25rem;
      background: #25d366;
      color: #fff;
      border: 2px solid rgba(255,255,255,0.5);
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.95rem;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .homepage-hero .hero-cta-wa:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,0.5); }
    .homepage-hero .hero-cta-fb {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.6rem 1.25rem;
      background: #1877f2;
      color: #fff;
      border: 2px solid rgba(255,255,255,0.5);
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.95rem;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .homepage-hero .hero-cta-fb:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(24,119,242,0.45); }

    @keyframes heroFadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>

<!-- Navigation -->
<?php omr_nav(); ?>

<!-- Hero Section -->
<section class="homepage-hero" aria-labelledby="hero-heading">
  <div class="hero-bg-slider">
    <div class="hero-bg-slide" aria-hidden="true"></div>
    <div class="hero-bg-slide" aria-hidden="true"></div>
    <div class="hero-bg-slide" aria-hidden="true"></div>
  </div>
  <div class="hero-overlay" aria-hidden="true"></div>
  <div class="hero-inner">
    <div class="hero-badge"><i class="fas fa-check-circle"></i> 100% Free &middot; No signup needed</div>
    <div class="hero-text-slider">
      <div class="hero-slide active" data-slide="0" role="region" aria-live="polite">
        <h1 class="hero-headline" id="hero-heading">IT Jobs on OMR</h1>
        <p class="hero-subtitle">Find IT jobs in Chennai&rsquo;s IT corridor. Connect with top employers on Old Mahabalipuram Road — post or apply, 100% free.</p>
      </div>
      <div class="hero-slide" data-slide="1">
        <h1 class="hero-headline">Explore OMR</h1>
        <p class="hero-subtitle">Your local directory for Old Mahabalipuram Road — businesses, places, events, jobs, hostels & coworking spaces.</p>
      </div>
      <div class="hero-slide" data-slide="2">
        <h1 class="hero-headline">Find Jobs in OMR</h1>
        <p class="hero-subtitle">Connect with top employers. Browse IT, Teaching, Healthcare, Retail & more. Post or apply — 100% free.</p>
      </div>
      <div class="hero-slide" data-slide="3">
        <h1 class="hero-headline">Discover Local Events</h1>
        <p class="hero-subtitle">What's happening in OMR. Community events, workshops, meetups, festivals. Add your event or explore.</p>
      </div>
      <div class="hero-slide" data-slide="4">
        <h1 class="hero-headline">Places & Listings</h1>
        <p class="hero-subtitle">Schools, restaurants, hostels, coworking spaces, banks, hospitals. Your guide to OMR and beyond.</p>
      </div>
    </div>
    <div class="hero-search-wrap">
      <form class="hero-search hero-search-unified" action="/omr-local-job-listings/" method="get" role="search" data-jobs-action="/omr-local-job-listings/" data-events-action="/omr-local-events/" data-places-action="/omr-listings/" data-buysell-action="/omr-buy-sell/">
        <input type="search" name="search" placeholder="What are you looking for?" aria-label="Search OMR">
        <input type="text" name="location" placeholder="Area in OMR" aria-label="Location">
        <select name="category" aria-label="Category">
          <option value="">All Categories</option>
          <option value="jobs">Jobs</option>
          <option value="events">Events</option>
          <option value="places">Places</option>
          <option value="schools">Schools</option>
          <option value="restaurants">Restaurants</option>
          <option value="hostels">Hostels & PGs</option>
          <option value="coworking">Coworking</option>
          <option value="buy-sell">Buy & Sell</option>
        </select>
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="hero-stats">
      <span><i class="fas fa-graduation-cap"></i> Schools</span>
      <span><i class="fas fa-briefcase"></i> Jobs</span>
      <span><i class="fas fa-calendar-day"></i> Events</span>
      <span><i class="fas fa-bed"></i> Hostels</span>
      <span><i class="fas fa-building"></i> Coworking</span>
      <span><i class="fas fa-house"></i> Rent & Lease</span>
      <span><i class="fas fa-shopping-bag"></i> Buy & Sell</span>
    </div>
    <div class="hero-quick-links">
      <a href="/it-jobs-omr-chennai.php"><i class="fas fa-laptop-code"></i> IT Jobs</a>
      <a href="/omr-local-job-listings/jobs-hub-omr.php"><i class="fas fa-briefcase"></i> All Jobs</a>
      <a href="/omr-local-events/"><i class="fas fa-calendar-day"></i> Events</a>
      <a href="/omr-listings/"><i class="fas fa-map-marker-alt"></i> Places</a>
      <a href="/omr-hostels-pgs/"><i class="fas fa-bed"></i> Hostels & PGs</a>
      <a href="/omr-coworking-spaces/"><i class="fas fa-building"></i> Coworking</a>
      <a href="/omr-rent-lease/"><i class="fas fa-house"></i> Rent & Lease</a>
      <a href="/omr-buy-sell/"><i class="fas fa-shopping-bag"></i> Buy & Sell</a>
      <a href="/elections-2026/"><i class="fas fa-vote-yea"></i> Elections 2026</a>
    </div>
    <div class="hero-dots" role="tablist" aria-label="Hero slides">
      <button type="button" role="tab" aria-selected="true" aria-label="Slide 1" class="active" data-dot="0"></button>
      <button type="button" role="tab" aria-selected="false" aria-label="Slide 2" data-dot="1"></button>
      <button type="button" role="tab" aria-selected="false" aria-label="Slide 3" data-dot="2"></button>
      <button type="button" role="tab" aria-selected="false" aria-label="Slide 4" data-dot="3"></button>
      <button type="button" role="tab" aria-selected="false" aria-label="Slide 5" data-dot="4"></button>
    </div>
    <div class="hero-cta-wrap">
      <a href="#subscribe" class="hero-cta">Join the Community</a>
      <a href="<?php echo htmlspecialchars(defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi'); ?>" target="_blank" rel="noopener" class="hero-cta-wa" aria-label="Join our WhatsApp group for updates"><i class="fab fa-whatsapp"></i> Join WhatsApp Group</a>
      <a href="<?php echo htmlspecialchars(defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'); ?>" target="_blank" rel="noopener" class="hero-cta-fb" aria-label="Join our Facebook group for updates" title="Join our Facebook group"><i class="fab fa-facebook-f"></i> Join Facebook Group</a>
    </div>
  </div>
</section>

<div class="omr-ad-zone-wrap omr-ad-zone-wrap--homepage-top">
<?php omr_ad_slot_row('homepage-top', 2); ?>
</div>

<script>
(function(){
  var slides = document.querySelectorAll('.hero-slide');
  var bgSlides = document.querySelectorAll('.hero-bg-slide');
  var dots = document.querySelectorAll('.hero-dots button');
  var total = 5;
  var current = 0;
  var interval;

  function goTo(n) {
    current = (n + total) % total;
    slides.forEach(function(s, i){ s.classList.toggle('active', i === current); });
    bgSlides.forEach(function(s, i){ s.classList.toggle('active', i === current); });
    dots.forEach(function(d, i){
      d.classList.toggle('active', i === current);
      d.setAttribute('aria-selected', i === current);
    });
  }
  function next(){ goTo(current + 1); }
  function start(){ interval = setInterval(next, 5000); }
  function stop(){ clearInterval(interval); }
  dots.forEach(function(d, i){
    d.addEventListener('click', function(){ goTo(i); stop(); start(); });
  });
  start();
})();

/* Hero search: route to events/places when category selected */
(function(){
  var form = document.querySelector('.hero-search-unified');
  if (!form) return;
  var catSelect = form.querySelector('select[name="category"]');
  if (!catSelect) return;
  form.addEventListener('submit', function() {
    var cat = (catSelect.value || '').toLowerCase();
    if (cat === 'events') form.action = form.getAttribute('data-events-action') || '/omr-local-events/';
    else if (cat === 'buy-sell') form.action = form.getAttribute('data-buysell-action') || '/omr-buy-sell/';
    else if (cat === 'places' || cat === 'schools' || cat === 'restaurants' || cat === 'hostels' || cat === 'coworking') form.action = form.getAttribute('data-places-action') || '/omr-listings/';
    else form.action = form.getAttribute('data-jobs-action') || '/omr-local-job-listings/';
  });
})();
</script>

<?php if ($total_jobs_home > 0 || $total_employers_home > 0): ?>
<div class="container text-center py-2" style="background:#f0fdf4;border-radius:8px;margin-bottom:1rem;">
  <span class="me-3"><strong><?php echo number_format($total_jobs_home); ?></strong> live jobs</span>
  <span><strong><?php echo number_format($total_employers_home); ?></strong> employers on OMR</span>
  <a href="/omr-local-job-listings/jobs-hub-omr.php" class="ms-3 btn btn-success btn-sm">Browse jobs</a>
</div>
<?php endif; ?>
<?php if (!empty($recent_jobs)): ?>
<?php include 'components/homepage-jobs-scroll-banner.php'; ?>
<?php endif; ?>

<?php if (!empty($recent_events)): ?>
<?php include 'components/homepage-events-widget.php'; ?>
<?php endif; ?>

<?php include 'components/homepage-buy-sell-section.php'; ?>

<?php include 'components/homepage-elections-2026-section.php'; ?>

<main id="main-content" class="homepage-main">
<!-- Latest News (Livemint-style editorial layout) -->
<section class="homepage-section homepage-section--news">
  <?php include 'components/myomr-news-bulletin.php'; ?>
  <?php include 'components/featured-news-links.php'; ?>
</section>
<?php omr_ad_slot('homepage-mid', '336x280'); ?>
</main>

<section id="subscribe" class="homepage-section" aria-labelledby="subscribe-heading">
  <div class="homepage-subscribe">
    <h2 id="subscribe-heading">Stay Updated on OMR</h2>
    <p>Get the latest news, events, job opportunities, and community updates delivered to your inbox.</p>
    <?php if ($subscribed): ?>
      <p style="color:#166534;background:#dcfce7;padding:12px 20px;border-radius:8px;margin:0;"><i class="fas fa-check-circle" style="margin-right:8px;"></i> You&rsquo;re subscribed! Welcome to the OMR community.</p>
    <?php elseif ($sub_error): ?>
      <p style="color:#991b1b;background:#fee2e2;padding:12px 20px;border-radius:8px;margin:0;"><i class="fas fa-exclamation-circle" style="margin-right:8px;"></i> Please enter a valid email address.</p>
    <?php else: ?>
      <form method="post" action="/components/subscribe.php" style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;align-items:center;"
            onsubmit="if(typeof gtag==='function') gtag('event','subscribe',{'event_category':'Newsletter','event_label':'homepage'});">
        <input type="hidden" name="source_url" value="/">
        <input type="email" name="email" required placeholder="Your email address" class="form-control" style="max-width:300px;"
               aria-label="Email for newsletter">
        <button type="submit" class="btn-subscribe">
          <i class="fas fa-paper-plane" style="margin-right:6px;"></i> Subscribe
        </button>
      </form>
      <p style="margin-top:12px;margin-bottom:0;font-size:0.85rem;color:#6b7280;">
        <i class="fas fa-shield-alt" style="margin-right:6px;"></i> No spam. Unsubscribe any time.
      </p>
    <?php endif; ?>
    <p style="margin-top:1.25rem;margin-bottom:0;font-size:0.9rem;color:#6b7280;">Follow us: <?php include ROOT_PATH . '/components/social-icons.php'; ?></p>
  </div>
</section>

<?php omr_footer(); ?>

<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>

</body>
</html>
