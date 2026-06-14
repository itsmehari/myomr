<?php
/**
 * Backwards-compatible module registry.
 * Flattens the unified navigation config so legacy includes can continue consuming it.
 */

$navSections = require __DIR__ . '/config/navigation.php';

$modules = [];
foreach ($navSections as $section) {
    foreach ($section['modules'] as $module) {
        $modules[$module['key']] = [
            'name' => $module['name'],
            'path' => $module['path'],
            'description' => $module['description'],
            'icon' => $module['icon'] ?? null,
            'section' => $section['label'],
            'tags' => $module['tags'] ?? [],
            'actions' => $module['actions'] ?? [],
        ];
    }
}

return $modules;

