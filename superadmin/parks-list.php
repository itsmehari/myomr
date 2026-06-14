<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';

$title = 'Manage Parks';
$breadcrumbs = ['Parks' => null];

$per_page = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where = ' WHERE 1=1 ';
if ($search !== '') {
    $esc = '%' . $conn->real_escape_string($search) . '%';
    $where .= " AND (parkname LIKE '{$esc}' OR location LIKE '{$esc}') ";
}

$count_sql = 'SELECT COUNT(*) FROM omrparkslist' . $where;
$count_res = $conn->query($count_sql);
$total = $count_res ? (int)$count_res->fetch_row()[0] : 0;
$pages = max(1, (int)ceil($total / $per_page));

$sql = 'SELECT slno, parkname, location FROM omrparkslist' . $where . ' ORDER BY parkname ASC LIMIT ' . $per_page . ' OFFSET ' . $offset;
$res = $conn->query($sql);

$pageTitle = $pageTitle ?? $title ?? 'Manage Parks';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<form method="get" class="mb-3">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="Search by name or location" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if ($search !== ''): ?><a href="parks-list.php" class="btn btn-secondary ms-2">Clear</a><?php endif; ?>
          </div>
        </div>
      </form>

      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($res && $res->num_rows > 0): ?>
              <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                  <td><?php echo (int)$row['slno']; ?></td>
                  <td><?php echo htmlspecialchars($row['parkname']); ?></td>
                  <td><?php echo htmlspecialchars($row['location']); ?></td>
                  <td>
                    <?php
                      $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $row['parkname']));
                      $slugBase = trim($slugBase, '-');
                      $pretty = '/parks/' . $slugBase . '-' . (int)$row['slno'];
                    ?>
                    <a class="btn btn-sm btn-outline-primary" href="parks-edit.php?slno=<?php echo (int)$row['slno']; ?>">Edit</a>
                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo htmlspecialchars($pretty, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center">No parks found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
        <?php if ($pages > 1): ?>
        <nav aria-label="Pagination"><ul class="pagination">
          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="parks-list.php?page=<?php echo $page - 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
          </li>
          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
              <a class="page-link" href="parks-list.php?page=<?php echo $i; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo $page >= $pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="parks-list.php?page=<?php echo $page + 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Next</a>
          </li>
        </ul></nav>
        <?php endif; ?>
      </div>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
