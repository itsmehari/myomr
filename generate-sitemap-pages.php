<?php
/**
 * MyOMR — Static/Core Pages Sitemap
 * Served at https://myomr.in/sitemap-pages.xml via .htaccess rewrite.
 *
 * Lists core static pages: homepage, discover-myomr, info, contact, modules.
 * Excludes: job landing pages (omr-local-job-listings sitemap), listings (omr-listings sitemap),
 * events (omr-local-events sitemap), articles (local-news sitemap).
 *
 * Each URL must match the page's canonical tag.
 */

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

$base  = 'https://myomr.in';
$today = date('Y-m-d');

$pages = [
    // Homepage (canonical: /)
    ['loc' => $base . '/', 'priority' => '1.0', 'changefreq' => 'daily'],

    // Contact
    ['loc' => $base . '/contact-my-omr-team.php', 'priority' => '0.7', 'changefreq' => 'monthly'],

    // Digital marketing
    ['loc' => $base . '/digital-marketing-landing.php', 'priority' => '0.8', 'changefreq' => 'monthly'],

    // WhatsApp community (SEO landing)
    ['loc' => $base . '/join-omr-whatsapp-group-myomr.php', 'priority' => '0.85', 'changefreq' => 'weekly'],

    // Rent & Lease
    ['loc' => $base . '/omr-rent-lease/', 'priority' => '0.9', 'changefreq' => 'weekly'],

    // Discover MyOMR
    ['loc' => $base . '/discover-myomr/overview.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/areas-covered.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/pricing.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/getting-started.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/support.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/community.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/features.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/it-parks-in-omr.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/it-careers-omr.php', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['loc' => $base . '/discover-myomr/it-corridor-jobs-omr.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/omr-job-search-guide-myomr.php', 'priority' => '0.75', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/fresher-resume-checklist-omr-chennai-myomr.php', 'priority' => '0.75', 'changefreq' => 'monthly'],
    ['loc' => $base . '/discover-myomr/job-seeker-profile-walkthrough-myomr.php', 'priority' => '0.8', 'changefreq' => 'monthly'],

    // Info
    ['loc' => $base . '/info/citizens-charter-old-mahabali-puram-road.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base . '/info/pallikaranai-marsh-ramsar-wetland.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base . '/info/report-civic-issue-omr.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base . '/info/find-blo-officer.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base . '/info/website-privacy-policy-of-my-omr.php', 'priority' => '0.5', 'changefreq' => 'yearly'],

    // Local news static pages
    ['loc' => $base . '/local-news/news-highlights-from-omr-road.php', 'priority' => '0.8', 'changefreq' => 'daily'],
    ['loc' => $base . '/local-news/omr-news-list-of-areas-covered.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base . '/local-news/this-weekend-in-omr.php', 'priority' => '0.7', 'changefreq' => 'weekly'],

    // Module indexes (detail pages in respective sitemaps)
    ['loc' => $base . '/omr-hostels-pgs/', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => $base . '/omr-coworking-spaces/', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['loc' => $base . '/local-events/', 'priority' => '0.9', 'changefreq' => 'daily'],
    ['loc' => $base . '/local-news/', 'priority' => '0.9', 'changefreq' => 'daily'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$xmlFlags = defined('ENT_XML1') ? (ENT_XML1 | ENT_QUOTES) : ENT_QUOTES;
foreach ($pages as $p) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($p['loc'] ?? '', $xmlFlags, 'UTF-8') . "</loc>\n";
    echo '    <lastmod>' . $today . "</lastmod>\n";
    echo '    <changefreq>' . htmlspecialchars($p['changefreq']) . "</changefreq>\n";
    echo '    <priority>' . htmlspecialchars($p['priority']) . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>' . "\n";
