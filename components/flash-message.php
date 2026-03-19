<?php
/**
 * Site-wide flash messages for public and employer-facing pages.
 * Uses session keys: site_flash_success, site_flash_error, site_flash_info
 * (prefix avoids clash with admin flash_success/flash_error and job application_errors).
 * Include once per page (e.g. via footer or at top of main content).
 */
if (session_status() === PHP_SESSION_NONE) {
    return;
}
$show = false;
$alerts = [];
if (!empty($_SESSION['site_flash_success'])) {
    $alerts[] = ['type' => 'success', 'text' => $_SESSION['site_flash_success']];
    unset($_SESSION['site_flash_success']);
    $show = true;
}
if (!empty($_SESSION['site_flash_error'])) {
    $alerts[] = ['type' => 'danger', 'text' => $_SESSION['site_flash_error']];
    unset($_SESSION['site_flash_error']);
    $show = true;
}
if (!empty($_SESSION['site_flash_info'])) {
    $alerts[] = ['type' => 'info', 'text' => $_SESSION['site_flash_info']];
    unset($_SESSION['site_flash_info']);
    $show = true;
}
if (!$show) {
    return;
}
?>
<div class="omr-flash-wrapper" role="alert" aria-live="polite">
    <div class="container py-2">
        <?php foreach ($alerts as $a): ?>
            <div class="alert alert-<?php echo htmlspecialchars($a['type'], ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show mb-0" role="alert">
                <?php echo htmlspecialchars($a['text'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<style>
.omr-flash-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    pointer-events: none;
}
.omr-flash-wrapper .alert {
    pointer-events: auto;
}
</style>
