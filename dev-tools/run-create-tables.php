<?php
/**
 * Create Missing Tables in Remote Database
 */

// Define access flag
define('DEV_TOOLS_ACCESS', true);

// Include remote config
require 'config-remote.php';

echo "<h1>🔧 Creating Missing Tables in Remote Database</h1>";

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

if ($dev_conn->query($create_businesses)) {
    echo "<p style='color: green; font-weight: bold;'>✅ businesses table created successfully!</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating businesses table: " . $dev_conn->error . "</p>";
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

if ($dev_conn->query($create_gallery)) {
    echo "<p style='color: green; font-weight: bold;'>✅ gallery table created successfully!</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating gallery table: " . $dev_conn->error . "</p>";
}

// ==========================================
// 3. Verify tables exist
// ==========================================
echo "<h2>Verifying tables...</h2>";

$tables = $dev_conn->query("SHOW TABLES LIKE 'businesses'");
if ($tables && $tables->num_rows > 0) {
    echo "<p style='color: green;'>✅ businesses table exists</p>";
} else {
    echo "<p style='color: red;'>❌ businesses table NOT found</p>";
}

$tables = $dev_conn->query("SHOW TABLES LIKE 'gallery'");
if ($tables && $tables->num_rows > 0) {
    echo "<p style='color: green;'>✅ gallery table exists</p>";
} else {
    echo "<p style='color: red;'>❌ gallery table NOT found</p>";
}

echo "<hr>";
echo "<p><strong>✅ Done! You can now upload the index.php and home-page-news-cards.php files.</strong></p>";
echo "<p><a href='index.php'>← Back to Dev Tools</a></p>";

$dev_conn->close();
?>

