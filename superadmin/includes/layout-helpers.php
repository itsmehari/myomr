<?php
/**
 * Shared nav helpers for superadmin unified layout.
 */
declare(strict_types=1);

if (!function_exists('sa_layout_nav_sections')) {
    function sa_layout_nav_sections(): array {
        $navigationConfig = require dirname(__DIR__) . '/config/navigation.php';
        return array_map(static function (array $section) {
            $links = [];
            foreach ($section['modules'] as $module) {
                $links[] = [
                    'href' => $module['path'],
                    'label' => $module['name'],
                    'icon' => $module['icon'] ?? 'fa-circle',
                    'is_action' => false,
                ];
                foreach ($module['actions'] ?? [] as $action) {
                    $links[] = [
                        'href' => $action['path'],
                        'label' => $action['label'],
                        'icon' => $action['icon'] ?? 'fa-circle-dot',
                        'is_action' => true,
                    ];
                }
            }
            return [
                'label' => $section['label'],
                'icon' => $section['icon'] ?? 'fa-circle',
                'links' => $links,
            ];
        }, $navigationConfig);
    }
}

if (!function_exists('sa_layout_nav_is_active')) {
    function sa_layout_nav_is_active(string $href, string $currentUri): bool {
        $path = parse_url($href, PHP_URL_PATH) ?: $href;
        if ($path === $currentUri) {
            return true;
        }
        if ($path !== '/' && str_starts_with($currentUri, rtrim($path, '/') . '/')) {
            return true;
        }
        return false;
    }
}
