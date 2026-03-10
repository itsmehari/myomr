<?php
/**
 * CISO / Ethical Hacker: Third-Party Script Isolation Audit
 *
 * Purpose: Identify which third-party causes the directfwd/sk-jspark malware injection.
 * Method:  Add third parties ONE BY ONE. If directfwd appears when you add X, X is the culprit.
 *
 * Usage:
 *   Bare minimum:     https://myomr.in/security-audit-thirdparty.php
 *   Add one:          ?add=bootstrap | fontawesome | googlefonts | bootstrapjs | jquery | ga
 *   Add multiple:     ?add=bootstrap,fontawesome,ga
 *   Add all:          ?add=all
 *
 * Test procedure:
 *   1. Open DevTools (F12) → Console + Network tabs
 *   2. Filter Network by "directfwd" or "jsinit"
 *   3. Visit each URL below. Record: directfwd present? YES/NO
 *   4. The first test where directfwd APPEARS = culprit
 *
 * ⚠️ DELETE OR RESTRICT this file after audit. Do not expose in production long-term.
 */
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store, no-cache, must-revalidate');

$add_raw = isset($_GET['add']) ? trim($_GET['add']) : '';
$add_list = array_filter(array_map('trim', $add_raw ? explode(',', strtolower($add_raw)) : []));

if (in_array('all', $add_list)) {
    $add_list = ['bootstrap', 'fontawesome', 'googlefonts', 'bootstrapjs', 'jquery', 'ga'];
}

$thirdparties = [
    'bootstrap'    => ['label' => 'Bootstrap CSS', 'in_head' => true,  'tag' => 'link', 'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css'],
    'fontawesome'  => ['label' => 'Font Awesome',  'in_head' => true,  'tag' => 'link', 'url' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'],
    'googlefonts'  => ['label' => 'Google Fonts',  'in_head' => true,  'tag' => 'link', 'url' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap'],
    'bootstrapjs'  => ['label' => 'Bootstrap JS',  'in_head' => false, 'tag' => 'script', 'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'],
    'jquery'       => ['label' => 'jQuery',       'in_head' => false, 'tag' => 'script', 'url' => 'https://code.jquery.com/jquery-3.6.0.min.js'],
    'ga'           => ['label' => 'Google Analytics (gtag)', 'in_head' => true, 'tag' => 'ga', 'url' => 'googletagmanager.com/gtag/js'],
];

$enabled = [];
foreach ($add_list as $k) {
    if (isset($thirdparties[$k])) {
        $enabled[$k] = $thirdparties[$k];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Security Audit: Third-Party Isolation | MyOMR</title>
<style>
* { box-sizing: border-box; }
body { font-family: 'Consolas', 'Monaco', monospace; background: #0d1117; color: #c9d1d9; margin: 0; padding: 20px; line-height: 1.5; }
h1 { color: #58a6ff; font-size: 1.2rem; }
h2 { color: #79c0ff; font-size: 1rem; margin-top: 1.5rem; }
.banner { background: #161b22; border: 1px solid #30363d; border-left: 4px solid #f85149; padding: 12px 16px; margin-bottom: 20px; }
.banner.warn { border-left-color: #d29922; }
.banner.ok { border-left-color: #3fb950; }
.banner code { background: #21262d; padding: 2px 6px; }
table { border-collapse: collapse; width: 100%; max-width: 640px; }
th, td { border: 1px solid #30363d; padding: 8px 12px; text-align: left; }
th { background: #21262d; color: #8b949e; }
a { color: #58a6ff; text-decoration: none; }
a:hover { text-decoration: underline; }
.links { margin-top: 12px; }
.links a { display: inline-block; margin-right: 12px; margin-bottom: 8px; }
.badge { display: inline-block; background: #238636; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.85em; }
.badge.active { background: #da3633; }
</style>
<?php
// Inject third parties in <head>
foreach ($enabled as $k => $tp) {
    if (!$tp['in_head']) continue;
    if ($tp['tag'] === 'ga') {
        echo "<!-- GA4 gtag -->\n";
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=G-JYSF141J1H"></script>' . "\n";
        echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-JYSF141J1H');</script>\n";
    } elseif ($tp['tag'] === 'link') {
        echo '<link rel="stylesheet" href="' . htmlspecialchars($tp['url']) . '">' . "\n";
    }
}
?>
</head>
<body>

<div class="banner <?php echo empty($enabled) ? 'ok' : 'warn'; ?>">
  <strong>🔒 CISO Audit: Third-Party Isolation Test</strong><br>
  Active: <?php echo empty($enabled) ? '<code>none</code> (baseline)' : implode(', ', array_keys($enabled)); ?>
  <br>
  <strong>Instructions:</strong> Open DevTools (F12) → Console &amp; Network. Look for <code>directfwd</code> or <code>jsinit</code>. If present → culprit found.
</div>

<h1>Test Matrix</h1>
<p>Add third parties one at a time. First test where directfwd appears = culprit.</p>

<table>
<thead>
<tr><th>Test</th><th>URL</th><th>directfwd?</th></tr>
</thead>
<tbody>
<tr><td>0. Baseline</td><td><a href="?">security-audit-thirdparty.php</a></td><td>__</td></tr>
<?php foreach (array_keys($thirdparties) as $k): ?>
<tr>
  <td><?php echo $thirdparties[$k]['label']; ?></td>
  <td><a href="?add=<?php echo urlencode($k); ?>">?add=<?php echo htmlspecialchars($k); ?></a></td>
  <td>__</td>
</tr>
<?php endforeach; ?>
<tr><td>All combined</td><td><a href="?add=all">?add=all</a></td><td>__</td></tr>
</tbody>
</table>

<h2>Current config</h2>
<p>You are viewing: <code>?add=<?php echo htmlspecialchars($add_raw ?: '(none)'); ?></code></p>

<?php if (!empty($enabled)): ?>
<ul>
<?php foreach ($enabled as $k => $tp): ?>
<li><span class="badge active"><?php echo htmlspecialchars($tp['label']); ?></span> → <?php echo htmlspecialchars($tp['url']); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<div class="links">
  <a href="?">Clear all</a>
  <a href="/">Back to homepage</a>
</div>

<!-- Body scripts (Bootstrap JS, jQuery) -->
<?php
foreach ($enabled as $k => $tp) {
    if ($tp['in_head'] || $tp['tag'] !== 'script') continue;
    echo '<script src="' . htmlspecialchars($tp['url']) . '"></script>' . "\n";
}
?>

<!-- Monitor: log external requests for debugging -->
<script>
(function(){
  var origFetch = window.fetch;
  var origXHROpen = XMLHttpRequest.prototype.open;
  if (origFetch) {
    window.fetch = function(url) {
      var u = typeof url === 'string' ? url : (url && url.url) || '';
      if (u.indexOf('directfwd') !== -1 || u.indexOf('jsinit') !== -1) {
        console.error('[AUDIT] MALICIOUS REQUEST DETECTED:', u);
      }
      return origFetch.apply(this, arguments);
    };
  }
  XMLHttpRequest.prototype.open = function(method, url) {
    if (typeof url === 'string' && (url.indexOf('directfwd') !== -1 || url.indexOf('jsinit') !== -1)) {
      console.error('[AUDIT] MALICIOUS XHR:', url);
    }
    return origXHROpen.apply(this, arguments);
  };
  // Log script/link loads (can't intercept, but document what we loaded)
  var scripts = document.querySelectorAll('script[src], link[rel="stylesheet"][href]');
  console.log('[AUDIT] Third-party resources loaded:', Array.from(scripts).map(function(s){ return s.src || s.href; }));
})();
</script>
</body>
</html>
