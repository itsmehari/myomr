<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once '../core/omr-connect.php';
$title = 'Add News Bulletin';
$breadcrumbs = ['News Bulletin' => 'news-list.php', 'Add News' => null];
$error = '';
$success = '';
$title_val = $summary_val = $date_val = $tags_val = $image_val = $article_url_val = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_val = trim($_POST['title'] ?? '');
    $summary_val = trim($_POST['summary'] ?? '');
    $date_val = $_POST['date'] ?? date('Y-m-d');
    $tags_val = trim($_POST['tags'] ?? '');
    $image_val = trim($_POST['image'] ?? '');
    $article_url_val = trim($_POST['article_url'] ?? '');
    if ($title_val && $summary_val && $date_val && $image_val && $article_url_val) {
        $stmt = $conn->prepare('INSERT INTO news_bulletin (title, summary, date, tags, image, article_url) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $title_val, $summary_val, $date_val, $tags_val, $image_val, $article_url_val);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'News item added successfully!';
            header('Location: news-list.php');
            exit;
        } else {
            $error = 'Error adding news item.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News Bulletin - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .sidebar { min-height: 100vh; background: #14532d; color: #fff; }
        .sidebar a { color: #fff; display: block; padding: 1rem; border-bottom: 1px solid #1e6b3a; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: #22c55e; color: #14532d; font-weight: bold; }
        .main-content { padding: 2rem; }
    </style>
    <script>
      window.onerror = function(msg, url, line, col, error) {
        alert('Error: ' + msg + '\n' + url + ':' + line + ':' + col);
        return false;
      };
    </script>
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <h2 class="mb-4">Add News Bulletin</h2>
      <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
      <form method="POST" autocomplete="off" aria-label="Add News Bulletin">
        <div class="form-group">
          <label for="title">Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="summary">Summary <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary" rows="3" required aria-required="true"><?php echo htmlspecialchars($summary_val); ?></textarea>
        </div>
        <div class="form-group">
          <label for="date">Date <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date_val ?: date('Y-m-d')); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="tags">Tags (comma separated)</label>
          <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($tags_val); ?>">
        </div>
        <div class="form-group">
          <label for="image">Image URL <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($image_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="article_url">Article URL <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="article_url" name="article_url" value="<?php echo htmlspecialchars($article_url_val); ?>" required aria-required="true">
        </div>
        <button type="submit" class="btn btn-success">Add News</button>
        <a href="news-list.php" class="btn btn-secondary ml-2">Back to List</a>
      </form>
    </main>
  </div>
</div>
</body>
</html> 