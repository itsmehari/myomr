<?php
require_once __DIR__ . '/../_bootstrap.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';

$title = 'News Articles';
$breadcrumbs = ['Dashboard' => '/admin/', 'Articles' => null];

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    // Fetch image_path before delete for file cleanup
    $rowStmt = $conn->prepare('SELECT image_path FROM articles WHERE id = ?');
    $rowStmt->bind_param('i', $id);
    $rowStmt->execute();
    $row = $rowStmt->get_result()->fetch_assoc();
    $rowStmt->close();

    $stmt = $conn->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        // Delete uploaded image file if it lives in our upload dir
        if ($row && !empty($row['image_path']) && strpos($row['image_path'], '/local-news/omr-news-images/') === 0 && $row['image_path'] !== '/My-OMR-Logo.png') {
            $diskPath = dirname(__DIR__, 2) . $row['image_path'];
            if (is_file($diskPath)) {
                @unlink($diskPath);
            }
        }
        $_SESSION['flash_success'] = 'Article deleted.';
    } else {
        $_SESSION['flash_error'] = 'Error deleting article.';
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

$status = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$where = "1=1";
$params = [];
$types = "";

if ($status === 'published' || $status === 'draft') {
    $where .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}
if ($search !== '') {
    $where .= " AND (title LIKE ? OR slug LIKE ? OR summary LIKE ?)";
    $term = '%' . $search . '%';
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $types .= "sss";
}

$countSql = "SELECT COUNT(*) FROM articles WHERE $where";
if (count($params) > 0) {
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total = (int) $stmt->get_result()->fetch_row()[0];
    $stmt->close();
} else {
    $total = (int) $conn->query($countSql)->fetch_row()[0];
}

$order = "ORDER BY published_date DESC, id DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;
$types .= "ii";
$listSql = "SELECT id, title, slug, summary, published_date, image_path, status, category FROM articles WHERE $where $order";
$stmt = $conn->prepare($listSql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

$totalPages = (int) ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Articles - MyOMR Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>body{background:#f4f6f8}.main-content{padding:2rem}</style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/../admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include __DIR__ . '/../admin-header.php'; ?>
      <?php include __DIR__ . '/../admin-flash.php'; ?>
<div class="admin-section">
  <div class="admin-section-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h2 class="mb-0">News Articles</h2>
    <a href="add.php" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Add Article</a>
  </div>

  <form method="get" class="d-flex flex-wrap gap-2 align-items-center mb-4">
    <input type="text" name="q" class="form-control" style="max-width:220px" placeholder="Search title/slug..." value="<?php echo htmlspecialchars($search); ?>">
    <select name="status" class="form-control" style="max-width:140px">
      <option value="">All</option>
      <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
      <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
    </select>
    <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
  </form>

  <div class="table-responsive">
    <table class="table table-hover bg-white rounded">
      <thead class="table-light">
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Slug</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="6" class="text-center py-4 text-muted">No articles found.</td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
            <tr>
              <td>
                <?php $img = $a['image_path'] ?: '/My-OMR-Logo.png'; ?>
                <img src="<?php echo htmlspecialchars($img); ?>" alt="" style="width:56px;height:42px;object-fit:cover;border-radius:4px" onerror="this.src='/My-OMR-Logo.png'">
              </td>
              <td><?php echo htmlspecialchars($a['title']); ?></td>
              <td><code class="small"><?php echo htmlspecialchars($a['slug']); ?></code></td>
              <td><?php echo htmlspecialchars($a['published_date'] ?? '-'); ?></td>
              <td><span class="badge <?php echo $a['status'] === 'published' ? 'bg-success' : 'bg-secondary'; ?>"><?php echo htmlspecialchars($a['status']); ?></span></td>
              <td>
                <a href="/local-news/<?php echo htmlspecialchars($a['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-external-link-alt"></i></a>
                <a href="edit.php?id=<?php echo (int)$a['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#del<?php echo (int)$a['id']; ?>" title="Delete"><i class="fas fa-trash"></i></button>
                <div class="modal fade" id="del<?php echo (int)$a['id']; ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header"><h5 class="modal-title">Delete article?</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                      <div class="modal-body">Are you sure? This cannot be undone.</div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="?delete=<?php echo (int)$a['id']; ?>" class="btn btn-danger">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
  <nav class="mt-3">
    <ul class="pagination mb-0">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
          <a class="page-link" href="?p=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>&q=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
