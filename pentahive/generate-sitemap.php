<?php
/**
 * Generate Sitemap for Pentahive Website Maintenance Service
 * URL: /pentahive/sitemap.xml
 */

header('Content-Type: application/xml; charset=utf-8');

$base_url = 'https://myomr.in';

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Static pages (using clean URLs only - canonical versions)
$pages = [
    // Primary landing page
    ['loc' => '/pentahive/', 'priority' => '1.0', 'changefreq' => 'weekly'],
    // Traffic source variants (clean URLs)
    // Note: These map to actual files via .htaccess
    ['loc' => '/pentahive/google-ads', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/pentahive/social-media', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/pentahive/myomr-internal', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/pentahive/website-maintenance-service-chennai-omr', 'priority' => '0.9', 'changefreq' => 'weekly'],
];

// Output pages
foreach ($pages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($base_url . $page['loc'], ENT_XML1) . "</loc>\n";
    echo "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
    echo "  </url>\n";
}

echo '</urlset>';

?>

