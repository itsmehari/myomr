<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('BUY_SELL_ADMIN_ROUTED', '/superadmin/buy-sell/view-reports-omr.php');
require_once __DIR__ . '/_urls.php';
/**
 * OMR Buy-Sell Admin — View Reports
 */
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/security-helpers.php';
require_once __DIR__ . '/../includes/listing-functions.php';

$statusFilter = $_GET['status'] ?? '';
if (isset($_GET['report_action'], $_GET['report_id'])) {
    $reportAction = $_GET['report_action'];
    $reportId = (int)$_GET['report_id'];
    $allowedReportActions = ['reviewed', 'dismiss', 'reject_listing', 'delete_listing'];
    if ($reportId > 0 && in_array($reportAction, $allowedReportActions, true)) {
        if ($reportAction === 'reviewed' || $reportAction === 'dismiss') {
            $newStatus = $reportAction === 'reviewed' ? 'reviewed' : 'dismissed';
            $stmt = $conn->prepare('UPDATE omr_buy_sell_reports SET status = ? WHERE id = ?');
            $stmt->bind_param('si', $newStatus, $reportId);
            $stmt->execute();
            $stmt->close();
        } else {
            $listingStmt = $conn->prepare('SELECT listing_id FROM omr_buy_sell_reports WHERE id = ?');
            $listingStmt->bind_param('i', $reportId);
            $listingStmt->execute();
            $listingRow = $listingStmt->get_result()->fetch_assoc();
            $listingStmt->close();
            $listingId = (int)($listingRow['listing_id'] ?? 0);
            if ($listingId > 0) {
                if ($reportAction === 'reject_listing') {
                    $upd = $conn->prepare("UPDATE omr_buy_sell_listings SET status = 'rejected' WHERE id = ?");
                    $upd->bind_param('i', $listingId);
                    $upd->execute();
                    $upd->close();
                } elseif ($reportAction === 'delete_listing') {
                    $del = $conn->prepare('DELETE FROM omr_buy_sell_listings WHERE id = ?');
                    $del->bind_param('i', $listingId);
                    $del->execute();
                    $del->close();
                }
                $mark = $conn->prepare("UPDATE omr_buy_sell_reports SET status = 'reviewed' WHERE id = ?");
                $mark->bind_param('i', $reportId);
                $mark->execute();
                $mark->close();
            }
        }
    }
    $redirect = BUY_SELL_ADMIN_REPORTS_URL;
    if ($statusFilter !== '') {
        $redirect .= '?status=' . urlencode($statusFilter);
    }
    header('Location: ' . $redirect);
    exit;
}

$reports = [];
$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_reports'");
if ($check && $check->num_rows > 0) {
    $where = '1=1';
    if ($statusFilter !== '' && in_array($statusFilter, ['pending', 'reviewed', 'dismissed'], true)) {
        $where = "r.status = '" . $conn->real_escape_string($statusFilter) . "'";
    }
    $sql = "SELECT r.*, l.title as listing_title, l.status as listing_status
            FROM omr_buy_sell_reports r
            LEFT JOIN omr_buy_sell_listings l ON r.listing_id = l.id
            WHERE {$where}
            ORDER BY r.created_at DESC LIMIT 200";
    $r = $conn->query($sql);
    if ($r) {
        $reports = $r->fetch_all(MYSQLI_ASSOC);
    }
}

$__modulePageTitle = 'View Reports | Buy & Sell Admin';
$__moduleActiveNav = '/superadmin/buy-sell/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>View Reports | Buy & Sell Admin</title></head>
<body>
<?php } ?>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4"><i class="fas fa-flag me-2"></i>Listing Reports</h1>
  <div class="mb-3">
    <a href="<?= htmlspecialchars(BUY_SELL_ADMIN_REPORTS_URL) ?>" class="btn btn-sm btn-outline-secondary">All</a>
    <a href="?status=pending" class="btn btn-sm btn-warning">Pending</a>
    <a href="?status=reviewed" class="btn btn-sm btn-success">Reviewed</a>
    <a href="?status=dismissed" class="btn btn-sm btn-secondary">Dismissed</a>
  </div>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Listing</th>
          <th>Reason</th>
          <th>Notes</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($reports as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td>
          <?php if ($r['listing_id'] && $r['listing_title']): ?>
          <a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$r['listing_id'], $r['listing_title'])) ?>" target="_blank">
            <?= htmlspecialchars(mb_strimwidth($r['listing_title'], 0, 40, '…')) ?>
          </a>
          <br><small class="text-muted">#<?= (int)$r['listing_id'] ?> · <?= htmlspecialchars($r['listing_status'] ?? '') ?></small>
          <?php else: ?>
          #<?= (int)$r['listing_id'] ?> (deleted)
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($r['reason']) ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($r['notes'] ?? '', 0, 60, '…')) ?></td>
        <td><span class="badge bg-<?= $r['status'] === 'pending' ? 'warning' : ($r['status'] === 'reviewed' ? 'success' : 'secondary') ?>"><?= htmlspecialchars($r['status']) ?></span></td>
        <td><?= date('M j, Y H:i', strtotime($r['created_at'])) ?></td>
        <td class="text-nowrap">
          <?php if ($r['status'] === 'pending'): ?>
          <a href="?report_action=reviewed&amp;report_id=<?= (int)$r['id'] ?><?= $statusFilter !== '' ? '&amp;status=' . urlencode($statusFilter) : '' ?>" class="btn btn-sm btn-success">Reviewed</a>
          <a href="?report_action=dismiss&amp;report_id=<?= (int)$r['id'] ?><?= $statusFilter !== '' ? '&amp;status=' . urlencode($statusFilter) : '' ?>" class="btn btn-sm btn-outline-secondary">Dismiss</a>
          <?php endif; ?>
          <?php if (!empty($r['listing_id'])): ?>
          <a href="<?= htmlspecialchars(BUY_SELL_ADMIN_MANAGE_URL) ?>" class="btn btn-sm btn-outline-primary">Listings</a>
          <?php if (($r['listing_status'] ?? '') === 'pending'): ?>
          <a href="?report_action=reject_listing&amp;report_id=<?= (int)$r['id'] ?><?= $statusFilter !== '' ? '&amp;status=' . urlencode($statusFilter) : '' ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject reported listing?');">Reject listing</a>
          <?php endif; ?>
          <a href="?report_action=delete_listing&amp;report_id=<?= (int)$r['id'] ?><?= $statusFilter !== '' ? '&amp;status=' . urlencode($statusFilter) : '' ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete reported listing?');">Delete listing</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($reports)): ?>
  <p class="text-muted">No reports yet.</p>
  <?php endif; ?>
  <a href="<?= htmlspecialchars(BUY_SELL_ADMIN_MANAGE_URL) ?>" class="btn btn-outline-secondary me-2">Manage Listings</a>
  <a href="<?= htmlspecialchars(BUY_SELL_ADMIN_INDEX_URL) ?>" class="btn btn-outline-secondary">Back</a>
</main>

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
