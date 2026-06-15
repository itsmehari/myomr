<?php
declare(strict_types=1);

/**
 * Superadmin module insights — trend SQL, status breakdowns, drill-down lists.
 */

require_once __DIR__ . '/superadmin-dashboard-metrics.php';

/**
 * @return list<string> Last N month labels (Y-m), oldest first.
 */
function sa_insight_month_keys(int $months = 12): array
{
    $keys = [];
    for ($i = $months - 1; $i >= 0; $i--) {
        $keys[] = date('Y-m', strtotime("-{$i} months"));
    }

    return $keys;
}

/**
 * @return array{labels: list<string>, values: list<int>}
 */
function sa_insight_series_from_sql(mysqli $conn, string $sql, int $months = 12): array
{
    $keys = sa_insight_month_keys($months);
    $map = array_fill_keys($keys, 0);

    $result = sa_dash_query($conn, $sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $ym = (string) ($row['ym'] ?? '');
            if (isset($map[$ym])) {
                $map[$ym] = (int) ($row['c'] ?? 0);
            }
        }
    }

    $labels = [];
    foreach ($keys as $key) {
        $labels[] = date('M y', strtotime($key . '-01'));
    }

    return [
        'labels' => $labels,
        'values' => array_values($map),
    ];
}

/**
 * @return list<array{status: string, count: int}>
 */
function sa_insight_status_breakdown(mysqli $conn, string $sql): array
{
    $rows = [];
    $result = sa_dash_query($conn, $sql);
    if (!$result) {
        return $rows;
    }

    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            'status' => (string) ($row['status'] ?? 'unknown'),
            'count'  => (int) ($row['c'] ?? 0),
        ];
    }

    return $rows;
}

/**
 * @return array<string, array<string, mixed>>
 */
function sa_insight_module_registry(): array
{
    $monthFilter = "created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)";

    return [
        'jobs' => [
            'name'        => 'Job Posts',
            'description' => 'All job postings on the OMR jobs portal — active, pending, and expired.',
            'icon'        => 'fa-briefcase',
            'color'       => '#a16207',
            'bg'          => '#fef9c3',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM job_postings WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM job_postings GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT j.id, j.title, j.status, j.created_at, e.company_name AS extra FROM job_postings j LEFT JOIN employers e ON j.employer_id = e.id ORDER BY j.created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Job title', 'extra' => 'Company', 'status' => 'Status', 'created_at' => 'Posted'],
            'open_href'   => '/superadmin/jobs/manage-jobs-omr.php',
            'manage_href' => '/superadmin/jobs/manage-jobs-omr.php?status=pending',
        ],
        'seekers' => [
            'name'        => 'Job Seekers',
            'description' => 'Registered job seeker profiles with résumés and contact preferences.',
            'icon'        => 'fa-user-graduate',
            'color'       => '#7c3aed',
            'bg'          => '#ede9fe',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM job_seeker_profiles WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => "SELECT COALESCE(experience_level, 'unspecified') AS status, COUNT(*) AS c FROM job_seeker_profiles GROUP BY experience_level ORDER BY c DESC",
            'list_sql'    => 'SELECT id, full_name AS title, email AS extra, experience_level AS status, created_at FROM job_seeker_profiles ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Name', 'extra' => 'Email', 'status' => 'Experience', 'created_at' => 'Joined'],
            'open_href'   => '/superadmin/manage-job-seeker-profiles-omr.php',
            'manage_href' => '/superadmin/jobs/view-all-applications-omr.php',
        ],
        'employers' => [
            'name'        => 'Employers',
            'description' => 'Companies registered to post jobs on MyOMR.',
            'icon'        => 'fa-building',
            'color'       => '#0369a1',
            'bg'          => '#e0f2fe',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM employers WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT COALESCE(status, \'unknown\') AS status, COUNT(*) AS c FROM employers GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, company_name AS title, email AS extra, status, created_at FROM employers ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Company', 'extra' => 'Email', 'status' => 'Status', 'created_at' => 'Registered'],
            'open_href'   => '/superadmin/jobs/verify-employers-omr.php',
            'manage_href' => '/superadmin/jobs/verify-employers-omr.php',
        ],
        'articles' => [
            'name'        => 'News Articles',
            'description' => 'Published and draft articles on /local-news/.',
            'icon'        => 'fa-newspaper',
            'color'       => '#2563eb',
            'bg'          => '#dbeafe',
            'trend_sql'   => "SELECT DATE_FORMAT(COALESCE(published_date, created_at), '%Y-%m') AS ym, COUNT(*) AS c FROM articles WHERE COALESCE(published_date, created_at) >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM articles GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, title, status, COALESCE(published_date, created_at) AS created_at, slug AS extra FROM articles ORDER BY COALESCE(published_date, created_at) DESC LIMIT 100',
            'list_cols'   => ['title' => 'Title', 'extra' => 'Slug', 'status' => 'Status', 'created_at' => 'Date'],
            'open_href'   => '/superadmin/articles/index.php',
            'manage_href' => '/superadmin/news-publisher.php',
        ],
        'events' => [
            'name'        => 'Events',
            'description' => 'Community events listed on /omr-local-events/.',
            'icon'        => 'fa-calendar-days',
            'color'       => '#15803d',
            'bg'          => '#dcfce7',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM event_listings WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM event_listings GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, title, status, created_at, start_datetime AS extra FROM event_listings ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Event', 'extra' => 'Starts', 'status' => 'Status', 'created_at' => 'Added'],
            'open_href'   => '/superadmin/community-events/view-listings.php',
            'manage_href' => '/superadmin/community-events/manage-events-omr.php',
        ],
        'classified' => [
            'name'        => 'Classified Ads',
            'description' => 'Services, wanted, and community notices on OMR Classified Ads.',
            'icon'        => 'fa-bullhorn',
            'color'       => '#c2410c',
            'bg'          => '#ffedd5',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM omr_classified_ads_listings WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM omr_classified_ads_listings GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, title, status, created_at, locality AS extra FROM omr_classified_ads_listings ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Title', 'extra' => 'Locality', 'status' => 'Status', 'created_at' => 'Posted'],
            'open_href'   => '/superadmin/classified-ads/manage-listings-omr.php',
            'manage_href' => '/superadmin/classified-ads/index.php',
        ],
        'buy_sell' => [
            'name'        => 'Buy & Sell',
            'description' => 'Used-goods marketplace listings on /omr-buy-sell/.',
            'icon'        => 'fa-shopping-bag',
            'color'       => '#be185d',
            'bg'          => '#fce7f3',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM omr_buy_sell_listings WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM omr_buy_sell_listings GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, title, status, created_at, price AS extra FROM omr_buy_sell_listings ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Item', 'extra' => 'Price', 'status' => 'Status', 'created_at' => 'Listed'],
            'open_href'   => '/superadmin/buy-sell/manage-listings-omr.php',
            'manage_href' => '/superadmin/buy-sell/index.php',
        ],
        'rent' => [
            'name'        => 'Rent & Lease',
            'description' => 'House, apartment, and land listings for rent or lease.',
            'icon'        => 'fa-house',
            'color'       => '#0d7a42',
            'bg'          => '#dcfce7',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM rent_lease_properties WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM rent_lease_properties GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, title, status, created_at, locality AS extra FROM rent_lease_properties ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Property', 'extra' => 'Locality', 'status' => 'Status', 'created_at' => 'Added'],
            'open_href'   => '/superadmin/rent-lease/manage-properties-omr.php',
            'manage_href' => '/superadmin/rent-lease/index.php',
        ],
        'hostels' => [
            'name'        => 'Hostels & PGs',
            'description' => 'Hostel and paying-guest accommodation along OMR.',
            'icon'        => 'fa-bed',
            'color'       => '#be185d',
            'bg'          => '#fce7f3',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM hostels_pgs WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM hostels_pgs GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, property_name AS title, status, created_at, locality AS extra FROM hostels_pgs ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Property', 'extra' => 'Locality', 'status' => 'Status', 'created_at' => 'Added'],
            'open_href'   => '/superadmin/hostels/manage-properties.php',
            'manage_href' => '/superadmin/hostels/index.php',
        ],
        'coworking' => [
            'name'        => 'Coworking Spaces',
            'description' => 'Coworking and shared office listings.',
            'icon'        => 'fa-desk',
            'color'       => '#6d28d9',
            'bg'          => '#ede9fe',
            'trend_sql'   => "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM coworking_spaces WHERE {$monthFilter} GROUP BY ym ORDER BY ym",
            'status_sql'  => 'SELECT status, COUNT(*) AS c FROM coworking_spaces GROUP BY status ORDER BY c DESC',
            'list_sql'    => 'SELECT id, space_name AS title, status, created_at, locality AS extra FROM coworking_spaces ORDER BY created_at DESC LIMIT 100',
            'list_cols'   => ['title' => 'Space', 'extra' => 'Locality', 'status' => 'Status', 'created_at' => 'Added'],
            'open_href'   => '/superadmin/coworking/manage-spaces.php',
            'manage_href' => '/superadmin/coworking/index.php',
        ],
    ];
}

function sa_insight_get_module(string $key): ?array
{
    $registry = sa_insight_module_registry();

    return $registry[$key] ?? null;
}

/**
 * Overview chart datasets for the command center home page.
 *
 * @return array<string, array{labels: list<string>, values: list<int>, color: string, label: string}>
 */
function sa_insight_overview_charts(mysqli $conn): array
{
    $registry = sa_insight_module_registry();

    $pick = static function (string $moduleKey) use ($registry, $conn): array {
        $mod = $registry[$moduleKey];
        $series = sa_insight_series_from_sql($conn, $mod['trend_sql']);

        return [
            'labels' => $series['labels'],
            'values' => $series['values'],
            'color'  => $mod['color'],
            'label'  => $mod['name'],
        ];
    };

    $jobs = $pick('jobs');
    $seekers = $pick('seekers');
    $articles = $pick('articles');
    $events = $pick('events');
    $classified = $pick('classified');
    $rent = $pick('rent');
    $buySell = $pick('buy_sell');

    $marketplaceValues = [];
    for ($i = 0; $i < 12; $i++) {
        $marketplaceValues[] = ($rent['values'][$i] ?? 0) + ($buySell['values'][$i] ?? 0) + ($classified['values'][$i] ?? 0);
    }

    return [
        'jobs'         => $jobs,
        'seekers'      => $seekers,
        'articles'     => $articles,
        'events'       => $events,
        'classified'   => $classified,
        'marketplace'  => [
            'labels' => $rent['labels'],
            'values' => $marketplaceValues,
            'color'  => '#0d7a42',
            'label'  => 'Marketplace activity',
        ],
    ];
}

/**
 * @return list<array<string, mixed>>
 */
function sa_insight_list_rows(mysqli $conn, array $module): array
{
    $rows = [];
    $result = sa_dash_query($conn, $module['list_sql']);
    if (!$result) {
        return $rows;
    }

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}

function sa_insight_format_cell(string $field, mixed $value): string
{
    if ($value === null || $value === '') {
        return '—';
    }

    $str = (string) $value;
    if ($field === 'created_at' || preg_match('/^\d{4}-\d{2}-\d{2}/', $str)) {
        $ts = strtotime($str);
        if ($ts !== false) {
            return date('M j, Y', $ts);
        }
    }

    if ($field === 'extra' && strlen($str) > 48) {
        return sa_dash_trim($str, 48);
    }

    return $str;
}

function sa_status_class(string $status): string
{
    $status = strtolower(trim($status));
    $allowed = ['active', 'published', 'scheduled', 'pending', 'draft', 'inactive', 'expired', 'approved', 'rejected'];
    return in_array($status, $allowed, true) ? 'sa-status--' . $status : 'sa-status--inactive';
}
