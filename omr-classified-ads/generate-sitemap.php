<?php
/**
 * OMR Classified Ads — sitemap
 */
define('SITEMAP_REQUEST', true);
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/omr-localities-classified-ads.php';
require_once __DIR__ . '/includes/listing-functions.php';

$base_url = 'https://myomr.in';

$check = $conn->query("SHOW TABLES LIKE 'omr_classified_ads_listings'");
$listings = [];
if ($check && $check->num_rows > 0) {
    $r = $conn->query("SELECT id, title, updated_at FROM omr_classified_ads_listings WHERE status = 'approved' ORDER BY updated_at DESC");
    $listings = $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}

$categories = getClassifiedAdsCategories();
$localities_raw = getClassifiedAdsLocalities();
$localities = array_map('classifiedAdsLocalityToSlug', $localities_raw);

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$xml .= '  <url><loc>' . $base_url . '/omr-classified-ads/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
$xml .= '  <url><loc>' . $base_url . '/omr-classified-ads/guidelines.php</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>' . "\n";

foreach ($categories as $c) {
    $xml .= '  <url><loc>' . $base_url . '/omr-classified-ads/category/' . htmlspecialchars($c['slug']) . '/</loc><changefreq>daily</changefreq><priority>0.9</priority></url>' . "\n";
}
foreach ($localities as $loc) {
    $xml .= '  <url><loc>' . $base_url . '/omr-classified-ads/locality/' . htmlspecialchars($loc) . '/</loc><changefreq>daily</changefreq><priority>0.85</priority></url>' . "\n";
}

foreach ($listings as $row) {
    $xml .= '  <url><loc>' . htmlspecialchars(getClassifiedAdsDetailUrl((int) $row['id'], $row['title'] ?? null)) . '</loc><lastmod>' . date('Y-m-d', strtotime($row['updated_at'])) . '</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
}

$xml .= '</urlset>';

if (php_sapi_name() === 'cli') {
    file_put_contents(__DIR__ . '/sitemap.xml', $xml);
    echo "Classified ads sitemap: " . (count($listings) + 2 + count($categories) + count($localities)) . " URLs\n";
    exit;
}

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');
echo $xml;
