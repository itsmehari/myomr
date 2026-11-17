<?php
/**
 * Private WhatsApp enquiry hop for the print-offer banner.
 * No HTML body. Not for search. Do not add to the sitemap.
 */
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/core/print-offer-enquiry.php';

header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex');
header('Cache-Control: private, no-store');
header('Referrer-Policy: no-referrer');
header('Location: ' . myomr_print_offer_whatsapp_url(), true, 302);
exit;
