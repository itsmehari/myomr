<?php
/**
 * Insert NOVA – Inclusive Women's Expo (Gutsee Foods / Rotary) into event_listings.
 * Remote: PowerShell: $env:DB_HOST='myomr.in'; php dev-tools/insert-nova-womens-expo-april-2026.php
 */
declare(strict_types=1);

function novaExpoGenerateSlug(string $title): string {
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

$fixedSlug = 'nova-womens-expo-chennai-2026-gutsee-foods-kelambakkam';
$check = $conn->prepare('SELECT id, slug FROM event_listings WHERE slug = ? OR (title LIKE ? AND start_datetime >= ? AND start_datetime < ?) LIMIT 1');
$like = '%NOVA%Women%Expo%';
$dayStart = '2026-04-14 00:00:00';
$dayEnd = '2026-04-15 00:00:00';
$check->bind_param('ssss', $fixedSlug, $like, $dayStart, $dayEnd);
$check->execute();
$existing = $check->get_result()->fetch_assoc();
if ($existing) {
    echo "Already present: id={$existing['id']} slug={$existing['slug']}\n";
    echo 'Public URL: https://myomr.in/omr-local-events/event/' . $existing['slug'] . "\n";
    exit(0);
}

$slug = $fixedSlug;

$categoryId = 0;
$catRes = $conn->query("SELECT id, slug FROM event_categories WHERE slug IN ('business-networking','community-lifestyle','community','arts-culture') AND is_active = 1");
if ($catRes) {
    $bySlug = [];
    while ($row = $catRes->fetch_assoc()) {
        $bySlug[$row['slug']] = (int) $row['id'];
    }
    foreach (['business-networking', 'community-lifestyle', 'community', 'arts-culture'] as $pref) {
        if (isset($bySlug[$pref])) {
            $categoryId = $bySlug[$pref];
            break;
        }
    }
}

$title = "NOVA – An Inclusive Women's Expo";
$organizerName = 'GUTSEE FOODS LLP (with Rotary Club of Chennai Spotlight, District 3232)';
$organizerEmail = '';
$organizerPhone = '+91 91764 09997';
$location = 'Hindustan College of Arts & Science, Kelambakkam, Chennai';
$locality = 'Kelambakkam';
$startDatetime = '2026-04-14 09:00:00';
$endDatetime = '2026-04-14 21:00:00';
$isAllDay = 0;
$isFree = 1;
$price = '';
$ticketsUrl = '';
$websiteUrl = '';
$imageUrl = 'https://myomr.in/omr-local-events/nova-womens-expo-chennai-2026-gutsee-foods-kelambakkam.jpg';
$featured = 0;
$status = 'scheduled';

$description = <<<'HTML'
<p><strong>Organised by GUTSEE FOODS LLP</strong> · In association with <strong>Rotary Club of Chennai Spotlight (District 3232)</strong></p>

<p class="lead mb-3">A platform for homepreneurs, business women and aspiring entrepreneurs to showcase, sell, network and grow — from <strong>homepreneur to entrepreneur</strong> and <strong>business woman to enterprise leader</strong>.</p>

<h3 class="h5 mt-4">When</h3>
<p><strong>Monday, 14 April 2026</strong><br>
<strong>9:00 AM – 9:00 PM</strong></p>

<h3 class="h5 mt-4">Venue</h3>
<p>Hindustan College of Arts &amp; Science, Kelambakkam, Chennai</p>

<h3 class="h5 mt-4">Entry &amp; stalls</h3>
<ul>
<li><strong>Visitor entry: FREE</strong></li>
<li><strong>Stall registration: ₹5000</strong> — book your space for visibility and networking</li>
</ul>

<h3 class="h5 mt-4">Contact</h3>
<p>Kavitha Giri<br>
Phone: <a href="tel:+919176409997">+91 91764 09997</a></p>
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
echo "Short hub URL: https://myomr.in/local-events/event/{$slug}\n";
