<?php
/**
 * Manual helper to insert/update the Domestic Voice Processing Executive job.
 *
 * Usage: php dev-tools/jobs/insert_domestic_voice_job.php
 *
 * The script is idempotent – re-running it will update the existing record based
 * on the job title + location combination instead of creating duplicates.
 */

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Attempt database connections using multiple endpoints (local, tunnel, remote).
 */
function buildConnection(): mysqli {
    $credentials = [
        'username' => 'metap8ok_myomr_admin',
        'password' => 'myomr@123',
        'database' => 'metap8ok_myomr',
    ];

    $hosts = [
        'localhost:3306',     // local dev server
        '127.0.0.1:3307',     // SSH tunnel endpoint
        'myomr.in:3306',      // direct remote host
    ];

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $credentials['username'], $credentials['password'], $credentials['database']);
            $conn->set_charset('utf8mb4');
            echo "Connected using host {$host}" . PHP_EOL;
            return $conn;
        } catch (mysqli_sql_exception $e) {
            echo "Connection attempt failed for {$host}: {$e->getMessage()}" . PHP_EOL;
        }
    }

    throw new RuntimeException('All database connection attempts failed.');
}

$conn = buildConnection();

$employerData = [
    'company_name'   => 'Mount Road BPO Hiring Partner',
    'contact_person' => 'HR Team',
    'email'          => 'voiceprocess.mountroad@myomr.in',
    'phone'          => '9025773016',
    'address'        => 'Mount Road, Chennai',
    'website'        => 'https://forms.gle/CA2kBMeXEjmqftNB7',
    'status'         => 'verified'
];

$jobData = [
    'title'        => 'Domestic Voice Processing Executive',
    'category'     => 'customer-service',
    'job_type'     => 'Full-time',
    'location'     => 'Mount Road, Chennai',
    'salary_range' => '₹15,000 take home - ₹17,000 CTC',
    'description'  => "Join a domestic voice process team handling inbound and outbound customer support for Mount Road clients. Fluent communication in English and Tamil is mandatory, and proficiency in one additional language (Hindi / Telugu / Kannada) helps you support wider customer segments. Work with a proven team, handle day-to-day customer resolutions, document call outcomes, and guide customers to the right solutions. Rotational day shifts with week-off rotations ensure everyone shares balanced schedules. \n\nReach the HR desk at 9025773016 / 9841783900 or submit the Google Form (https://forms.gle/CA2kBMeXEjmqftNB7) for quick screening.",
    'requirements' => implode("\n", [
        'Fluent English and Tamil with clear diction',
        'Proficiency in any ONE additional language: Hindi / Telugu / Kannada',
        'Comfortable working in rotational day shifts with rotational weekly offs',
        'Basic computer and CRM navigation skills',
        'Any degree with strong customer service mindset',
    ]),
    'benefits'     => "Rotational week-off, on-floor language support mentors, performance-based monthly incentives.",
    'application_deadline' => null,
    'status'       => 'approved',
    'featured'     => 0
];

$conn->begin_transaction();

try {
    // Upsert employer
    $employerStmt = $conn->prepare("SELECT id FROM employers WHERE email = ?");
    $employerStmt->bind_param("s", $employerData['email']);
    $employerStmt->execute();
    $employerResult = $employerStmt->get_result();
    $employerId = null;

    if ($employerRow = $employerResult->fetch_assoc()) {
        $employerId = (int)$employerRow['id'];
        $updateEmployer = $conn->prepare("
            UPDATE employers
            SET company_name = ?, contact_person = ?, phone = ?, address = ?, website = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $updateEmployer->bind_param(
            "ssssssi",
            $employerData['company_name'],
            $employerData['contact_person'],
            $employerData['phone'],
            $employerData['address'],
            $employerData['website'],
            $employerData['status'],
            $employerId
        );
        $updateEmployer->execute();
        $updateEmployer->close();
    } else {
        $insertEmployer = $conn->prepare("
            INSERT INTO employers (company_name, contact_person, email, phone, address, website, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insertEmployer->bind_param(
            "sssssss",
            $employerData['company_name'],
            $employerData['contact_person'],
            $employerData['email'],
            $employerData['phone'],
            $employerData['address'],
            $employerData['website'],
            $employerData['status']
        );
        $insertEmployer->execute();
        $employerId = $insertEmployer->insert_id;
        $insertEmployer->close();
    }
    $employerStmt->close();

    if (!$employerId) {
        throw new Exception('Unable to resolve employer ID');
    }

    // Check for existing job (title + location)
    $jobCheck = $conn->prepare("SELECT id FROM job_postings WHERE title = ? AND location = ? ORDER BY id DESC LIMIT 1");
    $jobCheck->bind_param("ss", $jobData['title'], $jobData['location']);
    $jobCheck->execute();
    $jobResult = $jobCheck->get_result();

    if ($existingJob = $jobResult->fetch_assoc()) {
        $jobId = (int)$existingJob['id'];
        $updateJob = $conn->prepare("
            UPDATE job_postings
            SET employer_id = ?, category = ?, job_type = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = ?, status = ?, featured = ?, updated_at = NOW()
            WHERE id = ?
        ");
        // Handle NULL application_deadline properly
        $appDeadline = $jobData['application_deadline'] ?? null;
        $updateJob->bind_param(
            "issssssssii",
            $employerId,
            $jobData['category'],
            $jobData['job_type'],
            $jobData['salary_range'],
            $jobData['description'],
            $jobData['requirements'],
            $jobData['benefits'],
            $appDeadline,
            $jobData['status'],
            $jobData['featured'],
            $jobId
        );
        $updateJob->execute();
        $updateJob->close();
        $action = "updated";
    } else {
        $insertJob = $conn->prepare("
            INSERT INTO job_postings (employer_id, title, category, job_type, location, salary_range, description, requirements, benefits, application_deadline, status, featured)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insertJob->bind_param(
            "isssssssssii",
            $employerId,
            $jobData['title'],
            $jobData['category'],
            $jobData['job_type'],
            $jobData['location'],
            $jobData['salary_range'],
            $jobData['description'],
            $jobData['requirements'],
            $jobData['benefits'],
            $jobData['application_deadline'],
            $jobData['status'],
            $jobData['featured']
        );
        $insertJob->execute();
        $jobId = $insertJob->insert_id;
        $insertJob->close();
        $action = "inserted";
    }

    $jobCheck->close();
    $conn->commit();

    echo "Job {$action} successfully. Job ID: {$jobId}" . PHP_EOL;
} catch (Throwable $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

exit(0);

