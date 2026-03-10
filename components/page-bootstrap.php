<?php
/**
 * Page Bootstrap — central entry for path + component helpers.
 * Requires ROOT_PATH (from core/include-path.php).
 *
 * Include once at top of page:
 *   $root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;  // or __DIR__ . '/..' for 1 level deep
 *   require_once $root . '/core/include-path.php';
 *   require_once ROOT_PATH . '/components/page-bootstrap.php';
 *
 * @see docs/workflows-pipelines/MODULAR-INCLUDES.md
 */
if (!defined('ROOT_PATH')) {
    trigger_error('ROOT_PATH must be defined (include core/include-path.php first)', E_USER_WARNING);
}
require_once ROOT_PATH . '/components/component-includes.php';
