<?php
/**
 * Insert free live webinar (career roadmap Grades 8–12, Career Group Squad launch) into event_listings.
 * Remote: $env:DB_HOST='myomr.in'; php dev-tools/insert-career-webinar-march-2026.php
 *
 * Date/time: 27 March 2026, 6:00 PM – 7:30 PM IST (stored as MySQL datetime, server local = IST on typical cPanel).
 */
declare(strict_types=1);

function generateSlug(string $title): string
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);

    return trim($slug, '-');
}

$db_host = getenv('DB_HOST');
if ($db_host !== false && $db_host !== '') {
    $db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
    $db_pass = getenv('DB_PASS') ?: 'myomr@123';
    $db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';
    $db_port = (int) (getenv('DB_PORT') ?: 3306);
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
} else {
    require_once __DIR__ . '/../core/omr-connect.php';
}

if (!isset($conn) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed.\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$dedupe = $conn->prepare(
    'SELECT id, slug FROM event_listings WHERE tickets_url = ? AND start_datetime >= ? AND start_datetime < ? LIMIT 1'
);
$formUrl = 'https://forms.gle/7JnWCwqYV1cscaLU8';
$rangeStart = '2026-03-27 00:00:00';
$rangeEnd = '2026-03-28 00:00:00';
$dedupe->bind_param('sss', $formUrl, $rangeStart, $rangeEnd);
$dedupe->execute();
$existing = $dedupe->get_result()->fetch_assoc();
if ($existing) {
    echo "Already present: id={$existing['id']} slug={$existing['slug']}\n";
    echo "Public URL: https://myomr.in/omr-local-events/event/{$existing['slug']}\n";
    exit(0);
}

$catSql = "SELECT id FROM event_categories WHERE slug IN (
        'education-training','business-networking','tech-it','community-lifestyle','arts-culture','community'
    ) AND is_active = 1 ORDER BY FIELD(slug,'education-training','business-networking','tech-it','community-lifestyle','arts-culture','community') LIMIT 1";
$catStmt = $conn->query($catSql);
$catRow = ($catStmt instanceof mysqli_result) ? $catStmt->fetch_assoc() : null;
$categoryId = $catRow ? (int) $catRow['id'] : 0;

$title = 'Free Live Webinar: Scientific Career Roadmap for Grades 8–12 + Career Group Squad Launch';
$baseSlug = generateSlug('career-roadmap-webinar-grades-8-12-career-group-squad-march-2026');
$slug = $baseSlug;
$check = $conn->prepare('SELECT id FROM event_listings WHERE slug = ? LIMIT 1');
for ($i = 0; $i < 20; $i++) {
    $check->bind_param('s', $slug);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        break;
    }
    $slug = $baseSlug . '-' . ($i + 1);
}

$organizerName = 'Career Group Squad';
$organizerEmail = '';
$organizerPhone = '';
$location = 'Online — live webinar (IST)';
$locality = 'Chennai & OMR (open to all)';
$startDatetime = '2026-03-27 18:00:00';
$endDatetime = '2026-03-27 19:30:00';
$isAllDay = 0;
$isFree = 1;
$price = '';
$ticketsUrl = $formUrl;
$websiteUrl = '';
$imageUrl = '';
$featured = 1;
$status = 'scheduled';

$description = <<<'HTML'
<p><strong>Free live webinar</strong> — happening <strong>27 March 2026</strong>, <strong>6:00 PM – 7:30 PM (IST)</strong>.</p>

<h3 class="h5 mt-3">Topic</h3>
<p><strong>Our Society&rsquo;s Future: A Scientific Career Roadmap for Grades 8–12</strong></p>

<h3 class="h5 mt-3">What&rsquo;s special</h3>
<p>Along with the session, the host will officially launch the <strong>Career Group Squad</strong> — a structured group career workshop to help students gain clarity, direction, and the right roadmap.</p>
<p><strong>Bonus:</strong> A special guest will join to share practical insights.</p>

<h3 class="h5 mt-3">Register</h3>
<p><a href="https://forms.gle/7JnWCwqYV1cscaLU8" rel="noopener noreferrer">Google Form — register here</a></p>

<p class="small text-muted mb-0">Organizers: confirm final time zone and any platform link with attendees after registration.</p>
HTML;

$ins = $conn->prepare(
    'INSERT INTO event_listings (title, slug, category_id, organizer_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, website_url, image_url, description, featured, status) VALUES (?, ?, NULLIF(?,0), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$paramTypes = 'ss' . 'i' . str_repeat('s', 7) . 'ii' . str_repeat('s', 5) . 'is';
$ins->bind_param(
    $paramTypes,
    $title,
    $slug,
    $categoryId,
    $organizerName,
    $organizerEmail,
    $organizerPhone,
    $location,
    $locality,
    $startDatetime,
    $endDatetime,
    $isAllDay,
    $isFree,
    $price,
    $ticketsUrl,
    $websiteUrl,
    $imageUrl,
    $description,
    $featured,
    $status
);

if (!$ins->execute()) {
    fwrite(STDERR, 'Insert failed: ' . $ins->error . "\n");
    exit(1);
}

$newId = (int) $conn->insert_id;
echo "Inserted event_listings id={$newId} slug={$slug}\n";
echo "Public URL: https://myomr.in/omr-local-events/event/{$slug}\n";
