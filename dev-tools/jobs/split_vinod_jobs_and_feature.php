<?php
/**
 * Split combined Vinod job into two jobs and adjust featured flags.
 *
 * Actions:
 * 1) Locate combined job (id=16 preferred, fallback by title/location)
 * 2) Update combined job into "Tea Master + Cook" featured post
 * 3) Insert/update separate "Billing Executive (Part-time)" featured post
 * 4) Set all jobs featured=0, then set featured=1 for:
 *    - Tea Master + Cook
 *    - Billing Executive
 *    - Femtosoft Technologies Internship Program
 *
 * Usage:
 *   php dev-tools/jobs/split_vinod_jobs_and_feature.php
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
    throw new RuntimeException('Unable to connect to DB.');
}

try {
    $conn = connectDb();
    $conn->begin_transaction();

    // Resolve employer (Mr Vinod).
    $employerId = 0;
    $empStmt = $conn->prepare("SELECT id FROM employers WHERE phone = ? ORDER BY id DESC LIMIT 1");
    $phone = '9176797745';
    $empStmt->bind_param("s", $phone);
    $empStmt->execute();
    $empRow = $empStmt->get_result()->fetch_assoc();
    if ($empRow) {
        $employerId = (int)$empRow['id'];
    }
    $empStmt->close();

    if ($employerId <= 0) {
        throw new RuntimeException('Critical: Employer Mr Vinod not found. Stopping for safety.');
    }

    // Locate combined job.
    $combinedJobId = 0;
    $combinedIdPreferred = 16;
    $checkCombined = $conn->prepare("SELECT id FROM job_postings WHERE id = ? LIMIT 1");
    $checkCombined->bind_param("i", $combinedIdPreferred);
    $checkCombined->execute();
    $rowCombined = $checkCombined->get_result()->fetch_assoc();
    $checkCombined->close();

    if ($rowCombined) {
        $combinedJobId = (int)$rowCombined['id'];
    } else {
        $titleLike = '%Hotel Restaurant Staff Hiring in Sholinganallur%';
        $locLike = '%Sholinganallur%';
        $fallback = $conn->prepare("SELECT id FROM job_postings WHERE title LIKE ? AND location LIKE ? ORDER BY id DESC LIMIT 1");
        $fallback->bind_param("ss", $titleLike, $locLike);
        $fallback->execute();
        $fallbackRow = $fallback->get_result()->fetch_assoc();
        $fallback->close();
        if ($fallbackRow) {
            $combinedJobId = (int)$fallbackRow['id'];
        }
    }

    if ($combinedJobId <= 0) {
        throw new RuntimeException('Critical: Combined Vinod job not found. Stopping for safety.');
    }

    // Job A: Tea Master + Cook (update combined job row)
    $jobATitle = 'Tea Master + Cook Hiring in Sholinganallur Hotel Restaurant';
    $jobADescription = "Immediate hiring for a Hotel Restaurant in Sholinganallur, OMR Chennai.\n\nOpen Roles (combined in this post):\n1) Tea Master - Timing: 6:00 AM to 2:00 PM - Salary: ₹16,000/month\n2) Cook - Tiffen and Lunch cooking - Salary: ₹16,000 to ₹18,000/month\n\nDirect employer contact: Mr Vinod (9176797745).";
    $jobARequirements = "Tea/Cook role details:\n- Experience in tea preparation and/or tiffen/lunch cooking preferred\n- Punctuality and hygiene are mandatory\n- Immediate joiners preferred";
    $jobABenefits = "Local job in Sholinganallur, stable work timing, direct employer hiring.";

    $updA = $conn->prepare("
        UPDATE job_postings
        SET employer_id = ?, title = ?, category = 'hospitality', job_type = 'Full-time', work_segment = 'manpower',
            location = 'Sholinganallur, OMR, Chennai',
            salary_range = '₹16,000 - ₹18,000 per month',
            description = ?, requirements = ?, benefits = ?,
            application_deadline = DATE_ADD(CURDATE(), INTERVAL 45 DAY),
            status = 'approved',
            updated_at = NOW()
        WHERE id = ?
    ");
    $updA->bind_param("issssi", $employerId, $jobATitle, $jobADescription, $jobARequirements, $jobABenefits, $combinedJobId);
    $updA->execute();
    $updA->close();

    // Job B: Billing Executive part-time (insert or update)
    $jobBTitle = 'Billing Executive (Part-time) Hiring in Sholinganallur Hotel Restaurant';
    $jobBDesc = "Immediate requirement for Billing Executive (Part-time) in a Hotel Restaurant at Sholinganallur, OMR Chennai.\n\nTiming: 3:00 PM to 10:00 PM\nSalary: ₹7,000/month\nNeed candidate with billing/accounts handling ability.\n\nDirect employer contact: Mr Vinod (9176797745).";
    $jobBReq = "Requirements:\n- Basic billing/accounting skills\n- Accuracy in bill entry and cash handling\n- Responsible, punctual, and polite communication";
    $jobBBenefits = "Part-time role, local commute convenience, direct employer hiring.";

    $jobBId = 0;
    $checkB = $conn->prepare("SELECT id FROM job_postings WHERE title = ? AND location = 'Sholinganallur, OMR, Chennai' ORDER BY id DESC LIMIT 1");
    $checkB->bind_param("s", $jobBTitle);
    $checkB->execute();
    $rowB = $checkB->get_result()->fetch_assoc();
    $checkB->close();

    if ($rowB) {
        $jobBId = (int)$rowB['id'];
        $updB = $conn->prepare("
            UPDATE job_postings
            SET employer_id = ?, category = 'hospitality', job_type = 'Part-time', work_segment = 'office',
                salary_range = '₹7,000 per month',
                description = ?, requirements = ?, benefits = ?,
                application_deadline = DATE_ADD(CURDATE(), INTERVAL 45 DAY),
                status = 'approved',
                updated_at = NOW()
            WHERE id = ?
        ");
        $updB->bind_param("isssi", $employerId, $jobBDesc, $jobBReq, $jobBBenefits, $jobBId);
        $updB->execute();
        $updB->close();
    } else {
        $insB = $conn->prepare("
            INSERT INTO job_postings (
                employer_id, title, category, job_type, work_segment, location, salary_range, description, requirements, benefits, application_deadline, status, featured
            ) VALUES (
                ?, ?, 'hospitality', 'Part-time', 'office', 'Sholinganallur, OMR, Chennai', '₹7,000 per month', ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'approved', 0
            )
        ");
        $insB->bind_param("issss", $employerId, $jobBTitle, $jobBDesc, $jobBReq, $jobBBenefits);
        $insB->execute();
        $jobBId = (int)$insB->insert_id;
        $insB->close();
    }

    if ($jobBId <= 0) {
        throw new RuntimeException('Critical: Unable to create/update billing executive job. Stopping.');
    }

    // Resolve Femtosoft internship job.
    $femtosoftId = 0;
    $femLike = '%Femtosoft%Internship%';
    $fem = $conn->prepare("SELECT id FROM job_postings WHERE title LIKE ? ORDER BY id DESC LIMIT 1");
    $fem->bind_param("s", $femLike);
    $fem->execute();
    $femRow = $fem->get_result()->fetch_assoc();
    $fem->close();
    if ($femRow) {
        $femtosoftId = (int)$femRow['id'];
    }

    // Reset all featured to normal.
    $conn->query("UPDATE job_postings SET featured = 0");

    // Set requested featured posts.
    $setFeatured = $conn->prepare("UPDATE job_postings SET featured = 1 WHERE id = ?");
    $setFeatured->bind_param("i", $combinedJobId);
    $setFeatured->execute();
    $setFeatured->bind_param("i", $jobBId);
    $setFeatured->execute();
    if ($femtosoftId > 0) {
        $setFeatured->bind_param("i", $femtosoftId);
        $setFeatured->execute();
    } else {
        echo "Warning: Femtosoft internship job not found by title pattern.\n";
    }
    $setFeatured->close();

    $conn->commit();

    echo "Done.\n";
    echo "Job A (Tea+Cook) ID: {$combinedJobId}\n";
    echo "Job B (Billing Exec) ID: {$jobBId}\n";
    echo "Femtosoft featured ID: " . ($femtosoftId > 0 ? (string)$femtosoftId : 'NOT FOUND') . "\n";
    echo "All other jobs set to featured=0.\n";

    exit(0);
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        try { $conn->rollback(); } catch (Throwable $ignored) {}
    }
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}

