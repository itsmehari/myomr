<?php
/**
 * Private print-offer enquiry hop.
 * Destination is for the 302 hop only — never echo it into HTML, schema, or sitemaps.
 */
if (!function_exists('myomr_print_offer_public_href')) {
    function myomr_print_offer_public_href(): string
    {
        return '/go/print-offer.php';
    }
}

if (!function_exists('myomr_print_offer_whatsapp_url')) {
    function myomr_print_offer_whatsapp_url(): string
    {
        $e164 = '91' . '7200' . '101' . '497';
        $text = 'Hi, I saw the MyOMR multicolor single-side print offer (20% off, 24-hour delivery in Chennai). I would like to enquire.';
        return 'https://wa.me/' . $e164 . '?text=' . rawurlencode($text);
    }
}
