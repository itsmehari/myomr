<?php
/**
 * MyOMR Google Analytics 4 — Smart Include
 * Measurement ID: G-JYSF141J1H
 *
 * Set any of these PHP variables BEFORE including this file:
 *
 *   $ga_content_group   (string)  — override auto-detected section
 *   $ga_custom_params   (array)   — event-scoped custom dimensions
 *                                   e.g. ['listing_type' => 'school', 'locality' => 'Perungudi']
 *   $ga_user_properties (array)   — user-scoped properties
 *                                   e.g. ['user_type' => 'employer']
 *   $ga_user_id         (int)     — logged-in user ID for cross-device tracking
 *                                   e.g. $ga_user_id = $employerId;
 */
function _ga_detect_content_group(): string {
    if (isset($GLOBALS['ga_content_group']) && $GLOBALS['ga_content_group'] !== '') {
        return $GLOBALS['ga_content_group'];
    }
    $path = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($path, '/omr-local-job-listings/') !== false) return 'Job Listings';
    if (strpos($path, '/omr-local-events/')        !== false) return 'Local Events';
    if (strpos($path, '/omr-coworking-spaces/')    !== false) return 'Coworking Spaces';
    if (strpos($path, '/omr-hostels-pgs/')         !== false) return 'Hostels & PGs';
    if (strpos($path, '/omr-listings/')            !== false) return 'Listings Directory';
    if (strpos($path, '/local-news/')              !== false) return 'Local News';
    if (strpos($path, '/discover-myomr/')          !== false) return 'Discover MyOMR';
    if (strpos($path, '/info/')                    !== false) return 'Info & Civic';
    if (strpos($path, '/listings/')                !== false) return 'Listings Forms';
    if (strpos($path, '/weblog/')                  !== false) return 'Blog & Articles';
    if (strpos($path, '/pentahive/')               !== false) return 'Pentahive';
    if (strpos($path, '/events/')                  !== false) return 'Events Legacy';
    return 'Homepage';
}

$_ga_cg         = htmlspecialchars(_ga_detect_content_group(), ENT_QUOTES, 'UTF-8');
$_ga_custom     = (isset($ga_custom_params)    && is_array($ga_custom_params))    ? $ga_custom_params    : [];
$_ga_user_props = (isset($ga_user_properties) && is_array($ga_user_properties)) ? $ga_user_properties : [];

// User ID for cross-device tracking (pass via $ga_user_id before include)
$_ga_uid = isset($ga_user_id) ? (int)$ga_user_id : 0;

// Internal traffic: auto-flag admin sessions so they can be filtered in GA4 Admin
$_ga_internal = (isset($_SESSION) && !empty($_SESSION['admin_logged_in']));

// Safe JSON flags: escape HTML entities to prevent XSS
if (!defined('_GA_JSON_FLAGS')) {
    define('_GA_JSON_FLAGS', JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JYSF141J1H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-JYSF141J1H', {
    'content_group': '<?= $_ga_cg ?>'<?php if ($_ga_uid): ?>,
    'user_id': '<?= $_ga_uid ?>'<?php endif; ?>
  });
<?php if (!empty($_ga_custom)): ?>
  gtag('set', <?= json_encode($_ga_custom, _GA_JSON_FLAGS) ?>);
<?php endif; ?>
<?php if (!empty($_ga_user_props)): ?>
  gtag('set', 'user_properties', <?= json_encode($_ga_user_props, _GA_JSON_FLAGS) ?>);
<?php endif; ?>
<?php if ($_ga_internal): ?>
  gtag('set', {'traffic_type': 'internal'});
<?php endif; ?>
</script>
<!-- End Google Analytics -->
