<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once '../core/omr-connect.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $del = $conn->prepare('DELETE FROM omr_it_companies_featured WHERE id = ?');
    $del->bind_param('i', $id);
    $del->execute();
    $del->close();
    $_SESSION['flash_success'] = 'Featured entry deleted';
    $_SESSION['ga_event'] = [
        'name' => 'admin_feature_delete',
        'featured_id' => (int)$id
    ];
    header('Location: featured-it-list.php');
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $rank = max(1, (int)($_POST['rank'] ?? 1));
    $blurb = trim($_POST['blurb'] ?? '');
    $cta_text = trim($_POST['cta_text'] ?? 'Learn more');
    $cta_url = trim($_POST['cta_url'] ?? '');
    $start_at = trim($_POST['start_at'] ?? '');
    $end_at = trim($_POST['end_at'] ?? '');

    $upd = $conn->prepare('UPDATE omr_it_companies_featured SET rank_position=?, blurb=?, cta_text=?, cta_url=?, start_at=?, end_at=? WHERE id=?');
    $upd->bind_param('isssssi', $rank, $blurb, $cta_text, $cta_url, $start_at, $end_at, $id);
    $upd->execute();
    $upd->close();
    $_SESSION['flash_success'] = 'Featured entry updated';
    $_SESSION['ga_event'] = [
        'name' => 'admin_feature_update',
        'featured_id' => (int)$id,
        'rank' => (int)$rank
    ];
    header('Location: featured-it-list.php');
    exit;
}

$sql = "SELECT f.id, f.rank_position, f.blurb, f.cta_text, f.cta_url, f.start_at, f.end_at, c.slno, c.company_name
        FROM omr_it_companies_featured f
        JOIN omr_it_companies c ON c.slno = f.company_slno
        ORDER BY f.rank_position ASC, f.start_at DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Featured IT Companies - MyOMR</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background:#f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <?php if (!empty($_SESSION['ga_event'])): $ev = $_SESSION['ga_event']; unset($_SESSION['ga_event']); ?>
      <script>
        (function(){
          if (typeof window.gtag !== 'function') return;
          var ev = <?php echo json_encode($ev, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); ?>;
          var name = ev.name || 'admin_action';
          var params = Object.assign({ event_category: 'admin_it' }, ev);
          delete params.name;
          window.gtag('event', name, params);
        })();
      </script>
      <?php endif; ?>
      <h2 class="mb-4">Featured IT Companies</h2>
      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Company</th>
              <th>Rank</th>
              <th>Blurb</th>
              <th>CTA Text</th>
              <th>CTA URL</th>
              <th>Start</th>
              <th>End</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($res && $res->num_rows > 0): ?>
              <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                  <td><?php echo (int)$row['id']; ?></td>
                  <td>
                    <?php echo htmlspecialchars($row['company_name']); ?> (<?php echo (int)$row['slno']; ?>)
                    <div><a class="btn btn-xs btn-link p-0" href="it-companies-edit.php?slno=<?php echo (int)$row['slno']; ?>">Edit profile</a></div>
                  </td>
                  <td>
                    <form method="post" class="form-inline">
                      <input type="hidden" name="update_id" value="<?php echo (int)$row['id']; ?>" />
                      <input type="number" name="rank" class="form-control form-control-sm" style="width:80px" value="<?php echo (int)$row['rank_position']; ?>" />
                  </td>
                  <td><input type="text" name="blurb" class="form-control form-control-sm" style="width:240px" value="<?php echo htmlspecialchars($row['blurb']); ?>" /></td>
                  <td><input type="text" name="cta_text" class="form-control form-control-sm" style="width:140px" value="<?php echo htmlspecialchars($row['cta_text']); ?>" /></td>
                  <td><input type="url" name="cta_url" class="form-control form-control-sm" style="width:260px" value="<?php echo htmlspecialchars($row['cta_url']); ?>" /></td>
                  <td><input type="datetime-local" name="start_at" class="form-control form-control-sm" style="width:210px" value="<?php echo $row['start_at'] ? date('Y-m-d\TH:i', strtotime($row['start_at'])) : ''; ?>" /></td>
                  <td><input type="datetime-local" name="end_at" class="form-control form-control-sm" style="width:210px" value="<?php echo $row['end_at'] ? date('Y-m-d\TH:i', strtotime($row['end_at'])) : ''; ?>" /></td>
                  <td>
                      <button type="submit" class="btn btn-sm btn-success">Save</button>
                      <a href="featured-it-list.php?delete=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this featured entry?');">Delete</a>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="9" class="text-center">No featured entries</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


