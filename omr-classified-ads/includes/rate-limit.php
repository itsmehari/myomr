<?php
/**
 * OMR Classified Ads — post rate limit (per IP, 24h).
 */
function checkClassifiedAdsPostRateLimit(): bool {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $dir = __DIR__ . '/../uploads/rate-limit';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $file = $dir . '/' . md5($ip) . '.json';
    $max_per_day = 5;
    $window = 86400;

    $now = time();
    $data = ['count' => 0, 'first' => $now];

    if (file_exists($file)) {
        $raw = file_get_contents($file);
        $data = json_decode($raw, true) ?: $data;
        if ($now - $data['first'] > $window) {
            $data = ['count' => 0, 'first' => $now];
        }
    }

    return $data['count'] < $max_per_day;
}

function recordClassifiedAdsPost(): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $dir = __DIR__ . '/../uploads/rate-limit';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $file = $dir . '/' . md5($ip) . '.json';
    $window = 86400;

    $now = time();
    $data = ['count' => 1, 'first' => $now];
    if (file_exists($file)) {
        $raw = file_get_contents($file);
        $d = json_decode($raw, true);
        if ($d && ($now - ($d['first'] ?? 0)) <= $window) {
            $data = ['count' => ($d['count'] ?? 0) + 1, 'first' => $d['first']];
        }
    }
    file_put_contents($file, json_encode($data));
}
