<?php
/**
 * Central path definitions for MyOMR.
 * Works from any subdirectory – use DOCUMENT_ROOT or dirname from core/.
 *
 * Usage (from any page depth):
 *   require_once ($_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..') . '/core/include-path.php';
 *   require_once ROOT_PATH . '/components/css-includes.php';
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

/** MyOMR WhatsApp community group — use for "Join our WhatsApp group" CTAs site-wide */
if (!defined('MYOMR_WHATSAPP_GROUP_URL')) {
    define('MYOMR_WHATSAPP_GROUP_URL', 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi');
}

/** MyOMR Facebook group — use for "Join our Facebook group" CTAs site-wide */
if (!defined('MYOMR_FACEBOOK_GROUP_URL')) {
    define('MYOMR_FACEBOOK_GROUP_URL', 'https://www.facebook.com/groups/416854920508620');
}
