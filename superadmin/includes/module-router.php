<?php
/**
 * Thin superadmin router — auth + constants + include module admin target.
 *
 * @param array{
 *   constant: string,
 *   urls?: array<string, string>,
 *   target: string,
 *   bootstrap?: string,
 *   activeNav?: string,
 *   pageTitle?: string
 * } $config
 */
declare(strict_types=1);

function myomr_module_router(array $config): void
{
    $bootstrap = $config['bootstrap'] ?? dirname(__DIR__) . '/_bootstrap.php';
    require_once $bootstrap;

    $constant = $config['constant'];
    if (!defined($constant)) {
        define($constant, true);
    }

    define('MYOMR_ADMIN_SHELL', true);

    foreach ($config['urls'] ?? [] as $name => $path) {
        if (!defined($name)) {
            define($name, $path);
        }
    }

    $target = $config['target'];
    if (!is_file($target)) {
        http_response_code(500);
        echo 'Admin module file not found.';
        exit;
    }

    ob_start();
    require $target;
    $html = (string) ob_get_clean();

    if ($html === '' || !preg_match('/<body[\s>]/i', $html)) {
        echo $html;
        return;
    }

    // Target already rendered unified shell (inline shell_open path).
    if (preg_match('/class=["\']sa-body["\']/', $html) || preg_match('/class=["\']sa-sidebar["\']/', $html)) {
        echo $html;
        return;
    }

    $pageTitle = $config['pageTitle'] ?? 'Superadmin';
    if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $titleMatch)) {
        $pageTitle = trim(html_entity_decode(strip_tags($titleMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $pageTitle = preg_replace('/\s*-\s*Admin.*$/i', '', $pageTitle) ?? $pageTitle;
        $pageTitle = preg_replace('/\s*\|\s*MyOMR.*$/i', '', $pageTitle) ?? $pageTitle;
    }

    $activeNav = $config['activeNav'] ?? '';
    $inner = $html;
    if (preg_match('/<body[^>]*>(.*)<\/body>\s*<\/html>/is', $html, $bodyMatch)) {
        $inner = $bodyMatch[1];
    }

    myomr_module_shell_open($pageTitle, $activeNav);
    echo $inner;
    myomr_module_shell_close();
}

function myomr_module_require_routed(string $constant, string $superadminPath): void
{
    if (defined($constant)) {
        return;
    }
    $q = $_SERVER['QUERY_STRING'] ?? '';
    $url = $superadminPath . ($q !== '' ? '?' . $q : '');
    header('Location: ' . $url, true, 302);
    exit;
}

function myomr_module_shell_open(string $pageTitle, string $activeNav = '', array $breadcrumbs = []): void
{
    if (!defined('MYOMR_ADMIN_SHELL')) {
        return;
    }
    extract([
        'pageTitle' => $pageTitle,
        'activeNav' => $activeNav,
        'breadcrumbs' => $breadcrumbs,
    ], EXTR_SKIP);
    include dirname(__DIR__) . '/includes/admin-shell-open.php';
}

function myomr_module_shell_close(): void
{
    if (!defined('MYOMR_ADMIN_SHELL')) {
        return;
    }
    include dirname(__DIR__) . '/includes/admin-shell-close.php';
    exit;
}

function myomr_module_using_shell(): bool
{
    return defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL;
}
