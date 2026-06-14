<?php
declare(strict_types=1);

/**
 * Aggregates live counts and recent rows for the superadmin dashboard.
 * Each query fails gracefully if a table or column is missing on the server.
 */

function sa_dash_count(mysqli $conn, string $sql): int
{
    try {
        $r = $conn->query($sql);
        if ($r && $r->num_rows > 0) {
            return (int) $r->fetch_row()[0];
        }
    } catch (Throwable $e) {
        // table/column may not exist on this deployment
    }

    return 0;
}

function sa_dash_query(mysqli $conn, string $sql): ?mysqli_result
{
    try {
        $r = $conn->query($sql);
        return ($r instanceof mysqli_result) ? $r : null;
    } catch (Throwable $e) {
        return null;
    }
}

function sa_dash_table_exists(mysqli $conn, string $table): bool
{
    $stmt = $conn->prepare('SHOW TABLES LIKE ?');
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $ok = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();

    return $ok;
}

function sa_dash_trim(?string $text, int $width): string
{
    $text = (string) $text;
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $width, '…');
    }

    return strlen($text) > $width ? substr($text, 0, max(0, $width - 1)) . '…' : $text;
}

/**
 * @return array<string, mixed>
 */
function sa_dash_collect_metrics(mysqli $conn): array
{
    $stats = [
        'articles_published' => sa_dash_count($conn, "SELECT COUNT(*) FROM articles WHERE status='published'"),
        'articles_draft'     => sa_dash_count($conn, "SELECT COUNT(*) FROM articles WHERE status='draft'"),
        'events_scheduled'   => sa_dash_count($conn, "SELECT COUNT(*) FROM event_listings WHERE status='scheduled'"),
        'events_pending'     => sa_dash_count($conn, "SELECT COUNT(*) FROM event_listings WHERE status='pending'"),
        'events_total'       => sa_dash_count($conn, 'SELECT COUNT(*) FROM event_listings'),
        'jobs_active'        => sa_dash_count($conn, "SELECT COUNT(*) FROM job_postings WHERE status='active'"),
        'jobs_pending'       => sa_dash_count($conn, "SELECT COUNT(*) FROM job_postings WHERE status='pending'"),
        'jobs_total'         => sa_dash_count($conn, 'SELECT COUNT(*) FROM job_postings'),
        'employers_total'    => sa_dash_count($conn, 'SELECT COUNT(*) FROM employers'),
        'employers_pending'  => sa_dash_count($conn, "SELECT COUNT(*) FROM employers WHERE status='pending'"),
        'job_seekers'        => sa_dash_count($conn, 'SELECT COUNT(*) FROM job_seeker_profiles'),
        'job_applications'   => sa_dash_count($conn, 'SELECT COUNT(*) FROM job_applications'),
        'coworking_active'   => sa_dash_count($conn, "SELECT COUNT(*) FROM coworking_spaces WHERE status='active'"),
        'coworking_pending'  => sa_dash_count($conn, "SELECT COUNT(*) FROM coworking_spaces WHERE status='pending'"),
        'hostels_active'     => sa_dash_count($conn, "SELECT COUNT(*) FROM hostels_pgs WHERE status='active'"),
        'hostels_pending'    => sa_dash_count($conn, "SELECT COUNT(*) FROM hostels_pgs WHERE status='pending'"),
        'rent_lease_total'   => sa_dash_count($conn, 'SELECT COUNT(*) FROM rent_lease_properties'),
        'rent_lease_pending' => sa_dash_count($conn, "SELECT COUNT(*) FROM rent_lease_properties WHERE status='pending'"),
        'buy_sell_total'     => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_buy_sell_listings'),
        'buy_sell_active'    => sa_dash_count($conn, "SELECT COUNT(*) FROM omr_buy_sell_listings WHERE status='active'"),
        'classified_total'   => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_classified_ads_listings'),
        'classified_pending' => sa_dash_count($conn, "SELECT COUNT(*) FROM omr_classified_ads_listings WHERE status='pending'"),
        'classified_users'   => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_classified_ads_users'),
        'restaurants'        => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_restaurants'),
        'schools'            => sa_dash_count($conn, 'SELECT COUNT(*) FROM omrschoolslist'),
        'hospitals'          => sa_dash_count($conn, 'SELECT COUNT(*) FROM omrhospitalslist'),
        'banks'              => sa_dash_count($conn, 'SELECT COUNT(*) FROM omrbankslist'),
        'atms'               => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_atms'),
        'parks'              => sa_dash_count($conn, 'SELECT COUNT(*) FROM omrparkslist'),
        'industries'         => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_industries'),
        'govt_offices'       => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_gov_offices'),
        'it_companies'       => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_it_companies'),
        'it_submissions'     => sa_dash_count($conn, "SELECT COUNT(*) FROM omr_it_company_submissions WHERE status='pending'"),
        'newsletter'         => sa_dash_count($conn, 'SELECT COUNT(*) FROM omr_newsletter_subscribers'),
        'hostel_owners'      => sa_dash_count($conn, 'SELECT COUNT(*) FROM property_owners'),
    ];

    $stats['pending_total'] = $stats['events_pending'] + $stats['jobs_pending']
        + $stats['coworking_pending'] + $stats['hostels_pending'] + $stats['rent_lease_pending']
        + $stats['classified_pending'] + $stats['employers_pending'];

    $stats['listings_total'] = $stats['jobs_total'] + $stats['hostels_active'] + $stats['coworking_active']
        + $stats['rent_lease_total'] + $stats['buy_sell_total'] + $stats['classified_total'];

    $stats['content_total'] = $stats['articles_published'] + $stats['events_total'];

    $stats['directory_total'] = $stats['restaurants'] + $stats['schools'] + $stats['hospitals']
        + $stats['banks'] + $stats['atms'] + $stats['parks'] + $stats['industries']
        + $stats['govt_offices'] + $stats['it_companies'];

    return $stats;
}

/**
 * @return array<string, mysqli_result|null>
 */
function sa_dash_collect_recent(mysqli $conn): array
{
    return [
        'articles'      => sa_dash_query($conn, "SELECT id, title, published_date, status FROM articles ORDER BY COALESCE(published_date, created_at) DESC LIMIT 6"),
        'events'        => sa_dash_query($conn, "SELECT id, title, start_datetime, status FROM event_listings ORDER BY created_at DESC LIMIT 6"),
        'jobs'          => sa_dash_query($conn, "SELECT j.id, j.title, j.status, j.created_at, e.company_name FROM job_postings j LEFT JOIN employers e ON j.employer_id = e.id ORDER BY j.created_at DESC LIMIT 6"),
        'job_seekers'   => sa_dash_query($conn, 'SELECT id, full_name, email, preferred_locality, experience_level, created_at FROM job_seeker_profiles ORDER BY created_at DESC LIMIT 6'),
        'employers'     => sa_dash_query($conn, "SELECT id, company_name, status, created_at FROM employers ORDER BY created_at DESC LIMIT 6"),
        'classified'    => sa_dash_query($conn, "SELECT id, title, status, created_at FROM omr_classified_ads_listings ORDER BY created_at DESC LIMIT 6"),
        'buy_sell'      => sa_dash_query($conn, "SELECT id, title, status, created_at FROM omr_buy_sell_listings ORDER BY created_at DESC LIMIT 6"),
        'rent_lease'    => sa_dash_query($conn, "SELECT id, title, listing_type, locality, status, created_at FROM rent_lease_properties ORDER BY created_at DESC LIMIT 6"),
        'hostels'       => sa_dash_query($conn, "SELECT id, property_name, locality, status, created_at FROM hostels_pgs ORDER BY created_at DESC LIMIT 6"),
        'pending_jobs'  => sa_dash_query($conn, "SELECT j.id, j.title, e.company_name, j.created_at FROM job_postings j LEFT JOIN employers e ON j.employer_id = e.id WHERE j.status='pending' ORDER BY j.created_at DESC LIMIT 8"),
    ];
}

/**
 * Module cards for the dashboard grid — counts filled from $stats.
 *
 * @return list<array<string, mixed>>
 */
function sa_dash_module_cards(array $stats): array
{
    return [
        [
            'group' => 'Content',
            'key'   => 'articles',
            'name'  => 'News Articles',
            'icon'  => 'fa-newspaper',
            'color' => '#2563eb',
            'bg'    => '#dbeafe',
            'total' => $stats['articles_published'],
            'meta'  => $stats['articles_draft'] > 0 ? $stats['articles_draft'] . ' drafts' : 'Published on /local-news/',
            'pending' => $stats['articles_draft'],
            'href'  => '/superadmin/articles/index.php',
            'manage'=> '/superadmin/news-publisher.php',
        ],
        [
            'group' => 'Content',
            'key'   => 'events',
            'name'  => 'Events',
            'icon'  => 'fa-calendar-days',
            'color' => '#15803d',
            'bg'    => '#dcfce7',
            'total' => $stats['events_total'],
            'meta'  => $stats['events_scheduled'] . ' scheduled',
            'pending' => $stats['events_pending'],
            'href'  => '/omr-local-events/admin/view-listings.php',
            'manage'=> '/omr-local-events/admin/manage-events-omr.php',
        ],
        [
            'group' => 'Jobs',
            'key'   => 'jobs',
            'name'  => 'Job Posts',
            'icon'  => 'fa-briefcase',
            'color' => '#a16207',
            'bg'    => '#fef9c3',
            'total' => $stats['jobs_total'],
            'meta'  => $stats['jobs_active'] . ' active',
            'pending' => $stats['jobs_pending'],
            'href'  => '/superadmin/jobs/manage-jobs-omr.php',
            'manage'=> '/superadmin/jobs/manage-jobs-omr.php?status=pending',
        ],
        [
            'group' => 'Jobs',
            'key'   => 'employers',
            'name'  => 'Employers',
            'icon'  => 'fa-building',
            'color' => '#0369a1',
            'bg'    => '#e0f2fe',
            'total' => $stats['employers_total'],
            'meta'  => 'Registered companies',
            'pending' => $stats['employers_pending'],
            'href'  => '/superadmin/jobs/verify-employers-omr.php',
            'manage'=> '/superadmin/jobs/verify-employers-omr.php',
        ],
        [
            'group' => 'Jobs',
            'key'   => 'seekers',
            'name'  => 'Job Seekers',
            'icon'  => 'fa-user-graduate',
            'color' => '#7c3aed',
            'bg'    => '#ede9fe',
            'total' => $stats['job_seekers'],
            'meta'  => $stats['job_applications'] . ' applications',
            'pending' => 0,
            'href'  => '/superadmin/manage-job-seeker-profiles-omr.php',
            'manage'=> '/superadmin/jobs/view-all-applications-omr.php',
        ],
        [
            'group' => 'Marketplace',
            'key'   => 'classified',
            'name'  => 'Classified Ads',
            'icon'  => 'fa-bullhorn',
            'color' => '#c2410c',
            'bg'    => '#ffedd5',
            'total' => $stats['classified_total'],
            'meta'  => $stats['classified_users'] . ' users',
            'pending' => $stats['classified_pending'],
            'href'  => '/omr-classified-ads/admin/manage-listings-omr.php',
            'manage'=> '/omr-classified-ads/admin/index.php',
        ],
        [
            'group' => 'Marketplace',
            'key'   => 'buy_sell',
            'name'  => 'Buy & Sell',
            'icon'  => 'fa-shopping-bag',
            'color' => '#be185d',
            'bg'    => '#fce7f3',
            'total' => $stats['buy_sell_total'],
            'meta'  => $stats['buy_sell_active'] . ' active',
            'pending' => 0,
            'href'  => '/omr-buy-sell/admin/manage-listings-omr.php',
            'manage'=> '/omr-buy-sell/admin/index.php',
        ],
        [
            'group' => 'Marketplace',
            'key'   => 'rent',
            'name'  => 'Rent & Lease',
            'icon'  => 'fa-house',
            'color' => '#0d7a42',
            'bg'    => '#dcfce7',
            'total' => $stats['rent_lease_total'],
            'meta'  => 'Properties & land',
            'pending' => $stats['rent_lease_pending'],
            'href'  => '/superadmin/rent-lease-manage.php',
            'manage'=> '/superadmin/rent-lease.php',
        ],
        [
            'group' => 'Spaces',
            'key'   => 'hostels',
            'name'  => 'Hostels & PGs',
            'icon'  => 'fa-bed',
            'color' => '#be185d',
            'bg'    => '#fce7f3',
            'total' => $stats['hostels_active'] + $stats['hostels_pending'],
            'meta'  => $stats['hostel_owners'] . ' owners',
            'pending' => $stats['hostels_pending'],
            'href'  => '/omr-hostels-pgs/admin/manage-properties.php',
            'manage'=> '/omr-hostels-pgs/admin/index.php',
        ],
        [
            'group' => 'Spaces',
            'key'   => 'coworking',
            'name'  => 'Coworking',
            'icon'  => 'fa-desk',
            'color' => '#6d28d9',
            'bg'    => '#ede9fe',
            'total' => $stats['coworking_active'] + $stats['coworking_pending'],
            'meta'  => $stats['coworking_active'] . ' active',
            'pending' => $stats['coworking_pending'],
            'href'  => '/omr-coworking-spaces/admin/manage-spaces.php',
            'manage'=> '/omr-coworking-spaces/admin/index.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'restaurants',
            'name'  => 'Restaurants',
            'icon'  => 'fa-utensils',
            'color' => '#c2410c',
            'bg'    => '#ffedd5',
            'total' => $stats['restaurants'],
            'meta'  => 'Food & cafés',
            'pending' => 0,
            'href'  => '/superadmin/restaurants-list.php',
            'manage'=> '/superadmin/restaurants-add.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'schools',
            'name'  => 'Schools',
            'icon'  => 'fa-school',
            'color' => '#0369a1',
            'bg'    => '#e0f2fe',
            'total' => $stats['schools'],
            'meta'  => 'Education listings',
            'pending' => 0,
            'href'  => '/superadmin/schools-list.php',
            'manage'=> '/superadmin/manage-schools.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'hospitals',
            'name'  => 'Hospitals',
            'icon'  => 'fa-hospital',
            'color' => '#dc2626',
            'bg'    => '#fee2e2',
            'total' => $stats['hospitals'],
            'meta'  => 'Healthcare',
            'pending' => 0,
            'href'  => '/superadmin/hospitals-list.php',
            'manage'=> '/superadmin/manage-hospitals.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'it',
            'name'  => 'IT Companies',
            'icon'  => 'fa-laptop-code',
            'color' => '#166534',
            'bg'    => '#dcfce7',
            'total' => $stats['it_companies'],
            'meta'  => $stats['it_submissions'] . ' submissions',
            'pending' => $stats['it_submissions'],
            'href'  => '/superadmin/it-companies-list.php',
            'manage'=> '/superadmin/it-submissions-list.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'banks',
            'name'  => 'Banks & ATMs',
            'icon'  => 'fa-landmark',
            'color' => '#854d0e',
            'bg'    => '#fef3c7',
            'total' => $stats['banks'] + $stats['atms'],
            'meta'  => $stats['banks'] . ' banks · ' . $stats['atms'] . ' ATMs',
            'pending' => 0,
            'href'  => '/superadmin/banks-list.php',
            'manage'=> '/superadmin/atms-list.php',
        ],
        [
            'group' => 'Directory',
            'key'   => 'parks',
            'name'  => 'Parks & Govt',
            'icon'  => 'fa-tree',
            'color' => '#047857',
            'bg'    => '#d1fae5',
            'total' => $stats['parks'] + $stats['govt_offices'] + $stats['industries'],
            'meta'  => 'Parks, industries, civic',
            'pending' => 0,
            'href'  => '/superadmin/parks-list.php',
            'manage'=> '/superadmin/government-offices-list.php',
        ],
    ];
}
