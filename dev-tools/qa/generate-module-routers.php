<?php
/**
 * Generate superadmin module router PHP files from router manifests.
 * Usage: php dev-tools/qa/generate-module-routers.php [--write]
 */
declare(strict_types=1);

$write = in_array('--write', $argv, true);
$root = dirname(__DIR__, 2);
$manifest = require __DIR__ . '/router-manifest.php';
$extra = require __DIR__ . '/router-manifest-extra.php';
$all = array_merge($manifest, $extra);

$navByConstant = [
    'JOBS_ADMIN_ROUTED' => '/superadmin/jobs/',
    'CLASSIFIED_ADS_ADMIN_ROUTED' => '/superadmin/classified-ads/',
    'BUY_SELL_ADMIN_ROUTED' => '/superadmin/buy-sell/',
    'COMMUNITY_EVENTS_ADMIN_ROUTED' => '/superadmin/community-events/',
    'HOSTELS_ADMIN_ROUTED' => '/superadmin/hostels/',
    'COWORKING_ADMIN_ROUTED' => '/superadmin/coworking/',
    'RENT_LEASE_ADMIN_ROUTED' => '/superadmin/rent-lease/',
];

$created = 0;
$skipFlatLegacy = [
    'rent-lease.php',
    'rent-lease-manage.php',
    'rent-lease-photos.php',
];

foreach ($all as $entry) {
    $route = $entry['route'];
    if (!str_starts_with($route, '/superadmin/')) {
        continue;
    }
    $rel = substr($route, strlen('/superadmin/'));
    if (in_array($rel, $skipFlatLegacy, true)) {
        continue;
    }
    $path = $root . '/superadmin/' . $rel;

    $urlsExport = var_export($entry['urls'], true);
    $constant = $entry['constant'];
    $activeNav = $navByConstant[$constant] ?? '';
    $relTarget = str_replace('\\', '/', str_replace($root . '/', '', $entry['target']));
    $targetExpr = "dirname(__DIR__, 2) . '/{$relTarget}'";
    // Routers live at superadmin/{module}/{file}.php — one level below superadmin/.
    $routerInclude = "dirname(__DIR__) . '/includes/module-router.php'";

    $php = <<<PHP
<?php
declare(strict_types=1);
require_once {$routerInclude};
myomr_module_router([
    'constant' => '{$constant}',
    'urls' => {$urlsExport},
    'target' => {$targetExpr},
    'activeNav' => '{$activeNav}',
]);

PHP;

    if ($write) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, $php);
        echo "Wrote superadmin/{$rel}\n";
        $created++;
    }
}

echo $write ? "Created/updated {$created} routers.\n" : "Dry run.\n";
