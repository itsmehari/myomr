<?php
/**
 * Physical path so /local-news/{slug} is a real directory on disk.
 * Front-end nginx 404s extensionless slugs that are not files (583-byte stub).
 */
declare(strict_types=1);

$_GET['slug'] = 'omr-uturn-ban-geetham-seevaram-wtc-perungudi-toll-sept-2026';
require dirname(__DIR__) . '/article.php';
