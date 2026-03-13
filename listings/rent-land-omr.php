<?php
/**
 * Permanent redirect to Rent & Lease module – List Land
 * Legacy URL: listings/rent-land-omr.php → omr-rent-lease/add-property (type=rent-land)
 */
header('Location: /omr-rent-lease/add-property-omr.php?type=rent-land', true, 301);
exit;
