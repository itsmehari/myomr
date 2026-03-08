<?php
/**
 * Election BLO Sitemap Generator
 * Generates sitemap for Election BLO search pages
 */

require_once __DIR__ . '/../core/omr-connect.php';

header('Content-Type: application/xml; charset=utf-8');

$base_url = 'https://myomr.in';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Static pages
$static_pages = [
    '/info/find-blo-officer.php' => date('Y-m-d'),
    // Add more static pages as they are created
    // '/info/find-your-blo-officer-omr.php' => date('Y-m-d'),
];

// Output static pages
foreach ($static_pages as $url => $lastmod) {
    echo "\n  <url>\n";
    echo "    <loc>$base_url$url</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>";
}

// Dynamic BLO detail pages (if we create individual pages for each BLO)
// This can be enabled when individual BLO detail pages are created
/*
if ($conn && !$conn->connect_error) {
    try {
        $sql = "SELECT id, part_no, location, updated_at 
                FROM omr_election_blo 
                ORDER BY part_no ASC 
                LIMIT 1000";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = (int)$row['id'];
                $part_no = (int)$row['part_no'];
                $location = urlencode(strtolower(str_replace(' ', '-', $row['location'])));
                $lastmod = date('Y-m-d', strtotime($row['updated_at']));
                
                // Example URL structure: /info/election-blo/part-123-perungudi
                $url = $base_url . "/info/election-blo/part-$part_no-$location";
                
                echo "\n  <url>\n";
                echo "    <loc>$url</loc>\n";
                echo "    <lastmod>$lastmod</lastmod>\n";
                echo "    <changefreq>monthly</changefreq>\n";
                echo "    <priority>0.6</priority>\n";
                echo "  </url>";
            }
        }
    } catch (Exception $e) {
        // Silently handle errors - don't break sitemap generation
    }
}
*/

echo "\n</urlset>";

