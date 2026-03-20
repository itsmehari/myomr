<?php
/**
 * Twilio SMS — env: MYOMR_TWILIO_ACCOUNT_SID, MYOMR_TWILIO_AUTH_TOKEN, MYOMR_TWILIO_FROM (E.164)
 */
function ca_twilio_config(): ?array {
    $sid = getenv('MYOMR_TWILIO_ACCOUNT_SID');
    $token = getenv('MYOMR_TWILIO_AUTH_TOKEN');
    $from = getenv('MYOMR_TWILIO_FROM');
    if (!$sid || !$token || !$from) {
        return null;
    }
    return ['sid' => $sid, 'token' => $token, 'from' => $from];
}

function ca_send_sms_twilio(string $to_e164, string $body): bool {
    $cfg = ca_twilio_config();
    if (!$cfg) {
        return false;
    }
    $url = 'https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($cfg['sid']) . '/Messages.json';
    $post = http_build_query(['To' => $to_e164, 'From' => $cfg['from'], 'Body' => $body]);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_USERPWD => $cfg['sid'] . ':' . $cfg['token'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
    ]);
    $raw = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code >= 200 && $code < 300;
}
