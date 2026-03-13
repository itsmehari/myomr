<?php
/**
 * Permanent redirect to Rent & Lease module – List House
 * Legacy URL: listings/rent-house-omr.php → omr-rent-lease/add-property (type=rent-house)
 */
header('Location: /omr-rent-lease/add-property-omr.php?type=rent-house', true, 301);
exit;
