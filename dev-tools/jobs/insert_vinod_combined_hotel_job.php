<?php
/**
 * Insert/update combined hotel hiring post for Mr Vinod.
 *
 * Usage:
 *   php dev-tools/jobs/insert_vinod_combined_hotel_job.php
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function connectDb(): mysqli
{
    $credentials = [
        'username' => 'metap8ok_myomr_admin',
        'password' => 'myomr@123',
        'database' => 'metap8ok_myomr',
    ];
    $hosts = ['localhost:3306', '127.0.0.1:3307', 'myomr.in:3306'];

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $credentials['username'], $credentials['password'], $credentials['database']);
            $conn->set_charset('utf8mb4');
            echo "Connected to: {$host}\n";
            return $conn;
        } catch (mysqli_sql_exception $e) {
            echo "Connection failed on {$host}: {$e->getMessage()}\n";
        }
    }

    throw new RuntimeException('Unable to connect to any DB host.');
}

try {
    $conn = connectDb();
    $conn->begin_transaction();

    $employer = [
        'company_name'   => 'Hotel Restaurant - Sholinganallur',
        'contact_person' => 'Mr Vinod',
        'email'          => 'vinod.9176797745@myomr.in',
        'phone'          => '9176797745',
        'address'        => 'Sholinganallur, Chennai, Tamil Nadu',
    ];

    // Upsert employer by email.
    $checkEmp = $conn->prepare("SELECT id FROM employers WHERE email = ? LIMIT 1");
    $checkEmp->bind_param("s", $employer['email']);
    $checkEmp->execute();
    $empRow = $checkEmp->get_result()->fetch_assoc();

    if ($empRow) {
        $employerId = (int)$empRow['id'];
        $updEmp = $conn->prepare("
            UPDATE employers
            SET company_name = ?, contact_person = ?, phone = ?, address = ?, status = 'verified', updated_at = NOW()
            WHERE id = ?
        ");
        $updEmp->bind_param(
            "ssssi",
            $employer['company_name'],
            $employer['contact_person'],
            $employer['phone'],
            $employer['address'],
            $employerId
        );
        $updEmp->execute();
        $updEmp->close();
        echo "Employer updated. ID: {$employerId}\n";
    } else {
        $insEmp = $conn->prepare("
            INSERT INTO employers (company_name, contact_person, email, phone, address, website, status)
            VALUES (?, ?, ?, ?, ?, NULL, 'verified')
        ");
        $insEmp->bind_param(
            "sssss",
            $employer['company_name'],
            $employer['contact_person'],
            $employer['email'],
            $employer['phone'],
            $employer['address']
        );
        $insEmp->execute();
        $employerId = (int)$insEmp->insert_id;
        $insEmp->close();
        echo "Employer inserted. ID: {$employerId}\n";
    }
    $checkEmp->close();

    $job = [
        'title'       => 'Hotel Restaurant Staff Hiring in Sholinganallur (Tea Master, Cook, Billing Executive)',
        'category'    => 'hospitality',
        'job_type'    => 'Full-time',
        'work_segment'=> 'hybrid',
        'location'    => 'Sholinganallur, OMR, Chennai',
        'salary'      => '₹7,000 - ₹18,000 per month',
        'description' => "Immediate hiring for a Hotel Restaurant in Sholinganallur, OMR Chennai. Multiple vacancies are combined into one post: Tea Master, Cook (Tiffen and Lunch), and Billing Executive (Part-time). Candidates can apply for the role best suited to their skills. Direct employer contact: Mr Vinod (9176797745).",
        'requirements'=> "Roles and details:\n1) Tea Master - Timing: 6:00 AM to 2:00 PM - Salary: ₹16,000/month\n2) Cook - Tiffen and Lunch cooking - Salary: ₹16,000 to ₹18,000/month\n3) Billing Executive (Part-time) - Timing: 3:00 PM to 10:00 PM - Salary: ₹7,000/month\nBasic hotel/restaurant experience preferred. For billing role, basic billing/accounts knowledge required. Immediate joiners preferred.",
        'benefits'    => 'Local job in Sholinganallur, direct employer hiring, quick interview process.',
    ];

    // Update existing latest same title+location job, else insert new.
    $checkJob = $conn->prepare("SELECT id FROM job_postings WHERE title = ? AND location = ? ORDER BY id DESC LIMIT 1");
    $checkJob->bind_param("ss", $job['title'], $job['location']);
    $checkJob->execute();
    $jobRow = $checkJob->get_result()->fetch_assoc();

    if ($jobRow) {
        $jobId = (int)$jobRow['id'];
        $updJob = $conn->prepare("
            UPDATE job_postings
            SET employer_id = ?, category = ?, job_type = ?, work_segment = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = DATE_ADD(CURDATE(), INTERVAL 45 DAY), status = 'approved', featured = 0, updated_at = NOW()
            WHERE id = ?
        ");
        $updJob->bind_param(
            "issssssssi",
            $employerId,
            $job['category'],
            $job['job_type'],
            $job['work_segment'],
            $job['salary'],
            $job['description'],
            $job['requirements'],
            $job['benefits'],
            $jobId
        );
        $updJob->execute();
        $updJob->close();
        echo "Job updated. ID: {$jobId}\n";
    } else {
        $insJob = $conn->prepare("
            INSERT INTO job_postings (
                employer_id, title, category, job_type, work_segment, location, salary_range, description, requirements, benefits, application_deadline, status, featured
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'approved', 0
            )
        ");
        $insJob->bind_param(
            "isssssssss",
            $employerId,
            $job['title'],
            $job['category'],
            $job['job_type'],
            $job['work_segment'],
            $job['location'],
            $job['salary'],
            $job['description'],
            $job['requirements'],
            $job['benefits']
        );
        $insJob->execute();
        $jobId = (int)$insJob->insert_id;
        $insJob->close();
        echo "Job inserted. ID: {$jobId}\n";
    }
    $checkJob->close();

    $conn->commit();

    $urlTitle = strtolower($job['title']);
    $urlTitle = preg_replace('/[^a-z0-9]+/', '-', $urlTitle);
    $urlTitle = trim((string)$urlTitle, '-');
    echo "Job URL: https://myomr.in/omr-local-job-listings/job/{$jobId}/{$urlTitle}\n";
    echo "Done.\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        try { $conn->rollback(); } catch (Throwable $ignored) {}
    }
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}

