<?php
/**
 * Articles Sitemap – all published news articles (local-news/*)
 * URL: https://myomr.in/weblog/articles-sitemap.xml (or via rewrite)
 * Include this in sitemap index so search engines and AI crawlers discover new articles quickly.
 */

header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');

require_once __DIR__ . '/../core/omr-connect.php';

$base_url = 'https://myomr.in';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

if ($conn) {
    $sql = "SELECT slug, published_date, updated_at FROM articles WHERE status = 'published' AND slug NOT LIKE '%-tamil' ORDER BY published_date DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $slug = htmlspecialchars($row['slug']);
            $lastmod = date('Y-m-d', strtotime($row['updated_at'] ? $row['updated_at'] : $row['published_date']));
            echo "\n  <url>\n";
            echo "    <loc>" . $base_url . '/local-news/' . htmlspecialchars($row['slug'], ENT_XML1) . "</loc>\n";
            echo "    <lastmod>" . $lastmod . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.9</priority>\n";
            echo "  </url>";
        }
    }
    $conn->close();
}

echo "\n</urlset>";
