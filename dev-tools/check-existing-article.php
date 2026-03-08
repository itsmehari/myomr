<?php
/**
 * Check how existing articles are structured in the database
 */

// Simple connection without config-remote.php
$dev_conn = new mysqli('127.0.0.1:3307', 'metap8ok_myomr_admin', 'myomr@123', 'metap8ok_myomr');

try {
    $dev_conn = new mysqli(
        $dev_db_config['host'],
        $dev_db_config['username'],
        $dev_db_config['password'],
        $dev_db_config['database']
    );
    
    if ($dev_conn->connect_error) {
        die("Connection failed: " . $dev_conn->connect_error);
    }
    
    echo "<h1>Article Structure Analysis</h1>";
    
    // Get the article about 70-Meter Crack
    $sql = "SELECT * FROM articles WHERE title LIKE '%70-Meter%' OR title LIKE '%Perungudi%Road%' LIMIT 5";
    $result = $dev_conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<h2>Found Article:</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<hr>";
            echo "<h3>Title: " . htmlspecialchars($row['title']) . "</h3>";
            echo "<p><strong>Slug:</strong> " . htmlspecialchars($row['slug']) . "</p>";
            echo "<p><strong>Summary Length:</strong> " . strlen($row['summary']) . " characters</p>";
            echo "<p><strong>Content Length:</strong> " . strlen($row['content']) . " characters</p>";
            echo "<p><strong>Content Preview (first 200 chars):</strong> " . htmlspecialchars(substr($row['content'], 0, 200)) . "...</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
            echo "<p><strong>Published Date:</strong> " . htmlspecialchars($row['published_date']) . "</p>";
            
            // Check if there's a standalone PHP file
            $slug = $row['slug'];
            echo "<p><strong>Would load from:</strong> local-news/$slug.php</p>";
        }
    } else {
        echo "<p>Article not found. Let's check what articles exist:</p>";
        
        $sql2 = "SELECT id, title, slug, LENGTH(content) as content_len FROM articles ORDER BY id DESC LIMIT 10";
        $result2 = $dev_conn->query($sql2);
        
        echo "<h3>Recent Articles:</h3>";
        while ($row = $result2->fetch_assoc()) {
            echo "<p>" . htmlspecialchars($row['title']) . " (Content: " . $row['content_len'] . " chars)</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Checking for standalone PHP files...</h2>";
    
    // Check some common article slugs
    $common_slugs = [
        'pallikaranai-ramsar-complete-guide-omr-residents',
        'EVs-Take-a-hit-Catches-Fire',
        'Fire-Breaks-Out-At-Corporation-Dump-Yard-Perungudi-2022'
    ];
    
    foreach ($common_slugs as $slug) {
        $file_exists = file_exists("../local-news/$slug.php");
        echo "<p>$slug.php: " . ($file_exists ? "✅ EXISTS" : "❌ MISSING") . "</p>";
    }
    
    $dev_conn->close();
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

