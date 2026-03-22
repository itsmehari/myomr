<?php
/**
 * Single source of truth for MyOMR community outbound links (WhatsApp invite, etc.).
 * Include from pages/footer so the invite URL never drifts across the site.
 */

if (!defined('MYOMR_WHATSAPP_INVITE_URL')) {
    define('MYOMR_WHATSAPP_INVITE_URL', 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi');
}

if (!defined('MYOMR_WHATSAPP_GROUP_URL')) {
    define('MYOMR_WHATSAPP_GROUP_URL', MYOMR_WHATSAPP_INVITE_URL);
}

/**
 * Invite URL with UTM parameters for GA4 / campaign attribution.
 *
 * @param array<string, string> $overrides Optional utm_* or other query keys
 */
function myomr_whatsapp_invite_url_with_utm(array $overrides = []): string
{
    $defaults = [
        'utm_source'   => 'myomr_site',
        'utm_medium'   => 'referral',
        'utm_campaign' => 'omr_whatsapp_community',
    ];
    $params = array_merge($defaults, $overrides);
    $base = MYOMR_WHATSAPP_INVITE_URL;
    $q = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

    return $base . (strpos($base, '?') !== false ? '&' : '?') . $q;
}
