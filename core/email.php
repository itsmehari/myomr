<?php
/**
 * Lightweight email helper for MyOMR
 * Uses PHP mail() by default; can be swapped to PHPMailer later.
 */

function sendEmail(string $to, string $subject, string $htmlBody, string $from = 'no-reply@myomr.in', string $fromName = 'MyOMR') : bool {
    $boundary = md5(uniqid((string)time(), true));
    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . sprintf('%s <%s>', $fromName, $from);
    $headers[] = 'Reply-To: ' . $from;
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    $cleanSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $ok = @mail($to, $cleanSubject, $htmlBody, implode("\r\n", $headers));
    if (!$ok) {
        error_log('sendEmail(): mail() failed to ' . $to . ' subject=' . $subject);
    }
    return $ok;
}

function renderEmailTemplate(string $title, string $bodyHtml): string {
    return '<!doctype html><html><head><meta charset="utf-8"><title>' .
           htmlspecialchars($title) .
           '</title></head><body style="font-family:Segoe UI,Arial,sans-serif;line-height:1.5;color:#111">' .
           '<div style="max-width:640px;margin:20px auto;padding:16px;border:1px solid #e5e7eb;border-radius:8px">' .
           $bodyHtml .
           '<hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0" />' .
           '<p style="font-size:12px;color:#6b7280;margin:0">This is an automated message from MyOMR.</p>' .
           '</div></body></html>';
}


