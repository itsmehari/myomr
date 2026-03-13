<?php
/**
 * MyOMR Google Analytics 4 — Base Include
 * Measurement ID: G-JYSF141J1H
 *
 * Include in <head>. Tracks page views + basic content grouping.
 *
 * Optional (set BEFORE including):
 *   $ga_content_group   (string) — override auto content group
 *   $ga_custom_params   (array)  — custom dimensions, e.g. ['article_category' => 'Local News']
 *   $ga_user_properties (array)  — user properties, e.g. ['user_type' => 'employer']
 *   $ga_user_id         (int)    — logged-in user ID for cross-device
 */
$ga_id = 'G-JYSF141J1H';

// Content group: override or auto-detect from URL
if (!empty($ga_content_group) && is_string($ga_content_group)) {
    $ga_content_group = htmlspecialchars($ga_content_group, ENT_QUOTES, 'UTF-8');
} else {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    $map = [
        '/omr-local-job-listings/' => 'Job Listings',
        '/omr-local-events/'       => 'Local Events',
        '/omr-coworking-spaces/'   => 'Coworking Spaces',
        '/omr-hostels-pgs/'        => 'Hostels & PGs',
        '/omr-rent-lease/'         => 'Rent & Lease',
        '/omr-listings/'           => 'Listings Directory',
        '/local-news/'             => 'Local News',
        '/discover-myomr/'         => 'Discover MyOMR',
        '/info/'                   => 'Info & Civic',
        '/listings/'               => 'Listings Forms',
        '/events/'                 => 'Events',
        '/pentahive/'              => 'Pentahive',
    ];
    $ga_content_group = 'Homepage';
    foreach ($map as $prefix => $label) {
        if (strpos($path, $prefix) !== false) {
            $ga_content_group = $label;
            break;
        }
    }
}

$ga_config = ['content_group' => $ga_content_group];
if (!empty($ga_user_id) && is_numeric($ga_user_id)) {
    $ga_config['user_id'] = (string)(int)$ga_user_id;
}
if (!empty($ga_custom_params) && is_array($ga_custom_params)) {
    $ga_config = array_merge($ga_config, $ga_custom_params);
}
$ga_user_props = (!empty($ga_user_properties) && is_array($ga_user_properties)) ? $ga_user_properties : [];

if (!defined('_GA_JSON_FLAGS')) {
    define('_GA_JSON_FLAGS', JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $ga_id ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?= $ga_id ?>', <?= json_encode($ga_config, _GA_JSON_FLAGS) ?>);
<?php if (!empty($ga_user_props)): ?>
gtag('set', 'user_properties', <?= json_encode($ga_user_props, _GA_JSON_FLAGS) ?>);
<?php endif; ?>
</script>
