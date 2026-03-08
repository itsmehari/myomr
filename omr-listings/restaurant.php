<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../core/omr-connect.php';

function extract_id_from_slug($slug) {
    if (!is_string($slug) || $slug === '') { return 0; }
    $parts = explode('-', $slug);
    $last = end($parts);
    return ctype_digit($last) ? (int)$last : 0;
}

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = extract_id_from_slug($slug);

if ($id <= 0) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Not Found</h1></body></html>';
    exit;
}

$stmt = $conn->prepare('SELECT id, name, address, locality, cuisine, cost_for_two, rating, availability, reviews, imagelocation FROM omr_restaurants WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$r = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$r) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Restaurant not found</h1></body></html>';
    exit;
}

$name = $r['name'];
$address = $r['address'];
$locality = $r['locality'];
$cuisine = $r['cuisine'];
$costForTwo = $r['cost_for_two'];
$rating = $r['rating'];
$availability = $r['availability'];
$imageUrl = $r['imagelocation'];
$mapsQuery = urlencode($name . ' ' . $address);
$canonical = 'https://myomr.in/restaurants/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Restaurant on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Restaurant on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = !empty($imageUrl) ? (strpos($imageUrl,'http')===0 ? $imageUrl : 'https://myomr.in'.$imageUrl) : 'https://myomr.in/My-OMR-Logo.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/restaurants','Restaurants'],
  [$canonical, $name]
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'restaurant', 'locality' => $restaurant['locality'] ?? '', 'cuisine' => $restaurant['cuisine'] ?? '']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<?php
$_r_schema = [
  '@context'     => 'https://schema.org',
  '@type'        => 'Restaurant',
  'name'         => $name,
  'address'      => [
    '@type'           => 'PostalAddress',
    'streetAddress'   => $address,
    'addressLocality' => $locality . ', OMR, Chennai',
    'addressRegion'   => 'Tamil Nadu',
    'addressCountry'  => 'IN',
  ],
  'servesCuisine' => $cuisine,
  'url'           => $canonical,
  'priceRange'    => '₹' . (int)$costForTwo . ' for two',
  'hasMap'        => 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery,
];
if (!empty($imageUrl)) {
  $_r_schema['image'] = strpos($imageUrl, 'http') === 0 ? $imageUrl : 'https://myomr.in' . $imageUrl;
}
if (!empty($rating) && is_numeric($rating)) {
  $_r_schema['aggregateRating'] = ['@type' => 'AggregateRating', 'ratingValue' => (float)$rating, 'bestRating' => 5, 'worstRating' => 1, 'ratingCount' => 1];
}
if (!empty($availability)) {
  $_r_schema['openingHours'] = $availability;
}
echo '<script type="application/ld+json">' . json_encode($_r_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>';
?>
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/restaurants">Restaurants</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-md-8">
      <h1 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
      <p><strong>Locality:</strong> <?php echo htmlspecialchars($locality); ?> | <strong>Cuisine:</strong> <?php echo htmlspecialchars($cuisine); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Cost for Two:</strong> ₹<?php echo (int)$costForTwo; ?> | <strong>Rating:</strong> <?php echo htmlspecialchars($rating); ?></p>
      <p><strong>Availability:</strong> <?php echo htmlspecialchars($availability); ?></p>
      <div class="mb-3">
        <a class="btn btn-outline-primary" href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapsQuery; ?>" target="_blank" rel="noopener">View on Google Maps</a>
        <a class="btn btn-warning" href="/contact-my-omr-team.php?subject=<?php echo urlencode('Listing Enquiry: ' . $name); ?>">Enquire</a>
      </div>
      <?php if (!empty($r['reviews'])): ?>
        <div class="card mb-3"><div class="card-body"><h5 class="card-title">Highlights</h5><p class="mb-0"><?php echo htmlspecialchars($r['reviews']); ?></p></div></div>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <?php if (!empty($imageUrl)): ?>
        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="img-fluid rounded mb-3" loading="lazy" decoding="async" />
      <?php endif; ?>
      <div class="embed-responsive embed-responsive-4by3">
        <iframe class="embed-responsive-item" src="https://www.google.com/maps?q=<?php echo $mapsQuery; ?>&output=embed" style="border:0; width:100%; height:260px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
      </div>
    </div>
  </div>

  <hr>
  <h3>More restaurants in <?php echo htmlspecialchars($locality); ?></h3>
  <?php
    if (!empty($locality)) {
      $like = '%' . $locality . '%';
      $rel = $conn->prepare('SELECT id, name, address FROM omr_restaurants WHERE id <> ? AND locality LIKE ? ORDER BY rating DESC LIMIT 6');
      $rel->bind_param('is', $id, $like);
      if ($rel->execute()) {
        $relRes = $rel->get_result();
        if ($relRes && $relRes->num_rows > 0) {
          $relatedItems = [];
          while ($row = $relRes->fetch_assoc()) {
            $nm = $row['name'];
            $rid = (int)$row['id'];
            $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
            $slugBase = trim($slugBase, '-');
            $url = '/restaurants/' . $slugBase . '-' . $rid;
            $relatedItems[] = [
              'name' => $nm,
              'address' => $row['address'],
              'url' => $url,
              'imageCandidates' => [
                '/assets/img/restaurants/' . $slugBase . '-' . $rid . '.webp',
                '/assets/img/restaurants/' . $slugBase . '-' . $rid . '.jpg',
                '/assets/img/restaurants/' . $slugBase . '-' . $rid . '.png',
              ]
            ];
          }
          $fallbackImage = '/My-OMR-Logo.jpg';
          include __DIR__ . '/components/related-cards.php';
        }
      }
      $rel->close();
    }
  ?>

  <div class="my-4"></div>
  <?php include '../components/subscribe.php'; ?>
</div>

<?php include '../components/footer.php'; ?>

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>
</body>
</html>


