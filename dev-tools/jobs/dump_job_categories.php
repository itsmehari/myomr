<?php
/**
 * Dump job_categories rows (CLI). Used for local tooling.
 */
if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$hosts = [getenv('DB_HOST') ? getenv('DB_HOST') . ':' . (getenv('DB_PORT') ?: '3306') : null, 'myomr.in:3306', '127.0.0.1:3307', 'localhost:3306'];
$hosts = array_filter($hosts);
$user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
$pass = getenv('DB_PASS') ?: 'myomr@123';
$db = getenv('DB_NAME') ?: 'metap8ok_myomr';
foreach ($hosts as $host) {
    try {
        $c = new mysqli($host, $user, $pass, $db);
        $c->set_charset('utf8mb4');
        $r = $c->query('SELECT id, name, slug, is_active FROM job_categories ORDER BY name');
        echo "host\t{$host}\n";
        echo "id\tname\tslug\tis_active\n";
        while ($row = $r->fetch_assoc()) {
            echo $row['id'] . "\t" . $row['name'] . "\t" . $row['slug'] . "\t" . $row['is_active'] . "\n";
        }
        $c->close();
        exit(0);
    } catch (Throwable $e) {
        fwrite(STDERR, "fail {$host}: " . $e->getMessage() . "\n");
    }
}
exit(1);
