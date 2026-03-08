<?php
/**
 * Create Missing Tables on Remote Database
 * Access via: https://myomr.in/create-tables-remote.php
 * 
 * SECURITY: Delete this file after running!
 */

// Simple password protection
$password = $_GET['key'] ?? '';

if ($password !== 'myomr2024') {
    die('Unauthorized. Add ?key=myomr2024 to URL');
}

require_once 'core/omr-connect.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Missing Tables</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        button { background: #008552; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔧 Creating Missing Tables</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_tables'])) {
    
    // ==========================================
    // 1. Create businesses table
    // ==========================================
    echo "<h2>Creating businesses table...</h2>";
    
    $create_businesses = "CREATE TABLE IF NOT EXISTS `businesses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `category` varchar(100) NOT NULL,
      `description` text DEFAULT NULL,
      `contact_url` varchar(255) DEFAULT NULL,
      `featured` tinyint(1) NOT NULL DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_featured` (`featured`),
      KEY `idx_category` (`category`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($create_businesses)) {
        echo "<div class='success'>✅ businesses table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating businesses table: " . $conn->error . "</div>";
    }
    
    // ==========================================
    // 2. Create gallery table
    // ==========================================
    echo "<h2>Creating gallery table...</h2>";
    
    $create_gallery = "CREATE TABLE IF NOT EXISTS `gallery` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `image_url` varchar(500) NOT NULL,
      `caption` text DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_created` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($create_gallery)) {
        echo "<div class='success'>✅ gallery table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating gallery table: " . $conn->error . "</div>";
    }
    
    // ==========================================
    // 3. Verify tables
    // ==========================================
    echo "<h2>Verifying tables...</h2>";
    
    $tables = $conn->query("SHOW TABLES LIKE 'businesses'");
    if ($tables && $tables->num_rows > 0) {
        echo "<div class='success'>✅ businesses table exists</div>";
    } else {
        echo "<div class='error'>❌ businesses table NOT found</div>";
    }
    
    $tables = $conn->query("SHOW TABLES LIKE 'gallery'");
    if ($tables && $tables->num_rows > 0) {
        echo "<div class='success'>✅ gallery table exists</div>";
    } else {
        echo "<div class='error'>❌ gallery table NOT found</div>";
    }
    
    echo "<hr>";
    echo "<div class='success'><strong>✅ Done! Now:</strong><br>1. Upload the fixed index.php and home-page-news-cards.php files<br>2. DELETE this create-tables-remote.php file for security</div>";
    
} else {
    ?>
    <form method="POST">
        <p>This will create the <strong>businesses</strong> and <strong>gallery</strong> tables in your database.</p>
        <button type="submit" name="create_tables">Create Tables Now</button>
    </form>
    <p style="color: #dc3545;"><strong>⚠️ IMPORTANT:</strong> Delete this file after running for security!</p>
    <?php
}

$conn->close();
?>
</body>
</html>

