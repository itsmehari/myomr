<?php
/**
 * Easy Article Adder
 * Upload this to: public_html/ADD-NEW-ARTICLE.php
 * Access via: https://myomr.in/ADD-NEW-ARTICLE.php
 * 
 * SECURITY: Password protect this file with .htaccess!
 */

require_once 'core/omr-connect.php';

// Simple security check (change this password!)
$ADMIN_PASSWORD = 'CHANGE_THIS_PASSWORD_123';

$show_form = true;
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_password = $_POST['password'] ?? '';
    
    if ($entered_password !== $ADMIN_PASSWORD) {
        $message = '<div style="color:red;">❌ Wrong password!</div>';
    } else {
        // Extract form data
        $title = $conn->real_escape_string($_POST['title']);
        $slug = $conn->real_escape_string($_POST['slug']);
        $summary = $conn->real_escape_string($_POST['summary']);
        $content = $_POST['content']; // Don't escape HTML
        $category = $conn->real_escape_string($_POST['category']);
        $author = $conn->real_escape_string($_POST['author']);
        $status = $conn->real_escape_string($_POST['status']);
        $published_date = $conn->real_escape_string($_POST['published_date']);
        $image_path = $conn->real_escape_string($_POST['image_path']);
        $tags = $conn->real_escape_string($_POST['tags']);
        
        // Insert article
        $sql = "INSERT INTO articles (title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at) 
                VALUES ('$title', '$slug', '$summary', '$content', '$category', '$author', '$status', '$published_date', '$image_path', '$tags', 0, NOW(), NOW())";
        
        if ($conn->query($sql)) {
            $message = '<div style="color:green; font-weight:bold;">✅ Article added successfully! ID: ' . $conn->insert_id . '</div>';
            $show_form = false;
            
            // Show sitemap update reminder
            $message .= '<div style="margin-top:20px; padding:15px; background:#e7f5e7; border-radius:5px;"><strong>Next Steps:</strong><br>';
            $message .= '1. Run sitemap generator: <a href="sitemap-generator.php" target="_blank">Generate Sitemap</a><br>';
            $message .= '2. Submit to Google Search Console<br>';
            $message .= '3. View article: <a href="/local-news/' . htmlspecialchars($slug) . '">View Article</a></div>';
        } else {
            $message = '<div style="color:red;">❌ Error: ' . $conn->error . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Article - MyOMR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="date"], select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { min-height: 150px; font-family: monospace; }
        button { background: #008552; color: white; padding: 10px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #006642; }
        .password-box { background: #fff3cd; padding: 15px; border: 2px solid #ffc107; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>📰 Add New Article to MyOMR</h1>
    
    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>
    
    <?php if ($show_form): ?>
    <form method="POST">
        <div class="password-box">
            <label>🔐 Password:</label>
            <input type="password" name="password" required style="width: 300px;">
        </div>
        
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required placeholder="e.g., New OMR Road Development Planned for 2026">
        </div>
        
        <div class="form-group">
            <label>Slug (URL-friendly) *</label>
            <input type="text" name="slug" required placeholder="e.g., new-omr-road-development-2026">
            <small>Auto-generate from title (lowercase, hyphens instead of spaces)</small>
        </div>
        
        <div class="form-group">
            <label>Summary (for homepage cards) *</label>
            <textarea name="summary" required placeholder="Brief 2-3 sentence summary for homepage display"></textarea>
        </div>
        
        <div class="form-group">
            <label>Content (HTML) *</label>
            <textarea name="content" required style="min-height: 400px;" placeholder="<p>Full article content here...</p>"></textarea>
            <small>Use HTML tags. Single quotes will cause issues - use &apos; instead.</small>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="e.g., Local News">
        </div>
        
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="MyOMR Editorial Team">
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="published" selected>Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Published Date</label>
            <input type="date" name="published_date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label>Image Path</label>
            <input type="text" name="image_path" placeholder="/local-news/omr-news-images/article.jpg">
        </div>
        
        <div class="form-group">
            <label>Tags (comma-separated)</label>
            <input type="text" name="tags" placeholder="e.g., OMR news, Chennai development, IT corridor">
        </div>
        
        <button type="submit">Add Article</button>
    </form>
    <?php endif; ?>
</body>
</html>

