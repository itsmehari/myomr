<?php
/**
 * Database Schema Exporter for MyOMR.in
 * This script exports the CREATE TABLE statements for all tables
 * 
 * Usage:
 * 1. Ensure SSH tunnel is running (if local)
 * 2. Visit: http://localhost/export-database-schema.php
 * 3. Copy the SQL output
 * 4. Save to docs/database-schema.sql
 */

require 'core/omr-connect.php';

// Set output headers
header('Content-Type: text/plain; charset=utf-8');
echo "-- MyOMR.in Database Schema Export\n";
echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
echo "-- Database: " . $database . "\n";
echo "-- ==========================================\n\n";

// Get all tables
$tables_query = "SHOW TABLES";
$tables_result = $conn->query($tables_query);

if (!$tables_result) {
    die("Error fetching tables: " . $conn->error);
}

$table_count = 0;

while ($table_row = $tables_result->fetch_array()) {
    $table_name = $table_row[0];
    $table_count++;
    
    echo "-- ==========================================\n";
    echo "-- Table: $table_name\n";
    echo "-- ==========================================\n\n";
    
    // Get CREATE TABLE statement
    $create_query = "SHOW CREATE TABLE `$table_name`";
    $create_result = $conn->query($create_query);
    
    if ($create_result) {
        $create_row = $create_result->fetch_assoc();
        echo $create_row['Create Table'] . ";\n\n";
    } else {
        echo "-- Error: Could not get CREATE statement for $table_name\n\n";
    }
    
    // Get table info
    $info_query = "SELECT 
        TABLE_ROWS,
        AVG_ROW_LENGTH,
        DATA_LENGTH,
        INDEX_LENGTH,
        CREATE_TIME,
        UPDATE_TIME
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = '$database' 
    AND TABLE_NAME = '$table_name'";
    
    $info_result = $conn->query($info_query);
    if ($info_result && $info_row = $info_result->fetch_assoc()) {
        echo "-- Table Statistics:\n";
        echo "-- Rows: " . ($info_row['TABLE_ROWS'] ?? 'unknown') . "\n";
        echo "-- Data Size: " . formatBytes($info_row['DATA_LENGTH'] ?? 0) . "\n";
        echo "-- Index Size: " . formatBytes($info_row['INDEX_LENGTH'] ?? 0) . "\n";
        echo "-- Created: " . ($info_row['CREATE_TIME'] ?? 'unknown') . "\n";
        echo "-- Updated: " . ($info_row['UPDATE_TIME'] ?? 'unknown') . "\n";
        echo "\n";
    }
    
    // Get column information
    $columns_query = "SHOW FULL COLUMNS FROM `$table_name`";
    $columns_result = $conn->query($columns_query);
    
    if ($columns_result) {
        echo "-- Column Details:\n";
        echo "-- " . str_repeat('-', 100) . "\n";
        echo "-- " . str_pad("Field", 20) . " | " . str_pad("Type", 20) . " | " . str_pad("Null", 6) . " | " . str_pad("Key", 6) . " | " . str_pad("Default", 15) . " | Extra\n";
        echo "-- " . str_repeat('-', 100) . "\n";
        
        while ($col = $columns_result->fetch_assoc()) {
            echo "-- " . 
                str_pad($col['Field'], 20) . " | " . 
                str_pad($col['Type'], 20) . " | " . 
                str_pad($col['Null'], 6) . " | " . 
                str_pad($col['Key'], 6) . " | " . 
                str_pad($col['Default'] ?? 'NULL', 15) . " | " . 
                $col['Extra'] . "\n";
        }
        echo "\n";
    }
    
    // Get sample data (first 3 rows)
    $sample_query = "SELECT * FROM `$table_name` LIMIT 3";
    $sample_result = $conn->query($sample_query);
    
    if ($sample_result && $sample_result->num_rows > 0) {
        echo "-- Sample Data (first 3 rows):\n";
        while ($sample = $sample_result->fetch_assoc()) {
            echo "-- " . json_encode($sample, JSON_UNESCAPED_UNICODE) . "\n";
        }
        echo "\n";
    }
    
    echo "\n";
}

echo "-- ==========================================\n";
echo "-- Export Complete\n";
echo "-- Total Tables: $table_count\n";
echo "-- ==========================================\n";

$conn->close();

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
