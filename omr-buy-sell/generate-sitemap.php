<?php
/**
 * OMR Buy-Sell — Sitemap Generator
 */
define('SITEMAP_REQUEST', true);
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/omr-localities-buy-sell.php';
require_once __DIR__ . '/includes/listing-functions.php';

$base_url = 'https://myomr.in';

$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_listings'");
$listings = [];
if ($check && $check->num_rows > 0) {
    $r = $conn->query("SELECT id, title, updated_at FROM omr_buy_sell_listings WHERE status = 'approved' ORDER BY updated_at DESC");
    $listings = $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}

$categories = getBuySellCategories();
$localities_raw = getBuySellLocalities();
$localities = array_map(function ($loc) {
    return strtolower(preg_replace('/\s+/', '-', $loc));
}, $localities_raw);

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$xml .= '  <url><loc>' . $base_url . '/omr-buy-sell/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
$xml .= '  <url><loc>' . $base_url . '/omr-buy-sell/post-listing-omr.php</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";

foreach ($categories as $c) {
    $xml .= '  <url><loc>' . $base_url . '/omr-buy-sell/category/' . htmlspecialchars($c['slug']) . '/</loc><changefreq>daily</changefreq><priority>0.9</priority></url>' . "\n";
}
foreach ($localities as $loc) {
    $xml .= '  <url><loc>' . $base_url . '/omr-buy-sell/locality/' . $loc . '/</loc><changefreq>daily</changefreq><priority>0.9</priority></url>' . "\n";
}

foreach ($listings as $job) {
    $xml .= '  <url><loc>' . htmlspecialchars(getListingDetailUrl((int)$job['id'], $job['title'] ?? null)) . '</loc><lastmod>' . date('Y-m-d', strtotime($job['updated_at'])) . '</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
}

$xml .= '</urlset>';

if (php_sapi_name() === 'cli') {
    file_put_contents(__DIR__ . '/sitemap.xml', $xml);
    echo "Buy-Sell sitemap generated: " . (count($listings) + 2 + count($categories) + count($localities)) . " URLs\n";
    exit;
}

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');
echo $xml;
