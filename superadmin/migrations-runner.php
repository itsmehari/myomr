<?php
require_once __DIR__ . '/_bootstrap.php';

requireRole(['super_admin']);

// Optional extra guard for production runs.
$secret = $_GET['secret'] ?? '';
$expectedSecret = getenv('MYOMR_MIGRATIONS_SECRET') ?: '';
if ($expectedSecret !== '' && !hash_equals($expectedSecret, (string)$secret)) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

// List of migrations to run
$migrations = [
  __DIR__ . '/../dev-tools/migrations/run_2025_10_31_add_it_company_profile_fields.php',
  __DIR__ . '/../dev-tools/migrations/run_2025_10_31_add_hospital_profile_fields.php',
  __DIR__ . '/../dev-tools/migrations/run_2025_10_31_add_banks_schools_profile_fields.php',
];

header('Content-Type: text/plain');
foreach ($migrations as $mig) {
  if (!file_exists($mig)) { echo basename($mig) . "\tmissing\n"; continue; }
  // Run in isolated scope
  try {
    include $mig;
  } catch (Throwable $e) {
    echo basename($mig) . "\terror:" . $e->getMessage() . "\n";
  }
}

?>


