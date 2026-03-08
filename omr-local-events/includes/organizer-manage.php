<?php
require_once __DIR__ . '/../../core/app-secrets.php';

function eventsGenerateManageToken(int $submissionId, string $organizerEmail): string {
    $msg = $submissionId . '|' . strtolower(trim($organizerEmail));
    return hash_hmac('sha256', $msg, (string)MYOMR_EVENTS_MANAGE_SECRET);
}

function eventsVerifyManageToken(int $submissionId, string $organizerEmail, string $token): bool {
    $expected = eventsGenerateManageToken($submissionId, $organizerEmail);
    return hash_equals($expected, (string)$token);
}


