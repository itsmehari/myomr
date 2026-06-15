<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$phpOpen = '<' . '?php';

foreach (glob($root . '/omr-rent-lease/admin/*.php') as $file) {
    if (basename($file) === '_urls.php') {
        continue;
    }
    $c = file_get_contents($file);
    if ($c === false) {
        continue;
    }
    $orig = $c;

    $c = preg_replace(
        '/if \(defined\(\'MYOMR_ADMIN_SHELL\'\) && MYOMR_ADMIN_SHELL\) \{[^}]+\} else \{\s*\n/s',
        '',
        $c
    );

    $c = str_replace(
        $phpOpen . " if (defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL) { ?>\n"
        . $phpOpen . " include __DIR__ . '/../../superadmin/includes/admin-shell-close.php'; ?>\n"
        . $phpOpen . " } else { ?>\n</body>\n</html>\n"
        . $phpOpen . ' } ?>',
        $phpOpen . " if (myomr_module_using_shell()) { ?>\n"
        . $phpOpen . " myomr_module_shell_close(); ?>\n"
        . $phpOpen . " } else { ?>\n</body>\n</html>\n"
        . $phpOpen . ' } ?>',
        $c
    );

    if ($c !== $orig) {
        file_put_contents($file, $c);
        echo str_replace('\\', '/', substr($file, strlen($root) + 1)) . "\n";
    }
}
