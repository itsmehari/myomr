<?php
/**
 * Homepage header – uses full megamenu with all MyOMR links
 * Menu → submenu → list items, responsive (desktop megamenu, mobile accordion)
 */
$megamenu_file = __DIR__ . '/megamenu-myomr.php';
if (file_exists($megamenu_file)) {
  include $megamenu_file;
} else {
  include __DIR__ . '/main-nav.php';
}
