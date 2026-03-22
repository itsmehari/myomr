<?php
/**
 * Insert two research-gated jobs: IDAM Creative Associate (Internship) + Digiclarity Payment Poster.
 * Mirrors insert_office_assistant_virugambakkam.php (employer upsert + job insert/update).
 *
 * Usage:
 *   php dev-tools/jobs/insert_idam_digiclarity_research_jobs.php
 *
 * Env: DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME (optional; defaults match other dev-tools/jobs scripts).
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

/**
 * @return array{employer_id: int, job_id: int, url: string}
 */
function upsertEmployerAndJob(
    mysqli $conn,
    array $employer,
    array $job,
    bool $hasWorkSegment
): array {
    $checkEmp = $conn->prepare('SELECT id FROM employers WHERE email = ? LIMIT 1');
    $checkEmp->bind_param('s', $employer['email']);
    $checkEmp->execute();
    $empRow = $checkEmp->get_result()->fetch_assoc();

    if ($empRow) {
        $employerId = (int) $empRow['id'];
        $updEmp = $conn->prepare('
            UPDATE employers
            SET company_name = ?, contact_person = ?, phone = ?, address = ?, website = ?, status = \'verified\', updated_at = NOW()
            WHERE id = ?
        ');
        $website = $employer['website'] ?? null;
        $updEmp->bind_param(
            'sssssi',
            $employer['company_name'],
            $employer['contact_person'],
            $employer['phone'],
            $employer['address'],
            $website,
            $employerId
        );
        $updEmp->execute();
        $updEmp->close();
        echo "Employer updated. ID: {$employerId} ({$employer['email']})\n";
    } else {
        $insEmp = $conn->prepare('
            INSERT INTO employers (company_name, contact_person, email, phone, address, website, status, plan_type)
            VALUES (?, ?, ?, ?, ?, ?, \'verified\', \'free\')
        ');
        $website = $employer['website'] ?? null;
        $insEmp->bind_param(
            'ssssss',
            $employer['company_name'],
            $employer['contact_person'],
            $employer['email'],
            $employer['phone'],
            $employer['address'],
            $website
        );
        $insEmp->execute();
        $employerId = (int) $insEmp->insert_id;
        $insEmp->close();
        echo "Employer inserted. ID: {$employerId} ({$employer['email']})\n";
    }
    $checkEmp->close();

    $checkJob = $conn->prepare('SELECT id FROM job_postings WHERE title = ? AND location = ? ORDER BY id DESC LIMIT 1');
    $checkJob->bind_param('ss', $job['title'], $job['location']);
    $checkJob->execute();
    $jobRow = $checkJob->get_result()->fetch_assoc();

    $deadline = $job['application_deadline'];

    if ($jobRow) {
        $jobId = (int) $jobRow['id'];
        if ($hasWorkSegment) {
            $sql = 'UPDATE job_postings SET employer_id = ?, category = ?, job_type = ?, work_segment = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = ?, status = \'approved\', featured = 0, updated_at = NOW() WHERE id = ?';
            $updJob = $conn->prepare($sql);
            $updJob->bind_param(
                'issssssssi',
                $employerId,
                $job['category'],
                $job['job_type'],
                $job['work_segment'],
                $job['salary'],
                $job['description'],
                $job['requirements'],
                $job['benefits'],
                $deadline,
                $jobId
            );
        } else {
            $sql = 'UPDATE job_postings SET employer_id = ?, category = ?, job_type = ?, salary_range = ?, description = ?, requirements = ?, benefits = ?,
                application_deadline = ?, status = \'approved\', featured = 0, updated_at = NOW() WHERE id = ?';
            $updJob = $conn->prepare($sql);
            $updJob->bind_param(
                'isssssssi',
                $employerId,
                $job['category'],
                $job['job_type'],
                $job['salary'],
                $job['description'],
                $job['requirements'],
                $job['benefits'],
                $deadline,
                $jobId
            );
        }
        $updJob->execute();
        $updJob->close();
        echo "Job updated. ID: {$jobId}\n";
    } else {
        if ($hasWorkSegment) {
            $insJob = $conn->prepare('
                INSERT INTO job_postings (employer_id, title, category, job_type, work_segment, location, salary_range, description, requirements, benefits, application_deadline, status, featured)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'approved\', 0)
            ');
            $insJob->bind_param(
                'issssssssss',
                $employerId,
                $job['title'],
                $job['category'],
                $job['job_type'],
                $job['work_segment'],
                $job['location'],
                $job['salary'],
                $job['description'],
                $job['requirements'],
                $job['benefits'],
                $deadline
            );
        } else {
            $insJob = $conn->prepare('
                INSERT INTO job_postings (employer_id, title, category, job_type, location, salary_range, description, requirements, benefits, application_deadline, status, featured)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'approved\', 0)
            ');
            $insJob->bind_param(
                'isssssssss',
                $employerId,
                $job['title'],
                $job['category'],
                $job['job_type'],
                $job['location'],
                $job['salary'],
                $job['description'],
                $job['requirements'],
                $job['benefits'],
                $deadline
            );
        }
        $insJob->execute();
        $jobId = (int) $insJob->insert_id;
        $insJob->close();
        echo "Job inserted. ID: {$jobId}\n";
    }
    $checkJob->close();

    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($job['title'])), '-'));
    $url = "https://myomr.in/omr-local-job-listings/job/{$jobId}/{$slug}";

    return ['employer_id' => $employerId, 'job_id' => $jobId, 'url' => $url];
}

try {
    $conn = connectDb();

    $r = $conn->query("SHOW COLUMNS FROM job_postings LIKE 'work_segment'");
    $hasWorkSegment = ($r && $r->num_rows > 0);

    $conn->begin_transaction();

    $idamEmployer = [
        'company_name'   => 'IDAM - The Art And Cultural Space',
        'contact_person' => 'HR',
        'email'          => 'idam.internship@myomr.in',
        'phone'          => '0000000000',
        'address'        => 'Chennai, Tamil Nadu, India',
        'website'        => 'https://www.idamthespace.com/',
    ];

    $idamJob = [
        'title'                 => 'Creative Associate (Internship)',
        'category'              => 'sales-marketing',
        'job_type'              => 'Internship',
        'work_segment'          => 'office',
        'location'              => 'Chennai',
        'salary'                => 'Stipend ₹3,500–4,100/month',
        'description'           => "In-office creative internship at an art and cultural venue in Chennai. Work includes content for social channels and the website, community engagement, light administration, and support for events (including photography and videography). Duration is about four months; stipend in the stated range. Apply via the official internship application on Internshala or the internship form linked from the employer website.",
        'requirements'          => "Chennai-based and available for a full-time in-office internship; strong written English; content and creative skills; familiarity with Canva or similar tools is useful; comfortable supporting events and basic photo/video tasks. Women restarting their career may also apply (per public listing).",
        'benefits'              => 'Stipend (fixed and incentive components per employer). Certificate and letter of recommendation may be offered. Informal dress code per employer.',
        'application_deadline'  => '2026-04-15',
    ];

    $digEmployer = [
        'company_name'   => 'Digiclarity Global Solutions Pvt Ltd',
        'contact_person' => 'Recruitment',
        'email'          => 'recruitment@digiclarity.in',
        'phone'          => '04424540003',
        'address'        => 'CeeDeeYes Tyche Tower, Block 1, 2nd Floor, No. 1 MGR Salai, Veeranam Bypass Road, Kodandarama Nagar, Perungudi, Chennai 600096',
        'website'        => 'https://www.digiclarity.in/',
    ];

    $digJob = [
        'title'                 => 'Payment Poster (Medical Billing)',
        'category'              => 'healthcare',
        'job_type'              => 'Full-time',
        'work_segment'          => 'office',
        'location'              => 'Chennai',
        'salary'                => 'Not Disclosed',
        'description'           => 'Medical billing role at a Chennai-based revenue cycle management company. Focus on accurate payment posting and related billing support. Shift pattern and client details are confirmed with HR.',
        'requirements'          => 'Prior experience in medical billing or payment posting is preferred; high accuracy with numbers; ability to follow defined processes and quality standards.',
        'benefits'              => 'Discuss benefits and salary with HR. Apply by email using the subject line format published on the company careers page.',
        'application_deadline'  => date('Y-m-d', strtotime('+45 days')),
    ];

    $out1 = upsertEmployerAndJob($conn, $idamEmployer, $idamJob, $hasWorkSegment);
    echo "IDAM listing: {$out1['url']}\n\n";

    $out2 = upsertEmployerAndJob($conn, $digEmployer, $digJob, $hasWorkSegment);
    echo "Digiclarity listing: {$out2['url']}\n\n";

    $conn->commit();
    echo "Done.\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        try {
            $conn->rollback();
        } catch (Throwable $ignored) {
        }
    }
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}
