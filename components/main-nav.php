<?php /* Unified Main Navigation Bar - Green Theme with Top Secondary Menu */ ?>
<style>
/* Top Secondary Menu Bar - 60px height */
.top-secondary-menu {
  background: #1e7e34; /* Slightly lighter green */
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  max-width: 100%;
  box-shadow: 0 2px 8px rgba(20, 83, 45, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.top-secondary-menu .secondary-links {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  list-style: none;
  margin: 0;
  padding: 0;
  flex: 1;
  justify-content: flex-start;
}

.top-secondary-menu .secondary-links li {
  margin: 0;
}

.top-secondary-menu .secondary-links a {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #fff;
  text-decoration: none;
  padding: 0.625rem 1.25rem;
  font-size: 0.95rem;
  font-weight: 500;
  border-radius: 6px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.top-secondary-menu .secondary-links a:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-1px);
}

.top-secondary-menu .secondary-links a.active {
  background: #22c55e;
  color: #14532d;
  font-weight: 600;
}

.top-secondary-menu .secondary-links a i {
  font-size: 1rem;
}

.top-secondary-menu .top-right-icons {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.top-secondary-menu .top-right-icons a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  color: #fff;
  text-decoration: none;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  transition: all 0.2s ease;
  font-size: 1.1rem;
}

.top-secondary-menu .top-right-icons a:hover {
  background: #22c55e;
  color: #14532d;
  transform: scale(1.1);
}

.top-secondary-menu .top-right-icons a i {
  font-size: 1.2rem;
}

/* Main Navigation Bar */
.main-navbar {
  background: #14532d; /* Deep green */
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(20, 83, 45, 0.12);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1rem;
  margin: 1rem auto 0.5rem auto;
  max-width: 1200px;
}

/* Quick Action Pills Bar */
.quick-action-pills {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0.75rem auto 1rem auto;
  max-width: 1200px;
  padding: 0 1rem;
}

.pills-container {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  flex-wrap: wrap;
  justify-content: center;
}

.action-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0;
  border-radius: 6px;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
  border: none;
}

.action-pill::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
  z-index: 1;
}

.action-pill:hover::before {
  left: 100%;
}

.action-pill .pill-accent {
  width: 20px;
  height: 100%;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 2;
}

.action-pill .pill-content {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1.25rem;
  flex: 1;
  position: relative;
  z-index: 2;
}

.action-pill i {
  font-size: 0.9rem;
  transition: transform 0.3s ease;
}

.action-pill:hover i {
  transform: scale(1.15);
}

.action-pill span {
  white-space: nowrap;
}

/* Job Button - Orange/Amber */
.pill-job {
  background: linear-gradient(135deg, #f59e0b, #f97316);
  color: #fff;
}

.pill-job .pill-accent {
  background: #ea580c; /* Darker contrasting color */
}

.pill-job:hover {
  background: linear-gradient(135deg, #f97316, #ea580c);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
  color: #fff;
}

.pill-job:hover .pill-accent {
  background: #c2410c; /* Even darker on hover */
}

/* Candidate / résumé — blue (job seekers) */
.pill-candidate {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: #fff;
}
.pill-candidate .pill-accent {
  background: #1e40af;
}
.pill-candidate:hover {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.45);
  color: #fff;
}
.pill-candidate:hover .pill-accent {
  background: #1e3a8a;
}

/* Event Button - Purple/Violet */
.pill-event {
  background: linear-gradient(135deg, #8b5cf6, #7c3aed);
  color: #fff;
}

.pill-event .pill-accent {
  background: #6d28d9; /* Darker contrasting color */
}

.pill-event:hover {
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
  color: #fff;
}

.pill-event:hover .pill-accent {
  background: #5b21b6; /* Even darker on hover */
}

/* Property Button - Teal/Cyan */
.pill-property {
  background: linear-gradient(135deg, #14b8a6, #0d9488);
  color: #fff;
}

.pill-property .pill-accent {
  background: #0f766e; /* Darker contrasting color */
}

.pill-property:hover {
  background: linear-gradient(135deg, #0d9488, #0f766e);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4);
  color: #fff;
}

.pill-property:hover .pill-accent {
  background: #0d5d56; /* Even darker on hover */
}

/* Buy & Sell Button - Green */
.pill-buysell {
  background: linear-gradient(135deg, #0d7a42, #0b4d2c);
  color: #fff;
}

.pill-buysell .pill-accent {
  background: #0a3d22;
}

.pill-buysell:hover {
  background: linear-gradient(135deg, #0b4d2c, #0a3d22);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(13, 122, 66, 0.4);
  color: #fff;
}

.pill-buysell:hover .pill-accent {
  background: #082d19;
}

.main-navbar .logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.main-navbar .logo img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid #22c55e;
}

.main-navbar .logo span {
  color: #fff;
  font-size: 1.5rem;
  font-weight: bold;
}

.main-navbar ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  gap: 0.5rem;
}

.main-navbar li {
  position: relative;
}

.main-navbar a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  font-weight: 500;
  border-radius: 6px;
  transition: background 0.2s, color 0.2s;
}

.main-navbar a:focus,
.main-navbar button:focus {
  outline: 3px solid #ffbf47;
  outline-offset: 2px;
}

.main-navbar a:hover,
.main-navbar .active > a {
  background: #22c55e;
  color: #14532d;
}

.main-navbar a.cta-event {
  background: #22c55e;
  color: #14532d;
  font-weight: 600;
}

.main-navbar .dropdown:hover > a,
.main-navbar .dropdown:focus-within > a {
  background: #22c55e;
  color: #14532d;
}

.main-navbar .dropdown-content {
  display: none;
  position: absolute;
  left: 0;
  top: 100%;
  min-width: 200px;
  background: #fff;
  box-shadow: 0 8px 24px rgba(20, 83, 45, 0.15);
  border-radius: 0 0 10px 10px;
  z-index: 100;
  margin-top: 0.5rem;
}

.main-navbar .dropdown-content a {
  color: #14532d;
  background: #fff;
  border-radius: 0;
  padding: 0.75rem 1.5rem;
}

.main-navbar .dropdown-content a:hover {
  background: #bbf7d0;
  color: #14532d;
}

.main-navbar .dropdown-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 0.5rem 0;
  border: none;
}

.main-navbar .dropdown-sub-item {
  padding-left: 2rem;
  font-size: 0.9rem;
  color: #6b7280;
}

.main-navbar .dropdown:hover .dropdown-content,
.main-navbar .dropdown:focus-within .dropdown-content {
  display: block;
}

.main-navbar .mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  color: #fff;
  padding: 0.5rem;
  cursor: pointer;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
  .top-secondary-menu {
    height: auto;
    padding: 0.75rem 1rem;
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .top-secondary-menu .secondary-links {
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.25rem;
  }
  
  .top-secondary-menu .secondary-links a {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
  }
  
  .top-secondary-menu .top-right-icons {
    gap: 0.5rem;
  }
  
  .top-secondary-menu .top-right-icons a {
    width: 36px;
    height: 36px;
    font-size: 1rem;
  }
  
  .main-navbar {
    flex-direction: column;
    padding: 1rem;
  }
  
  .main-navbar .logo {
    width: 100%;
    justify-content: space-between;
  }
  
  .main-navbar .mobile-menu-btn {
    display: block;
  }
  
  .main-navbar ul {
    display: none;
    flex-direction: column;
    width: 100%;
    margin-top: 1rem;
  }
  
  .main-navbar ul.active {
    display: flex;
  }
  
  .main-navbar li {
    width: 100%;
  }
  
  .main-navbar a {
    width: 100%;
    text-align: left;
  }
  
  .main-navbar .dropdown-content {
    position: static;
    min-width: 100%;
    box-shadow: none;
    border-radius: 0 0 10px 10px;
    margin-top: 0;
  }
}

@media (max-width: 480px) {
  .top-secondary-menu .secondary-links a {
    padding: 0.4rem 0.75rem;
    font-size: 0.8rem;
  }
  
  .top-secondary-menu .secondary-links a i {
    font-size: 0.9rem;
  }
  
  .quick-action-pills {
    margin: 0.5rem auto;
    padding: 0 0.5rem;
  }
  
  .pills-container {
    gap: 0.5rem;
    width: 100%;
    justify-content: center;
  }
  
  .action-pill {
    flex: 1;
    min-width: 0;
  }
  
  .action-pill .pill-accent {
    width: 16px;
  }
  
  .action-pill .pill-content {
    padding: 0.45rem 1rem;
    font-size: 0.8rem;
    justify-content: center;
  }
  
  .action-pill span {
    font-size: 0.75rem;
  }
  
  .action-pill i {
    font-size: 0.85rem;
  }
}
</style>

<?php
require_once __DIR__ . '/../core/site-navigation.php';
require_once __DIR__ . '/../core/include-path.php';

// Get the current request URI for active class comparison
$current_uri = $_SERVER['REQUEST_URI'];
$hub_links = myomr_get_primary_hub_links();

// Helper function to determine if a link is active
function is_active($link, $current_uri) {
    // Remove query strings for comparison
    $current_path = parse_url($current_uri, PHP_URL_PATH);
    $link_path = parse_url($link, PHP_URL_PATH);
    
    // Special case for Explore Places (schools-list.php, restaurants.php)
    if ($link_path === '/omr-listings/index.php') {
        return in_array($current_path, ['/omr-listings/schools-list.php', '/omr-listings/restaurants.php', '/omr-listings/index.php']);
    }

    // Events hub: short URL + legacy module path
    if ($link_path === MYOMR_EVENTS_HUB_PATH . '/') {
        return $current_path === MYOMR_EVENTS_HUB_PATH || $current_path === MYOMR_EVENTS_HUB_PATH . '/'
            || strpos($current_path, MYOMR_EVENTS_HUB_PATH . '/') === 0
            || $current_path === '/omr-local-events' || $current_path === '/omr-local-events/'
            || strpos($current_path, '/omr-local-events/') === 0;
    }
    
    // General case: match the path exactly
    return $current_path === $link_path;
}
?>

<!-- Top Secondary Menu Bar -->
<nav class="top-secondary-menu" role="navigation" aria-label="Secondary">
  <ul class="secondary-links">
    <?php foreach ($hub_links as $hub): ?>
      <?php if (in_array($hub['label'], ['Home', 'Contact', 'News Highlights'], true)) { continue; } ?>
      <li>
        <a href="<?php echo htmlspecialchars($hub['path']); ?>" class="<?php echo is_active($hub['path'], $current_uri) ? 'active' : ''; ?>">
          <?php if ($hub['label'] === 'Jobs'): ?>
            <i class="fas fa-briefcase"></i>
          <?php elseif ($hub['label'] === 'Events'): ?>
            <i class="fas fa-calendar-alt"></i>
          <?php elseif ($hub['label'] === 'Explore Places'): ?>
            <i class="fas fa-compass"></i>
          <?php elseif ($hub['label'] === 'Buy & Sell'): ?>
            <i class="fas fa-shopping-bag"></i>
          <?php elseif ($hub['label'] === 'Elections 2026'): ?>
            <i class="fas fa-vote-yea"></i>
          <?php endif; ?>
          <span><?php echo htmlspecialchars($hub['label']); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
    <li>
      <a href="/omr-hostels-pgs/" class="<?php echo is_active('/omr-hostels-pgs/', $current_uri) ? 'active' : ''; ?>">
        <i class="fas fa-bed"></i>
        <span>Hostels & PGs</span>
      </a>
    </li>
    <li>
      <a href="/omr-coworking-spaces/" class="<?php echo is_active('/omr-coworking-spaces/', $current_uri) ? 'active' : ''; ?>">
        <i class="fas fa-building"></i>
        <span>Coworking Spaces</span>
      </a>
    </li>
    <li>
      <a href="/omr-rent-lease/" class="<?php echo is_active('/omr-rent-lease/', $current_uri) ? 'active' : ''; ?>">
        <i class="fas fa-house"></i>
        <span>Rent & Lease</span>
      </a>
    </li>
  </ul>
  
  <ul class="top-right-icons">
    <li>
      <a href="/" class="<?php echo is_active('/', $current_uri) ? 'active' : ''; ?>" aria-label="Home" title="Home">
        <i class="fas fa-home"></i>
      </a>
    </li>
    <li>
      <a href="tel:+919445088028" aria-label="Call Us" title="Call Us: +91 94450 88028">
        <i class="fas fa-phone"></i>
      </a>
    </li>
    <li>
      <a href="mailto:myomrnews@gmail.com" aria-label="Email Us" title="Email Us: myomrnews@gmail.com">
        <i class="fas fa-envelope"></i>
      </a>
    </li>
  </ul>
</nav>

<!-- Main Navigation Bar -->
<nav class="main-navbar" role="navigation" aria-label="Primary">
  <div class="logo">
    <img src="/My-OMR-Logo.png" alt="MyOMR Logo">
    <span>MyOMR.in</span>
  </div>
  
  <button class="mobile-menu-btn" 
          onclick="toggleMainMenu()"
          aria-label="Toggle navigation menu"
          aria-expanded="false"
          aria-controls="main-menu">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
  </button>
  
  <ul id="main-menu">
    <li class="nav-item"><a class="nav-link <?php echo is_active('/omr-listings/index.php', $current_uri) ? 'active' : ''; ?>" href="/omr-listings/index.php"><i class="fas fa-home mr-1"></i>Explore Places</a></li>
    <li><a href="/discover-myomr/overview.php" class="<?php echo (is_active('/discover-myomr/overview.php', $current_uri) || is_active('/about-myomr-omr-community-portal.php', $current_uri)) ? 'active' : ''; ?>">About My OMR</a></li>
    <li><a href="/local-news/news-highlights-from-omr-road.php" class="<?php echo is_active('/local-news/news-highlights-from-omr-road.php', $current_uri) ? 'active' : ''; ?>">News Highlights</a></li>
    <li><a href="/local-news/image-video-gallery-old-mahabalipuram-road-news.php" class="<?php echo is_active('/local-news/image-video-gallery-old-mahabalipuram-road-news.php', $current_uri) ? 'active' : ''; ?>">Gallery</a></li>
    <li><a href="/elections-2026/" class="<?php echo (is_active('/elections-2026/', $current_uri) || strpos($current_uri, 'elections-2026') !== false) ? 'active' : ''; ?>">Elections 2026</a></li>
    <li class="dropdown">
      <a href="#" tabindex="0" aria-label="Services menu" aria-haspopup="true" aria-expanded="false">Services ▾</a>
      <div class="dropdown-content">
        <a href="/omr-local-job-listings/jobs-hub-omr.php" class="<?php echo (is_active('/omr-local-job-listings/jobs-hub-omr.php', $current_uri) || strpos($current_uri, 'jobs-in-') !== false || strpos($current_uri, 'it-jobs') !== false || strpos($current_uri, 'fresher-jobs') !== false || strpos($current_uri, 'part-time-jobs') !== false) ? 'active' : ''; ?>">Find Jobs in OMR</a>
        <a href="/omr-local-job-listings/" class="<?php echo (is_active('/omr-local-job-listings/', $current_uri) && strpos($current_uri, 'jobs-hub') === false && strpos($current_uri, 'candidate-profile') === false) ? 'active' : ''; ?>">Browse All Jobs</a>
        <a href="/omr-local-job-listings/candidate-profile-omr.php" class="<?php echo strpos($current_uri, 'candidate-profile-omr') !== false ? 'active' : ''; ?>">Résumé &amp; job seeker profile</a>
        <div class="dropdown-divider"></div>
        <a href="/it-jobs-omr-chennai.php" class="dropdown-sub-item">IT Jobs</a>
        <a href="/teaching-jobs-omr-chennai.php" class="dropdown-sub-item">Teaching Jobs</a>
        <a href="/fresher-jobs-omr-chennai.php" class="dropdown-sub-item">Fresher Jobs</a>
        <a href="/part-time-jobs-omr-chennai.php" class="dropdown-sub-item">Part-Time Jobs</a>
        <div class="dropdown-divider"></div>
        <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/" class="<?php echo is_active(MYOMR_EVENTS_HUB_PATH . '/', $current_uri) ? 'active' : ''; ?>">Events</a>
        <a href="/omr-hostels-pgs/" class="<?php echo is_active('/omr-hostels-pgs/', $current_uri) ? 'active' : ''; ?>">Hostels & PGs</a>
        <a href="/omr-coworking-spaces/" class="<?php echo is_active('/omr-coworking-spaces/', $current_uri) ? 'active' : ''; ?>">Coworking Spaces</a>
        <a href="/omr-rent-lease/" class="<?php echo is_active('/omr-rent-lease/', $current_uri) ? 'active' : ''; ?>">Rent & Lease</a>
        <a href="/omr-buy-sell/" class="<?php echo is_active('/omr-buy-sell/', $current_uri) ? 'active' : ''; ?>">Buy & Sell</a>
        <a href="/citizens-charter-old-mahabali-puram-road.php" class="<?php echo is_active('/citizens-charter-old-mahabali-puram-road.php', $current_uri) ? 'active' : ''; ?>">Citizens Charter</a>
      </div>
    </li>
    <li class="dropdown">
      <a href="#" tabindex="0" aria-label="Discover menu" aria-haspopup="true" aria-expanded="false">Discover ▾</a>
      <div class="dropdown-content">
        <a href="/discover-myomr/overview.php" class="<?php echo is_active('/discover-myomr/overview.php', $current_uri) ? 'active' : ''; ?>">Overview</a>
        <a href="/discover-myomr/getting-started.php" class="<?php echo is_active('/discover-myomr/getting-started.php', $current_uri) ? 'active' : ''; ?>">Get Started</a>
        <a href="/discover-myomr/features.php" class="<?php echo is_active('/discover-myomr/features.php', $current_uri) ? 'active' : ''; ?>">Features</a>
        <a href="/discover-myomr/pricing.php" class="<?php echo is_active('/discover-myomr/pricing.php', $current_uri) ? 'active' : ''; ?>">Pricing</a>
        <a href="/discover-myomr/community.php" class="<?php echo is_active('/discover-myomr/community.php', $current_uri) ? 'active' : ''; ?>">Community</a>
        <a href="/discover-myomr/support.php" class="<?php echo is_active('/discover-myomr/support.php', $current_uri) ? 'active' : ''; ?>">Support</a>
        <a href="/discover-myomr/sustainable-development-goals.php" class="<?php echo is_active('/discover-myomr/sustainable-development-goals.php', $current_uri) ? 'active' : ''; ?>">SDGs</a>
      </div>
    </li>
    <li><a href="/contact-my-omr-team.php" class="<?php echo is_active('/contact-my-omr-team.php', $current_uri) ? 'active' : ''; ?>">Contact</a></li>
  </ul>
</nav>

<!-- Quick Action Pills Bar -->
<div class="quick-action-pills">
  <div class="pills-container">
    <a href="/omr-local-job-listings/candidate-profile-omr.php" class="action-pill pill-candidate">
      <div class="pill-accent"></div>
      <div class="pill-content">
        <i class="fas fa-file-upload"></i>
        <span>Résumé &amp; profile</span>
      </div>
    </a>
    <a href="/omr-local-job-listings/employer-landing-omr.php" class="action-pill pill-job">
      <div class="pill-accent"></div>
      <div class="pill-content">
        <i class="fas fa-briefcase"></i>
        <span>List a Job</span>
      </div>
    </a>
    <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/post-event-omr.php" class="action-pill pill-event">
      <div class="pill-accent"></div>
      <div class="pill-content">
        <i class="fas fa-calendar-plus"></i>
        <span>List an Event</span>
      </div>
    </a>
    <a href="/omr-hostels-pgs/owner-login.php" class="action-pill pill-property">
      <div class="pill-accent"></div>
      <div class="pill-content">
        <i class="fas fa-home"></i>
        <span>List a Property</span>
      </div>
    </a>
    <a href="/omr-buy-sell/post-listing-omr.php" class="action-pill pill-buysell">
      <div class="pill-accent"></div>
      <div class="pill-content">
        <i class="fas fa-shopping-bag"></i>
        <span>Post Ad</span>
      </div>
    </a>
  </div>
</div>

<script>
function toggleMainMenu() {
  const menu = document.getElementById('main-menu');
  const button = document.querySelector('.mobile-menu-btn');
  const isExpanded = menu.classList.contains('active');
  
  menu.classList.toggle('active');
  button.setAttribute('aria-expanded', !isExpanded);
}
</script>