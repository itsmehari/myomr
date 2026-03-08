<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(EALL);

session_start();
if (empty($_SESSION['admin_logged_in'])) { header('Location: /admin/login.php'); exit; }
require __DIR__ . '/../../core/omr-connect.php';

function sanitize_str($s) { return trim(filter_var($s, FILTER_UNSAFE_RAW)); }

// Export
if (isset($_GET['action']) && $_GET['action'] === 'export') {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="it-parks-export.csv"');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['id','name','locality','address','phone','website','inauguration_year','owner','built_up_area','total_area','image','lat','lng','verified']);
  $rs = $conn->query('SELECT id, name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, lat, lng, verified FROM omr_it_parks ORDER BY id ASC');
  if ($rs) {
    while ($r = $rs->fetch_assoc()) {
      fputcsv($out, [$r['id'],$r['name'],$r['locality'],$r['address'],$r['phone'],$r['website'],$r['inauguration_year'],$r['owner'],$r['built_up_area'],$r['total_area'],$r['image'],$r['lat'],$r['lng'],$r['verified']]);
    }
  }
  fclose($out);
  exit;
}

$message = '';$stats = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='import') {
  if (!empty($_FILES['csv']['name']) && is_uploaded_file($_FILES['csv']['tmp_name'])) {
    $fp = fopen($_FILES['csv']['tmp_name'], 'r');
    if ($fp) {
      $header = fgetcsv($fp);
      $expected = ['id','name','locality','address','phone','website','inauguration_year','owner','built_up_area','total_area','image','lat','lng','verified'];
      $ins = 0; $upd = 0; $skip = 0; $line = 1;
      $stmtIns = $conn->prepare('INSERT INTO omr_it_parks (name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, lat, lng, verified) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
      $stmtUpd = $conn->prepare('UPDATE omr_it_parks SET name=?, locality=?, address=?, phone=?, website=?, inauguration_year=?, owner=?, built_up_area=?, total_area=?, image=?, lat=?, lng=?, verified=? WHERE id=?');
      while (($row = fgetcsv($fp)) !== false) {
        $line++;
        if (count($row) < 14) { $skip++; continue; }
        list($id,$name,$locality,$address,$phone,$website,$year,$owner,$built,$total,$image,$lat,$lng,$verified) = $row;
        $id = (int)$id; $verified = (int)$verified; $lat = ($lat!==''? (float)$lat:null); $lng = ($lng!==''? (float)$lng:null);
        if ($id > 0) {
          $stmtUpd->bind_param('sssssssssssdii', $name,$locality,$address,$phone,$website,$year,$owner,$built,$total,$image,$lat,$lng,$verified,$id);
          $stmtUpd->execute(); $upd++;
        } else {
          $stmtIns->bind_param('sssssssssssdi', $name,$locality,$address,$phone,$website,$year,$owner,$built,$total,$image,$lat,$lng,$verified);
          $stmtIns->execute(); $ins++;
        }
      }
      fclose($fp);
      $stmtIns->close(); $stmtUpd->close();
      $stats = "Imported: $ins, Updated: $upd, Skipped: $skip";
    } else { $message = 'Unable to read CSV.'; }
  } else { $message = 'No file uploaded.'; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · IT Parks Import/Export</title>
  <?php if (file_exists(__DIR__ . '/../../components/head-resources.php')) include __DIR__ . '/../../components/head-resources.php'; ?>
</head>
<body>
  <div class="container py-3">
    <h1 style="color:#0583D2;">IT Parks · Import / Export</h1>
    <?php if ($message): ?><div class="alert alert-warning"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <?php if ($stats): ?><div class="alert alert-success"><?php echo htmlspecialchars($stats, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

    <div class="card mb-3"><div class="card-body">
      <h5 class="card-title">Export CSV</h5>
      <a class="btn btn-outline-primary" href="?action=export">Download CSV</a>
    </div></div>

    <div class="card"><div class="card-body">
      <h5 class="card-title">Import CSV</h5>
      <form method="post" enctype="multipart/form-data" action="">
        <input type="hidden" name="action" value="import">
        <div class="form-group">
          <label for="csv">CSV File</label>
          <input type="file" name="csv" id="csv" class="form-control-file" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
      </form>
      <p class="mt-2 text-muted small">Expected headers: id,name,locality,address,phone,website,inauguration_year,owner,built_up_area,total_area,image,lat,lng,verified. Leave id blank for inserts.</p>
    </div></div>

    <div class="mt-3"><a class="btn btn-link" href="manage.php">Back to Manage</a></div>
  </div>
</body>
</html>


