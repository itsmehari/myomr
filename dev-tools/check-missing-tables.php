<?php
/**
 * Check Missing Tables in Live Database
 * This script checks which tables exist and which are missing
 */

require '../core/omr-connect.php';

// List of tables that should exist
$expected_tables = [
    'articles',
    'news_bulletin', 
    'events',
    'businesses',
    'gallery',
    'omr_restaurants',
    'omrschoolslist',
    'omrhospitalslist',
    'omrbankslist',
    'omr_atms',
    'omr_gov_offices',
    'omr_industries',
    'omr_it_companies',
    'omr_parks',
    'omr_schools',
    'admin_users'
];

// Get all existing tables
$tables_query = "SHOW TABLES";
$tables_result = $conn->query($tables_query);

$existing_tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $existing_tables[] = $row[0];
    }
}

// Check which tables are missing
$missing_tables = [];
$existing = [];
foreach ($expected_tables as $table) {
    if (!in_array($table, $existing_tables)) {
        $missing_tables[] = $table;
    } else {
        $existing[] = $table;
    }
}

echo "=== Database Table Status ===\n\n";

echo "✅ EXISTING TABLES:\n";
foreach ($existing as $table) {
    $row_count = $conn->query("SELECT COUNT(*) as count FROM `$table`")->fetch_assoc()['count'];
    echo "  - $table ($row_count rows)\n";
}

echo "\n❌ MISSING TABLES:\n";
foreach ($missing_tables as $table) {
    echo "  - $table\n";
}

// Check articles table specifically
echo "\n=== ARTICLES TABLE DETAILS ===\n";
if (in_array('articles', $existing_tables)) {
    // Get total count
    $total_count = $conn->query("SELECT COUNT(*) as count FROM articles")->fetch_assoc()['count'];
    echo "Total articles: $total_count\n";
    
    // Get published count
    $published_count = $conn->query("SELECT COUNT(*) as count FROM articles WHERE status = 'published'")->fetch_assoc()['count'];
    echo "Published articles: $published_count\n";
    
    // Get unpublished count
    $unpublished_count = $conn->query("SELECT COUNT(*) as count FROM articles WHERE status != 'published' OR status IS NULL")->fetch_assoc()['count'];
    echo "Unpublished articles: $unpublished_count\n";
    
    // Show last 5 articles
    echo "\nLast 5 articles:\n";
    $recent = $conn->query("SELECT id, title, slug, status, published_date FROM articles ORDER BY id DESC LIMIT 5");
    while ($row = $recent->fetch_assoc()) {
        echo "  ID: {$row['id']} - {$row['title']} (Status: {$row['status']}, Date: {$row['published_date']})\n";
    }
}

$conn->close();
?>

