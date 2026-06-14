<?php
require_once __DIR__ . '/../_bootstrap.php';
require_once __DIR__ . '/../../core/omr-connect.php';

$title = 'Add Article';
$breadcrumbs = ['Dashboard' => '/superadmin/index.php', 'Articles' => 'index.php', 'Add' => null];

$error = '';
$vals = [
    'title' => '', 'slug' => '', 'summary' => '', 'content' => '', 'category' => 'Local News',
    'author' => 'MyOMR Editorial Team', 'status' => 'published', 'published_date' => date('Y-m-d'),
    'image_path' => '/My-OMR-Logo.png',     'tags' => '', 'image_mode' => 'url', 'is_featured' => '0'
];

$uploadDir = dirname(__DIR__, 2) . '/local-news/omr-news-images/';
$webBase = '/local-news/omr-news-images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['title', 'slug', 'summary', 'content', 'category', 'author', 'status', 'published_date', 'tags'] as $k) {
        $vals[$k] = trim($_POST[$k] ?? ($k === 'content' ? '' : ''));
    }
    $vals['image_mode'] = $_POST['image_mode'] ?? 'url';
    $vals['is_featured'] = isset($_POST['is_featured']) && $_POST['is_featured'] === '1' ? '1' : '0';
    $imagePath = '';

    if ($vals['image_mode'] === 'url') {
        $imagePath = trim($_POST['image_url'] ?? '');
        if (!$imagePath) {
            $error = 'Image URL or upload is required.';
        } elseif (strpos($imagePath, '..') !== false || (preg_match('#^[^/]#', $imagePath) && strpos($imagePath, 'http') !== 0)) {
            $error = 'Invalid image path. Use a full URL (https://...) or a path starting with /.';
        }
    } else {
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['image_file'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $error = 'Only JPG, PNG, GIF, WebP allowed.';
            } elseif ($f['size'] > 2 * 1024 * 1024) {
                $error = 'Image max 2MB.';
            } else {
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                    if (is_dir($uploadDir) && !is_file($uploadDir . '.htaccess')) {
                        @file_put_contents($uploadDir . '.htaccess', "# Prevent PHP execution\n<FilesMatch \"\\.php$\">\n    Require all denied\n</FilesMatch>\nOptions -Indexes\n");
                    }
                }
                $fname = preg_replace('/[^a-z0-9\-\.]/', '-', strtolower(pathinfo($f['name'], PATHINFO_FILENAME))) . '-' . time() . '.' . $ext;
                if (move_uploaded_file($f['tmp_name'], $uploadDir . $fname)) {
                    $imagePath = $webBase . $fname;
                } else {
                    $error = 'Upload failed.';
                }
            }
        } else {
            $error = 'Please select an image file to upload.';
        }
    }

    if (!$error && $vals['title'] && $vals['slug'] && $vals['summary'] && $vals['content'] && $imagePath) {
        $stmt = $conn->prepare('INSERT INTO articles (title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $feat = (int) $vals['is_featured'];
        $stmt->bind_param('ssssssssssi', $vals['title'], $vals['slug'], $vals['summary'], $vals['content'], $vals['category'], $vals['author'], $vals['status'], $vals['published_date'], $imagePath, $vals['tags'], $feat);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'Article added.';
            header('Location: index.php');
            exit;
        }
        $error = 'Database error: ' . $stmt->error;
        $stmt->close();
    } elseif (!$error) {
        $error = 'Fill required fields: title, slug, summary, content, image.';
    }
}

$pageTitle = $pageTitle ?? $title ?? 'Add Article';
include __DIR__ . '/../includes/admin-shell-open.php';
?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($vals['title']); ?>" required>
        </div>
        <div class="form-group">
          <label>Slug (URL) <span class="text-danger">*</span></label>
          <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($vals['slug']); ?>" placeholder="article-slug-here" required>
        </div>
        <div class="form-group">
          <label>Summary <span class="text-danger">*</span></label>
          <textarea name="summary" class="form-control" rows="3" required><?php echo htmlspecialchars($vals['summary']); ?></textarea>
        </div>
        <div class="form-group">
          <label>Content (HTML) <span class="text-danger">*</span></label>
          <textarea name="content" class="form-control" rows="12" required><?php echo htmlspecialchars($vals['content']); ?></textarea>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Category</label>
              <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($vals['category']); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Author</label>
              <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($vals['author']); ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="published" <?php echo $vals['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                <option value="draft" <?php echo $vals['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Published Date</label>
              <input type="date" name="published_date" class="form-control" value="<?php echo htmlspecialchars($vals['published_date']); ?>">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo $vals['is_featured'] === '1' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_featured">Feature on homepage</label>
          </div>
        </div>
        <div class="form-group">
          <label>Tags (comma-separated)</label>
          <input type="text" name="tags" class="form-control" value="<?php echo htmlspecialchars($vals['tags']); ?>">
        </div>

        <div class="form-group">
          <label>Image</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="image_mode" id="imUrl" value="url" <?php echo $vals['image_mode'] === 'url' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="imUrl">Enter URL</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="image_mode" id="imUpload" value="upload" <?php echo $vals['image_mode'] === 'upload' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="imUpload">Upload file</label>
          </div>
        </div>
        <div id="imgUrlBox" class="form-group">
          <label>Image URL or path</label>
          <input type="text" name="image_url" id="image_url" class="form-control" value="<?php echo htmlspecialchars($vals['image_path']); ?>" placeholder="/local-news/omr-news-images/your-image.jpg">
        </div>
        <div id="imgUploadBox" class="form-group" style="display:none">
          <label>Upload image (JPG, PNG, GIF, WebP, max 2MB)</label>
          <input type="file" name="image_file" id="image_file" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Save Article</button>
        <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
      </form>
<?php include __DIR__ . '/../includes/admin-shell-close.php'; ?>
