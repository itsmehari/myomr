<?php
/**
 * Wrap module admin HTML pages to use superadmin shell when routed.
 * Usage: php dev-tools/qa/wrap-module-html-shell.php [--write]
 */
declare(strict_types=1);

$write = in_array('--write', $argv, true);
$root = dirname(__DIR__, 2);

$moduleNav = [
    'omr-local-job-listings/admin' => ['/superadmin/jobs/', 'Jobs Portal'],
    'omr-classified-ads/admin' => ['/superadmin/classified-ads/', 'Classified Ads'],
    'omr-buy-sell/admin' => ['/superadmin/buy-sell/', 'Buy & Sell'],
    'omr-local-events/admin' => ['/superadmin/community-events/', 'Community Events'],
    'omr-hostels-pgs/admin' => ['/superadmin/hostels/', 'Hostels & PGs'],
    'omr-coworking-spaces/admin' => ['/superadmin/coworking/', 'Coworking'],
    'omr-rent-lease/admin' => ['/superadmin/rent-lease/', 'Rent & Lease'],
];

foreach ($moduleNav as $relDir => [$activeNav, $defaultTitle]) {
    $dir = $root . '/' . $relDir;
    foreach (glob($dir . '/*.php') as $file) {
        if (basename($file) === '_urls.php') {
            continue;
        }
        $c = file_get_contents($file);
        if ($c === false || !str_contains($c, '<!DOCTYPE html>') || str_contains($c, 'myomr_module_shell_open')) {
            continue;
        }

        if (!preg_match('/<title>([^<]+)<\/title>/i', $c, $m)) {
            $pageTitle = $defaultTitle;
        } else {
            $pageTitle = trim(html_entity_decode(strip_tags($m[1])));
            $pageTitle = preg_replace('/\s*-\s*Admin.*$/i', '', $pageTitle);
            $pageTitle = preg_replace('/\s*\|\s*MyOMR.*$/i', '', $pageTitle);
        }

        $titleEsc = addslashes($pageTitle);
        $navEsc = addslashes($activeNav);

        $shellHeader = <<<PHP

\$__modulePageTitle = '{$titleEsc}';
\$__moduleActiveNav = '{$navEsc}';
if (myomr_module_using_shell()) {
    myomr_module_shell_open(\$__modulePageTitle, \$__moduleActiveNav);
} else {
PHP;

        $c2 = preg_replace(
            '/\?>\s*<!DOCTYPE html>.*?<body[^>]*>\s*\r?\n/s',
            "\n" . $shellHeader . "\n?><!DOCTYPE html>\n<html lang=\"en\">\n<head><meta charset=\"UTF-8\"><title>{$pageTitle}</title></head>\n<body>\n",
            $c,
            1
        );

        if ($c2 === null || $c2 === $c) {
            echo "SKIP (no body match): $file\n";
            continue;
        }

        $shellFooter = <<<'PHP'

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
PHP;

        $c2 = preg_replace(
            '/<\/body>\s*<\/html>\s*\Z/s',
            $shellFooter . "\n</body></html>\n<?php }\n",
            $c2,
            1
        );

        if ($write) {
            file_put_contents($file, $c2);
            echo "Wrapped: " . str_replace('\\', '/', substr($file, strlen($root) + 1)) . "\n";
        } else {
            echo "Would wrap: " . str_replace('\\', '/', substr($file, strlen($root) + 1)) . "\n";
        }
    }
}

echo $write ? "Wrap complete.\n" : "Dry run.\n";
