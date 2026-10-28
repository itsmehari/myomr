<?php
/**
 * Five starter community events from public November 2026 Chennai chess listings.
 * Sources: tamilchess.com event pages and saturangam360.com (checked 28 Oct 2026).
 * Only the Perungudi tournament is featured. The other four are Chennai-wide
 * listings; TNSCA does not publish a street address for them.
 *
 * Live: $env:DB_HOST='myomr.in'; php dev-tools/insert-chennai-chess-nov-2026-starter.php
 */
declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
$db_pass = getenv('DB_PASS') ?: 'myomr@123';
$db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';
$db_port = (int) (getenv('DB_PORT') ?: 3306);

if ($db_host !== false && $db_host !== '') {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
} else {
    require_once __DIR__ . '/../core/omr-connect.php';
}

if (!isset($conn) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed.\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$categoryId = 0;
$catRes = $conn->query(
    "SELECT id, slug FROM event_categories WHERE slug IN ('sports-fitness','education-workshops','community') AND is_active = 1"
);
if ($catRes) {
    $bySlug = [];
    while ($row = $catRes->fetch_assoc()) {
        $bySlug[$row['slug']] = (int) $row['id'];
    }
    foreach (['sports-fitness', 'education-workshops', 'community'] as $pref) {
        if (isset($bySlug[$pref])) {
            $categoryId = $bySlug[$pref];
            break;
        }
    }
}

$events = [
    [
        'title' => 'The NEST School 1st International FIDE Rated Rapid Chess Tournament 2026',
        'slug' => 'nest-school-1st-fide-rapid-chess-chennai-1-nov-2026',
        'organizer' => 'Ten Ten Chess Academy',
        'email' => 'tentenchessacademy@gmail.com',
        'phone' => '8438221095',
        'contact' => 'Mr. Amarnath',
        'location' => 'Chennai',
        'locality' => 'Chennai',
        'start' => '2026-11-01 09:00:00',
        'end' => '2026-11-01 18:00:00',
        'price' => 'See TNSCA prospectus RFR-13.CHE.26-27',
        'tickets' => 'https://tamilchess.com/events/ten-ten-chess-academy-1st-international-fide-rated-rapid-chess-tournament-2026/',
        'website' => 'https://tamilchess.com/events/ten-ten-chess-academy-1st-international-fide-rated-rapid-chess-tournament-2026/',
        'image' => '',
        'featured' => 0,
        'note' => 'Listed by the Tamil Nadu State Chess Association for 1 November 2026 in Chennai. Prospectus code RFR-13.CHE.26-27. Street address was not published on the association page.',
    ],
    [
        'title' => 'Eight Square Chess Academy 3rd International FIDE Rated (Below 1700) Chess Tournament 2026',
        'slug' => 'eight-square-fide-below-1700-chess-chennai-13-nov-2026',
        'organizer' => 'Eight Square Chess Academy',
        'email' => 'Chessacademy32@gmail.com',
        'phone' => '9150442252',
        'contact' => 'Ms. Sivaranjani S',
        'location' => 'Chennai',
        'locality' => 'Chennai',
        'start' => '2026-11-13 09:00:00',
        'end' => '2026-11-15 18:00:00',
        'price' => 'See TNSCA prospectus RR-20.CHE.26-27',
        'tickets' => 'https://tamilchess.com/events/eight-square-chess-academy-3rd-international-fide-rated-below-1700-chess-tournament-2026/',
        'website' => 'https://chessbase.in/chess-calendar/event/eight-square-chess-academy-3rd-international-fide-rated-below-1700-chess-tournam-2026',
        'image' => '',
        'featured' => 0,
        'note' => 'TNSCA lists 13 November 2026 in Chennai. ChessBase India lists the same event as 13-15 November 2026. Prospectus code RR-20.CHE.26-27.',
    ],
    [
        'title' => '4th Tamil Nadu State Level Children\'s and Open Chess Tournament 2026',
        'slug' => 'saturangam-360-4th-tn-state-chess-bvm-perungudi-15-nov-2026',
        'organizer' => 'Saturangam 360 Academy',
        'email' => 'saturangam360@hotmail.com',
        'phone' => '6380606143',
        'contact' => 'Ms. B. Vidya',
        'location' => 'BVM Global, 144 Corporation Road, Seevaram, Perungudi, Chennai 600096',
        'locality' => 'Perungudi',
        'start' => '2026-11-15 09:00:00',
        'end' => '2026-11-15 18:00:00',
        'price' => 'Rs.500 entry fee',
        'tickets' => 'https://saturangam360.com/chess-tournaments',
        'website' => 'https://www.bookmyfee.com/booking-tournament/saturangam-360-academy-4th-tamil-nadu-state-level-childrens-open-chess-tournament',
        'image' => '/assets/images/modals/saturangam-360-chess-2026.jpg',
        'featured' => 1,
        'note' => 'Sunday 15 November 2026 at BVM Global Perungudi, next to Perungudi D Mart. Reporting 8:30 am. First round 9:00 am. Entry Rs.500. Registration closes 6:00 pm on 13 November 2026. No spot entry. Open to Tamil Nadu players. Categories Under 7, 9, 11, 13, 17 and Open. TNSCA registration Rs.150 is separate. Chennai district players also need CDCA registration Rs.75. Open category cash prizes total Rs.25,000. 150 trophies. Every player receives a medal and a printed certificate. Also listed: Ms. Jayanthi K, 9025601304. Chief arbiter: Mr. Banel Andross, 9094001547. TNSCA prospectus CO-186.CHE.26-27.',
    ],
    [
        'title' => '15th BBCF International FIDE Open Rated Chess Tournament',
        'slug' => 'bbcf-15th-fide-open-chess-chennai-18-nov-2026',
        'organizer' => 'Brains Behind Chess Foundation',
        'email' => 'brainsbehind.chessfoundation@gmail.com',
        'phone' => '7667006464',
        'contact' => 'Mr. Balaji S',
        'location' => 'Chennai',
        'locality' => 'Chennai',
        'start' => '2026-11-18 09:00:00',
        'end' => '2026-11-18 18:00:00',
        'price' => 'See TNSCA event page',
        'tickets' => 'https://tamilchess.com/events/15th-bbcf-international-fide-open-rated-chess-tournament/',
        'website' => 'https://tamilchess.com/events/15th-bbcf-international-fide-open-rated-chess-tournament/',
        'image' => '',
        'featured' => 0,
        'note' => 'Listed by the Tamil Nadu State Chess Association for 18 November 2026 in Chennai. Street address was not published on the association page.',
    ],
    [
        'title' => '16th BBCF International FIDE Blitz Rated Chess Tournament',
        'slug' => 'bbcf-16th-fide-blitz-chess-chennai-21-nov-2026',
        'organizer' => 'Brains Behind Chess Foundation',
        'email' => 'brainsbehind.chessfoundation@gmail.com',
        'phone' => '7667006464',
        'contact' => 'Mr. Balaji S',
        'location' => 'Chennai',
        'locality' => 'Chennai',
        'start' => '2026-11-21 09:00:00',
        'end' => '2026-11-21 18:00:00',
        'price' => 'See TNSCA event page',
        'tickets' => 'https://tamilchess.com/events/16th-bbcf-international-fide-blitz-rated-chess-tournament/',
        'website' => 'https://tamilchess.com/events/16th-bbcf-international-fide-blitz-rated-chess-tournament/',
        'image' => '',
        'featured' => 0,
        'note' => 'Listed by the Tamil Nadu State Chess Association for 21 November 2026 in Chennai. Street address was not published on the association page.',
    ],
];

$ins = $conn->prepare(
    'INSERT INTO event_listings (title, slug, category_id, organizer_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, website_url, image_url, description, featured, status) VALUES (?, ?, NULLIF(?,0), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$paramTypes = 'ss' . 'i' . str_repeat('s', 7) . 'ii' . str_repeat('s', 5) . 'is';
$check = $conn->prepare('SELECT id, slug FROM event_listings WHERE slug = ? LIMIT 1');

$inserted = 0;
$skipped = 0;
foreach ($events as $ev) {
    $check->bind_param('s', $ev['slug']);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();
    if ($existing) {
        echo "Skip (exists): id={$existing['id']} {$ev['slug']}\n";
        $skipped++;
        continue;
    }

    $description = '<p>' . htmlspecialchars($ev['note'], ENT_QUOTES, 'UTF-8') . '</p>';
    $description .= '<p>Contact: ' . htmlspecialchars($ev['contact'], ENT_QUOTES, 'UTF-8') . ', ' . htmlspecialchars($ev['organizer'], ENT_QUOTES, 'UTF-8') . '.</p>';
    $description .= '<p>Source: <a href="' . htmlspecialchars($ev['website'], ENT_QUOTES, 'UTF-8') . '" rel="noopener noreferrer">' . htmlspecialchars($ev['website'], ENT_QUOTES, 'UTF-8') . '</a></p>';

    $title = $ev['title'];
    $slug = $ev['slug'];
    $organizerName = $ev['organizer'];
    $organizerEmail = $ev['email'];
    $organizerPhone = $ev['phone'];
    $location = $ev['location'];
    $locality = $ev['locality'];
    $startDatetime = $ev['start'];
    $endDatetime = $ev['end'];
    $isAllDay = 0;
    $isFree = 0;
    $price = $ev['price'];
    $ticketsUrl = $ev['tickets'];
    $websiteUrl = $ev['website'];
    $imageUrl = $ev['image'];
    $featured = (int) $ev['featured'];
    $status = 'scheduled';

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
    $ins->execute();
    echo "Inserted id={$conn->insert_id}: {$title}\n";
    $inserted++;
}

echo "Done. Inserted: {$inserted}, Skipped: {$skipped}\n";
