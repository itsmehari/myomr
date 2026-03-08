<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once '../core/omr-connect.php';

$title = 'Manage Schools';
$breadcrumbs = ['Schools' => null];

$per_page = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where = ' WHERE 1=1 ';
if ($search !== '') {
    $esc = '%' . $conn->real_escape_string($search) . '%';
    $where .= " AND (schoolname LIKE '{$esc}' OR address LIKE '{$esc}' OR locality LIKE '{$esc}') ";
}

$count_sql = 'SELECT COUNT(*) FROM omrschoolslist' . $where;
$count_res = $conn->query($count_sql);
$total = $count_res ? (int)$count_res->fetch_row()[0] : 0;
$pages = max(1, (int)ceil($total / $per_page));

$sql = 'SELECT slno, schoolname, address, locality, verified FROM omrschoolslist' . $where . ' ORDER BY schoolname ASC LIMIT ' . $per_page . ' OFFSET ' . $offset;
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Schools - MyOMR</title>
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
      <h2 class="mb-4">Schools</h2>
      <form method="get" class="mb-3">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="Search by name, locality, or address" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if ($search !== ''): ?><a href="schools-list.php" class="btn btn-secondary ml-2">Clear</a><?php endif; ?>
          </div>
        </div>
      </form>

      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Locality</th>
              <th>Verified</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($res && $res->num_rows > 0): ?>
              <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                  <td><?php echo (int)$row['slno']; ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($row['schoolname']); ?></strong><br>
                    <small class="text-muted"><?php echo htmlspecialchars($row['address']); ?></small>
                  </td>
                  <td><?php echo htmlspecialchars($row['locality'] ?? ''); ?></td>
                  <td><?php echo ((int)$row['verified'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'); ?></td>
                  <td>
                    <a class="btn btn-sm btn-info" href="schools-edit.php?slno=<?php echo (int)$row['slno']; ?>">Edit</a>
                    <?php
                      $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $row['schoolname']));
                      $slugBase = trim($slugBase, '-');
                      $pretty = '/schools/' . $slugBase . '-' . (int)$row['slno'];
                    ?>
                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo htmlspecialchars($pretty, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center">No schools found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
        <?php if ($pages > 1): ?>
        <nav aria-label="Pagination"><ul class="pagination">
          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="schools-list.php?page=<?php echo $page - 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
          </li>
          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
              <a class="page-link" href="schools-list.php?page=<?php echo $i; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo $page >= $pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="schools-list.php?page=<?php echo $page + 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Next</a>
          </li>
        </ul></nav>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


