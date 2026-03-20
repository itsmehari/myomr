<?php
/**
 * Linked identities (Google sub, phone E.164) for omr_classified_ads_users.
 */
function ca_link_identity(mysqli $conn, int $user_id, string $provider, string $provider_uid): void {
    $stmt = $conn->prepare(
        'INSERT IGNORE INTO omr_classified_ads_linked_identities (user_id, provider, provider_uid) VALUES (?,?,?)'
    );
    if ($stmt) {
        $stmt->bind_param('iss', $user_id, $provider, $provider_uid);
        $stmt->execute();
    }
}
