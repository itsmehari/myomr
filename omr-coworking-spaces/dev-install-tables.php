<?php
/**
 * Install Coworking Spaces Database Tables
 * Run this via browser or command line
 */

define('DEV_TOOLS_ACCESS', true);

// Include remote config
require __DIR__ . '/../dev-tools/config-remote.php';

echo "<!DOCTYPE html>\n<html><head><meta charset='UTF-8'>\n";
echo "<title>Install Coworking Spaces Tables</title>\n";
echo "<style>body{font-family:Arial;max-width:900px;margin:40px auto;padding:20px;}";
echo ".success{color:green;font-weight:bold;margin:10px 0;padding:10px;background:#e7f5e7;border-radius:5px;}";
echo ".error{color:red;margin:10px 0;padding:10px;background:#ffe7e7;border-radius:5px;}";
echo "h1{color:#14532d;} h2{border-bottom:2px solid #14532d;padding-bottom:10px;}</style></head><body>";

echo "<h1>🔧 Installing Coworking Spaces Database Tables</h1>";

// Read SQL file
$sql_file = __DIR__ . '/CREATE-COWORKING-DATABASE.sql';
if (!file_exists($sql_file)) {
    die('<div class="error">❌ SQL file not found: ' . htmlspecialchars($sql_file) . '</div></body></html>');
}

$sql_content = file_get_contents($sql_file);

// Remove DROP TABLE statements for safety
$lines = explode("\n", $sql_content);
$safe_sql = [];
foreach ($lines as $line) {
    if (strpos(trim($line), 'DROP TABLE IF EXISTS') !== false) {
        echo "<p style='color:#666;'>⏭️ Skipping: " . htmlspecialchars(trim($line)) . "</p>";
        continue;
    }
    if (strpos(trim($line), 'VERIFY DATABASE SETUP') !== false) {
        break; // Stop before verification section
    }
    $safe_sql[] = $line;
}

$final_sql = implode("\n", $safe_sql);

// Split into individual statements
$statements = array_filter(array_map('trim', explode(';', $final_sql)));

$success_count = 0;
$error_count = 0;

foreach ($statements as $statement) {
    if (empty($statement) || substr($statement, 0, 2) === '--') {
        continue;
    }
    
    // Execute statement
    if ($dev_conn->query($statement)) {
        $success_count++;
        // Extract table name if CREATE TABLE
        if (preg_match('/CREATE\s+(?:TABLE|INDEX)\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([a-z_]+)`?/i', $statement, $matches)) {
            $table_or_index = $matches[1];
            echo "<div class='success'>✅ Created: " . htmlspecialchars($table_or_index) . "</div>\n";
        }
    } else {
        $error_count++;
        echo "<div class='error'>❌ Error: " . htmlspecialchars($dev_conn->error) . "</div>\n";
        echo "<div style='background:#f5f5f5;padding:10px;margin:5px 0;font-family:monospace;font-size:12px;'>";
        echo htmlspecialchars(substr($statement, 0, 200)) . "...";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p><strong>Successful operations:</strong> {$success_count}</p>";
echo "<p><strong>Errors:</strong> {$error_count}</p>";

if ($error_count === 0) {
    echo "<div class='success' style='font-size:18px;'>✅ All tables installed successfully!</div>";
    echo "<p><a href='../dev-tools/check-tables.php'>Check Tables</a> | ";
    echo "<a href='index.php'>Go to Coworking Spaces</a></p>";
} else {
    echo "<div class='error'>⚠️ Some errors occurred. Please review above.</div>";
}

echo "</body></html>";

?>

