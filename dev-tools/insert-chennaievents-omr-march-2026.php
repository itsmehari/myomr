<?php
/**
 * Insert ChennaiEvents.com OMR-adjacent events into event_listings.
 * Source: https://www.chennaievent.com/
 *
 * Remote: $env:DB_HOST='myomr.in'; php dev-tools/insert-chennaievents-omr-march-2026.php
 */
declare(strict_types=1);

function generateSlug(string $title): string {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

function ensureUniqueSlug(mysqli $conn, string $baseSlug): string {
    $slug = $baseSlug;
    $check = $conn->prepare('SELECT id FROM event_listings WHERE slug = ? LIMIT 1');
    for ($i = 0; $i < 20; $i++) {
        $check->bind_param('s', $slug);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            return $slug;
        }
        $slug = $baseSlug . '-' . ($i + 1);
    }
    return $slug;
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

// Resolve Arts & Culture category
$catStmt = $conn->prepare("SELECT id FROM event_categories WHERE slug = 'arts-culture' AND is_active = 1 LIMIT 1");
$catStmt->execute();
$catRow = $catStmt->get_result()->fetch_assoc();
$categoryId = $catRow ? (int) $catRow['id'] : 0;

$ins = $conn->prepare(
    'INSERT INTO event_listings (title, slug, category_id, organizer_id, organizer_name, organizer_email, organizer_phone, location, locality, start_datetime, end_datetime, is_all_day, is_free, price, tickets_url, website_url, image_url, description, featured, status) VALUES (?, ?, NULLIF(?,0), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$events = [
    [
        'title'   => 'Carnatic Vocal – Hemmige S. Prashanth',
        'slug'    => 'carnatic-vocal-hemmige-prashanth-sri-krishna-mandiram-thiruvanmiyur-march-2026',
        'artist'  => 'Hemmige S. Prashanth',
        'type'    => 'Classical Music - Vocal',
        'venue'   => 'Sri Krishna Mandiram',
        'address' => '19/1 Canal Road, Kamaraj Nagar, Thiruvanmiyur 600041',
        'locality'=> 'Thiruvanmiyur',
        'date'    => '2026-03-28',
        'time'    => '18:00:00',
        'free'    => 1,
        'phone'   => '044 24410595',
        'source'  => 'https://www.chennaievent.com/event-view.php?event_id=NTMzNTQ=',
        'image'   => 'https://www.chennaievent.com/photo_folder/1688726219HemmigeS.Prashanth.jpg',
    ],
    [
        'title'   => 'Carnatic Flute – K. Bhaskaran',
        'slug'    => 'carnatic-flute-k-bhaskaran-sri-krishna-mandiram-thiruvanmiyur-march-2026',
        'artist'  => 'K. Bhaskaran',
        'type'    => 'Classical Music - Flute',
        'venue'   => 'Sri Krishna Mandiram',
        'address' => '19/1 Canal Road, Kamaraj Nagar, Thiruvanmiyur 600041',
        'locality'=> 'Thiruvanmiyur',
        'date'    => '2026-03-29',
        'time'    => '18:00:00',
        'free'    => 1,
        'phone'   => '044 24410595',
        'source'  => 'https://www.chennaievent.com/event-view.php?event_id=NTMzNTU=',
        'image'   => 'https://www.chennaievent.com/photo_folder/1763513390bhr.jpg',
    ],
    [
        'title'   => 'Carnatic Vocal – Swetaranyam Sisters',
        'slug'    => 'carnatic-vocal-swetaranyam-sisters-sri-krishna-mandiram-thiruvanmiyur-march-2026',
        'artist'  => 'Swetaranyam Sisters',
        'type'    => 'Classical Music - Vocal',
        'venue'   => 'Sri Krishna Mandiram',
        'address' => '19/1 Canal Road, Kamaraj Nagar, Thiruvanmiyur 600041',
        'locality'=> 'Thiruvanmiyur',
        'date'    => '2026-03-30',
        'time'    => '18:00:00',
        'free'    => 1,
        'phone'   => '044 24410595',
        'source'  => 'https://www.chennaievent.com/event-view.php?event_id=NTMzNTY=',
        'image'   => 'https://www.chennaievent.com/photo_folder/1763657138mdr.jpg',
    ],
    [
        'title'   => 'Tirumurai Isai – M. Kotilingam',
        'slug'    => 'tirumurai-isai-m-kotilingam-sri-krishna-mandiram-thiruvanmiyur-march-2026',
        'artist'  => 'M. Kotilingam',
        'type'    => 'Tirumurai Isai',
        'venue'   => 'Sri Krishna Mandiram',
        'address' => '19/1 Canal Road, Kamaraj Nagar, Thiruvanmiyur 600041',
        'locality'=> 'Thiruvanmiyur',
        'date'    => '2026-03-31',
        'time'    => '18:00:00',
        'free'    => 1,
        'phone'   => '044 24410595',
        'source'  => 'https://www.chennaievent.com/event-view.php?event_id=NTMzNTc=',
        'image'   => 'https://www.chennaievent.com/photo_folder/1692321569KL.PNG',
    ],
    [
        'title'   => 'Drama – P. Muthukumaran',
        'slug'    => 'drama-p-muthukumaran-sri-ganapathi-ashram-velachery-march-2026',
        'artist'  => 'P. Muthukumaran',
        'type'    => 'Drama',
        'venue'   => 'Sri Ganapathi Sachchidananda Ashram Hall',
        'address' => 'Sachchidananda Nagar Main Road (Taramani Link Road), Seshadripuram, Baby Nagar, Velachery 600042',
        'locality'=> 'Velachery',
        'date'    => '2026-03-29',
        'time'    => '18:30:00',
        'free'    => 0,
        'price'   => 'Ticketed (contact venue)',
        'phone'   => '98410 70390',
        'source'  => 'https://www.chennaievent.com/event-view.php?event_id=NTI3NjE=',
        'image'   => '',  // No artist photo found on ChennaiEvents
    ],
];

$inserted = 0;
$skipped = 0;

foreach ($events as $ev) {
    $dedupe = $conn->prepare('SELECT id, slug FROM event_listings WHERE slug = ? LIMIT 1');
    $dedupe->bind_param('s', $ev['slug']);
    $dedupe->execute();
    if ($dedupe->get_result()->fetch_assoc()) {
        echo "Skip (exists): {$ev['title']}\n";
        $skipped++;
        continue;
    }

    $slug = ensureUniqueSlug($conn, $ev['slug']);
    $location = $ev['venue'] . ', ' . $ev['address'];
    $startDatetime = $ev['date'] . ' ' . $ev['time'];
    $endDatetime = $ev['date'] . ' ' . ($ev['time'] === '18:30:00' ? '21:30:00' : '21:00:00');
    $isFree = $ev['free'];
    $price = $ev['price'] ?? '';
    $organizerName = $ev['venue'];
    $organizerEmail = '';
    $organizerPhone = $ev['phone'];
    $ticketsUrl = $ev['source'];
    $websiteUrl = 'https://www.chennaievent.com/';
    $imageUrl = $ev['image'] ?? '';
    $isAllDay = 0;
    $featured = 0;
    $status = 'scheduled';

    $description = '<p><strong>' . htmlspecialchars($ev['artist']) . '</strong> – ' . htmlspecialchars($ev['type']) . '</p>';
    $description .= '<p>Venue: ' . htmlspecialchars($ev['venue']) . ', ' . htmlspecialchars($ev['locality']) . '</p>';
    $description .= '<p>Source: <a href="' . htmlspecialchars($ev['source']) . '" rel="noopener noreferrer">ChennaiEvents.com</a></p>';

    $ins->bind_param(
        'ssisssssssiisssssis',
        $ev['title'],
        $slug,
        $categoryId,
        $organizerName,
        $organizerEmail,
        $organizerPhone,
        $location,
        $ev['locality'],
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
        fwrite(STDERR, "Insert failed for {$ev['title']}: " . $ins->error . "\n");
        continue;
    }

    $newId = (int) $conn->insert_id;
    echo "Inserted id={$newId}: {$ev['title']}\n";
    echo "  URL: https://myomr.in/omr-local-events/event/{$slug}\n";
    $inserted++;
}

echo "\nDone. Inserted: {$inserted}, Skipped: {$skipped}\n";
