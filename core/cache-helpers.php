<?php
// Lightweight output caching helper for list pages

function omr_cache_dir() {
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function omr_cache_should_bypass() {
    if (php_sapi_name() === 'cli') return true;
    if (isset($_GET['no_cache'])) return true;
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') return true;
    return false;
}

function omr_cache_key_from_request($prefix = 'page:') {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return $prefix . md5($uri);
}

function omr_output_cache_start($key, $ttlSeconds = 300) {
    if (omr_cache_should_bypass()) return;
    $dir = omr_cache_dir();
    $file = $dir . '/' . preg_replace('/[^a-zA-Z0-9_:\-]/', '_', $key) . '.html';
    if (is_file($file)) {
        $age = time() - @filemtime($file);
        if ($age >= 0 && $age < $ttlSeconds) {
            // Serve cached file and exit early
            header('X-OMR-Cache: HIT');
            readfile($file);
            exit;
        }
    }
    header('X-OMR-Cache: MISS');
    ob_start();
    register_shutdown_function(function () use ($file) {
        $out = ob_get_contents();
        if ($out !== false) {
            @file_put_contents($file, $out, LOCK_EX);
        }
        ob_end_flush();
    });
}


// Asset helpers
if (!function_exists('omr_logo_candidates_for')) {
    /**
     * Returns ordered logo asset candidates for a directory entity
     * @param string $type e.g., 'it-companies', 'hospitals', 'banks', 'schools'
     * @param string $slugBase kebab-case name
     * @param int $id numeric id
     * @return array relative paths
     */
    function omr_logo_candidates_for($type, $slugBase, $id) {
        $base = '/assets/img/' . $type . '/' . $slugBase . '-' . (int)$id;
        return [ $base . '.webp', $base . '.jpg', $base . '.png' ];
    }
}


