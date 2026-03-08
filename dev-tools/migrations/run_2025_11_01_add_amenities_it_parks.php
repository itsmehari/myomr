<?php
require_once __DIR__ . '/../../core/omr-connect.php';

function col_exists($conn, $table, $col) {
  $stmt = $conn->prepare("SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
  $stmt->bind_param('ss', $table, $col);
  $stmt->execute();
  $res = $stmt->get_result();
  $ok = $res && $res->num_rows > 0;
  $stmt->close();
  return $ok;
}

$added = [];
foreach (['amenity_sez','amenity_parking','amenity_cafeteria','amenity_shuttle'] as $col) {
  if (!col_exists($conn, 'omr_it_parks', $col)) {
    $ok = $conn->query("ALTER TABLE omr_it_parks ADD COLUMN `{$col}` TINYINT(1) NOT NULL DEFAULT 0");
    $added[] = $ok ? $col : ($col . '(ERROR:' . $conn->error . ')');
  }
}

header('Content-Type: text/plain');
echo 'amenities\t' . (empty($added) ? 'no_changes' : implode(',', $added)) . "\n";


