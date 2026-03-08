<?php
/**
 * One-time script: Insert OMR Infrastructure article into articles table.
 *
 * DO NOT paste this file into phpMyAdmin — it is PHP, not SQL.
 * For phpMyAdmin use: weblog/insert-omr-infrastructure-article.sql
 *
 * Run this script:
 * - Browser: https://myomr.in/weblog/insert-omr-infrastructure-article.php?key=INSERT_OMR_INFRA_2025
 * - CLI: php weblog/insert-omr-infrastructure-article.php
 *
 * Result:
 * - Article appears on homepage news cards
 * - Detail page: /local-news/omr-infrastructure-update-flyover-metro-road-repairs-feb-2025
 *
 * SECURITY: Delete this file after running once, or protect via .htaccess.
 */

$INSERT_KEY = 'INSERT_OMR_INFRA_2025'; // Change or remove after use

$is_cli = (php_sapi_name() === 'cli');
if (!$is_cli) {
    $key = $_GET['key'] ?? '';
    if ($key !== $INSERT_KEY) {
        header('HTTP/1.0 403 Forbidden');
        echo 'Forbidden. Use ?key=' . $INSERT_KEY . ' to run.';
        exit;
    }
}

require_once __DIR__ . '/../core/omr-connect.php';

$slug = 'omr-infrastructure-update-flyover-metro-road-repairs-feb-2025';

// Check if already inserted
$check = $conn->prepare("SELECT id FROM articles WHERE slug = ? LIMIT 1");
$check->bind_param('s', $slug);
$check->execute();
$res = $check->get_result();
if ($res->num_rows > 0) {
    $is_cli ? print("Article already exists. Detail: /local-news/{$slug}\n") : null;
    if (!$is_cli) {
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Already added</title></head><body>';
        echo '<p>Article already in database. It appears on the <a href="/">homepage</a> news cards and has a ';
        echo '<a href="/local-news/' . htmlspecialchars($slug) . '">detail page</a>.</p>';
        echo '</body></html>';
    }
    exit;
}
$check->close();

$title = 'OMR Infrastructure Update: New Flyover at Tidel Park, Metro Impact, and Road Repairs';
$summary = 'A round-up of OMR infrastructure: new U-shaped flyover and foot overbridge at Tidel Park, Chennai Metro Phase II traffic impact between SRP Tools and Navalur, 5 km relaying by September 2025, and Siruseri–Padur widening by Feb 2026.';
$category = 'Local News';
$author = 'MyOMR Editorial Team';
$status = 'published';
$published_date = '2025-02-24';
$image_path = '/My-OMR-Logo.jpg';
$tags = 'OMR, Old Mahabalipuram Road, Chennai, infrastructure, flyover, Tidel Park, Chennai Metro, road repair, Sholinganallur, Navalur, SRP Tools, TNRDC';
$is_featured = 0;

$content = <<<'HTML'
<p>Old Mahabalipuram Road (OMR) is seeing a mix of new infrastructure openings, ongoing Metro work, and planned road repairs. Here's a collated round-up of what's happening along the IT corridor and how it affects residents and commuters.</p>

<h2>New U-Shaped Flyover and Foot Overbridge at Tidel Park</h2>

<p>In late February 2025, Deputy Chief Minister Udhayanidhi Stalin inaugurated a second U-shaped flyover and a foot overbridge at the Tidel Park junction on OMR. The flyover spans 510 metres and was built at a cost of around ₹27.5 crore. It allows vehicles from ECR to reach Madhya Kailash without stopping at signals and connects to CSIR Road in Taramani.</p>

<p>A 155-metre foot overbridge with escalators was also opened at an estimated cost of ₹11.3 crore. It connects Thiruvanmiyur MRTS, Tidel Park, and nearby IT companies, making it easier for pedestrians and office-goers to cross OMR safely.</p>

<div class="info-box">
<strong>At a glance</strong><br>
• Flyover: 510 m, ~₹27.5 crore; ECR → Madhya Kailash / CSIR Road<br>
• Foot overbridge: 155 m with escalators, ~₹11.3 crore<br>
• Location: Tidel Park junction, OMR
</div>

<h2>Chennai Metro Phase II and OMR Traffic</h2>

<p>Phase II of the Chennai Metro Rail project is affecting traffic and road conditions on OMR. The stretch between SRP Tools and Navalur is especially congested during peak hours. The road surface has deteriorated in several places, and many footpaths have been removed or blocked by construction, making it harder for pedestrians.</p>

<p>Motorists report longer commute times. Once Metro work is fully completed and the remaining stretch is handed over to the Tamil Nadu Road Development Company (TNRDC), a full relaying of the road is planned.</p>

<h2>OMR–ECR Link Road Near Sholinganallur</h2>

<p>The OMR–ECR link road near the Sholinganallur junction is in poor condition. Construction debris, dust, and a reduced road width are making commutes difficult. The road level has dipped in places due to construction, and there have been reports of accidents, including a motorcycle fall and a car overturn at the SRP Tools junction. Potholes are widespread; some temporary repairs using steel frames have themselves been linked to safety concerns.</p>

<div class="alert-box">
<strong>Commuter note</strong><br>
Drive with extra care on the OMR–ECR link and around SRP Tools. Expect delays and uneven surfaces until repairs and Metro handover are complete.
</div>

<h2>5 km of OMR to Be Relaid by September 2025</h2>

<p>The Tamil Nadu Road Development Company (TNRDC) plans to re-lay about 5 km of OMR by the end of September 2025. TNRDC has called four tenders worth around ₹40 crore to repair service lanes, correct road levels, and improve drainage. The full road will be completely re-laid once the remaining stretch is handed over to TNRDC after about one-and-a-half years.</p>

<h2>Road Widening: Siruseri SIPCOT to Padur</h2>

<p>A separate ₹42-crore road-widening project is under way on a 2.5 km stretch between Siruseri SIPCOT and Padur. It is expected to be completed by February 2026 and will ease traffic on this part of OMR.</p>

<h2>Broader Infrastructure Concerns on the IT Corridor</h2>

<p>A joint inspection of 2,513 buildings on the IT corridor highlighted wider issues: inadequate water and sewage connections, poor road infrastructure, ineffective stormwater drainage, and solid-waste management problems. The area saw severe flooding even with minimal rainfall in 2024, which led to a Chief Secretary-level inspection. Residents have indicated they will take these infrastructure concerns to the State government.</p>

<p>These findings underline that, alongside new flyovers and Metro work, OMR still needs sustained investment in water, drainage, and roads to become a fully resilient corridor.</p>

<h2>Summary for OMR Residents</h2>

<p>New infrastructure such as the Tidel Park flyover and foot overbridge is improving connectivity and safety. At the same time, Metro Phase II work is causing congestion and damaged roads, especially between SRP Tools and Navalur and on the OMR–ECR link. Planned measures include about 5 km of OMR relaying by September 2025 and the Siruseri–Padur widening by February 2026. Residents can expect a mix of short-term disruption and medium-term improvement as these projects are completed.</p>

<p><strong>Sources:</strong> The Hindu, The New Indian Express, Times of India, Live Chennai (February 2025).</p>
HTML;

$stmt = $conn->prepare("INSERT INTO articles (title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
if (!$stmt) {
    $err = $conn->error;
    if ($is_cli) { echo "Prepare failed: $err\n"; exit(1); }
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><body><p>Database error: ' . htmlspecialchars($err) . '</p></body></html>';
    exit(1);
}
$stmt->bind_param('ssssssssssi', $title, $slug, $summary, $content, $category, $author, $status, $published_date, $image_path, $tags, $is_featured);

if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    $conn->close();
    if ($is_cli) { echo "Insert failed: $err\n"; exit(1); }
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><body><p>Insert failed: ' . htmlspecialchars($err) . '</p></body></html>';
    exit(1);
}

$id = $conn->insert_id;
$stmt->close();
$conn->close();

$detail_url = '/local-news/' . $slug;
$home_url = '/';

if ($is_cli) {
    echo "Article inserted (ID: $id). Homepage: $home_url | Detail: $detail_url\n";
    exit(0);
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article added - MyOMR</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 560px; margin: 40px auto; padding: 20px; }
        .ok { color: #0a0; font-weight: bold; }
        a { color: #008552; }
        ul { margin: 1em 0; padding-left: 1.2em; }
    </style>
</head>
<body>
    <p class="ok">Article added successfully (ID: <?php echo (int) $id; ?>).</p>
    <ul>
        <li><strong>Homepage news cards:</strong> <a href="<?php echo htmlspecialchars($home_url); ?>"><?php echo htmlspecialchars($home_url); ?></a> — the new article appears in the first cards.</li>
        <li><strong>Detail page:</strong> <a href="<?php echo htmlspecialchars($detail_url); ?>"><?php echo htmlspecialchars($detail_url); ?></a> — full story and SEO.</li>
    </ul>
    <p><small>You can delete this file (weblog/insert-omr-infrastructure-article.php) after use.</small></p>
</body>
</html>
