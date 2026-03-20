<?php
/**
 * Update Eco-Bazaar event with image URL for preview card.
 * Remote: PowerShell: $env:DB_HOST='myomr.in'; php dev-tools/update-eco-bazaar-image.php
 */
declare(strict_types=1);

$imageUrl = 'https://myomr.in/events/imgs/Eco-Bazaar-Events-28thMarch-2026-Myomr-Events.jpg';

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

$slug = 'eco-bazaar-sustainable-living-kottivakkam-march-2026';
$stmt = $conn->prepare('UPDATE event_listings SET image_url = ? WHERE slug = ? LIMIT 1');
$stmt->bind_param('ss', $imageUrl, $slug);

if (!$stmt->execute()) {
    fwrite(STDERR, 'Update failed: ' . $stmt->error . "\n");
    exit(1);
}

$affected = $stmt->affected_rows;
if ($affected > 0) {
    echo "Updated Eco-Bazaar event with image URL.\n";
} else {
    echo "No rows updated (slug may not exist).\n";
}
