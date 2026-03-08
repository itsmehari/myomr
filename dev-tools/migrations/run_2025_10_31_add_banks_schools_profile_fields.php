<?php
// Adds profile fields to Banks and Schools: about, services, careers_url (idempotent)

require_once __DIR__ . '/../../core/omr-connect.php';

function col_exists($conn, $table, $col) {
    $sql = "SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $table, $col);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

$targets = ['omrbankslist' => 'bankname', 'omrschoolslist' => 'schoolname'];
$report = [];

foreach ($targets as $table => $nameCol) {
  if (!col_exists($conn, $table, 'about')) {
      $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `about` TEXT NULL");
      $report[] = $table . ':about' . ($ok ? '' : '(ERROR:' . $conn->error . ')');
  }
  if (!col_exists($conn, $table, 'services')) {
      $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `services` TEXT NULL");
      $report[] = $table . ':services' . ($ok ? '' : '(ERROR:' . $conn->error . ')');
  }
  if (!col_exists($conn, $table, 'careers_url')) {
      $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `careers_url` VARCHAR(255) NULL");
      $report[] = $table . ':careers_url' . ($ok ? '' : '(ERROR:' . $conn->error . ')');
  }
}

header('Content-Type: text/plain');
echo empty($report) ? "no_changes\n" : implode("\n", $report) . "\n";

?>


