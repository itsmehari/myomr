<?php
/**
 * MyOMR — Master Sitemap Index Generator
 * Served at https://myomr.in/sitemap.xml via .htaccess rewrite.
 *
 * Lists all module sitemaps so Google can crawl the entire site from one entry point.
 * To add a new module: append a $sitemaps[] entry below.
 */

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

$base    = 'https://myomr.in';
$today   = date('Y-m-d');

$sitemaps = [
    // Static pages (core, info, discover-myomr, jobs, etc.)
    ['loc' => $base . '/sitemap-pages.xml',                                    'lastmod' => '2026-03-06'],

    // Info sub-section sitemap
    ['loc' => $base . '/info/sitemap.xml',                                     'lastmod' => '2025-03-08'],

    // Local News articles (dynamic – generated from DB)
    ['loc' => $base . '/local-news/sitemap.xml',                               'lastmod' => $today],

    // OMR Listings: schools, hospitals, banks, parks, industries, ATMs, etc.
    ['loc' => $base . '/omr-listings/sitemap.xml',                             'lastmod' => $today],

    // IT Parks detail pages
    ['loc' => $base . '/it-parks/sitemap.xml',                                 'lastmod' => $today],

    // Local Events
    ['loc' => $base . '/omr-local-events/sitemap.xml',                        'lastmod' => $today],

    // Job Portal
    ['loc' => $base . '/omr-local-job-listings/sitemap.xml',                  'lastmod' => $today],

    // Hostels & PGs
    ['loc' => $base . '/omr-hostels-pgs/sitemap.xml',                         'lastmod' => $today],

    // Coworking Spaces
    ['loc' => $base . '/omr-coworking-spaces/sitemap.xml',                    'lastmod' => $today],

    // Pentahive
    ['loc' => $base . '/pentahive/sitemap.xml',                               'lastmod' => $today],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($sitemaps as $s) {
    echo "  <sitemap>\n";
    echo '    <loc>' . htmlspecialchars($s['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . "</loc>\n";
    echo '    <lastmod>' . $s['lastmod'] . "</lastmod>\n";
    echo "  </sitemap>\n";
}

echo '</sitemapindex>' . "\n";
