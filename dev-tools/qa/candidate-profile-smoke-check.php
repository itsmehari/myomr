<?php
/**
 * Static smoke check: required files exist for job seeker profile feature.
 * Run: php dev-tools/qa/candidate-profile-smoke-check.php
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$need = [
    '/omr-local-job-listings/candidate-profile-omr.php',
    '/omr-local-job-listings/process-candidate-profile-omr.php',
    '/omr-local-job-listings/includes/job-seeker-profile-cta-strip.php',
    '/dev-tools/migrations/2026-03-31-job-seeker-profiles.sql',
    '/dev-tools/sql/run-job-seeker-profiles-migration.php',
    '/admin/manage-job-seeker-profiles-omr.php',
    '/admin/download-job-seeker-resume-omr.php',
];
$ok = true;
foreach ($need as $rel) {
    $p = $root . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (!is_readable($p)) {
        fwrite(STDERR, "MISSING: $rel\n");
        $ok = false;
    }
}
if ($ok) {
    echo "OK: candidate profile file smoke check passed.\n";
    exit(0);
}
exit(1);
