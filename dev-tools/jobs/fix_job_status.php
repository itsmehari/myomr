<?php
/**
 * Fix Job Status - Update job ID 14 to approved status
 */

// Try multiple connection methods
$hosts = [
    'localhost:3306',
    '127.0.0.1:3307',
    'myomr.in:3306',
];

$conn = null;
$connected_host = null;

foreach ($hosts as $host) {
    try {
        $conn = new mysqli($host, 'metap8ok_myomr_admin', 'myomr@123', 'metap8ok_myomr');
        if (!$conn->connect_error) {
            $connected_host = $host;
            break;
        }
    } catch (Exception $e) {
        continue;
    }
}

if (!$conn || $conn->connect_error) {
    die("❌ Could not connect to database\n");
}

echo "✅ Connected using: $connected_host\n\n";

// Update job status to approved
$job_id = 14;
$stmt = $conn->prepare("UPDATE job_postings SET status = 'approved' WHERE id = ?");
$stmt->bind_param("i", $job_id);

if ($stmt->execute()) {
    echo "✅ Job ID $job_id status updated to 'approved'\n";
    
    // Verify
    $check = $conn->query("SELECT id, title, status FROM job_postings WHERE id = $job_id");
    if ($row = $check->fetch_assoc()) {
        echo "\n📋 Verification:\n";
        echo "Job ID: {$row['id']}\n";
        echo "Title: {$row['title']}\n";
        echo "Status: {$row['status']} " . ($row['status'] === 'approved' ? '✅' : '⚠️') . "\n";
    }
} else {
    echo "❌ Failed to update: " . $conn->error . "\n";
}

$stmt->close();
$conn->close();
?>

