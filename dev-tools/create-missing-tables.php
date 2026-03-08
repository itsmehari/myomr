<?php
/**
 * Create Missing Tables in Live Database
 * This script will create businesses and gallery tables
 */

// Define access flag
define('DEV_TOOLS_ACCESS', true);

// Include remote config
require 'config-remote.php';

echo "<h1>🔧 Creating Missing Tables</h1>";

// ==========================================
// 1. Create businesses table
// ==========================================
echo "<h2>Creating businesses table...</h2>";

$create_businesses = "
CREATE TABLE IF NOT EXISTS `businesses` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if ($dev_conn->query($create_businesses)) {
    echo "<p style='color: green;'>✅ businesses table created successfully!</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating businesses table: " . $dev_conn->error . "</p>";
}

// ==========================================
// 2. Create gallery table
// ==========================================
echo "<h2>Creating gallery table...</h2>";

$create_gallery = "
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(500) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if ($dev_conn->query($create_gallery)) {
    echo "<p style='color: green;'>✅ gallery table created successfully!</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating gallery table: " . $dev_conn->error . "</p>";
}

// ==========================================
// 3. Update articles to published (optional)
// ==========================================
echo "<h2>Checking articles status...</h2>";

$check_total = $dev_conn->query("SELECT COUNT(*) as total FROM articles");
$total = $check_total->fetch_assoc()['total'];

$check_published = $dev_conn->query("SELECT COUNT(*) as published FROM articles WHERE status = 'published'");
$published = $check_published->fetch_assoc()['published'];

$not_published = $total - $published;

echo "<p>Total articles: $total</p>";
echo "<p>Published articles: $published</p>";
echo "<p>Not published: $not_published</p>";

if ($not_published > 0) {
    echo "<h3>Publish all articles?</h3>";
    echo "<p style='color: orange;'>$not_published articles are not published and won't show on homepage.</p>";
    echo "<p><em>If you want to publish them all, uncomment the UPDATE query in this file and refresh.</em></p>";
    
    // UNCOMMENT THE NEXT 3 LINES TO PUBLISH ALL ARTICLES:
    // if ($dev_conn->query("UPDATE articles SET status = 'published' WHERE status IS NULL OR status != 'published'")) {
    //     echo "<p style='color: green;'>✅ All articles have been published!</p>";
    // }
}

echo "<hr>";
echo "<p><a href='check-articles-and-tables.php'>← Back to Status Check</a></p>";

$dev_conn->close();
?>

