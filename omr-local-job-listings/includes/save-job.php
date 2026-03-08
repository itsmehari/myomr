<?php
/**
 * Save / remove job from session (no login required for now)
 * POST JSON: { job_id: int, action: "save"|"remove" }
 */
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Method not allowed']));
}

$raw = json_decode(file_get_contents('php://input'), true);
$job_id = (int)($raw['job_id'] ?? 0);
$action = in_array($raw['action'] ?? '', ['save', 'remove']) ? $raw['action'] : 'save';

if ($job_id <= 0) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Invalid job ID']));
}

if (!isset($_SESSION['saved_jobs'])) {
    $_SESSION['saved_jobs'] = [];
}

if ($action === 'save') {
    $_SESSION['saved_jobs'][$job_id] = time();
} else {
    unset($_SESSION['saved_jobs'][$job_id]);
}

echo json_encode([
    'ok'    => true,
    'action' => $action,
    'job_id' => $job_id,
    'total'  => count($_SESSION['saved_jobs']),
]);
