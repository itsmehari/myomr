<?php
// This file expects variables from parent scope:
// $companyName, $address, $contact, $industry, $aboutTextDb, $servicesTextDb, $careersUrlDb, $mapsUrl
// Optional helper inputs: $entityName, $industryOrType, $localitiesList

$aboutText = trim((string)($aboutTextDb ?? ''));
$servicesText = trim((string)($servicesTextDb ?? ''));
$careersUrl = trim((string)($careersUrlDb ?? ''));

// Detect locality from address for nearby transit block
$detectedLocality = '';
if (isset($localitiesList) && is_array($localitiesList)) {
  foreach ($localitiesList as $loc) { if (stripos((string)$address, $loc) !== false) { $detectedLocality = $loc; break; } }
}

// Heuristic nearby transit by locality
$nearbyTransit = [];
switch (strtolower($detectedLocality)) {
  case 'perungudi':
    $nearbyTransit = ['Perungudi MRTS Station', 'Thoraipakkam Bus Depot']; break;
  case 'kandhanchavadi':
    $nearbyTransit = ['Kandhanchavadi Bus Stop', 'Perungudi MRTS Station']; break;
  case 'thoraipakkam':
    $nearbyTransit = ['Chellammal College Bus Stop', 'Perungudi MRTS Station']; break;
  case 'sholinganallur':
    $nearbyTransit = ['Sholinganallur Bus Terminus', 'Semmancheri Bus Stop']; break;
  case 'siruseri':
    $nearbyTransit = ['Siruseri SIPCOT Bus Stop', 'Navalur Bus Stop']; break;
  default:
    $nearbyTransit = ['OMR Bus Corridor', 'MRTS (Perungudi/Taramani)'];
}

?>

<div class="row">
  <div class="col-lg-8">
    <section class="mb-4">
      <h2 class="h4">About</h2>
      <p><?php echo $aboutText !== '' ? nl2br(htmlspecialchars($aboutText)) : 'Information about this company will be updated soon.'; ?></p>
    </section>

    <section class="mb-4">
      <h2 class="h4">Services</h2>
      <p><?php echo $servicesText !== '' ? nl2br(htmlspecialchars($servicesText)) : 'Services and capabilities will be updated shortly.'; ?></p>
    </section>

    <section class="mb-4">
      <h2 class="h4">Location & Map</h2>
      <p class="mb-2"><strong>Address:</strong> <?php echo htmlspecialchars((string)$address); ?></p>
      <a class="btn btn-sm btn-primary" href="<?php echo htmlspecialchars((string)$mapsUrl); ?>" target="_blank" rel="noopener">Open in Google Maps</a>
    </section>

    <section class="mb-4">
      <h2 class="h4">Nearby Transit</h2>
      <ul class="mb-0">
        <?php foreach ($nearbyTransit as $t): ?>
          <li><?php echo htmlspecialchars($t); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
  </div>
  <div class="col-lg-4">
    <aside class="mb-4">
      <div class="card">
        <div class="card-body">
          <h3 class="h5">Company Info</h3>
          <p class="mb-1"><strong>Industry</strong><br><?php echo htmlspecialchars((string)$industry); ?></p>
          <?php if (!empty($contact)): ?>
            <p class="mb-1"><strong>Contact</strong><br><?php echo htmlspecialchars((string)$contact); ?></p>
          <?php endif; ?>
          <?php if ($careersUrl !== ''): ?>
            <a class="btn btn-sm btn-success mt-2" href="<?php echo htmlspecialchars($careersUrl); ?>" target="_blank" rel="noopener">Careers</a>
          <?php endif; ?>
          <a class="btn btn-sm btn-outline-primary mt-2" href="/local-news/news-highlights-from-omr-road.php">Read OMR News</a>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php
// Generic profile blocks include: expects variables defined by caller
// Required: $entityName, $address, $mapsQuery
// Optional: $industryOrType, $aboutTextDb, $servicesTextDb, $careersUrlDb, $localitiesList

if (!isset($entityName)) { $entityName = ''; }
if (!isset($address)) { $address = ''; }
if (!isset($industryOrType)) { $industryOrType = ''; }
if (!isset($aboutTextDb)) { $aboutTextDb = ''; }
if (!isset($servicesTextDb)) { $servicesTextDb = ''; }
if (!isset($careersUrlDb)) { $careersUrlDb = ''; }
if (!isset($mapsQuery)) { $mapsQuery = urlencode(trim($entityName . ' ' . $address)); }
if (!isset($localitiesList) || !is_array($localitiesList)) {
  $localitiesList = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
}
?>

<div class="row my-4">
  <div class="col-md-8">
    <section class="mb-4">
      <h3>About <?php echo htmlspecialchars($entityName, ENT_QUOTES, 'UTF-8'); ?></h3>
      <p>
        <?php
          if (is_string($aboutTextDb) && trim($aboutTextDb) !== '') {
            echo nl2br(htmlspecialchars($aboutTextDb, ENT_QUOTES, 'UTF-8'));
          } else {
            $fallback = [];
            if (!empty($industryOrType)) { $fallback[] = htmlspecialchars($industryOrType, ENT_QUOTES, 'UTF-8') . ' services'; }
            if (!empty($address)) { $fallback[] = 'located at ' . htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); }
            $fallbackText = !empty($fallback) ? implode(', ', $fallback) : 'Local listing on OMR, Chennai';
            echo $fallbackText . '.';
          }
        ?>
      </p>
    </section>

    <section class="mb-4">
      <h4>Services</h4>
      <p>
        <?php
          $services = trim((string)$servicesTextDb);
          if ($services === '') { $services = trim((string)$industryOrType); }
          echo $services !== ''
            ? nl2br(htmlspecialchars($services, ENT_QUOTES, 'UTF-8'))
            : 'Service information will be updated soon.';
        ?>
      </p>
    </section>

    <section class="mb-4">
      <h4>Careers</h4>
      <p>Looking for opportunities at <?php echo htmlspecialchars($entityName, ENT_QUOTES, 'UTF-8'); ?>?</p>
      <?php $careerQuery = urlencode($entityName . ' careers Chennai'); ?>
      <?php if (is_string($careersUrlDb) && preg_match('#^https?://#i', trim($careersUrlDb))): ?>
        <a class="btn btn-outline-primary" href="<?php echo htmlspecialchars(trim($careersUrlDb), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Visit Careers Page</a>
      <?php else: ?>
        <a class="btn btn-outline-primary" href="https://www.google.com/search?q=<?php echo $careerQuery; ?>" target="_blank" rel="noopener">Explore Careers</a>
      <?php endif; ?>
    </section>
  </div>
  <div class="col-md-4">
    <section class="mb-4">
      <h4>Map</h4>
      <div class="embed-responsive embed-responsive-4by3">
        <iframe class="embed-responsive-item" src="https://www.google.com/maps?q=<?php echo $mapsQuery; ?>&output=embed" style="border:0; width:100%; height:260px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
      </div>
      <?php if (!empty($address)): ?>
        <small class="text-muted d-block mt-2">Address: <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></small>
      <?php endif; ?>
    </section>

    <section class="mb-4">
      <h4>Nearby transit</h4>
      <p>
        <?php
          $nearby = '';
          foreach ($localitiesList as $lc) { if (stripos($address, $lc) !== false) { $nearby = $lc; break; } }
          if ($nearby !== '') {
            echo 'This listing is in or near ' . htmlspecialchars($nearby, ENT_QUOTES, 'UTF-8') . ' along OMR.';
          } else {
            echo 'Located along OMR, accessible by local buses, cabs, and metro feeder routes.';
          }
        ?>
      </p>
    </section>
  </div>
  
</div>


