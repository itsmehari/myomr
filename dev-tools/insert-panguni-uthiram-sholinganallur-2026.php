<?php
/**
 * Insert: Subramanya Swamy – Panguni Uthiram Vaibhoha Festival (Sholinganallur, 1 Apr 2026).
 *
 * Remote: $env:DB_HOST='myomr.in'; php dev-tools/insert-panguni-uthiram-sholinganallur-2026.php
 */
declare(strict_types=1);

function generateSlug(string $title): string {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
$db_pass = getenv('DB_PASS') ?: 'myomr@123';
$db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';
$db_port = (int) (getenv('DB_PORT') ?: 3306);

if ($db_host) {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
} else {
    require_once __DIR__ . '/../core/omr-connect.php';
}

if (!isset($conn) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed.\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$baseSlug = generateSlug('subramanya-swamy-panguni-uthiram-vaibhoha-festival-sholinganallur-2026');
$dedupe = $conn->prepare('SELECT id, slug FROM event_listings WHERE slug = ? OR (title LIKE ? AND start_datetime >= ? AND start_datetime < ?) LIMIT 1');
$likeTitle = '%Panguni Uthiram%Sholinganallur%';
$rangeStart = '2026-04-01 00:00:00';
$rangeEnd = '2026-04-02 00:00:00';
$dedupe->bind_param('ssss', $baseSlug, $likeTitle, $rangeStart, $rangeEnd);
$dedupe->execute();
$existing = $dedupe->get_result()->fetch_assoc();
if ($existing) {
    echo "Already present: id={$existing['id']} slug={$existing['slug']}\n";
    echo "Public URL: https://myomr.in/omr-local-events/event/{$existing['slug']}\n";
    exit(0);
}

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

$catStmt = $conn->query(
    "SELECT id FROM event_categories WHERE slug IN ('community-lifestyle','community','arts-culture') AND is_active = 1 ORDER BY FIELD(slug,'community-lifestyle','community','arts-culture') LIMIT 1"
);
$catRow = $catStmt ? $catStmt->fetch_assoc() : null;
$categoryId = $catRow ? (int) $catRow['id'] : 0;

$title = 'Subramanya Swamy – Panguni Uthiram Vaibhoha Festival';
$organizerName = 'Sarva Sathagam Sri Sathurvedha Vidhya Ganapathy Vedhaashram Padasalai, Aathur – Chengalpattu';
$organizerEmail = '';
$organizerPhone = '';
$location = 'OMR Murugan Temple (Subramanya Swamy), Classic Farms Road, Sholinganallur, Chennai – 600119';
$locality = 'Sholinganallur';
$startDatetime = '2026-04-01 05:45:00';
$endDatetime = '2026-04-01 22:30:00';
$isAllDay = 0;
$isFree = 1;
$price = '';
$ticketsUrl = '';
$websiteUrl = '';
$imageUrl = 'https://myomr.in/omr-local-events/assets/Panguni-Uthiram-OMR-Murugan-Temple-Sholinganallur-2026.jpg';
$featured = 0;
$status = 'scheduled';

$description = <<<'HTML'
<p><strong>Wednesday, 1 April 2026</strong> — Panguni Uthiram at OMR Murugan Temple, Sholinganallur. Grand celestial wedding of Sri Subramanya Swamy with Devasena &amp; Valli; celebrated on Panguni Uthiram (full moon with Uthiram star).</p>

<h3 class="h5 mt-4">Schedule</h3>
<dl class="row small mb-0">
<dt class="col-sm-3">5:45 AM</dt><dd class="col-sm-9">Vinayagar Velukku Etru</dd>
<dt class="col-sm-3">6:00 AM</dt><dd class="col-sm-9">Thirupalli Ezhichi Vizha — early morning ritual to awaken Lord Murugan and bless devotees</dd>
<dt class="col-sm-3">6:30 AM</dt><dd class="col-sm-9">Mahaganapathi Homam &amp; Kalasa Abhishekam</dd>
<dt class="col-sm-3">7:15 AM</dt><dd class="col-sm-9">Valli Devasena Sametha Subramanya Swamy Maha Abhishekam</dd>
<dt class="col-sm-3">9:00 AM</dt><dd class="col-sm-9">Tirukalyanam — grand celestial wedding of Sri Subramanya Swamy with Devasena &amp; Valli</dd>
<dt class="col-sm-3">4:30 PM</dt><dd class="col-sm-9">Sahasranama Archanai</dd>
<dt class="col-sm-3">6:30 PM</dt><dd class="col-sm-9">Kalyana Kola Purappadu — wedding procession of Subramanya Swamy with Valli &amp; Devasena</dd>
<dt class="col-sm-3">8:30 PM</dt><dd class="col-sm-9">Thiru Poon Oonjal &amp; Palli Arai Vizha — final night ritual; prayers, music, closing of the sanctum</dd>
</dl>

<h3 class="h5 mt-4">Organizer</h3>
<p>Sarva Sathagam Sri Sathurvedha Vidhya Ganapathy Vedhaashram Padasalai, Aathur – Chengalpattu</p>

<p class="mb-0"><strong>Note:</strong> All are welcome.</p>
HTML;

$ins = $conn->prepare(
    'INSERT INTO event_listings (title, slug, category_id, organizer_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, website_url, image_url, description, featured, status) VALUES (?, ?, NULLIF(?,0), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$ins->bind_param(
    'ssisssssssiisssssis',
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
