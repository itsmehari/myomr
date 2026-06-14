<?php
/**
 * Smoke-check superadmin DB tables and admin_users login readiness.
 * Usage: DB_HOST=myomr.in php dev-tools/qa/superadmin-db-smoke-check.php
 */
declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

if (!($conn instanceof mysqli)) {
    fwrite(STDERR, "No DB connection.\n");
    exit(1);
}

$checks = [
    'admin_users' => 'SELECT COUNT(*) AS c FROM admin_users',
    'articles' => 'SELECT COUNT(*) AS c FROM articles',
    'event_listings' => 'SELECT COUNT(*) AS c FROM event_listings',
    'job_postings' => 'SELECT COUNT(*) AS c FROM job_postings',
    'rent_lease_properties' => 'SELECT COUNT(*) AS c FROM rent_lease_properties',
    'omr_classified_ads_listings' => 'SELECT COUNT(*) AS c FROM omr_classified_ads_listings',
];

echo "DB host: " . ($conn->host_info ?? 'unknown') . "\n\n";

foreach ($checks as $label => $sql) {
    try {
        $res = $conn->query($sql);
        if (!$res) {
            echo "[FAIL] $label: " . $conn->error . "\n";
            continue;
        }
        $row = $res->fetch_assoc();
        echo "[OK] $label rows: " . (int) ($row['c'] ?? 0) . "\n";
    } catch (Throwable $e) {
        echo "[FAIL] $label: " . $e->getMessage() . "\n";
    }
}

echo "\nAdmin users:\n";
$res = $conn->query('SELECT id, username, role, email, last_login FROM admin_users ORDER BY id');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo sprintf(
            "  id=%d username=%s role=%s last_login=%s\n",
            (int) $row['id'],
            $row['username'] ?? '',
            $row['role'] ?? '',
            $row['last_login'] ?? 'never'
        );
    }
} else {
    echo "  (query failed: {$conn->error})\n";
}

echo "\nSuperadmin login URL: https://myomr.in/superadmin/login.php\n";
