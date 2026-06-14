<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
require_once '../core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: featured-it-list.php');
        exit;
    }

    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
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

    if ($action !== 'update') {
        $_SESSION['flash_error'] = 'Invalid action.';
        header('Location: featured-it-list.php');
        exit;
    }

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

$pageTitle = $pageTitle ?? $title ?? 'Admin';
include __DIR__ . '/includes/admin-shell-open.php';
?>
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
      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
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
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                      <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>" />
                      <input type="number" name="rank" class="form-control form-control-sm" style="width:80px" value="<?php echo (int)$row['rank_position']; ?>" />
                  </td>
                  <td><input type="text" name="blurb" class="form-control form-control-sm" style="width:240px" value="<?php echo htmlspecialchars($row['blurb']); ?>" /></td>
                  <td><input type="text" name="cta_text" class="form-control form-control-sm" style="width:140px" value="<?php echo htmlspecialchars($row['cta_text']); ?>" /></td>
                  <td><input type="url" name="cta_url" class="form-control form-control-sm" style="width:260px" value="<?php echo htmlspecialchars($row['cta_url']); ?>" /></td>
                  <td><input type="datetime-local" name="start_at" class="form-control form-control-sm" style="width:210px" value="<?php echo $row['start_at'] ? date('Y-m-d\TH:i', strtotime($row['start_at'])) : ''; ?>" /></td>
                  <td><input type="datetime-local" name="end_at" class="form-control form-control-sm" style="width:210px" value="<?php echo $row['end_at'] ? date('Y-m-d\TH:i', strtotime($row['end_at'])) : ''; ?>" /></td>
                  <td>
                      <button type="submit" name="action" value="update" class="btn btn-sm btn-success">Save</button>
                      <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete this featured entry?');">Delete</button>
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
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
