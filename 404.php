<?php
/**
 * MyOMR — Custom 404 Page
 * Invoked by ErrorDocument 404 in .htaccess when a physical file/path is not found.
 * Sends HTTP 404, tracks in GA4, and shows the branded 404 page.
 */
require_once __DIR__ . '/core/serve-404.php';
