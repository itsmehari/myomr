<?php
/**
 * Find your BLO – Redirect to main BLO search page (Sholinganallur AC).
 * Keeps all election tools under one roof from the subsite nav.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$blo_url = 'https://myomr.in/info/find-blo-officer.php';
if (headers_sent()) {
    echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($blo_url) . '"></head><body><p>Redirecting to <a href="' . htmlspecialchars($blo_url) . '">Find your BLO</a>...</p></body></html>';
} else {
    header('Location: ' . $blo_url, true, 302);
    exit;
}
