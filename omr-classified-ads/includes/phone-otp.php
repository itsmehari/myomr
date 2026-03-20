<?php
/**
 * Phone OTP helpers (India-first normalization).
 */
function ca_normalize_phone_in(string $input): ?string {
    $d = preg_replace('/\D/', '', $input);
    if ($d === '') {
        return null;
    }
    if (strlen($d) === 10 && $d[0] >= '6' && $d[0] <= '9') {
        return '+91' . $d;
    }
    if (strlen($d) === 12 && substr($d, 0, 2) === '91') {
        return '+' . $d;
    }
    if (strlen($d) >= 11 && $d[0] === '0') {
        return ca_normalize_phone_in(substr($d, 1));
    }
    if ($d[0] === '+') {
        return null;
    }
    if (strlen($d) >= 11) {
        return '+' . $d;
    }
    return null;
}

function ca_synthetic_email_for_phone(string $e164): string {
    $digits = preg_replace('/\D/', '', $e164);
    return 'phone_' . $digits . '@classified-ads.myomr.in';
}

/** Rate: max $max sends per phone per hour */
function ca_otp_rate_ok(mysqli $conn, string $phone_e164, int $max = 5): bool {
    $stmt = $conn->prepare(
        'SELECT COUNT(*) AS c FROM omr_classified_ads_phone_otp WHERE phone_e164 = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)'
    );
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $phone_e164);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int) ($row['c'] ?? 0) < $max;
}

function ca_otp_purge_expired(mysqli $conn): void {
    $conn->query('DELETE FROM omr_classified_ads_phone_otp WHERE expires_at < NOW()');
}
