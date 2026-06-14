<?php
declare(strict_types=1);

if (!defined('COMMUNITY_EVENTS_ADMIN_INDEX_URL')) {
    define('COMMUNITY_EVENTS_ADMIN_INDEX_URL', '/superadmin/community-events/index.php');
    define('COMMUNITY_EVENTS_ADMIN_MANAGE_URL', '/superadmin/community-events/manage-events-omr.php');
    define('COMMUNITY_EVENTS_ADMIN_LISTINGS_URL', '/superadmin/community-events/view-listings.php');
    define('COMMUNITY_EVENTS_ADMIN_CALENDAR_URL', '/superadmin/community-events/calendar-export.php');
    define('COMMUNITY_EVENTS_ADMIN_DIGEST_URL', '/superadmin/community-events/email-digest.php');
    define('COMMUNITY_EVENTS_ADMIN_PLAYBOOK_URL', '/superadmin/community-events/share-playbook.php');
}

function community_events_admin_process_url(string $script): string
{
    return '/superadmin/community-events/' . ltrim($script, '/');
}

function community_events_admin_manage_url(): string
{
    return COMMUNITY_EVENTS_ADMIN_MANAGE_URL;
}

function community_events_admin_listings_url(): string
{
    return COMMUNITY_EVENTS_ADMIN_LISTINGS_URL;
}
