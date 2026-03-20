<?php
/**
 * Email open tracking pixel — returns 1x1 transparent GIF.
 * Used in job posting / employer promotional emails.
 *
 * URL params (optional, for segmentation):
 *   c = campaign (e.g. job_posting_promo)
 *   t = template (e.g. employer_email)
 *
 * Note: Many email clients block images by default. When enabled, this fires.
 */
header('Content-Type: image/gif');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
// 1x1 transparent GIF (43 bytes)
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
exit;
