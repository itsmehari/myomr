<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../core/omr-connect.php';

function colExists(mysqli $c, string $t, string $col): bool {
  $s = $c->prepare("SHOW COLUMNS FROM `$t` LIKE ?");
  $s->bind_param('s', $col);
  $s->execute();
  $r = $s->get_result();
  $ok = $r && $r->num_rows > 0;
  $s->close();
  return $ok;
}

function idxExists(mysqli $c, string $t, string $idx): bool {
  $s = $c->prepare("SHOW INDEX FROM `$t` WHERE Key_name = ?");
  $s->bind_param('s', $idx);
  $s->execute();
  $r = $s->get_result();
  $ok = $r && $r->num_rows > 0;
  $s->close();
  return $ok;
}

$table = 'omr_it_companies_featured';
echo "=== Featured lifecycle migration ===\n";

if (!colExists($conn, $table, 'start_at')) {
  echo "Adding start_at...\n";
  $conn->query("ALTER TABLE `$table` ADD COLUMN `start_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
}
if (!colExists($conn, $table, 'end_at')) {
  echo "Adding end_at...\n";
  $conn->query("ALTER TABLE `$table` ADD COLUMN `end_at` DATETIME NULL DEFAULT NULL");
}
if (!idxExists($conn, $table, 'idx_featured_start_at')) {
  echo "Adding idx_featured_start_at...\n";
  $conn->query("CREATE INDEX `idx_featured_start_at` ON `$table` (`start_at`)");
}
if (!idxExists($conn, $table, 'idx_featured_end_at')) {
  echo "Adding idx_featured_end_at...\n";
  $conn->query("CREATE INDEX `idx_featured_end_at` ON `$table` (`end_at`)");
}

echo "Done.\n";


