<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'COMMUNITY_EVENTS_ADMIN_ROUTED',
    'urls' => array (
  'COMMUNITY_EVENTS_ADMIN_INDEX_URL' => '/superadmin/community-events/index.php',
  'COMMUNITY_EVENTS_ADMIN_MANAGE_URL' => '/superadmin/community-events/manage-events-omr.php',
  'COMMUNITY_EVENTS_ADMIN_LISTINGS_URL' => '/superadmin/community-events/view-listings.php',
  'COMMUNITY_EVENTS_ADMIN_CALENDAR_URL' => '/superadmin/community-events/calendar-export.php',
  'COMMUNITY_EVENTS_ADMIN_DIGEST_URL' => '/superadmin/community-events/email-digest.php',
  'COMMUNITY_EVENTS_ADMIN_PLAYBOOK_URL' => '/superadmin/community-events/share-playbook.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-local-events/admin/process-delete-listing.php',
    'activeNav' => '/superadmin/community-events/',
]);
