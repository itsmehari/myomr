<?php
/**
 * Dynamic Sitemap Generator
 * Auto-generates sitemap.xml from database and static pages
 */

require_once 'core/omr-connect.php';

header('Content-Type: application/xml; charset=utf-8');

// Base URL
$base_url = 'https://myomr.in';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
?>
  <?php
  // Static pages (always included)
  $static_pages = [
    '/' => '2025-01-01',
    '/index.php' => '2025-01-01',
    '/omr-road-social-media.php' => '2025-01-01',
    '/contact-my-omr-team.php' => '2025-01-01',
    '/website-privacy-policy-of-my-omr.php' => '2025-01-01',
    '/general-data-policy-of-my-omr.php' => '2025-01-01',
    '/terms-and-conditions-my-omr.php' => '2025-01-01',
    '/webmaster-contact-my-omr.php' => '2025-01-01',
    '/local-news/news-highlights-from-omr-road.php' => '2025-01-01',
    '/about-myomr-omr-community-portal.php' => '2025-01-01',
    '/local-news/omr-news-list-of-areas-covered.php' => '2025-01-01',
    '/discover-myomr/overview.php' => '2025-01-01',
    '/discover-myomr/areas-covered.php' => '2025-01-01',
    '/discover-myomr/pricing.php' => '2025-01-01',
    '/discover-myomr/getting-started.php' => '2025-01-01',
    '/discover-myomr/support.php' => '2025-01-01',
    '/discover-myomr/community.php' => '2025-01-01',
    '/discover-myomr/features.php' => '2025-01-01',
    '/discover-myomr/sustainable-development-goals.php' => '2025-01-01',
    '/discover-myomr/it-parks-in-omr.php' => '2025-01-01',
    '/discover-myomr/sdg-education-schools.php' => '2025-01-01',
    '/info/pallikaranai-marsh-ramsar-wetland.php' => '2025-10-27',
    '/info/citizens-charter-old-mahabali-puram-road.php' => '2025-01-01',
    '/info/report-civic-issue-omr.php' => '2025-01-01',
    '/info/find-blo-officer.php' => date('Y-m-d'),
    // Directory landing
    '/it-companies' => date('Y-m-d'),
    // Directory lists (canonical URLs only)
    '/omr-listings/banks.php' => date('Y-m-d'),
    '/omr-listings/hospitals.php' => date('Y-m-d'),
    '/restaurants' => date('Y-m-d'),
    '/omr-listings/schools.php' => date('Y-m-d'),
    '/omr-listings/parks.php' => date('Y-m-d'),
    '/omr-listings/industries.php' => date('Y-m-d'),
    '/atms' => date('Y-m-d'),
    '/government-offices' => date('Y-m-d'),
    // Locality hubs
    '/omr-listings/locality/perungudi.php' => date('Y-m-d'),
    '/omr-listings/locality/kandhanchavadi.php' => date('Y-m-d'),
    '/omr-listings/locality/thoraipakkam.php' => date('Y-m-d'),
    '/omr-listings/locality/sholinganallur.php' => date('Y-m-d'),
    '/omr-listings/locality/siruseri.php' => date('Y-m-d'),
    '/omr-listings/locality/navalur.php' => date('Y-m-d'),
    '/omr-listings/locality/karapakkam.php' => date('Y-m-d'),
    // Job Portal - Main pages
    '/omr-local-job-listings/' => date('Y-m-d'),
    '/omr-local-job-listings/post-job-omr.php' => date('Y-m-d'),
    '/omr-local-job-listings/employer-register-omr.php' => date('Y-m-d'),
    // Job Landing Pages - Primary
    '/jobs-in-omr-chennai.php' => date('Y-m-d'),
    // Job Landing Pages - Location-Specific
    '/jobs-in-perungudi-omr.php' => date('Y-m-d'),
    '/jobs-in-sholinganallur-omr.php' => date('Y-m-d'),
    '/jobs-in-navalur-omr.php' => date('Y-m-d'),
    '/jobs-in-thoraipakkam-omr.php' => date('Y-m-d'),
    '/jobs-in-kelambakkam-omr.php' => date('Y-m-d'),
    // Job Landing Pages - Industry-Specific
    '/it-jobs-omr-chennai.php' => date('Y-m-d'),
    '/retail-jobs-omr-chennai.php' => date('Y-m-d'),
    '/hospitality-jobs-omr-chennai.php' => date('Y-m-d'),
    '/teaching-jobs-omr-chennai.php' => date('Y-m-d'),
    '/healthcare-jobs-omr-chennai.php' => date('Y-m-d'),
    // Job Landing Pages - Experience-Level
    '/fresher-jobs-omr-chennai.php' => date('Y-m-d'),
    '/experienced-jobs-omr-chennai.php' => date('Y-m-d'),
    // Job Landing Pages - Job-Type
    '/part-time-jobs-omr-chennai.php' => date('Y-m-d'),
    '/work-from-home-jobs-omr.php' => date('Y-m-d'),
    // Pentahive Industry Pages (Root Level)
    '/restaurant-website-design-maintenance.php' => date('Y-m-d'),
    '/school-website-design-maintenance.php' => date('Y-m-d'),
  ];

  // Output static pages
  foreach ($static_pages as $url => $lastmod) {
    echo "\n  <url>\n";
    echo "    <loc>$base_url$url</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>";
  }

  // Dynamic articles from database (exclude Tamil versions)
  if ($conn) {
    $sql = "SELECT slug, published_date, updated_at FROM articles WHERE status = 'published' AND slug NOT LIKE '%-tamil' ORDER BY published_date DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $slug = htmlspecialchars($row['slug']);
        $lastmod = date('Y-m-d', strtotime($row['updated_at'] ? $row['updated_at'] : $row['published_date']));
        
        echo "\n  <url>\n";
        echo "    <loc>$base_url/local-news/$slug</loc>\n";
        echo "    <lastmod>$lastmod</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.9</priority>\n";
        echo "  </url>";
      }
    }
    // IT company detail pages
    $itSql = "SELECT slno, company_name FROM omr_it_companies ORDER BY slno DESC";
    $itRes = $conn->query($itSql);
    if ($itRes && $itRes->num_rows > 0) {
      while ($it = $itRes->fetch_assoc()) {
        $name = $it['company_name'];
        $id = (int)$it['slno'];
        $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slugBase = trim($slugBase, '-');
        $url = $base_url . '/it-companies/' . $slugBase . '-' . $id;
        echo "\n  <url>\n";
        echo "    <loc>$url</loc>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>";
      }
    }

    // Hospital detail pages
    $hSql = "SELECT slno, hospitalname FROM omrhospitalslist ORDER BY slno DESC";
    $hRes = $conn->query($hSql);
    if ($hRes && $hRes->num_rows > 0) {
      while ($h = $hRes->fetch_assoc()) {
        $name = $h['hospitalname'];
        $id = (int)$h['slno'];
        $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slugBase = trim($slugBase, '-');
        $url = $base_url . '/hospitals/' . $slugBase . '-' . $id;
        echo "\n  <url>\n";
        echo "    <loc>$url</loc>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>";
      }
    }

    $conn->close();
  }
  ?>
</urlset>

