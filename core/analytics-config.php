<?php
/**
 * GA4 + Clarity — measurement IDs (public, sent to browsers).
 *
 * Change defaults here, or on the server set:
 *   MYOMR_GA_MEASUREMENT_ID  (e.g. G-XXXXXXXXXX)
 *   MYOMR_CLARITY_ID         (Clarity project id)
 *
 * GA4: Admin → Data streams → Web → Measurement ID
 *
 * Data API (server-side reports): numeric Property ID — Admin → Property settings.
 * Override with MYOMR_GA_PROPERTY_ID; default matches the live MyOMR GA4 property.
 */
if (!function_exists('myomr_ga4_property_id')) {
    function myomr_ga4_property_id(): string
    {
        static $id = null;
        if ($id !== null) {
            return $id;
        }
        $env = getenv('MYOMR_GA_PROPERTY_ID');
        if (is_string($env)) {
            $env = trim($env);
            if ($env !== '' && preg_match('/^\d+$/', $env)) {
                $id = $env;
                return $id;
            }
        }
        $id = '510768072';
        return $id;
    }
}

if (!function_exists('myomr_ga_measurement_id')) {
    function myomr_ga_measurement_id(): string
    {
        static $id = null;
        if ($id !== null) {
            return $id;
        }
        $env = getenv('MYOMR_GA_MEASUREMENT_ID');
        $id = (is_string($env) && $env !== '') ? $env : 'G-JYSF141J1H';
        return $id;
    }
}

if (!function_exists('myomr_clarity_id')) {
    function myomr_clarity_id(): string
    {
        static $id = null;
        if ($id !== null) {
            return $id;
        }
        $env = getenv('MYOMR_CLARITY_ID');
        $id = (is_string($env) && $env !== '') ? $env : 'vnpelcljv4';
        return $id;
    }
}
