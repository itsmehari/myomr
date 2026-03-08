<?php
/**
 * Verify Job Insert - Check if job was inserted correctly
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

// Check for the job
$query = "SELECT id, title, status, location, salary_range, created_at, employer_id 
          FROM job_postings 
          WHERE title LIKE '%Domestic Voice%' OR title LIKE '%Voice Processing%'
          ORDER BY id DESC 
          LIMIT 5";

$result = $conn->query($query);

if (!$result) {
    die("❌ Query failed: " . $conn->error . "\n");
}

echo "📋 Found " . $result->num_rows . " matching job(s):\n\n";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Job ID: " . $row['id'] . "\n";
        echo "Title: " . $row['title'] . "\n";
        echo "Status: " . $row['status'] . " " . ($row['status'] === 'approved' ? '✅' : '⚠️') . "\n";
        echo "Location: " . $row['location'] . "\n";
        echo "Salary: " . $row['salary_range'] . "\n";
        echo "Created: " . $row['created_at'] . "\n";
        echo "Employer ID: " . $row['employer_id'] . "\n";
        echo "---\n";
    }
} else {
    echo "❌ No matching jobs found!\n\n";
    
    // Check latest jobs
    echo "📋 Latest 5 jobs in database:\n";
    $latest = $conn->query("SELECT id, title, status FROM job_postings ORDER BY id DESC LIMIT 5");
    while ($row = $latest->fetch_assoc()) {
        echo "ID: {$row['id']} | {$row['title']} | Status: {$row['status']}\n";
    }
}

// Check employer
echo "\n📋 Checking employer record:\n";
$emp_query = "SELECT id, company_name, email, status FROM employers WHERE email LIKE '%voiceprocess%' OR company_name LIKE '%Voice%'";
$emp_result = $conn->query($emp_query);
if ($emp_result && $emp_result->num_rows > 0) {
    while ($row = $emp_result->fetch_assoc()) {
        echo "Employer ID: {$row['id']} | {$row['company_name']} | {$row['email']} | Status: {$row['status']}\n";
    }
} else {
    echo "❌ No matching employer found\n";
}

// Check approved jobs count
$approved = $conn->query("SELECT COUNT(*) as count FROM job_postings WHERE status = 'approved'");
if ($approved) {
    $row = $approved->fetch_assoc();
    echo "\n📊 Total approved jobs: " . $row['count'] . "\n";
}

$conn->close();
?>

