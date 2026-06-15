<?php
/**
 * Repair module admin files broken by wrap-module-html-shell.php (bare PHP after ?>).
 * Usage: php dev-tools/qa/fix-wrap-shell-artifacts.php [--write]
 */
declare(strict_types=1);

$write = in_array('--write', $argv, true);
$root = dirname(__DIR__, 2);
$fixed = 0;
$phpOpen = '<' . '?php';

foreach (glob($root . '/omr-*/admin/*.php') as $file) {
    if (basename($file) === '_urls.php') {
        continue;
    }
    $c = file_get_contents($file);
    if ($c === false) {
        continue;
    }
    $orig = $c;

    $dupRouter = "?>\nrequire_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';\n";
    $c = str_replace($dupRouter, "\n", $c);
    $dupRouterCr = str_replace("\n", "\r\n", $dupRouter);
    $c = str_replace($dupRouterCr, "\n", $c);

    $flashPattern = '#\s*' . preg_quote($phpOpen, '#') . ' include [\'"]\.\./\.\./admin/admin-flash\.php[\'"]; \?>\s*#';
    $c = preg_replace($flashPattern, "\n", $c);

    $c = preg_replace(
        "/(require_once __DIR__ \. '\/_urls\.php';)\s*\r?\n" . preg_quote($phpOpen, '/') . "\s*\r?\n/",
        "$1\n",
        $c
    );

    if ($c !== $orig) {
        $rel = str_replace('\\', '/', substr($file, strlen($root) + 1));
        echo ($write ? 'Fixed: ' : 'Would fix: ') . $rel . "\n";
        if ($write) {
            file_put_contents($file, $c);
        }
        $fixed++;
    }
}

echo $write ? "Repaired {$fixed} file(s).\n" : "Dry run — {$fixed} file(s) need repair.\n";
