<?php
/**
 * Insert Office Assistant job listing – Virugambakkam.
 *
 * Job details: Office Assistant (male or female), computer operating,
 * mechanical field knowledge (basic), accounts as added qualifications.
 * Qualification: Degree. Place: Virugambakkam. Contact: Shankar +91 98412 53800.
 *
 * Usage:
 *   php dev-tools/jobs/insert_office_assistant_virugambakkam.php
 *
 * For remote live DB: set DB_HOST=myomr.in (or script will try localhost, tunnel, myomr.in)
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function connectDb(): mysqli
{
    $username = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
    $password = getenv('DB_PASS') ?: 'myomr@123';
    $database = getenv('DB_NAME') ?: 'metap8ok_myomr';
    $hosts = ['localhost:3306', '127.0.0.1:3307', 'myomr.in:3306'];
    $dbHost = getenv('DB_HOST');
    if ($dbHost && $dbHost !== '') {
        array_unshift($hosts, $dbHost . ':' . (getenv('DB_PORT') ?: '3306'));
    }

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $username, $password, $database);
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
        'company_name'   => 'Hiring in Virugambakkam',
        'contact_person' => 'Shankar',
        'email'          => 'shankar.9841253800@myomr.in',
        'phone'          => '9841253800',
        'address'        => 'Virugambakkam, Chennai',
    ];

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
        'title'        => 'Office Assistant (Male or Female)',
        'category'     => 'customer-service',
        'job_type'     => 'Full-time',
        'work_segment' => 'office',
        'location'     => 'Virugambakkam, Chennai',
        'salary'       => 'Not Disclosed',
        'description'  => "Office Assistant required in Virugambakkam. We are looking for a male or female candidate with basic computer operating skills. Knowledge in mechanical field (basic) and accounts will be added qualifications. Degree qualification required.",
        'requirements' => "Required:\n• Computer operating skills\n• Degree qualification\n\nAdded qualifications (preferred):\n• Basic mechanical field knowledge\n• Accounts knowledge",
        'benefits'     => 'Direct employer contact. Call for details.',
    ];

    $checkJob = $conn->prepare("SELECT id FROM job_postings WHERE title = ? AND location = ? ORDER BY id DESC LIMIT 1");
    $checkJob->bind_param("ss", $job['title'], $job['location']);
    $checkJob->execute();
    $jobRow = $checkJob->get_result()->fetch_assoc();

    $hasWorkSegment = false;
    try {
        $r = $conn->query("SHOW COLUMNS FROM job_postings LIKE 'work_segment'");
        $hasWorkSegment = ($r && $r->num_rows > 0);
    } catch (Throwable $e) {
        // ignore
    }

    if ($jobRow) {
        $jobId = (int)$jobRow['id'];
        $sql = $hasWorkSegment
            ? "UPDATE job_postings SET employer_id = ?, category = ?, job_type = ?, work_segment = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = DATE_ADD(CURDATE(), INTERVAL 45 DAY), status = 'approved', featured = 0, updated_at = NOW() WHERE id = ?"
            : "UPDATE job_postings SET employer_id = ?, category = ?, job_type = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = DATE_ADD(CURDATE(), INTERVAL 45 DAY), status = 'approved', featured = 0, updated_at = NOW() WHERE id = ?";
        $updJob = $conn->prepare($sql);
        if ($hasWorkSegment) {
            $updJob->bind_param("issssssssi", $employerId, $job['category'], $job['job_type'], $job['work_segment'], $job['salary'],
                $job['description'], $job['requirements'], $job['benefits'], $jobId);
        } else {
            $updJob->bind_param("isssssssi", $employerId, $job['category'], $job['job_type'], $job['salary'],
                $job['description'], $job['requirements'], $job['benefits'], $jobId);
        }
        $updJob->execute();
        $updJob->close();
        echo "Job updated. ID: {$jobId}\n";
    } else {
        if ($hasWorkSegment) {
            $insJob = $conn->prepare("
                INSERT INTO job_postings (employer_id, title, category, job_type, work_segment, location, salary_range, description, requirements, benefits, application_deadline, status, featured)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'approved', 0)
            ");
            $insJob->bind_param("isssssssss", $employerId, $job['title'], $job['category'], $job['job_type'], $job['work_segment'], $job['location'],
                $job['salary'], $job['description'], $job['requirements'], $job['benefits']);
        } else {
            $insJob = $conn->prepare("
                INSERT INTO job_postings (employer_id, title, category, job_type, location, salary_range, description, requirements, benefits, application_deadline, status, featured)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'approved', 0)
            ");
            $insJob->bind_param("issssssss", $employerId, $job['title'], $job['category'], $job['job_type'], $job['location'],
                $job['salary'], $job['description'], $job['requirements'], $job['benefits']);
        }
        $insJob->execute();
        $jobId = (int)$insJob->insert_id;
        $insJob->close();
        echo "Job inserted. ID: {$jobId}\n";
    }
    $checkJob->close();

    $conn->commit();

    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $job['title']), '-'));
    echo "Job URL: https://myomr.in/omr-local-job-listings/job/{$jobId}/{$slug}\n";
    echo "Contact: Shankar +91 98412 53800\n";
    echo "Done.\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        try { $conn->rollback(); } catch (Throwable $ignored) {}
    }
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}
