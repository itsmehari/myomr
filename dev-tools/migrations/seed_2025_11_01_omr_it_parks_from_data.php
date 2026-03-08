<?php
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../omr-listings/data/it-parks-data.php';

// If table is empty, seed from data file
$res = $conn->query('SELECT COUNT(*) AS c FROM omr_it_parks');
$row = $res ? $res->fetch_assoc() : ['c' => 0];
$count = (int)($row['c'] ?? 0);
if ($count > 0) {
  header('Content-Type: text/plain');
  echo "omr_it_parks\talready_has_rows\n";
  exit;
}

$stmt = $conn->prepare('INSERT INTO omr_it_parks (name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, verified) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
foreach ($omr_it_parks_all as $p) {
  $name = $p['name'] ?? '';
  $locality = $p['location'] ?? '';
  $address = $p['address'] ?? '';
  $phone = $p['phone'] ?? '';
  $website = $p['website'] ?? '';
  $year = $p['inauguration_year'] ?? '';
  $owner = $p['owner'] ?? '';
  $built = $p['built_up_area'] ?? '';
  $total = $p['total_area'] ?? '';
  $image = $p['image'] ?? '';
  $verified = 0;
  $stmt->bind_param('ssssssssssi', $name, $locality, $address, $phone, $website, $year, $owner, $built, $total, $image, $verified);
  $stmt->execute();
}
$stmt->close();

header('Content-Type: text/plain');
echo "omr_it_parks\tseeded\n";


