<?php
/**
 * One increment per browser for a page key (cookie + file counter).
 */
function myomr_unique_page_views(string $key): int
{
    $safe = preg_replace('/[^a-z0-9_-]/i', '', $key);
    if ($safe === '') {
        $safe = 'page';
    }

    $dir = ROOT_PATH . '/storage/job-page-views';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $file = $dir . '/counts.json';
    $cookie = 'myomr_upv_' . substr(hash('sha256', $safe), 0, 20);
    $fp = fopen($file, 'c+');
    if ($fp === false) {
        return 0;
    }

    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $data = json_decode($raw !== false && $raw !== '' ? $raw : '{}', true);
    if (!is_array($data)) {
        $data = [];
    }
    if (!isset($data[$safe])) {
        $data[$safe] = 0;
    }

    $already = isset($_COOKIE[$cookie]) && $_COOKIE[$cookie] === '1';
    if (!$already) {
        $data[$safe] = (int) $data[$safe] + 1;
        setcookie($cookie, '1', [
            'expires' => time() + 365 * 86400,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[$cookie] = '1';
    }

    rewind($fp);
    ftruncate($fp, 0);
    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return (int) $data[$safe];
}
