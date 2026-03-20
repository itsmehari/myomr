<?php
/**
 * Insert Mega Property Expo (ARK Eventz) into event_listings.
 * Remote: PowerShell: $env:DB_HOST='myomr.in'; php dev-tools/insert-mega-property-expo-march-2026.php
 */
declare(strict_types=1);

function insertMegaPropertyExpoGenerateSlug(string $title): string {
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
$likeTitle = '%Mega Property Expo%Idhu Ungal Sothu%';
$rangeStart = '2026-03-21 00:00:00';
$rangeEnd = '2026-03-23 00:00:00';
$dedupe->bind_param('sss', $likeTitle, $rangeStart, $rangeEnd);
$dedupe->execute();
$existing = $dedupe->get_result()->fetch_assoc();
if ($existing) {
    echo "Already present: id={$existing['id']} slug={$existing['slug']}\n";
    echo 'Public URL: https://myomr.in/omr-local-events/event/' . $existing['slug'] . "\n";
    exit(0);
}

$title = 'Mega Property Expo – Idhu Ungal Sothu (ARK Eventz)';
$baseSlug = insertMegaPropertyExpoGenerateSlug('mega-property-expo-medavakkam-march-2026');
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

$categoryId = 5; // Business & Networking
$organizerName = 'ARK Eventz – The Sign of Miracles';
$organizerEmail = '';
$organizerPhone = '+91 94444 56087';
$location = 'Shri Varalakshmi Mahal, Velachery Main Rd, Medavakkam, Chennai – 600100';
$locality = 'Medavakkam';
$startDatetime = '2026-03-21 09:30:00';
$endDatetime = '2026-03-22 21:00:00';
$isAllDay = 0;
$isFree = 1;
$price = '';
$ticketsUrl = 'https://www.arkeventz.com';
$websiteUrl = 'https://www.arkeventz.com';
$imageUrl = '';
$featured = 0;
$status = 'scheduled';

$description = <<<'HTML'
<p><strong>ARK Eventz – The Sign of Miracles</strong> presents <strong>Idhu Ungal Sothu – Mega Property Expo</strong>. Limited free passes; free entry.</p>

<h3 class="h5 mt-4">When</h3>
<p>Saturday &amp; Sunday, <strong>21 &amp; 22 March 2026</strong>, from <strong>9:30 AM</strong> onwards.</p>

<h3 class="h5 mt-4">Venue</h3>
<p>Shri Varalakshmi Mahal, Velachery Main Rd, Medavakkam, Chennai – 600100</p>

<h3 class="h5 mt-4">Scale</h3>
<ul>
<li>50+ developers</li>
<li>500+ properties</li>
<li>Price range: properties from ₹4 lakhs to ₹5 crores</li>
</ul>

<h3 class="h5 mt-4">Sponsors &amp; partners</h3>
<p><strong>Title sponsor:</strong> The Nest Builders, Casagrand</p>
<p><strong>Co-sponsor:</strong> Asset Tree Homes, Urban Assets</p>
<p><strong>Powered by:</strong> Square, DAC</p>
<p><strong>Supported by:</strong> VGN, VGK, Jain Housing, Urbanrise, Ruby Builders, GTC, Sri Properties, DRA, Bharathi Homes, Navins, Blue Realty, Sameera, STAAR Homes, Eastlands, ABI Estates, MP Developers, GTB, RWD, and others</p>
<p><strong>Banking partner:</strong> HDFC Life</p>
<p><strong>Media partner:</strong> Sponlight</p>

<h3 class="h5 mt-4">Complimentaries</h3>
<p>Free gifts, popcorn, cotton candy, bouncing castle, and mehendi.</p>

<h3 class="h5 mt-4">Contact</h3>
<p>Phone: <a href="tel:+919444456087">+91 94444 56087</a><br>
Website: <a href="https://www.arkeventz.com" rel="noopener noreferrer">www.arkeventz.com</a></p>
HTML;

$ins = $conn->prepare(
    'INSERT INTO event_listings (title, slug, category_id, organizer_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, website_url, image_url, description, featured, status) VALUES (?, ?, NULLIF(?,0), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$catBind = $categoryId;
// Types: title,slug,cat, org×3, loc×2, datetime×2, is_all_day, is_free, price, urls×3, desc, featured, status
$ins->bind_param(
    'ssisssssssiisssssis',
    $title,
    $slug,
    $catBind,
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
