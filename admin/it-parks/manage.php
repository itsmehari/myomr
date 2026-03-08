<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(EALL);

require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
require __DIR__ . '/../../core/omr-connect.php';
require __DIR__ . '/../../core/cache-helpers.php';

function purge_it_parks_cache() {
  $dir = omr_cache_dir();
  foreach (glob($dir . '/it_parks:*') as $f) { @unlink($f); }
}

function sanitize_str($s) { return trim(filter_var($s, FILTER_UNSAFE_RAW)); }

$message = '';

// CSRF token
if (empty($_SESSION['admin_csrf'])) { $_SESSION['admin_csrf'] = bin2hex(random_bytes(16)); }

// Simple admin action log
function admin_log($action, $details = '') {
  $line = date('c') . "\t" . ($_SESSION['admin_role'] ?? 'admin') . "\t" . ($action) . "\t" . ($details) . "\t" . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n";
  @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/weblog/logfile.txt', $line, FILE_APPEND);
}

// Create table if missing (safety)
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_parks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  locality VARCHAR(100) NULL,
  address VARCHAR(400) NULL,
  phone VARCHAR(100) NULL,
  website VARCHAR(255) NULL,
  inauguration_year VARCHAR(10) NULL,
  owner VARCHAR(160) NULL,
  built_up_area VARCHAR(80) NULL,
  total_area VARCHAR(80) NULL,
  image VARCHAR(255) NULL,
  lat DECIMAL(10,7) NULL,
  lng DECIMAL(10,7) NULL,
  verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF check
  $token = $_POST['csrf'] ?? '';
  if (!hash_equals($_SESSION['admin_csrf'], (string)$token)) {
    http_response_code(403);
    exit('Invalid session token.');
  }
  $action = isset($_POST['action']) ? sanitize_str($_POST['action']) : '';
  if ($action === 'create' || $action === 'update') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = sanitize_str($_POST['name'] ?? '');
    $locality = sanitize_str($_POST['locality'] ?? '');
    $address = sanitize_str($_POST['address'] ?? '');
    $phone = sanitize_str($_POST['phone'] ?? '');
    $website = sanitize_str($_POST['website'] ?? '');
    $year = sanitize_str($_POST['inauguration_year'] ?? '');
    $owner = sanitize_str($_POST['owner'] ?? '');
    $built = sanitize_str($_POST['built_up_area'] ?? '');
    $total = sanitize_str($_POST['total_area'] ?? '');
    $lat = $_POST['lat'] !== '' ? (float)$_POST['lat'] : null;
    $lng = $_POST['lng'] !== '' ? (float)$_POST['lng'] : null;
    $verified = isset($_POST['verified']) ? 1 : 0;
    $imagePath = sanitize_str($_POST['existing_image'] ?? '');

    // Handle image upload
    if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
      $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];
      $mime = mime_content_type($_FILES['image']['tmp_name']);
      if (!isset($allowed[$mime])) { $message = 'Invalid image type.'; }
      else if ($_FILES['image']['size'] > 2*1024*1024) { $message = 'Image too large (max 2MB).'; }
      else {
        $dim = @getimagesize($_FILES['image']['tmp_name']);
        if (!$dim || $dim[0] < 300 || $dim[1] < 200) { $message = 'Image too small (min 300x200).'; }
        else {
        $ext = $allowed[$mime];
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/it-parks';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/','-', $name), '-'));
        $fname = $slug . '-' . time() . $ext;
        $dest = $dir . '/' . $fname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          $imagePath = '/assets/images/it-parks/' . $fname;
        } else {
          $message = 'Failed to save image.';
        }
        }
      }
    }

    // Duplicate check name+locality
    if ($message === '' && $name !== '') {
      $locKey = $locality !== '' ? $locality : '';
      if ($action === 'create') {
        $st = $conn->prepare('SELECT id FROM omr_it_parks WHERE name=? AND COALESCE(locality, "") = ? LIMIT 1');
        $st->bind_param('ss', $name, $locKey);
      } else {
        $st = $conn->prepare('SELECT id FROM omr_it_parks WHERE name=? AND COALESCE(locality, "") = ? AND id <> ? LIMIT 1');
        $st->bind_param('ssi', $name, $locKey, $id);
      }
      $st->execute();
      $dupr = $st->get_result();
      if ($dupr && $dupr->num_rows > 0) {
        $message = 'Duplicate: An IT Park with the same name and locality already exists.';
      }
      $st->close();
    }

    if ($name !== '' && $message === '') {
      if ($action === 'create') {
        $stmt = $conn->prepare('INSERT INTO omr_it_parks (name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, lat, lng, verified, amenity_sez, amenity_parking, amenity_cafeteria, amenity_shuttle) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $amen_sez = !empty($_POST['amenity_sez']) ? 1 : 0;
        $amen_parking = !empty($_POST['amenity_parking']) ? 1 : 0;
        $amen_cafe = !empty($_POST['amenity_cafeteria']) ? 1 : 0;
        $amen_shuttle = !empty($_POST['amenity_shuttle']) ? 1 : 0;
        $stmt->bind_param('sssssssssssdiIIII', $name, $locality, $address, $phone, $website, $year, $owner, $built, $total, $imagePath, $lat, $lng, $verified, $amen_sez, $amen_parking, $amen_cafe, $amen_shuttle);
        $stmt->execute();
        $stmt->close();
        purge_it_parks_cache();
        admin_log('it_parks_create', $name);
        header('Location: manage.php?ok=1');
        exit;
      } else if ($id > 0) {
        $stmt = $conn->prepare('UPDATE omr_it_parks SET name=?, locality=?, address=?, phone=?, website=?, inauguration_year=?, owner=?, built_up_area=?, total_area=?, image=?, lat=?, lng=?, verified=?, amenity_sez=?, amenity_parking=?, amenity_cafeteria=?, amenity_shuttle=? WHERE id=?');
        $amen_sez = !empty($_POST['amenity_sez']) ? 1 : 0;
        $amen_parking = !empty($_POST['amenity_parking']) ? 1 : 0;
        $amen_cafe = !empty($_POST['amenity_cafeteria']) ? 1 : 0;
        $amen_shuttle = !empty($_POST['amenity_shuttle']) ? 1 : 0;
        $stmt->bind_param('sssssssssssdiiiii', $name, $locality, $address, $phone, $website, $year, $owner, $built, $total, $imagePath, $lat, $lng, $verified, $amen_sez, $amen_parking, $amen_cafe, $amen_shuttle, $id);
        $stmt->execute();
        $stmt->close();
        purge_it_parks_cache();
        admin_log('it_parks_update', $name.'#'.$id);
        header('Location: manage.php?ok=1');
        exit;
      } else {
        $message = 'Invalid ID.';
      }
    }
  } elseif ($action === 'delete') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
      // Role gate: only super_admin can delete
      requireRole(['super_admin']);
      $stmt = $conn->prepare('DELETE FROM omr_it_parks WHERE id=?');
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->close();
      purge_it_parks_cache();
      admin_log('it_parks_delete', '#'.$id);
      header('Location: manage.php?deleted=1');
      exit;
    } else {
      $message = 'Invalid ID for delete.';
    }
  } elseif ($action === 'purge_cache') {
    purge_it_parks_cache();
    admin_log('it_parks_purge_cache');
    header('Location: manage.php?purged=1');
    exit;
  }
}

// Load rows
$rows = [];
// List with search/sort/pagination
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 20;
$where = '';$params=[];$types='';
if ($q !== '') { $where = ' WHERE name LIKE ? OR locality LIKE ? '; $like='%'.$q.'%'; $params=[$like,$like]; $types='ss'; }
$order = ' ORDER BY name ASC';
if ($sort === 'name_desc') $order = ' ORDER BY name DESC';
if ($sort === 'newest') $order = ' ORDER BY id DESC';
// count
$countSql = 'SELECT COUNT(*) AS c FROM omr_it_parks' . $where;
$stmt = $conn->prepare($countSql);
if ($stmt) { if ($types!=='') $stmt->bind_param($types, ...$params); $stmt->execute(); $rc=$stmt->get_result(); $rowc=$rc?$rc->fetch_assoc():['c'=>0]; $total=(int)($rowc['c']??0); $stmt->close(); } else { $total=0; }
$pages = max(1, (int)ceil($total/$perPage)); $page = min($page,$pages); $off = ($page-1)*$perPage;
$dataSql = 'SELECT id, name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, verified, amenity_sez, amenity_parking, amenity_cafeteria, amenity_shuttle FROM omr_it_parks' . $where . $order . ' LIMIT ? OFFSET ?';
$stmt = $conn->prepare($dataSql);
if ($stmt) { $types2=$types.'ii'; $params2=array_merge($params, [$perPage,$off]); $stmt->bind_param($types2, ...$params2); $stmt->execute(); $rs=$stmt->get_result(); while ($r=$rs->fetch_assoc()) { $rows[]=$r; } $stmt->close(); }

$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$editRow = null;
if ($editId > 0) { foreach ($rows as $r) { if ((int)$r['id'] === $editId) { $editRow = $r; break; } } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · IT Parks</title>
  <?php if (file_exists(__DIR__ . '/../../components/head-resources.php')) include __DIR__ . '/../../components/head-resources.php'; ?>
  <style> body { font-family: 'Poppins', sans-serif; } .maxw-1280 { max-width: 1280px; } </style>
</head>
<body>
  <div class="container maxw-1280 py-3">
    <h1 style="color:#0583D2;">Manage IT Parks</h1>
    <?php if (!empty($_GET['ok'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert">Saved.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php endif; ?>
    <?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert">Deleted.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php endif; ?>
    <?php if (!empty($_GET['purged'])): ?><div class="alert alert-info alert-dismissible fade show" role="alert">Cache purged.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php endif; ?>
    <?php if (!empty($message)): ?><div class="alert alert-warning"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title"><?php echo $editRow ? 'Edit Park' : 'Add Park'; ?></h5>
        <form method="post" enctype="multipart/form-data" action="">
          <input type="hidden" name="action" value="<?php echo $editRow ? 'update' : 'create'; ?>">
          <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
          <?php if ($editRow): ?><input type="hidden" name="id" value="<?php echo (int)$editRow['id']; ?>"><?php endif; ?>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($editRow['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="locality">Locality</label>
              <input type="text" class="form-control" id="locality" name="locality" value="<?php echo htmlspecialchars($editRow['locality'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="inauguration_year">Year</label>
              <input type="text" class="form-control" id="inauguration_year" name="inauguration_year" value="<?php echo htmlspecialchars($editRow['inauguration_year'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($editRow['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($editRow['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="website">Website</label>
              <input type="url" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($editRow['website'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="owner">Owner</label>
              <input type="text" class="form-control" id="owner" name="owner" value="<?php echo htmlspecialchars($editRow['owner'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="built_up_area">Built-up</label>
              <input type="text" class="form-control" id="built_up_area" name="built_up_area" value="<?php echo htmlspecialchars($editRow['built_up_area'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="total_area">Total area</label>
              <input type="text" class="form-control" id="total_area" name="total_area" value="<?php echo htmlspecialchars($editRow['total_area'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="lat">Lat</label>
              <input type="text" class="form-control" id="lat" name="lat" value="">
            </div>
            <div class="form-group col-md-3">
              <label for="lng">Lng</label>
              <input type="text" class="form-control" id="lng" name="lng" value="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="image">Image</label>
              <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
              <?php if (!empty($editRow['image'])): ?>
                <div class="mt-2"><img src="<?php echo htmlspecialchars($editRow['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="current" style="max-height:80px"></div>
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editRow['image'], ENT_QUOTES, 'UTF-8'); ?>">
              <?php endif; ?>
              <div class="mt-2"><img id="preview" src="" alt="preview" style="display:none;max-height:80px"></div>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="verified" name="verified" <?php echo (!empty($editRow['verified']) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="verified">Verified</label>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="amenity_sez" name="amenity_sez" <?php echo (!empty($editRow['amenity_sez']) ? 'checked' : ''); ?>><label class="form-check-label" for="amenity_sez">SEZ</label></div></div>
            <div class="form-group col-md-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="amenity_parking" name="amenity_parking" <?php echo (!empty($editRow['amenity_parking']) ? 'checked' : ''); ?>><label class="form-check-label" for="amenity_parking">Parking</label></div></div>
            <div class="form-group col-md-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="amenity_cafeteria" name="amenity_cafeteria" <?php echo (!empty($editRow['amenity_cafeteria']) ? 'checked' : ''); ?>><label class="form-check-label" for="amenity_cafeteria">Cafeteria</label></div></div>
            <div class="form-group col-md-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="amenity_shuttle" name="amenity_shuttle" <?php echo (!empty($editRow['amenity_shuttle']) ? 'checked' : ''); ?>><label class="form-check-label" for="amenity_shuttle">Shuttle</label></div></div>
          </div>
          <button type="submit" class="btn btn-primary"><?php echo $editRow ? 'Update' : 'Add'; ?></button>
          <?php if ($editRow): ?><a href="manage.php" class="btn btn-secondary ml-2">Cancel</a><?php endif; ?>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">All IT Parks</h5>
        <form class="form-inline mb-2" method="get" action="">
          <input type="text" class="form-control mr-2" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search name/locality">
          <select class="form-control mr-2" name="sort">
            <option value="name_asc" <?php echo $sort==='name_asc'?'selected':''; ?>>Name A–Z</option>
            <option value="name_desc" <?php echo $sort==='name_desc'?'selected':''; ?>>Name Z–A</option>
            <option value="newest" <?php echo $sort==='newest'?'selected':''; ?>>Newest</option>
          </select>
          <button class="btn btn-outline-primary" type="submit">Apply</button>
          <form method="post" class="d-inline ml-2" onsubmit="return confirm('Purge cached pages for IT Parks?');">
            <input type="hidden" name="action" value="purge_cache">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
            <button class="btn btn-outline-secondary" type="submit">Purge Cache</button>
          </form>
        </form>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead><tr><th>ID</th><th>Name</th><th>Locality</th><th>Website</th><th>Verified</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="6" class="text-center">No parks yet.</td></tr>
            <?php else: foreach ($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($r['locality'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php if (!empty($r['website'])): ?><a href="<?php echo htmlspecialchars($r['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Visit</a><?php endif; ?></td>
                <td><?php echo !empty($r['verified']) ? 'Yes' : 'No'; ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-primary" href="manage.php?edit_id=<?php echo (int)$r['id']; ?>">Edit</a>
                  <?php $slugBase = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/','-', $r['name']), '-')); ?>
                  <a class="btn btn-sm btn-outline-secondary" href="/it-parks/<?php echo htmlspecialchars($slugBase.'-'.(int)$r['id'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Preview</a>
                  <form method="post" action="" style="display:inline-block" onsubmit="return confirm('Delete this park?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          <a class="btn btn-sm btn-outline-info" href="import-export.php">Import/Export CSV</a>
        </div>
        <?php if ($pages > 1): ?>
          <nav class="mt-2" aria-label="Pagination"><ul class="pagination">
            <li class="page-item <?php echo $page<=1?'disabled':''; ?>"><a class="page-link" href="?q=<?php echo urlencode($q); ?>&sort=<?php echo urlencode($sort); ?>&page=1">&laquo;</a></li>
            <li class="page-item <?php echo $page<=1?'disabled':''; ?>"><a class="page-link" href="?q=<?php echo urlencode($q); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo max(1,$page-1); ?>">&lsaquo;</a></li>
            <li class="page-item active"><span class="page-link"><?php echo $page; ?> / <?php echo $pages; ?></span></li>
            <li class="page-item <?php echo $page>=$pages?'disabled':''; ?>"><a class="page-link" href="?q=<?php echo urlencode($q); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo min($pages,$page+1); ?>">&rsaquo;</a></li>
            <li class="page-item <?php echo $page>=$pages?'disabled':''; ?>"><a class="page-link" href="?q=<?php echo urlencode($q); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $pages; ?>">&raquo;</a></li>
          </ul></nav>
        <?php endif; ?>
      </div>
    </div>

  </div>
<script>
document.getElementById('image')?.addEventListener('change', function(e){
  const f = e.target.files[0];
  if (!f) return;
  const url = URL.createObjectURL(f);
  const img = document.getElementById('preview');
  img.src = url; img.style.display = 'block';
});
</script>
</body>
</html>


