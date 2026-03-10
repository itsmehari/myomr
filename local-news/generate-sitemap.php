<?php
/**
 * MyOMR — Local News Sitemap Generator
 * Routes as: /local-news/sitemap.xml  (via .htaccess)
 */
require_once __DIR__ . '/../core/omr-connect.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

$base = 'https://myomr.in/local-news/';

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

// Static local-news section pages
$static = [
    ['loc' => 'https://myomr.in/local-news/', 'priority' => '0.9'],
    ['loc' => 'https://myomr.in/local-news/news-highlights-from-omr-road.php', 'priority' => '0.8'],
];
foreach ($static as $page) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($page['loc'], ENT_XML1) . "</loc>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// Dynamic article pages from database
$stmt = $conn->prepare(
    "SELECT slug, published_date, updated_at, title, category
     FROM articles
     WHERE status = 'published'
     ORDER BY published_date DESC
     LIMIT 1000"
);

if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $loc     = $base . $row['slug'];
        $lastmod = !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at']))
                                              : date('Y-m-d', strtotime($row['published_date']));
        $pubDate = date('Y-m-d', strtotime($row['published_date']));
        $title   = htmlspecialchars($row['title'] ?? '', ENT_XML1);
        $genre   = htmlspecialchars($row['category'] ?? 'General', ENT_XML1);

        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
        echo "    <lastmod>{$lastmod}</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        // Google News sitemap extension (only for articles < 2 days old is strictly required,
        // but including for all lets Google re-evaluate freshness)
        echo "    <news:news>\n";
        echo "      <news:publication>\n";
        echo "        <news:name>MyOMR Local News</news:name>\n";
        echo "        <news:language>en</news:language>\n";
        echo "      </news:publication>\n";
        echo "      <news:publication_date>{$pubDate}</news:publication_date>\n";
        echo "      <news:title>{$title}</news:title>\n";
        echo "      <news:genres>{$genre}</news:genres>\n";
        echo "    </news:news>\n";
        echo "  </url>\n";
    }
    $stmt->close();
}

echo "</urlset>\n";
