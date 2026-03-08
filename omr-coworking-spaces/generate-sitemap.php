<?php
/**
 * Generate XML Sitemap for Coworking Spaces
 * Run this script via cron or manual execution
 */

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Base URL
$base_url = 'https://www.myomr.in/omr-coworking-spaces/';

// Start XML
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Homepage
echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($base_url . 'index.php') . "</loc>\n";
echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "    <priority>1.0</priority>\n";
echo "  </url>\n";

// Get all active spaces
$query = "SELECT slug, updated_at FROM coworking_spaces WHERE status = 'active' ORDER BY updated_at DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($base_url . 'space-detail.php?slug=' . urlencode($row['slug'])) . "</loc>\n";
        echo "    <lastmod>" . date('Y-m-d', strtotime($row['updated_at'])) . "</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.6</priority>\n";
        echo "  </url>\n";
    }
}

echo '</urlset>';

// Optionally save to file
file_put_contents(__DIR__ . '/sitemap.xml', ob_get_contents());

?>

