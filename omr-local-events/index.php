<?php
// Events Listing - MyOMR
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';
include __DIR__ . '/includes/dev-diagnostics.php';

$filters = [
  'search' => isset($_GET['search']) ? sanitizeInput($_GET['search']) : '',
  'category' => isset($_GET['category']) ? (int)$_GET['category'] : 0,
  'locality' => isset($_GET['locality']) ? sanitizeInput($_GET['locality']) : '',
  'is_free' => isset($_GET['is_free']) ? $_GET['is_free'] : '',
  'date_from' => isset($_GET['date_from']) ? sanitizeInput($_GET['date_from']) : '',
  'date_to' => isset($_GET['date_to']) ? sanitizeInput($_GET['date_to']) : ''
];

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

$categories = getEventCategories();
$events = getEvents($filters, $per_page, $offset);
$total = getEventCount($filters);
$pages = max(1, (int)ceil($total / $per_page));

// SEO
$page_title = 'Events in OMR Chennai – Local Community & Happenings | MyOMR';
$page_description = 'Discover upcoming events across OMR: community, education, sports, arts, networking and more. Submit your event and reach local residents.';
$canonical_url = 'https://myomr.in/omr-local-events/';
$og_title            = $page_title;
$og_description      = $page_description;
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = $canonical_url;
$twitter_title       = $page_title;
$twitter_description = $page_description;
$twitter_image       = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php $breadcrumbs = [
    ['https://myomr.in/','Home'],
    ['https://myomr.in/omr-local-events/','Events']
  ]; ?>
  <?php include __DIR__ . '/../components/meta.php'; ?>
  <?php if (!empty($_GET) && (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['locality']) || isset($_GET['is_free']) || isset($_GET['date_from']) || isset($_GET['date_to']))): ?>
  <meta name="robots" content="noindex, follow" />
  <?php endif; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/omr-jobs-unified-design.css" />
  <link rel="stylesheet" href="../omr-local-job-listings/assets/post-job-form-modern.css" />
  <link rel="stylesheet" href="assets/events-dashboard.css" />
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <?php if (!empty($filters['search'])): ?>
  <script>
  (function() {
    if (typeof gtag !== 'function') return;
    gtag('event', 'search', {
      'search_term': <?= json_encode($filters['search'], _GA_JSON_FLAGS) ?>,
      'locality':    <?= json_encode($filters['locality'] ?? '', _GA_JSON_FLAGS) ?>
    });
  })();
  </script>
  <?php endif; ?>
  <?php $orgSchema = __DIR__ . '/../components/organization-schema.php'; if (file_exists($orgSchema)) { include $orgSchema; } ?>
</head>
<body class="modern-page">
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2 mb-md-0">
      <h1 class="title mb-0" style="font-size:inherit;font-weight:inherit;color:inherit;">Events in OMR</h1>
      <div class="small opacity-90">Find and share happenings around Old Mahabalipuram Road</div>
    </div>
    <div class="dashboard-actions">
      <a href="post-event-omr.php" class="btn-modern btn-modern-primary">
        <i class="fas fa-plus"></i><span>List an Event</span>
      </a>
      <a href="index.php" class="btn-modern btn-modern-secondary">
        <i class="fas fa-rotate"></i><span>Refresh</span>
      </a>
    </div>
  </div>
</div>

<main class="py-5">
  <div class="container">
    <!-- Filters -->
    <form class="card-modern mb-4 dashboard-toolbar" method="get">
      <div class="">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label-modern">Search</label>
            <input type="text" class="form-control-modern" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>" placeholder="Search by title or location" />
          </div>
          <div class="col-md-3">
            <label class="form-label-modern">Category</label>
            <select name="category" class="form-select-modern">
              <option value="">All</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?php echo (int)$cat['id']; ?>" <?php echo ((int)$filters['category'] === (int)$cat['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($cat['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label-modern">Locality</label>
            <input type="text" class="form-control-modern" name="locality" value="<?php echo htmlspecialchars($filters['locality']); ?>" placeholder="e.g., Sholinganallur" />
          </div>
          <div class="col-md-2">
            <label class="form-label-modern">Free</label>
            <select name="is_free" class="form-select-modern">
              <option value="">Any</option>
              <option value="1" <?php echo ($filters['is_free'] === '1') ? 'selected' : ''; ?>>Free</option>
              <option value="0" <?php echo ($filters['is_free'] === '0') ? 'selected' : ''; ?>>Paid</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label-modern">From</label>
            <input type="date" class="form-control-modern" name="date_from" value="<?php echo htmlspecialchars($filters['date_from']); ?>" />
          </div>
          <div class="col-md-2">
            <label class="form-label-modern">To</label>
            <input type="date" class="form-control-modern" name="date_to" value="<?php echo htmlspecialchars($filters['date_to']); ?>" />
          </div>
        </div>
        <div class="mt-3 d-flex gap-3">
          <button class="btn-modern btn-modern-primary" type="submit"><i class="fas fa-filter"></i><span>Apply Filters</span></button>
          <a class="btn-modern btn-modern-secondary" href="index.php"><i class="fas fa-rotate-left"></i><span>Reset</span></a>
        </div>
        <div class="mt-2 d-flex gap-2 flex-wrap">
          <?php
            // Quick pills for ranges
            $base = $_GET; unset($base['page']);
            $today = date('Y-m-d');
            $weekend_start = date('Y-m-d', strtotime('saturday this week'));
            $weekend_end = date('Y-m-d', strtotime('sunday this week'));
            $month_start = date('Y-m-01');
            $month_end = date('Y-m-t');
            if (!function_exists('pillUrl')) {
              function pillUrl($from, $to, $base) {
                $base['date_from'] = $from;
                $base['date_to'] = $to;
                return '?' . htmlspecialchars(http_build_query($base));
              }
            }
          ?>
          <a class="btn btn-sm btn-outline-secondary" href="<?php echo pillUrl($today, $today, $base); ?>">Today</a>
          <a class="btn btn-sm btn-outline-secondary" href="<?php echo pillUrl($weekend_start, $weekend_end, $base); ?>">This Weekend</a>
          <a class="btn btn-sm btn-outline-secondary" href="<?php echo pillUrl($month_start, $month_end, $base); ?>">This Month</a>
          <a class="btn btn-sm btn-link text-decoration-underline" href="/local-news/this-weekend-in-omr.php?utm_source=events&utm_medium=internal&utm_campaign=weekend_roundup_link">Weekend Roundup</a>
        </div>
      </div>
    </form>

    <!-- Results -->
    <div class="row">
      <?php if (count($events) === 0): ?>
        <div class="col-12">
          <div class="alert-modern">No events found. Try different filters or <a href="post-event-omr.php">list an event</a>.</div>
        </div>
      <?php else: ?>
        <?php foreach ($events as $ev): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="job-card-modern h-100 dashboard-card">
              <div class="job-header">
                <div class="job-title"><?php echo htmlspecialchars($ev['title']); ?></div>
                <div class="company-name"><?php echo htmlspecialchars($ev['location']); ?><?php echo $ev['locality'] ? ' • ' . htmlspecialchars($ev['locality']) : ''; ?></div>
                <div class="job-meta">
                  <span class="badge-modern badge-modern-success"><i class="far fa-calendar"></i><?php echo date('M d, Y g:i a', strtotime($ev['start_datetime'])); ?></span>
                  <?php if (!empty($ev['featured'])): ?>
                    <span class="badge-modern badge-featured"><i class="fas fa-star"></i> Featured</span>
                  <?php endif; ?>
                  <?php if ($ev['is_free']): ?><span class="badge-modern badge-modern-primary">Free</span><?php endif; ?>
                  <?php if (!empty($ev['locality'])): ?>
                    <?php $locSlug = localityToSlug($ev['locality']); ?>
                    <a class="badge-modern badge-modern-secondary" href="locality.php?slug=<?php echo urlencode($locSlug); ?>">#<?php echo htmlspecialchars($ev['locality']); ?></a>
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <a href="event/<?php echo urlencode($ev['slug']); ?>" class="btn-modern btn-modern-secondary" data-analytics="viewClicked" data-analytics-label="<?php echo htmlspecialchars($ev['slug']); ?>"><i class="fas fa-eye"></i><span>View</span></a>
                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($ev['location'] . ' ' . ($ev['locality'] ?? 'OMR Chennai')); ?>" target="_blank" class="btn-modern btn-modern-secondary" data-analytics="mapClicked" data-analytics-label="<?php echo htmlspecialchars($ev['slug']); ?>"><i class="fas fa-map-marker-alt"></i><span>Map</span></a>
                <a href="post-event-omr.php" class="btn-modern btn-modern-primary"><i class="fas fa-plus"></i><span>List Event</span></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
      <nav class="mt-4" aria-label="Event pages">
        <ul class="pagination justify-content-center">
          <?php for ($p = 1; $p <= $pages; $p++): ?>
            <li class="page-item <?php echo ($p === $page) ? 'active' : ''; ?>">
              <a class="page-link" href="?<?php 
                $q = $_GET; $q['page'] = $p; echo htmlspecialchars(http_build_query($q));
              ?>"><?php echo $p; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</main>

<section class="py-4 bg-light">
  <div class="container d-flex justify-content-center">
    <?php include __DIR__ . '/components/newsletter-signup.php'; ?>
  </div>
  <div class="container mt-3 text-center">
    <small>Quick links: <a href="today.php">Today</a> · <a href="weekend.php">This Weekend</a> · <a href="month.php">This Month</a></small>
  </div>
</section>

<?php
// JSON-LD for listing page – emit Event objects for current page
if (!empty($events)) {
  $ld = [];
  foreach ($events as $ev) {
    $event = [
      '@context' => 'https://schema.org',
      '@type' => 'Event',
      'name' => $ev['title'],
      'startDate' => date('c', strtotime($ev['start_datetime'])),
      'eventStatus' => 'https://schema.org/EventScheduled',
      'isAccessibleForFree' => (bool)$ev['is_free'],
      'location' => [
        '@type' => 'Place',
        'name' => $ev['location'],
        'address' => [
          '@type' => 'PostalAddress',
          'addressLocality' => $ev['locality'] ?: 'OMR, Chennai',
          'addressRegion' => 'Tamil Nadu',
          'addressCountry' => 'IN'
        ]
      ],
      'url' => 'https://myomr.in/omr-local-events/event-detail-omr.php?slug=' . urlencode($ev['slug'])
    ];
    if (!empty($ev['end_datetime'])) {
      $event['endDate'] = date('c', strtotime($ev['end_datetime']));
    }
    if (!empty($ev['image_url'])) {
      $event['image'] = $ev['image_url'];
    }
    if (!$ev['is_free'] && !empty($ev['price'])) {
      $event['offers'] = [
        '@type' => 'Offer',
        'price' => $ev['price'],
        'priceCurrency' => 'INR',
        'url' => 'https://myomr.in/omr-local-events/event-detail-omr.php?slug=' . urlencode($ev['slug'])
      ];
    }
    $ld[] = $event;
  }
  echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}
?>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/events-analytics.js"></script>

<!-- UN SDG Floating Badges -->
<?php include __DIR__ . '/../components/sdg-badge.php'; ?>

</body>
</html>


