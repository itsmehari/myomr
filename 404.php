<?php
/**
 * MyOMR — Custom 404 Page
 * Sends HTTP 404 status, tracks the broken URL in GA4, and gives users helpful links.
 */
http_response_code(404);

$missed_url = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8');
$referrer   = htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '', ENT_QUOTES, 'UTF-8');

$ga_content_group = '404 Errors';
include __DIR__ . '/components/analytics.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found | MyOMR</title>
  <meta name="robots" content="noindex, nofollow">
  <?php /* GA analytics.php already included above — output 404 event once gtag loads */ ?>
  <script>
  (function poll() {
    if (typeof gtag === 'function') {
      gtag('event', 'page_not_found', {
        'page_location': <?= json_encode('https://' . ($_SERVER['HTTP_HOST'] ?? 'myomr.in') . ($missed_url), _GA_JSON_FLAGS) ?>,
        'referrer':      <?= json_encode($referrer, _GA_JSON_FLAGS) ?>
      });
    } else {
      setTimeout(poll, 200);
    }
  })();
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #f8fafc; font-family: 'Poppins', sans-serif; }
    .err-hero { min-height: 70vh; display: flex; align-items: center; justify-content: center; text-align: center; }
    .err-code { font-size: 7rem; font-weight: 800; color: #22c55e; line-height: 1; }
    .err-title { font-size: 1.6rem; font-weight: 700; color: #1e293b; margin: .5rem 0; }
    .err-sub   { color: #64748b; margin-bottom: 2rem; }
    .err-links a { margin: .3rem; }
    .badge-url { font-size: .78rem; background: #f1f5f9; color: #475569; border-radius: 6px; padding: .25rem .6rem; word-break: break-all; display: inline-block; margin-top: .5rem; }
  </style>
</head>
<body>
<?php include __DIR__ . '/components/main-nav.php'; ?>

<div class="err-hero">
  <div class="container">
    <div class="err-code"><i class="fas fa-map-signs" style="font-size:4rem;opacity:.2;display:block;margin-bottom:.5rem"></i>404</div>
    <h1 class="err-title">Page not found on OMR!</h1>
    <p class="err-sub">The page you're looking for may have moved, been renamed, or no longer exists.</p>
    <p class="badge-url"><?= $missed_url ?></p>
    <!-- Search form for user recovery -->
    <form action="/omr-local-job-listings/" method="get"
          class="d-flex gap-2 mt-4 justify-content-center flex-wrap"
          onsubmit="if(typeof gtag==='function') gtag('event','404_search',{'page_location':window.location.href});">
      <input type="text" name="search" class="form-control"
             placeholder="Search jobs, events, businesses in OMR…"
             style="max-width:340px;border-radius:8px;">
      <button type="submit" class="btn btn-success fw-semibold" style="border-radius:8px;">
        <i class="fas fa-search me-1"></i> Search
      </button>
    </form>
    <div class="err-links mt-4">
      <a href="/" class="btn btn-success"><i class="fas fa-home me-1"></i> Go Home</a>
      <a href="/omr-local-job-listings/" class="btn btn-outline-secondary"><i class="fas fa-briefcase me-1"></i> Jobs</a>
      <a href="/omr-local-events/" class="btn btn-outline-secondary"><i class="fas fa-calendar me-1"></i> Events</a>
      <a href="/omr-listings/" class="btn btn-outline-secondary"><i class="fas fa-list me-1"></i> Directory</a>
      <a href="/local-news/" class="btn btn-outline-secondary"><i class="fas fa-newspaper me-1"></i> News</a>
    </div>
  </div>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
