<?php
/**
 * Set hero image_path for recent election / manifesto articles (full HTTPS URLs supported by article.php).
 * Remote: $env:DB_HOST='myomr.in'; php dev-tools/sql/update-election-article-images-2026-03.php
 *
 * TVK / DMK (add slug => URL to $map when those articles exist):
 * - TVK: https://pplx-res.cloudinary.com/image/upload/pplx_search_images/202c963c28f295d3453c6f59d209d1721d484891.jpg
 * - DMK: https://pplx-res.cloudinary.com/image/upload/pplx_search_images/4d2c880e905eb6b3bcda760737024dbffbb59a0c.jpg
 */
declare(strict_types=1);

$db_host = getenv('DB_HOST');
if ($db_host !== false && $db_host !== '') {
    $db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
    $db_pass = getenv('DB_PASS') ?: 'myomr@123';
    $db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';
    $db_port = (int) (getenv('DB_PORT') ?: 3306);
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
} else {
    require_once __DIR__ . '/../../core/omr-connect.php';
}

if (!isset($conn) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed.\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$map = [
    // Published in 2026-03-26 draft batch
    'ntk-2026-manifesto-five-capitals-state-rail-supreme-court-bench' => 'https://pplx-res.cloudinary.com/image/upload/pplx_search_images/7ff54ec885daecfec7fe2ccdcd3e88996700d13b.jpg',
    'ntk-2026-manifesto-five-capitals-state-rail-supreme-court-bench-tamil' => 'https://pplx-res.cloudinary.com/image/upload/pplx_search_images/7ff54ec885daecfec7fe2ccdcd3e88996700d13b.jpg',
    'aiadmk-first-candidate-list-2026-tn-assembly-elections' => 'https://pplx-res.cloudinary.com/image/upload/pplx_search_images/5b0aed104f4e6a2a63bc62513d489f45fb72d1bf.jpg',
    'aiadmk-first-candidate-list-2026-tn-assembly-elections-tamil' => 'https://pplx-res.cloudinary.com/image/upload/pplx_search_images/5b0aed104f4e6a2a63bc62513d489f45fb72d1bf.jpg',
];

$stmt = $conn->prepare('UPDATE articles SET image_path = ?, updated_at = NOW() WHERE slug = ? LIMIT 1');
if ($stmt === false) {
    fwrite(STDERR, 'Prepare failed: ' . $conn->error . "\n");
    exit(1);
}

$updated = 0;
$skipped = 0;
foreach ($map as $slug => $url) {
    $stmt->bind_param('ss', $url, $slug);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Updated: {$slug}\n";
        ++$updated;
    } else {
        echo "No row (skipped): {$slug}\n";
        ++$skipped;
    }
}
$stmt->close();

echo "---\nDone. Updated {$updated} article(s); {$skipped} slug(s) had no matching row.\n";

// Help discover TVK/DMK slugs if still unknown
$res = $conn->query(
    "SELECT slug, title FROM articles WHERE status = 'published' AND (
        slug LIKE '%tvk%' OR slug LIKE '%dmk%' OR slug LIKE '%manifesto%'
    ) ORDER BY updated_at DESC LIMIT 25"
);
if ($res && $res->num_rows > 0) {
    echo "\nRelated published slugs on DB (for reference):\n";
    while ($row = $res->fetch_assoc()) {
        echo '  ' . $row['slug'] . "\n";
    }
}
