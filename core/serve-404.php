<?php
/**
 * MyOMR — Shared 404 Handler
 * Call this when an entity is not found. Sends HTTP 404 and outputs the branded 404 page.
 *
 * Usage:
 *   require_once ROOT_PATH . '/core/serve-404.php';
 *   exit;
 *
 * Requires ROOT_PATH. Set before calling if not already defined:
 *   if (!defined('ROOT_PATH')) define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
 *
 * @see 404.php (root) - uses this for ErrorDocument 404
 * @see docs/ERROR-HANDLING-404-RULES.md
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

http_response_code(404);

$missed_url = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8');
$referrer   = htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '', ENT_QUOTES, 'UTF-8');

$ga_content_group = '404 Errors';
require_once ROOT_PATH . '/components/analytics.php';
require_once ROOT_PATH . '/components/404-page.php';
