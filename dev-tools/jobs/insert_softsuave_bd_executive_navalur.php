<?php
/**
 * Insert Soft Suave — Business Development Executive (Navalur, OMR).
 * Restructured from Naukri-sourced JSON (job_id myomr_softsuave_bd_executive_2026_01).
 *
 * MyOMR mapping:
 *   employers: company, contact, careers email, Navalur office address, website
 *   job_postings: title, category sales-marketing, Full-time, office segment, OMR location,
 *   description (role + responsibilities + context), requirements (skills + education),
 *   benefits (compensation note + how to apply), deadline 2026-04-30
 *
 * Usage:
 *   php dev-tools/jobs/insert_softsuave_bd_executive_navalur.php
 *
 * Remote live DB: DB_HOST=myomr.in (optional DB_PORT, DB_USER, DB_PASS, DB_NAME)
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

function jobPostingsHasColumn(mysqli $conn, string $col): bool
{
    $c = $conn->real_escape_string($col);
    $r = $conn->query("SHOW COLUMNS FROM job_postings LIKE '{$c}'");
    return $r && $r->num_rows > 0;
}

/**
 * @return array{employer_id: int, job_id: int, url: string}
 */
function upsertEmployerAndJob(mysqli $conn, array $employer, array $job, bool $hasWorkSegment): array
{
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

    if (jobPostingsHasColumn($conn, 'experience_level')) {
        $exp = $job['experience_level'] ?? 'Any';
        $st = $conn->prepare('UPDATE job_postings SET experience_level = ? WHERE id = ?');
        $st->bind_param('si', $exp, $jobId);
        $st->execute();
        $st->close();
        echo "Set experience_level = {$exp}\n";
    }
    if (jobPostingsHasColumn($conn, 'is_remote')) {
        $remote = (int) ($job['is_remote'] ?? 0);
        $st = $conn->prepare('UPDATE job_postings SET is_remote = ? WHERE id = ?');
        $st->bind_param('ii', $remote, $jobId);
        $st->execute();
        $st->close();
        echo "Set is_remote = {$remote}\n";
    }

    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($job['title'])), '-'));
    $url = "https://myomr.in/omr-local-job-listings/job/{$jobId}/{$slug}";

    return ['employer_id' => $employerId, 'job_id' => $jobId, 'url' => $url];
}

try {
    $conn = connectDb();

    $r = $conn->query("SHOW COLUMNS FROM job_postings LIKE 'work_segment'");
    $hasWorkSegment = ($r && $r->num_rows > 0);

    $conn->begin_transaction();

    $employer = [
        'company_name'   => 'Soft Suave Technologies',
        'contact_person' => 'HR / Careers',
        'email'          => 'careers@softsuave.com',
        'phone'          => '8015159981',
        'address'        => 'SSPDL Alpha City, Rajiv Gandhi Salai (OMR), Navalur, Chennai 600130',
        'website'        => 'https://www.softsuave.com',
    ];

    $description = <<<'TXT'
Soft Suave Technologies is an offshore software development and AI solutions company (web, mobile, cloud, IT staffing) serving global clients. This role is based at the Navalur office on the OMR IT corridor (Sholinganallur–Kelambakkam stretch).

Role overview: Drive business growth through lead generation, client acquisition, and end-to-end sales cycles in the IT services space. Work mode: on-site. Shift: international / night shift may be required.

Key responsibilities:
• Generate new business opportunities
• Outbound sales (cold calls, emails, follow-ups)
• Prepare proposals and respond to RFPs
• Manage the full sales lifecycle from lead to closure
• Build and maintain client relationships
• Market research and competitor analysis
• Coordinate with internal delivery and tech teams
• Present solutions to international clients

Additional context: Company also has a registered office at Lake View Estate, Kundrathur Main Road, Porur, Chennai. Listing summarized for OMR job seekers; verify details on the employer careers channel.
TXT;

    $requirements = <<<'TXT'
Experience: 4–8 years (IT services / B2B sales preferred).

Education: Bachelor’s degree (any discipline).

Skills and traits:
• Strong communication and negotiation skills
• B2B sales experience (IT services preferred)
• Lead generation and prospecting
• Client relationship management
• CRM tools
• Market research and analysis
• Target-driven mindset
TXT;

    $benefits = <<<'TXT'
Salary: Not disclosed by employer. Performance-based incentives may apply.

How to apply: Email careers@softsuave.com or call HR +91 8015159981. General line +91 9952732708. Source reference: public listing (Naukri); posted on MyOMR for Chennai OMR corridor visibility.
TXT;

    $job = [
        /* Title shaped so createSlug() → business-development-executive-soft-suave-navalur-chennai */
        'title'                 => 'Business Development Executive - Soft Suave Navalur Chennai',
        'category'              => 'sales-marketing',
        'job_type'              => 'Full-time',
        'work_segment'          => 'office',
        'location'              => 'Navalur, OMR, Chennai',
        'salary'                => 'Not Disclosed',
        'description'           => trim($description),
        'requirements'          => trim($requirements),
        'benefits'              => trim($benefits),
        'application_deadline'  => '2026-04-30',
        'experience_level'      => 'Senior',
        'is_remote'             => 0,
    ];

    $out = upsertEmployerAndJob($conn, $employer, $job, $hasWorkSegment);
    echo "Listing: {$out['url']}\n";

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
