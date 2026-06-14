<?php
require_once __DIR__ . '/../_bootstrap.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/security-helpers.php';

$title = 'News Articles';
$breadcrumbs = ['Dashboard' => '/superadmin/index.php', 'Articles' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: index.php');
        exit;
    }
    $id = (int) ($_POST['id'] ?? 0);
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

$pageTitle = $pageTitle ?? $title ?? 'News Articles';
include __DIR__ . '/../includes/admin-shell-open.php';
?>
<div class="admin-section">
  <div class="admin-section-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h2 class="mb-0">News Articles</h2>
    <div>
      <a href="/superadmin/news-publisher.php" class="btn btn-success me-2"><i class="fas fa-database me-1"></i>Token-Free Publisher</a>
      <a href="add.php" class="btn btn-outline-success"><i class="fas fa-plus me-1"></i>Add Article</a>
    </div>
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
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#del<?php echo (int)$a['id']; ?>" title="Delete"><i class="fas fa-trash"></i></button>
                <div class="modal fade" id="del<?php echo (int)$a['id']; ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header"><h5 class="modal-title">Delete article?</h5><button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button></div>
                      <div class="modal-body">Are you sure? This cannot be undone.</div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="post" class="m-0">
                          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
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
<?php include __DIR__ . '/../includes/admin-shell-close.php'; ?>
