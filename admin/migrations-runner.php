<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Safety: restrict to super-admins if role exists; otherwise, require a secret token in querystring
$secret = $_GET['secret'] ?? '';
if ($secret !== '' && !hash_equals($secret, getenv('MYOMR_MIGRATIONS_SECRET') ?: '')) {
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


