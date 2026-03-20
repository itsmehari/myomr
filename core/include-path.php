<?php
/**
 * Central path definitions for MyOMR.
 * ROOT_PATH is derived from this file's location (core/ parent) so it works regardless of DOCUMENT_ROOT.
 *
 * Usage (from any page depth):
 *   require_once __DIR__ . '/../core/include-path.php';   // or from root: require_once 'core/include-path.php';
 *   require_once ROOT_PATH . '/components/css-includes.php';
 *
 * Override: set DB_HOST or OMR_ROOT_PATH before including to use a different root (e.g. for tests).
 */
if (!defined('ROOT_PATH')) {
    $root = getenv('OMR_ROOT_PATH');
    if ($root !== false && $root !== '') {
        define('ROOT_PATH', $root);
    } else {
        $resolved = realpath(dirname(__DIR__));
        define('ROOT_PATH', $resolved !== false ? $resolved : dirname(__DIR__));
    }
}

/** MyOMR WhatsApp community group — use for "Join our WhatsApp group" CTAs site-wide */
if (!defined('MYOMR_WHATSAPP_GROUP_URL')) {
    define('MYOMR_WHATSAPP_GROUP_URL', 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi');
}

/** MyOMR Facebook group — use for "Join our Facebook group" CTAs site-wide */
if (!defined('MYOMR_FACEBOOK_GROUP_URL')) {
    define('MYOMR_FACEBOOK_GROUP_URL', 'https://www.facebook.com/groups/416854920508620');
}
