<?php
/**
 * Insert Eco-Bazaar (reStore, Kottivakkam) into event_listings.
 * Remote: PowerShell: $env:DB_HOST='myomr.in'; php dev-tools/insert-eco-bazaar-march-2026.php
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
    fwrite(STDERR, 'Database connection failed.' . "\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$dedupe = $conn->prepare('SELECT id, slug FROM event_listings WHERE title LIKE ? AND start_datetime >= ? AND start_datetime < ? LIMIT 1');
$likeTitle = '%Eco-Bazaar%Sustainable Living%';
$rangeStart = '2026-03-28 00:00:00';
$rangeEnd = '2026-03-29 00:00:00';
$dedupe->bind_param('sss', $likeTitle, $rangeStart, $rangeEnd);
$dedupe->execute();
$existing = $dedupe->get_result()->fetch_assoc();
if ($existing) {
    echo "Already present: id={$existing['id']} slug={$existing['slug']}\n";
    echo "Public URL: https://myomr.in/omr-local-events/event/{$existing['slug']}\n";
    exit(0);
}

// Resolve category: Arts & Culture or Community & Lifestyle
$catStmt = $conn->prepare("SELECT id FROM event_categories WHERE slug IN ('arts-culture','community-lifestyle','community') AND is_active = 1 ORDER BY slug LIMIT 1");
$catStmt->execute();
$catRow = $catStmt->get_result()->fetch_assoc();
$categoryId = $catRow ? (int) $catRow['id'] : 0;

$title = 'Eco-Bazaar - A Celebration of Sustainable Living';
$baseSlug = generateSlug('eco-bazaar-sustainable-living-kottivakkam-march-2026');
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

$organizerName = 'reStore';
$organizerEmail = '';
$organizerPhone = '+91 98405 71842';
$location = 'reStore, Kottivakkam, ECR, Chennai';
$locality = 'Kottivakkam';
$startDatetime = '2026-03-28 10:00:00';
$endDatetime = '2026-03-28 13:00:00';
$isAllDay = 0;
$isFree = 0;
$price = 'Workshops from ₹500';
$ticketsUrl = 'https://forms.gle/Jdy9m4gksC4ut8RLA';
$websiteUrl = '';
$imageUrl = '';
$featured = 0;
$status = 'scheduled';

$description = <<<'HTML'
<p><strong>Eco-Bazaar – A Celebration of Sustainable Living</strong></p>
<p>Theme: <strong>Health • Livelihoods • Nature</strong></p>
<p>Art workshops, sustainable crafts, and community celebration. Audience: Kids (7+) &amp; Adults. Materials provided.</p>

<h3 class="h5 mt-4">Art Workshops</h3>
<ul>
<li><strong>Painting with Gobi</strong> — 1.5 hr, ₹500. Slots: 10am–11:30am, 11:30am–1pm</li>
<li><strong>Coconut Shell Craft</strong> with Kavin's Art Gallery — 3 hr, ₹700. Slot: 10am–1pm</li>
<li><strong>Palm Leaves Craft</strong> with Saarolai — 1.5 hr, ₹500. Slots: 10am–11:30am, 11:30am–1pm</li>
</ul>

<h3 class="h5 mt-4">When</h3>
<p>Saturday, <strong>28 March 2026</strong>, 10:00 AM onwards.</p>

<h3 class="h5 mt-4">Venue</h3>
<p>reStore, Kottivakkam, ECR, Chennai</p>

<h3 class="h5 mt-4">Registration</h3>
<p>Phone: <a href="tel:+919840571842">+91 98405 71842</a><br>
Register online: <a href="https://forms.gle/Jdy9m4gksC4ut8RLA" rel="noopener noreferrer">Google Form</a></p>
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
