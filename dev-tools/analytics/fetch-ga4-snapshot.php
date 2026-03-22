<?php
/**
 * CLI: pull MyOMR GA4 metrics and write dev-tools/analytics/GA4-SNAPSHOT.md
 * for planning in Cursor (@-mention the file in chat).
 *
 * Requires: .cursor/secrets/google-analytics.json OR MYOMR_GA_SERVICE_ACCOUNT_PATH
 * Usage: php dev-tools/analytics/fetch-ga4-snapshot.php
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
require_once $root . '/core/analytics-config.php';
require_once $root . '/core/ga4-data-api.php';

$outFile = __DIR__ . DIRECTORY_SEPARATOR . 'GA4-SNAPSHOT.md';

$sa = myomr_ga4_load_service_account();
if (!$sa['ok']) {
    fwrite(STDERR, $sa['error'] ?? 'Service account error' . PHP_EOL);
    exit(1);
}

$propertyId = myomr_ga4_property_id();

$sections = [];
$sections[] = '# GA4 snapshot — myomr.in';
$sections[] = '';
$sections[] = '- **Generated:** ' . gmdate('Y-m-d H:i:s') . ' UTC';
$sections[] = '- **Property ID:** `' . $propertyId . '`';
$sections[] = '- **Service account:** `' . ($sa['data']['client_email'] ?? '') . '`';
$sections[] = '';

function run_block(array $sa, string $propertyId, string $title, array $body): string
{
    $r = myomr_ga4_run_report($sa['data'], $propertyId, $body);
    if (!$r['ok']) {
        return "## $title\n\n**Error:** " . ($r['error'] ?? 'unknown') . "\n\n";
    }
    return "## $title\n\n" . _md_table_from_report($r['data']) . "\n\n";
}

function _md_table_from_report(array $data): string
{
    $rows = myomr_ga4_parse_rows($data);
    if ($rows === []) {
        return '_No rows._';
    }
    $dimKeys = array_keys($rows[0]['dimensions']);
    $metKeys = array_keys($rows[0]['metrics']);
    $headers = array_merge($dimKeys, $metKeys);
    $lines = [];
    $lines[] = '| ' . implode(' | ', $headers) . ' |';
    $lines[] = '|' . str_repeat(' --- |', count($headers));
    foreach ($rows as $row) {
        $cells = [];
        foreach ($dimKeys as $k) {
            $cells[] = str_replace('|', '\\|', $row['dimensions'][$k] ?? '');
        }
        foreach ($metKeys as $k) {
            $cells[] = str_replace('|', '\\|', $row['metrics'][$k] ?? '');
        }
        $lines[] = '| ' . implode(' | ', $cells) . ' |';
    }
    return implode("\n", $lines);
}

function summary_block(array $sa, string $propertyId, string $label, string $start, string $end): string
{
    $body = [
        'dateRanges' => [['startDate' => $start, 'endDate' => $end]],
        'metrics' => [
            ['name' => 'sessions'],
            ['name' => 'activeUsers'],
            ['name' => 'newUsers'],
            ['name' => 'screenPageViews'],
            ['name' => 'eventCount'],
            ['name' => 'engagementRate'],
            ['name' => 'averageSessionDuration'],
        ],
    ];
    $r = myomr_ga4_run_report($sa['data'], $propertyId, $body);
    if (!$r['ok']) {
        return "## Summary ($label)\n\n**Error:** " . ($r['error'] ?? 'unknown') . "\n\n";
    }
    $vals = myomr_ga4_first_row_metrics($r['data']);
    $names = [];
    foreach ($r['data']['metricHeaders'] ?? [] as $mh) {
        $names[] = $mh['name'] ?? '?';
    }
    $lines = ["## Summary ($label)", '', "_Date range: `$start` → `$end`_", ''];
    for ($i = 0; $i < count($names); $i++) {
        $v = $vals[$i] ?? '—';
        if (($names[$i] ?? '') === 'engagementRate') {
            $v = (is_numeric($v) ? round((float)$v * 100, 2) : $v) . '%';
        }
        if (($names[$i] ?? '') === 'averageSessionDuration') {
            $v = is_numeric($v) ? round((float)$v, 1) . ' s' : $v;
        }
        $lines[] = '- **' . ($names[$i] ?? '?') . ':** ' . $v;
    }
    $lines[] = '';
    return implode("\n", $lines);
}

$sections[] = summary_block($sa, $propertyId, 'Last 7 days', '7daysAgo', 'today');
$sections[] = summary_block($sa, $propertyId, 'Last 28 days', '28daysAgo', 'today');

$sections[] = run_block($sa, $propertyId, 'Top pages (last 28 days)', [
    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
    'dimensions' => [['name' => 'pagePath']],
    'metrics' => [['name' => 'screenPageViews'], ['name' => 'sessions']],
    'orderBys' => [['desc' => true, 'metric' => ['metricName' => 'screenPageViews']]],
    'limit' => 20,
]);

$sections[] = run_block($sa, $propertyId, 'Traffic channel (last 28 days)', [
    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
    'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
    'metrics' => [['name' => 'sessions'], ['name' => 'activeUsers']],
    'orderBys' => [['desc' => true, 'metric' => ['metricName' => 'sessions']]],
    'limit' => 15,
]);

$sections[] = run_block($sa, $propertyId, 'Device category (last 28 days)', [
    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
    'dimensions' => [['name' => 'deviceCategory']],
    'metrics' => [['name' => 'sessions'], ['name' => 'activeUsers']],
    'orderBys' => [['desc' => true, 'metric' => ['metricName' => 'sessions']]],
    'limit' => 10,
]);

$sections[] = '---';
$sections[] = '_Regenerate: `php dev-tools/analytics/fetch-ga4-snapshot.php`_';

$markdown = implode("\n", $sections);
if (strpos($markdown, 'insufficient permissions') !== false) {
    $markdown .= "\n\n## Troubleshooting\n\n";
    $markdown .= "If you see **permission** errors, in GA4 go to **Admin → Property access management**, add the service account email (from your JSON) with role **Viewer**, then run this script again.\n";
}
if (file_put_contents($outFile, $markdown) === false) {
    fwrite(STDERR, "Could not write: $outFile\n");
    exit(1);
}

echo "Wrote " . $outFile . PHP_EOL;
