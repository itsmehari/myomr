<?php
// Build sitemap.xml by capturing output of sitemap-generator.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$root = dirname(__DIR__, 1); // dev-tools\tasks -> dev-tools
$projectRoot = dirname($root, 1); // -> project root

chdir($projectRoot);

ob_start();
// Include generator; it echoes XML
include $projectRoot . DIRECTORY_SEPARATOR . 'sitemap-generator.php';
$xml = ob_get_clean();

// Write to sitemap.xml at project root
$target = $projectRoot . DIRECTORY_SEPARATOR . 'sitemap.xml';
file_put_contents($target, $xml);

echo "Wrote sitemap.xml (" . strlen($xml) . " bytes)\n";


