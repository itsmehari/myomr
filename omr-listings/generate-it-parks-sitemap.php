<?php
require_once __DIR__ . '/../core/omr-connect.php';

header('Content-Type: application/xml; charset=UTF-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$base = 'https://myomr.in';
echo '  <url><loc>'.$base.'/it-parks</loc></url>' . "\n";

$chk = $conn->query('SHOW TABLES LIKE "omr_it_parks"');
if ($chk && $chk->num_rows > 0) {
  $res = $conn->query('SELECT id, name, updated_at FROM omr_it_parks ORDER BY id ASC');
  if ($res) {
    while ($r = $res->fetch_assoc()) {
      $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/','-', $r['name']), '-')) . '-' . (int)$r['id'];
      $loc = $base . '/it-parks/' . $slug;
      $lastmod = !empty($r['updated_at']) ? date('c', strtotime($r['updated_at'])) : date('c');
      echo '  <url><loc>'.$loc.'</loc><lastmod>'.$lastmod.'</lastmod></url>' . "\n";
    }
  }
}

echo '</urlset>';
?>


