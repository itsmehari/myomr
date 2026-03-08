<?php
/**
 * Check Missing Tables and Article Status in Live Database
 */

// Define access flag
define('DEV_TOOLS_ACCESS', true);

// Include remote config
require 'config-remote.php';

echo "<h2>=== MyOMR Database Analysis ===</h2>";

// ==========================================
// 1. Check existing tables
// ==========================================
echo "<h3>📋 Existing Tables:</h3>";
$tables_query = "SHOW TABLES";
$tables_result = $dev_conn->query($tables_query);

$existing_tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $existing_tables[] = $row[0];
    }
}

echo "<ul>";
foreach ($existing_tables as $table) {
    $row_count = $dev_conn->query("SELECT COUNT(*) as count FROM `$table`")->fetch_assoc()['count'];
    echo "<li><strong>$table</strong> - $row_count rows</li>";
}
echo "</ul>";

// ==========================================
// 2. Check missing tables
// ==========================================
echo "<h3>❌ Missing Tables:</h3>";
$missing = ['businesses', 'gallery'];
$missing_found = [];

foreach ($missing as $table) {
    if (!in_array($table, $existing_tables)) {
        $missing_found[] = $table;
        echo "<li><strong>$table</strong> - MISSING ❌</li>";
    }
}

if (empty($missing_found)) {
    echo "<p>✅ All tables exist!</p>";
}

// ==========================================
// 3. Check articles table status
// ==========================================
echo "<h3>📰 Articles Table Analysis:</h3>";

// Check if articles table exists
if (in_array('articles', $existing_tables)) {
    // Get total count
    $total_count = $dev_conn->query("SELECT COUNT(*) as count FROM articles")->fetch_assoc()['count'];
    echo "<p><strong>Total articles:</strong> $total_count</p>";
    
    // Get status distribution
    $status_query = "SELECT status, COUNT(*) as count FROM articles GROUP BY status";
    $status_result = $dev_conn->query($status_query);
    
    echo "<p><strong>Status distribution:</strong></p>";
    echo "<ul>";
    while ($status_row = $status_result->fetch_assoc()) {
        $status = $status_row['status'] ?: 'NULL/Empty';
        $count = $status_row['count'];
        echo "<li>$status: $count articles</li>";
    }
    echo "</ul>";
    
    // Show published count
    $published_count = $dev_conn->query("SELECT COUNT(*) as count FROM articles WHERE status = 'published'")->fetch_assoc()['count'];
    echo "<p><strong>Published articles:</strong> $published_count (will show on homepage)</p>";
    
    // Show last 5 articles
    echo "<p><strong>Last 5 articles:</strong></p>";
    $recent = $dev_conn->query("SELECT id, title, slug, status, published_date FROM articles ORDER BY id DESC LIMIT 5");
    echo "<ul>";
    while ($row = $recent->fetch_assoc()) {
        $status = $row['status'] ?: 'NULL';
        echo "<li>ID: {$row['id']} - <em>{$row['title']}</em> (Status: $status, Date: {$row['published_date']})</li>";
    }
    echo "</ul>";
    
    // Show articles that won't display
    $not_published = $total_count - $published_count;
    if ($not_published > 0) {
        echo "<p style='color: orange;'><strong>⚠️ Note:</strong> $not_published articles are NOT published and won't show on homepage!</p>";
    }
} else {
    echo "<p>❌ Articles table does not exist!</p>";
}

echo "<hr>";

// ==========================================
// 4. Recommendations
// ==========================================
echo "<h3>💡 Recommendations:</h3>";
echo "<ol>";

if (!empty($missing_found)) {
    echo "<li>Create missing tables by running <code>create-missing-tables.sql</code> in phpMyAdmin</li>";
}

if (isset($not_published) && $not_published > 0) {
    echo "<li>To show ALL articles on homepage: Edit <code>home-page-news-cards.php</code> and remove <code>WHERE status = 'published'</code></li>";
    echo "<li>Or publish all articles by running: <code>UPDATE articles SET status = 'published' WHERE status IS NULL</code></li>";
}

echo "</ol>";

$dev_conn->close();
?>

