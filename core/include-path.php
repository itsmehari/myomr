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
