<?php
/**
 * Fix shell brace structure in module admin HTML pages (jobs-index pattern).
 * Usage: php dev-tools/qa/fix-shell-braces.php [--write]
 */
declare(strict_types=1);

$write = in_array('--write', $argv, true);
$root = dirname(__DIR__, 2);
$fixed = 0;
$phpOpen = '<' . '?php';

foreach (glob($root . '/omr-*/admin/*.php') as $file) {
    if (basename($file) === '_urls.php') {
        continue;
    }
    $c = file_get_contents($file);
    if ($c === false || !str_contains($c, 'myomr_module_shell_open')) {
        continue;
    }
    $orig = $c;

    // Close opening "} else {" right after <body> when missing.
    if (!preg_match('/<body[^>]*>\s*' . preg_quote($phpOpen, '/') . '\s*\}\s*\?>/i', $c)) {
        $c = preg_replace(
            '/(<body[^>]*>\s*\r?\n)/i',
            "$1{$phpOpen} } ?>\n",
            $c,
            1
        );
    }

    $badFooter = $phpOpen . "\nif (myomr_module_using_shell()) {\n    myomr_module_shell_close();\n} else {\n?>\n</body></html>\n" . $phpOpen . ' }';
    $goodFooter = $phpOpen . " if (myomr_module_using_shell()) { ?>\n" . $phpOpen . " myomr_module_shell_close(); ?>\n" . $phpOpen . " } else { ?>\n</body></html>\n" . $phpOpen . ' } ?>';
    $c = str_replace($badFooter, $goodFooter, $c);

    $badFooter2 = $phpOpen . "\nif (myomr_module_using_shell()) {\n    myomr_module_shell_close();\n} else {\n?>\n</body>\n</html>\n" . $phpOpen . ' }';
    $goodFooter2 = $phpOpen . " if (myomr_module_using_shell()) { ?>\n" . $phpOpen . " myomr_module_shell_close(); ?>\n" . $phpOpen . " } else { ?>\n</body>\n</html>\n" . $phpOpen . ' } ?>';
    $c = str_replace($badFooter2, $goodFooter2, $c);

    if ($c !== $orig) {
        $rel = str_replace('\\', '/', substr($file, strlen($root) + 1));
        echo ($write ? 'Fixed: ' : 'Would fix: ') . $rel . "\n";
        if ($write) {
            file_put_contents($file, $c);
        }
        $fixed++;
    }
}

echo $write ? "Fixed {$fixed} file(s).\n" : "Dry run — {$fixed} file(s).\n";
