<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
header('X-Robots-Tag: noindex, nofollow, noarchive');
require_once ROOT_PATH . '/core/serve-404.php';
exit;
