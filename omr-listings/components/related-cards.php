<?php
// Expects $relatedItems (array of ['name','address','url','imageCandidates']), and $fallbackImage
if (empty($relatedItems) || !is_array($relatedItems)) { return; }
?>
<div class="row">
  <?php foreach ($relatedItems as $item): ?>
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <?php
          $img = $fallbackImage;
          if (!empty($item['imageCandidates']) && is_array($item['imageCandidates'])) {
            foreach ($item['imageCandidates'] as $candidate) {
              // optimistic use; server may not have file—fallback will still show
              $img = $candidate; break;
            }
          }
        ?>
        <img class="card-img-top" src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy" decoding="async">
        <div class="card-body">
          <h5 class="card-title" style="font-size:1rem;"><?php echo htmlspecialchars($item['name']); ?></h5>
          <?php if (!empty($item['address'])): ?>
            <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($item['address']); ?></small></p>
          <?php endif; ?>
          <a class="btn btn-sm btn-primary" href="<?php echo htmlspecialchars($item['url']); ?>">View</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php
// Generic related cards include.
// Expects $relatedItems = [ [ 'name' => string, 'address' => string, 'url' => string, 'imageCandidates' => [ '/path.webp', '/path.jpg', ... ] ], ... ]
// Optional: $fallbackImage (default '/My-OMR-Logo.png')

if (!isset($relatedItems) || !is_array($relatedItems)) { $relatedItems = []; }
if (!isset($fallbackImage) || !is_string($fallbackImage) || $fallbackImage === '') { $fallbackImage = '/My-OMR-Logo.png'; }

if (!empty($relatedItems)):
?>
<div class="row">
  <?php foreach ($relatedItems as $item):
    $nm = (string)($item['name'] ?? '');
    $addr = (string)($item['address'] ?? '');
    $url = (string)($item['url'] ?? '#');
    $candidates = isset($item['imageCandidates']) && is_array($item['imageCandidates']) ? $item['imageCandidates'] : [];
    $imgSrc = $fallbackImage;
    foreach ($candidates as $relPath) {
      $disk = $_SERVER['DOCUMENT_ROOT'] . $relPath;
      if (is_string($relPath) && $relPath !== '' && file_exists($disk)) { $imgSrc = $relPath; break; }
    }
  ?>
  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <img class="card-img-top" src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($nm, ENT_QUOTES, 'UTF-8'); ?>" style="object-fit:contain;height:120px;background:#f8fafc;" loading="lazy" decoding="async">
      <div class="card-body">
        <h5 class="card-title" style="font-size:1rem;"><?php echo htmlspecialchars($nm, ENT_QUOTES, 'UTF-8'); ?></h5>
        <?php if ($addr !== ''): ?><p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($addr, ENT_QUOTES, 'UTF-8'); ?></small></p><?php endif; ?>
        <a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">View</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>


