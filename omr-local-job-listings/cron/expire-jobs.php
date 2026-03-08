<?php
/**
 * MyOMR Job Portal — Auto-Expiry Cron Job
 *
 * Closes job postings whose application_deadline has passed.
 * Should be scheduled via cPanel Cron Jobs to run once daily:
 *
 *   0 1 * * * php /home/<cpanel-user>/public_html/omr-local-job-listings/cron/expire-jobs.php
 *
 * Can also be triggered manually:
 *   php expire-jobs.php
 *   php expire-jobs.php --dry-run   (shows what would be closed, changes nothing)
 */

// ── Security: prevent web access, allow CLI only ─────────────────────────────
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('This script may only be run from the command line.');
}

// ── Bootstrap ─────────────────────────────────────────────────────────────────
define('CRON_RUN', true);

// Resolve paths regardless of where the script is called from
$root = dirname(__DIR__, 2); // two levels up from /cron/
require_once $root . '/core/omr-connect.php';
require_once $root . '/core/email.php';

// ── Options ───────────────────────────────────────────────────────────────────
$dry_run = in_array('--dry-run', $argv ?? [], true);
$now     = date('Y-m-d');

echo "[" . date('Y-m-d H:i:s') . "] expire-jobs.php started" . ($dry_run ? ' (DRY RUN)' : '') . "\n";

if (!$conn instanceof mysqli || $conn->connect_error) {
    echo "[ERROR] Database connection failed.\n";
    exit(1);
}

// ── Find jobs to expire ───────────────────────────────────────────────────────
$selectStmt = $conn->prepare(
    "SELECT j.id, j.title, e.email AS employer_email, e.company_name
     FROM job_postings j
     LEFT JOIN employers e ON j.employer_id = e.id
     WHERE j.status = 'approved'
       AND j.application_deadline IS NOT NULL
       AND j.application_deadline != '0000-00-00'
       AND j.application_deadline < ?"
);

if (!$selectStmt) {
    echo "[ERROR] prepare() failed: " . $conn->error . "\n";
    exit(1);
}

$selectStmt->bind_param('s', $now);
$selectStmt->execute();
$result   = $selectStmt->get_result();
$expired  = $result->fetch_all(MYSQLI_ASSOC);
$count    = count($expired);

echo "[INFO] Found {$count} job(s) past deadline.\n";

if ($count === 0) {
    echo "[INFO] Nothing to do. Exiting.\n";
    exit(0);
}

// ── Close expired jobs ────────────────────────────────────────────────────────
if (!$dry_run) {
    $updateStmt = $conn->prepare(
        "UPDATE job_postings SET status = 'closed'
         WHERE status = 'approved'
           AND application_deadline IS NOT NULL
           AND application_deadline != '0000-00-00'
           AND application_deadline < ?"
    );

    if (!$updateStmt) {
        echo "[ERROR] prepare() failed for UPDATE: " . $conn->error . "\n";
        exit(1);
    }

    $updateStmt->bind_param('s', $now);
    $updateStmt->execute();
    $affected = $updateStmt->affected_rows;
    echo "[INFO] Closed {$affected} job(s).\n";
}

// ── Notify employers ─────────────────────────────────────────────────────────
$notified = 0;
foreach ($expired as $job) {
    echo "[INFO] " . ($dry_run ? '[DRY] ' : '') . "Expiring job #{$job['id']}: {$job['title']}\n";

    if (!$dry_run && !empty($job['employer_email'])) {
        $subject = 'Your job listing has closed — ' . $job['title'];
        $body    = renderEmailTemplate(
            'Job listing closed',
            '<h2>Your job listing has automatically closed</h2>'
            . '<p>The application deadline for <strong>' . htmlspecialchars($job['title']) . '</strong> has passed.</p>'
            . '<p>You can re-post or extend the listing by logging into your '
            . '<a href="https://myomr.in/omr-local-job-listings/employer-dashboard-omr.php">employer dashboard</a>.</p>'
        );
        $sent = @sendEmail($job['employer_email'], $subject, $body);
        if ($sent) {
            $notified++;
        }
    }
}

echo "[INFO] Notified {$notified} employer(s).\n";
echo "[" . date('Y-m-d H:i:s') . "] expire-jobs.php finished.\n";
exit(0);
