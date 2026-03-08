<?php
/**
 * TEMPORARY SIMPLE INDEX - Server Load Test
 * 
 * Minimal page to verify the site loads. No includes, no DB, no external scripts.
 * Original homepage is saved as: index-main-backup.php
 * 
 * To restore: rename index-main-backup.php to index.php and replace this file.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyOMR.in - Test Page</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 600px; margin: 2rem auto; padding: 1rem; }
    h1 { color: #008552; }
    .ok { color: green; }
    p { line-height: 1.6; }
  </style>
</head>
<body>
  <h1>MyOMR.in – Test Page</h1>
  <p class="ok">If you see this, the site is loading.</p>
  <p>This is a temporary minimal page to verify server response. PHP is working.</p>
  <p>Server time: <?php echo date('Y-m-d H:i:s'); ?></p>
  <p><strong>To restore the full homepage:</strong> Rename <code>index-main-backup.php</code> to <code>index.php</code>.</p>
</body>
</html>
