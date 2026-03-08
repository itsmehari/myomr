<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyOMR Database Connection Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #008552;
            border-bottom: 3px solid #9ebd13;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #008552;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            color: #17a2b8;
        }
        .warning {
            color: #ffc107;
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }
        code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #008552;
            color: white;
        }
        .cmd-box {
            background: #2d2d2d;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔌 MyOMR Database Connection Test</h1>
        
        <?php
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        echo "<div class='test-section'>";
        echo "<h2>📊 Environment Information</h2>";
        echo "<table>";
        echo "<tr><th>Property</th><th>Value</th></tr>";
        echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
        echo "<tr><td>Server</td><td>" . $_SERVER['SERVER_SOFTWARE'] . "</td></tr>";
        echo "<tr><td>Host</td><td>" . $_SERVER['HTTP_HOST'] . "</td></tr>";
        echo "<tr><td>Document Root</td><td>" . $_SERVER['DOCUMENT_ROOT'] . "</td></tr>";
        echo "<tr><td>MySQL Extension</td><td>" . (extension_loaded('mysqli') ? '✅ Loaded' : '❌ Not Loaded') . "</td></tr>";
        echo "</table>";
        echo "</div>";
        
        // Test 1: Current configuration
        echo "<div class='test-section'>";
        echo "<h2>🧪 Test 1: Current Database Configuration</h2>";
        
        if (file_exists('core/omr-connect.php')) {
            echo "<p class='info'>📄 File found: <code>core/omr-connect.php</code></p>";
            
            try {
                require_once 'core/omr-connect.php';
                
                if ($conn && !$conn->connect_error) {
                    echo "<p class='success'>✅ Connection successful!</p>";
                    echo "<table>";
                    echo "<tr><th>Property</th><th>Value</th></tr>";
                    echo "<tr><td>Server</td><td>" . $servername . "</td></tr>";
                    echo "<tr><td>Database</td><td>" . $database . "</td></tr>";
                    echo "<tr><td>Username</td><td>" . $username . "</td></tr>";
                    echo "<tr><td>Character Set</td><td>" . $conn->character_set_name() . "</td></tr>";
                    echo "<tr><td>Server Info</td><td>" . $conn->server_info . "</td></tr>";
                    echo "<tr><td>Host Info</td><td>" . $conn->host_info . "</td></tr>";
                    echo "</table>";
                    
                    // Test query
                    echo "<h3>📝 Query Test</h3>";
                    $test_queries = [
                        "SELECT COUNT(*) as count FROM news_bulletin" => "News Bulletin",
                        "SELECT COUNT(*) as count FROM omrschoolslist" => "Schools",
                        "SELECT COUNT(*) as count FROM omrhospitalslist" => "Hospitals",
                        "SELECT COUNT(*) as count FROM omr_restaurants" => "Restaurants"
                    ];
                    
                    echo "<table>";
                    echo "<tr><th>Table</th><th>Count</th><th>Status</th></tr>";
                    
                    foreach ($test_queries as $query => $table_name) {
                        $result = $conn->query($query);
                        if ($result) {
                            $row = $result->fetch_assoc();
                            echo "<tr><td>" . $table_name . "</td><td>" . $row['count'] . "</td><td class='success'>✅ OK</td></tr>";
                        } else {
                            echo "<tr><td>" . $table_name . "</td><td>-</td><td class='error'>❌ Error: " . $conn->error . "</td></tr>";
                        }
                    }
                    echo "</table>";
                    
                } else {
                    echo "<p class='error'>❌ Connection failed: " . $conn->connect_error . "</p>";
                    echo "<p class='error'>Error Code: " . $conn->connect_errno . "</p>";
                }
                
            } catch (Exception $e) {
                echo "<p class='error'>❌ Exception: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='error'>❌ File not found: <code>core/omr-connect.php</code></p>";
        }
        echo "</div>";
        
        // Test 2: Check if SSH tunnel is needed
        echo "<div class='test-section'>";
        echo "<h2>🔐 Test 2: Connection Type Analysis</h2>";
        
        $is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
                     strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
        
        if ($is_local) {
            echo "<p class='warning'>⚠️ <strong>You're on LOCAL environment</strong></p>";
            echo "<p>To connect to the <strong>live database</strong> from your local Windows machine, you need to:</p>";
            
            echo "<h3>Option 1: SSH Tunnel (Recommended)</h3>";
            echo "<p>1. Open PowerShell/Command Prompt</p>";
            echo "<p>2. Run this command:</p>";
            echo "<div class='cmd-box'>ssh -L 3307:localhost:3306 YOUR_CPANEL_USERNAME@myomr.in</div>";
            echo "<p>3. Update <code>core/omr-connect.php</code> to use port <strong>3307</strong></p>";
            echo "<code>\$servername = \"localhost:3307\";</code>";
            
            echo "<h3>Option 2: Use Remote Config File</h3>";
            if (file_exists('core/omr-connect-remote.php')) {
                echo "<p class='success'>✅ Remote config file exists: <code>core/omr-connect-remote.php</code></p>";
                echo "<p>Change your includes to use this file instead.</p>";
            } else {
                echo "<p class='error'>❌ Remote config file not found. It should be created at <code>core/omr-connect-remote.php</code></p>";
            }
            
            echo "<h3>📚 Full Documentation</h3>";
            if (file_exists('docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md')) {
                echo "<p class='success'>✅ Setup guide available: <code>docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md</code></p>";
                echo "<p>Open this file for complete step-by-step instructions.</p>";
            } else {
                echo "<p class='error'>❌ Setup guide not found</p>";
            }
            
        } else {
            echo "<p class='success'>✅ You're on LIVE/PRODUCTION environment</p>";
            echo "<p>Connection should use localhost:3306 (current setting)</p>";
        }
        
        echo "</div>";
        
        // Test 3: Port connectivity
        echo "<div class='test-section'>";
        echo "<h2>🌐 Test 3: Port Connectivity</h2>";
        
        $ports_to_test = [3306, 3307];
        echo "<table>";
        echo "<tr><th>Port</th><th>Status</th><th>Purpose</th></tr>";
        
        foreach ($ports_to_test as $port) {
            $connection = @fsockopen('localhost', $port, $errno, $errstr, 1);
            if ($connection) {
                echo "<tr><td>$port</td><td class='success'>✅ Open</td>";
                echo "<td>" . ($port == 3306 ? "Local MySQL" : "SSH Tunnel") . "</td></tr>";
                fclose($connection);
            } else {
                echo "<tr><td>$port</td><td class='error'>❌ Closed</td>";
                echo "<td>" . ($port == 3306 ? "Local MySQL" : "SSH Tunnel") . "</td></tr>";
            }
        }
        echo "</table>";
        
        echo "</div>";
        
        // Recommendations
        echo "<div class='test-section'>";
        echo "<h2>💡 Recommendations</h2>";
        echo "<ul>";
        
        if ($is_local) {
            if (!$conn || $conn->connect_error) {
                echo "<li class='error'>❌ <strong>Action Required:</strong> Set up SSH tunnel to access live database</li>";
                echo "<li>📖 Read <code>docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md</code> for instructions</li>";
                echo "<li>🔧 Or install local MySQL and import database copy</li>";
            } else {
                echo "<li class='success'>✅ Connection working! You're connected to: <strong>" . $database . "</strong></li>";
            }
        } else {
            if (!$conn || $conn->connect_error) {
                echo "<li class='error'>❌ Database connection failed on live server</li>";
                echo "<li>Check cPanel MySQL configuration</li>";
                echo "<li>Verify database credentials</li>";
            } else {
                echo "<li class='success'>✅ All systems operational!</li>";
            }
        }
        
        echo "</ul>";
        echo "</div>";
        
        // Close connection
        if (isset($conn) && $conn) {
            $conn->close();
        }
        ?>
        
        <div style="text-align: center; margin-top: 30px; color: #666;">
            <p>MyOMR.in - Version 2.0.0</p>
            <p>Database Connection Diagnostic Tool</p>
        </div>
    </div>
</body>
</html>
