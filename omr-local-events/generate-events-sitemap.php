<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';

header('Content-Type: application/xml; charset=utf-8');

$urls = [];
// Listing page
$urls[] = 'https://myomr.in/omr-local-events/';
// Date intent hubs
$urls[] = 'https://myomr.in/omr-local-events/today';
$urls[] = 'https://myomr.in/omr-local-events/weekend';
$urls[] = 'https://myomr.in/omr-local-events/month';

// Pull approved events
try {
  global $conn;
  if ($conn && !$conn->connect_error) {
    $res = $conn->query("SELECT slug, updated_at FROM event_listings WHERE status IN ('scheduled','ongoing') ORDER BY updated_at DESC LIMIT 1000");
    while ($row = $res->fetch_assoc()) {
      $urls[] = 'https://myomr.in/omr-local-events/event/' . urlencode($row['slug']);
    }

    // Category hubs
    $res = $conn->query("SELECT slug FROM event_categories WHERE is_active = 1 ORDER BY display_order, name LIMIT 100");
    while ($row = $res->fetch_assoc()) {
      $urls[] = 'https://myomr.in/omr-local-events/category/' . urlencode($row['slug']);
    }

    // Locality hubs (top 30 distinct localities)
    $res = $conn->query("SELECT DISTINCT locality FROM event_listings WHERE locality IS NOT NULL AND locality <> '' ORDER BY locality ASC LIMIT 30");
    while ($row = $res->fetch_assoc()) {
      $slug = strtolower(trim(preg_replace('/\s+/', '-', $row['locality'])));
      $urls[] = 'https://myomr.in/omr-local-events/locality/' . urlencode($slug);
    }

    // Venue hubs (top 50 distinct locations)
    $res = $conn->query("SELECT DISTINCT location FROM event_listings WHERE location IS NOT NULL AND location <> '' ORDER BY location ASC LIMIT 50");
    while ($row = $res->fetch_assoc()) {
      $vslug = strtolower(trim(preg_replace('/[^a-z0-9\s-]/', '', $row['location'])));
      $vslug = trim(preg_replace('/[\s-]+/', '-', $vslug), '-');
      $urls[] = 'https://myomr.in/omr-local-events/venue/' . urlencode($vslug);
    }
  }
} catch (Throwable $e) {
  error_log('Events: sitemap generation failed: ' . $e->getMessage());
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $u) {
  echo "  <url><loc>" . htmlspecialchars($u, ENT_XML1) . "</loc></url>\n";
}
echo "</urlset>\n";


