<?php
/**
 * Legacy “About My OMR” URL used in main nav, megamenu, and emails.
 * Canonical about content lives at discover-myomr/overview.php (deployed with discover-myomr/).
 *
 * @see docs/inbox/INDEX-PAGE-REVIEW-25-02-2026.md
 */
header('Location: /discover-myomr/overview.php', true, 301);
exit;
