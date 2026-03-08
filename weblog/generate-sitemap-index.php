<?php
// Root sitemap index for MyOMR (aggregates module sitemaps)
header('Content-Type: application/xml; charset=utf-8');

$base = 'https://myomr.in';

$sitemaps = [
  $base . '/omr-local-events/sitemap.xml',
  $base . '/omr-listings/sitemap.xml',
  $base . '/omr-local-job-listings/sitemap.xml',
  $base . '/omr-hostels-pgs/sitemap.xml',
  $base . '/omr-coworking-spaces/sitemap.xml',
  $base . '/pentahive/sitemap.xml',
  $base . '/election-blo-details/sitemap.xml',
  $base . '/local-news/sitemap.xml',
];

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($sitemaps as $s) {
  echo "  <sitemap><loc>" . htmlspecialchars($s, ENT_XML1) . "</loc></sitemap>\n";
}
echo "</sitemapindex>\n";


