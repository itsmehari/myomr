<?php
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../omr-listings/data/it-parks-data.php';

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_parks_featured (
  id INT AUTO_INCREMENT PRIMARY KEY,
  park_id INT NOT NULL,
  rank_position INT NOT NULL DEFAULT 1,
  blurb VARCHAR(400) DEFAULT NULL,
  cta_text VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  start_at DATETIME NULL,
  end_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(park_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// If already seeded, skip
$check = $conn->query('SELECT COUNT(*) AS c FROM omr_it_parks_featured');
$row = $check ? $check->fetch_assoc() : ['c' => 0];
if ((int)($row['c'] ?? 0) > 0) {
  header('Content-Type: text/plain');
  echo "featured\talready_has_rows\n";
  exit;
}

// Pick top dataset parks to feature (by array order)
$toFeature = [];
foreach ($omr_it_parks_all as $p) {
  if (count($toFeature) >= 5) break;
  $toFeature[] = $p;
}

$rank = 1;
$stmt = $conn->prepare('INSERT INTO omr_it_parks_featured (park_id, rank_position, blurb, cta_text, cta_url, start_at, end_at) VALUES (?,?,?,?,?,?,?)');
foreach ($toFeature as $p) {
  $parkId = (int)$p['id'];
  $blurb = ($p['owner'] ?? '') !== '' ? ('Owned by ' . $p['owner']) : null;
  $ctaText = 'View details';
  $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/','-', $p['name']), '-'));
  $ctaUrl = '/it-parks/' . $slug . '-' . $parkId;
  $startAt = date('Y-m-d H:00:00');
  $endAt = null; // open-ended
  $stmt->bind_param('iisssss', $parkId, $rank, $blurb, $ctaText, $ctaUrl, $startAt, $endAt);
  $stmt->execute();
  $rank++;
}
$stmt->close();

header('Content-Type: text/plain');
echo "featured\tseeded\n";


