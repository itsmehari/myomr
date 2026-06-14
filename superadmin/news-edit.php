<?php
/**
 * Legacy news_bulletin edit — retired. Edit via News Articles.
 * Optional: ?id=N attempts articles search by legacy slug.
 */
$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    header('Location: /superadmin/articles/index.php?q=legacy-bulletin-' . $id, true, 302);
    exit;
}
header('Location: /superadmin/articles/index.php', true, 301);
exit;
