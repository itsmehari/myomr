<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
require __DIR__ . '/../../core/omr-connect.php';
require __DIR__ . '/../../omr-listings/data/it-parks-data.php';

// Ensure featured table exists
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_parks_featured (
  id INT AUTO_INCREMENT PRIMARY KEY,
  park_id INT NOT NULL,
  rank_position INT NOT NULL DEFAULT 1,
  blurb VARCHAR(400) DEFAULT NULL,
  cta_text VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  start_at DATETIME NULL,
  end_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(park_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Build park id -> name map
$parkIdToName = [];
foreach ($omr_it_parks_all as $p) {
  $parkIdToName[(int)$p['id']] = $p['name'];
}

$message = '';
if (empty($_SESSION['admin_csrf'])) { $_SESSION['admin_csrf'] = bin2hex(random_bytes(16)); }
function sanitize_str($s) { return trim(filter_var($s, FILTER_UNSAFE_RAW)); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!hash_equals($_SESSION['admin_csrf'], (string)$token)) {
    http_response_code(403);
    exit('Invalid session token.');
  }
  $action = isset($_POST['action']) ? sanitize_str($_POST['action']) : '';
  if ($action === 'create' || $action === 'update') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $park_id = isset($_POST['park_id']) ? (int)$_POST['park_id'] : 0;
    $rank = isset($_POST['rank_position']) ? (int)$_POST['rank_position'] : 1;
    $blurb = isset($_POST['blurb']) ? substr(sanitize_str($_POST['blurb']), 0, 400) : null;
    $cta_text = isset($_POST['cta_text']) ? substr(sanitize_str($_POST['cta_text']), 0, 80) : null;
    $cta_url = isset($_POST['cta_url']) ? substr(sanitize_str($_POST['cta_url']), 0, 255) : null;
    $start_at = isset($_POST['start_at']) ? sanitize_str($_POST['start_at']) : null;
    $end_at = isset($_POST['end_at']) ? sanitize_str($_POST['end_at']) : null;

    if ($park_id <= 0 || !isset($parkIdToName[$park_id])) {
      $message = 'Invalid park selected.';
    } else {
      // Prevent overlapping schedules for same rank
      $q = "SELECT id FROM omr_it_parks_featured WHERE rank_position=? AND id<>? AND (COALESCE(start_at, '1970-01-01') <= COALESCE(?, '9999-12-31')) AND (COALESCE(?, '1970-01-01') <= COALESCE(end_at, '9999-12-31')) LIMIT 1";
      $chk = $conn->prepare($q);
      $sid = $id ?: 0; $sa = $start_at ?: null; $ea = $end_at ?: null;
      $chk->bind_param('iiss', $rank, $sid, $ea, $sa);
      $chk->execute(); $rr = $chk->get_result();
      if ($rr && $rr->num_rows > 0) {
        $message = 'Overlap: Another featured slot with same rank overlaps this schedule.';
      }
      $chk->close();

      if ($action === 'create') {
        $stmt = $conn->prepare("INSERT INTO omr_it_parks_featured (park_id, rank_position, blurb, cta_text, cta_url, start_at, end_at) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('iisssss', $park_id, $rank, $blurb, $cta_text, $cta_url, $start_at, $end_at);
        $stmt->execute();
        $stmt->close();
        header('Location: featured.php?ok=1');
        exit;
      } else {
        if ($id > 0) {
          $stmt = $conn->prepare("UPDATE omr_it_parks_featured SET park_id=?, rank_position=?, blurb=?, cta_text=?, cta_url=?, start_at=?, end_at=? WHERE id=?");
          $stmt->bind_param('iisssssi', $park_id, $rank, $blurb, $cta_text, $cta_url, $start_at, $end_at, $id);
          $stmt->execute();
          $stmt->close();
          header('Location: featured.php?ok=1');
          exit;
        } else {
          $message = 'Invalid ID for update.';
        }
      }
    }
  } elseif ($action === 'delete') {
    requireRole(['super_admin']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
      $stmt = $conn->prepare("DELETE FROM omr_it_parks_featured WHERE id=?");
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->close();
      header('Location: featured.php?deleted=1');
      exit;
    } else {
      $message = 'Invalid ID for delete.';
    }
  }
}

// Load rows
$rows = [];
$res = $conn->query("SELECT id, park_id, rank_position, blurb, cta_text, cta_url, start_at, end_at, created_at FROM omr_it_parks_featured ORDER BY rank_position ASC, id DESC");
if ($res) { while ($r = $res->fetch_assoc()) { $rows[] = $r; } }

$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$editRow = null;
if ($editId > 0) {
  foreach ($rows as $r) { if ((int)$r['id'] === $editId) { $editRow = $r; break; } }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · Featured IT Parks</title>
  <?php if (file_exists(__DIR__ . '/../../components/head-resources.php')) include __DIR__ . '/../../components/head-resources.php'; ?>
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .maxw-1280 { max-width: 1280px; }
  </style>
</head>
<body>
  <?php if (file_exists($_SERVER['DOCUMENT_ROOT'].'/components/admin-breadcrumbs.php')) include $_SERVER['DOCUMENT_ROOT'].'/components/admin-breadcrumbs.php'; ?>

  <div class="container maxw-1280 py-3">
    <h1 style="color:#0583D2;">Featured IT Parks</h1>
    <?php if (!empty($_GET['ok'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>
    <?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Deleted.</div><?php endif; ?>
    <?php if (!empty($message)): ?><div class="alert alert-warning"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title"><?php echo $editRow ? 'Edit Featured Slot' : 'Add Featured Slot'; ?></h5>
        <form method="post" action="">
          <input type="hidden" name="action" value="<?php echo $editRow ? 'update' : 'create'; ?>">
          <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
          <?php if ($editRow): ?><input type="hidden" name="id" value="<?php echo (int)$editRow['id']; ?>"><?php endif; ?>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="park_id">IT Park</label>
              <select name="park_id" id="park_id" class="form-control" required>
                <option value="">Select a park</option>
                <?php foreach ($omr_it_parks_all as $p): ?>
                  <option value="<?php echo (int)$p['id']; ?>" <?php echo ($editRow && (int)$editRow['park_id']===(int)$p['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="rank_position">Rank</label>
              <input type="number" class="form-control" name="rank_position" id="rank_position" min="1" value="<?php echo htmlspecialchars($editRow['rank_position'] ?? '1', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="blurb">Blurb</label>
              <input type="text" class="form-control" name="blurb" id="blurb" maxlength="400" value="<?php echo htmlspecialchars($editRow['blurb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="cta_text">CTA Text</label>
              <input type="text" class="form-control" name="cta_text" id="cta_text" maxlength="80" value="<?php echo htmlspecialchars($editRow['cta_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-5">
              <label for="cta_url">CTA URL</label>
              <input type="url" class="form-control" name="cta_url" id="cta_url" maxlength="255" value="<?php echo htmlspecialchars($editRow['cta_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="start_at">Start</label>
              <input type="datetime-local" class="form-control" name="start_at" id="start_at" value="<?php echo htmlspecialchars(isset($editRow['start_at']) && $editRow['start_at'] ? date('Y-m-d\TH:i', strtotime($editRow['start_at'])) : '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="end_at">End</label>
              <input type="datetime-local" class="form-control" name="end_at" id="end_at" value="<?php echo htmlspecialchars(isset($editRow['end_at']) && $editRow['end_at'] ? date('Y-m-d\TH:i', strtotime($editRow['end_at'])) : '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <button type="submit" class="btn btn-primary"><?php echo $editRow ? 'Update' : 'Add Featured'; ?></button>
          <?php if ($editRow): ?><a href="featured.php" class="btn btn-secondary ml-2">Cancel</a><?php endif; ?>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Current Featured Slots</h5>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Rank</th>
                <th>Park</th>
                <th>Status</th>
                <th>Blurb</th>
                <th>CTA</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($rows)): ?>
                <tr><td colspan="9" class="text-center">No featured slots yet.</td></tr>
              <?php else: foreach ($rows as $r): ?>
                <tr>
                  <td><?php echo (int)$r['id']; ?></td>
                  <td><?php echo (int)$r['rank_position']; ?></td>
                  <td><?php echo htmlspecialchars($parkIdToName[(int)$r['park_id']] ?? ('#'.$r['park_id']), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <?php
                      $now = time();
                      $sa = !empty($r['start_at']) ? strtotime($r['start_at']) : null;
                      $ea = !empty($r['end_at']) ? strtotime($r['end_at']) : null;
                      $active = ($sa === null || $sa <= $now) && ($ea === null || $ea >= $now);
                      $badge = $active ? 'success' : (($ea !== null && $ea < $now) ? 'secondary' : 'warning');
                      $label = $active ? 'Active' : (($ea !== null && $ea < $now) ? 'Expired' : 'Scheduled');
                    ?>
                    <span class="badge badge-<?php echo $badge; ?>"><?php echo $label; ?></span>
                  </td>
                  <td><?php echo htmlspecialchars($r['blurb'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <?php if (!empty($r['cta_text'])): ?>
                      <a href="<?php echo htmlspecialchars($r['cta_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($r['cta_text'], ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($r['start_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($r['end_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary" href="featured.php?edit_id=<?php echo (int)$r['id']; ?>">Edit</a>
                    <?php $slugBase = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/','-', $parkIdToName[(int)$r['park_id']] ?? ''), '-')); ?>
                    <a class="btn btn-sm btn-outline-secondary" href="/it-parks/<?php echo htmlspecialchars($slugBase.'-'.(int)$r['park_id'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Preview</a>
                    <form method="post" action="" style="display:inline-block" onsubmit="return confirm('Delete this featured slot?');">
                      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
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
      </div>
    </div>
  </div>

</body>
</html>


