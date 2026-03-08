<?php
// Adds profile fields to IT companies: about, services, careers_url (idempotent)

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

$table = 'omr_it_companies';
$report = [];

if (!col_exists($conn, $table, 'about')) {
    $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `about` TEXT NULL AFTER `verified`");
    $report[] = $ok ? 'about' : ('about(ERROR:' . $conn->error . ')');
}

if (!col_exists($conn, $table, 'services')) {
    $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `services` TEXT NULL AFTER `about`");
    $report[] = $ok ? 'services' : ('services(ERROR:' . $conn->error . ')');
}

if (!col_exists($conn, $table, 'careers_url')) {
    $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `careers_url` VARCHAR(255) NULL AFTER `services`");
    $report[] = $ok ? 'careers_url' : ('careers_url(ERROR:' . $conn->error . ')');
}

header('Content-Type: text/plain');
echo $table . "\t" . (empty($report) ? 'no_changes' : implode(',', $report)) . "\n";

?>


