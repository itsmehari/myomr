<?php
/**
 * Wrap module admin HTML output in unified superadmin shell when MYOMR_ADMIN_SHELL is set.
 *
 * Before HTML: call myomr_module_page_start($pageTitle, $activeNav)
 * After content: call myomr_module_page_end()
 */
declare(strict_types=1);

require_once __DIR__ . '/module-router.php';

function myomr_module_page_start(string $pageTitle, string $activeNav = '', array $extraHead = []): void
{
    if (!myomr_module_using_shell()) {
        return;
    }
    $GLOBALS['pageTitle'] = $pageTitle;
    $GLOBALS['activeNav'] = $activeNav;
    $GLOBALS['myomr_module_extra_head'] = $extraHead;
    ob_start();
}

function myomr_module_page_end(): void
{
    if (!myomr_module_using_shell()) {
        return;
    }
    $content = ob_get_clean();
    myomr_module_shell_open($GLOBALS['pageTitle'] ?? 'Admin', $GLOBALS['activeNav'] ?? '');
    echo $content;
    myomr_module_shell_close();
}

function myomr_module_legacy_head(string $title, array $cssLinks = []): void
{
    if (myomr_module_using_shell()) {
        myomr_module_page_start($title);
        return;
    }
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">';
    foreach ($cssLinks as $href) {
        echo '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">';
    }
    echo '</head><body>';
}

function myomr_module_legacy_foot(): void
{
    if (myomr_module_using_shell()) {
        myomr_module_page_end();
        return;
    }
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}
