<?php
/**
 * Elections 2026 sitemap – list of subsite URLs for Search Console.
 * Access as /elections-2026/sitemap.xml (or run via cron and save; or .htaccess rewrite).
 */
header('Content-Type: application/xml; charset=utf-8');

$base = 'https://myomr.in/elections-2026';
$today = date('Y-m-d');

$urls = [
    ['loc' => $base . '/', 'lastmod' => $today, 'priority' => '1.0'],
    ['loc' => $base . '/know-your-constituency.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/find-blo.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/dates.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/how-to-vote.php', 'lastmod' => $today, 'priority' => '0.8'],
    ['loc' => $base . '/faq.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/candidates.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/news.php', 'lastmod' => $today, 'priority' => '0.8'],
    ['loc' => $base . '/announcements.php', 'lastmod' => $today, 'priority' => '0.8'],
    ['loc' => $base . '/newsletter.php', 'lastmod' => $today, 'priority' => '0.7'],
    ['loc' => $base . '/index-tamil.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/quiz.php', 'lastmod' => $today, 'priority' => '0.8'],
    ['loc' => $base . '/results-2026.php', 'lastmod' => $today, 'priority' => '0.8'],
    ['loc' => $base . '/constituency/sholinganallur.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/constituency/velachery.php', 'lastmod' => $today, 'priority' => '0.9'],
    ['loc' => $base . '/constituency/thiruporur.php', 'lastmod' => $today, 'priority' => '0.9'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo '  <url><loc>' . htmlspecialchars($u['loc']) . '</loc><lastmod>' . $u['lastmod'] . '</lastmod><priority>' . $u['priority'] . '</priority></url>' . "\n";
}
echo '</urlset>';
