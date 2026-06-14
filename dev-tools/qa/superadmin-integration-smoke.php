<?php
/**
 * Verify single superadmin integration (no legacy /admin/ folder deps).
 * Usage: php dev-tools/qa/superadmin-integration-smoke.php
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

if (is_dir($root . '/admin')) {
    $errors[] = 'admin/ folder still exists — should be removed';
}

$required = [
    'superadmin/_bootstrap.php',
    'superadmin/index.php',
    'superadmin/login.php',
    'superadmin/config/navigation.php',
    'core/admin-auth.php',
];

foreach ($required as $rel) {
    if (!is_file($root . '/' . $rel)) {
        $errors[] = "Missing required file: {$rel}";
    }
}

$removed = [
    'superadmin/config/navigation-DESKTOP-LUHDV82.php',
    'superadmin/events-list.php',
    'components/admin-sidebar.php',
];

foreach ($removed as $rel) {
    if (is_file($root . '/' . $rel)) {
        $errors[] = "Duplicate/legacy file should be removed: {$rel}";
    }
}

$grepFiles = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $path = str_replace('\\', '/', $file->getPathname());
    if (str_contains($path, '/vendor/') || str_contains($path, '/node_modules/')) {
        continue;
    }
    $grepFiles[] = $path;
}

$badPatterns = [
    '/admin/_bootstrap.php' => 'Legacy admin bootstrap path',
    'href="/admin/' => 'Legacy /admin/ URL in HTML',
    "href='/admin/" => 'Legacy /admin/ URL in HTML',
    "'/admin/index.php'" => 'Legacy admin index URL',
    "'/admin/login.php'" => 'Legacy admin login URL',
    "'/admin/dashboard.php'" => 'Legacy admin dashboard URL',
];

$rootNorm = str_replace('\\', '/', $root);

foreach ($grepFiles as $path) {
    $pathNorm = str_replace('\\', '/', $path);
    if (!str_starts_with($pathNorm, $rootNorm)) {
        continue;
    }
    $rel = ltrim(substr($pathNorm, strlen($rootNorm)), '/');
    if (str_starts_with($rel, 'docs/')
        || str_starts_with($rel, 'dev-tools/docs/')
        || str_starts_with($rel, 'backups/')
        || str_contains($rel, '/backups/')) {
        continue;
    }
    if ($rel === 'dev-tools/qa/superadmin-integration-smoke.php' || $rel === 'superadmin/_auth.php') {
        continue;
    }
    $content = file_get_contents($path);
    if ($content === false) {
        continue;
    }
    foreach ($badPatterns as $pattern => $label) {
        if (str_contains($content, $pattern)) {
            $errors[] = "{$label} in {$rel}";
        }
    }
}

$content = file_get_contents($root . '/core/admin-auth.php');
if ($content && !str_contains($content, '/superadmin/login.php')) {
    $errors[] = 'core/admin-auth.php must redirect to /superadmin/login.php';
}

$cpanel = file_get_contents($root . '/.cpanel.yml');
if ($cpanel && preg_match('/\badmin\b/', $cpanel) && str_contains($cpanel, 'cp -R admin')) {
    $errors[] = '.cpanel.yml still deploys admin/ folder';
}

if ($errors === []) {
    echo "OK: Superadmin integration smoke check passed.\n";
    echo "Entry: /superadmin/login.php → /superadmin/index.php\n";
    exit(0);
}

echo "FAIL: Superadmin integration issues:\n";
foreach ($errors as $e) {
    echo "  - {$e}\n";
}
exit(1);
