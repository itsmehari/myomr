<?php
require_once __DIR__ . '/../../core/omr-connect.php';

// Add unique index on (name, locality) if not exists
$check = $conn->query("SHOW INDEX FROM omr_it_parks WHERE Key_name='uniq_name_locality'");
if ($check && $check->num_rows === 0) {
  $ok = $conn->query("ALTER TABLE omr_it_parks ADD UNIQUE KEY uniq_name_locality (name, locality)");
  header('Content-Type: text/plain');
  echo $ok ? "uniq_name_locality\tcreated\n" : ("uniq_name_locality\terror:\t".$conn->error."\n");
  exit;
}
header('Content-Type: text/plain');
echo "uniq_name_locality\talready_exists\n";


