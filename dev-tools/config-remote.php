<?php
/**
 * Remote Database Configuration for Development Tools
 * 
 * This file is used ONLY by development tools in this folder.
 * Production code uses: core/omr-connect.php
 * 
 * SECURITY: This folder is protected by .htaccess (localhost only)
 */

// Prevent direct access
if (!defined('DEV_TOOLS_ACCESS')) {
    die('Direct access not permitted');
}

// Determine environment
$is_local = (
    $_SERVER['SERVER_NAME'] === 'localhost' ||
    $_SERVER['SERVER_ADDR'] === '127.0.0.1' ||
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false
);

if (!$is_local) {
    die('Development tools only accessible from localhost');
}

// Database configuration
// Local: Uses SSH tunnel port 3307
// Remote: Uses standard port 3306
$dev_db_config = [
    'host' => '127.0.0.1:3307',  // SSH tunnel endpoint
    'username' => 'metap8ok_myomr_admin',
    'password' => 'myomr@123',
    'database' => 'metap8ok_myomr',
    'charset' => 'utf8mb4'
];

// Create connection
$dev_conn = new mysqli(
    $dev_db_config['host'],
    $dev_db_config['username'],
    $dev_db_config['password'],
    $dev_db_config['database']
);

// Set charset
$dev_conn->set_charset($dev_db_config['charset']);

// Check connection
if ($dev_conn->connect_error) {
    die("
    <div style='padding:20px; background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; border-radius:5px;'>
        <h3>❌ Connection Failed</h3>
        <p><strong>Error:</strong> {$dev_conn->connect_error}</p>
        <hr>
        <h4>Troubleshooting:</h4>
        <ul>
            <li>Is SSH tunnel running? Run: <code>start-tunnel.ps1</code></li>
            <li>Check if port 3307 is forwarding correctly</li>
            <li>Verify credentials in config-remote.php</li>
            <li>See: <a href='../docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md'>Setup Guide</a></li>
        </ul>
    </div>
    ");
}

// Connection successful - set as global for tools
$GLOBALS['dev_conn'] = $dev_conn;
$GLOBALS['dev_db_name'] = $dev_db_config['database'];

// Helper function for safe queries
function dev_query($sql, $params = [], $types = '') {
    global $dev_conn;
    
    if (empty($params)) {
        return $dev_conn->query($sql);
    }
    
    $stmt = $dev_conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $dev_conn->error);
    }
    
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}
?>
