<?php
/**
 * Election 2026 newsletter signup – Get OMR election updates (dates, candidates, reminders).
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Election 2026 Updates – Subscribe | MyOMR';
$page_description = 'Subscribe for OMR election 2026 updates: key dates, candidates and reminders.';
$page_keywords = 'election 2026 subscribe, OMR election updates';
$canonical_url = 'https://myomr.in/elections-2026/newsletter.php';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Subscribe'],
];

$message = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Option 1: store in existing omr_newsletter_subscribers with source=elections2026
        if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
            $stmt = $conn->prepare("INSERT INTO omr_newsletter_subscribers (email, locality, source_page) VALUES (?, ?, 'elections-2026')");
            if ($stmt) {
                $loc = 'OMR';
                $stmt->bind_param('ss', $email, $loc);
                if ($stmt->execute()) {
                    $message = 'Thank you. You are subscribed for election 2026 updates.';
                    $success = true;
                } else {
                    if ($conn->errno === 1062) {
                        $message = 'This email is already subscribed.';
                        $success = true;
                    } else {
                        $message = 'Subscription could not be saved. Please try again.';
                    }
                }
                $stmt->close();
            }
        } else {
            $message = 'Thank you. We will add you to the list.';
            $success = true;
        }
    } else {
        $message = 'Please enter a valid email address.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="modern-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
    <header class="bg-light py-4 border-bottom">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="https://myomr.in/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Subscribe</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Get election updates</h1>
            <p class="text-muted mb-0">We will send you key dates, candidate updates and reminders for OMR election 2026.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                    <?php if (!$success): ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" id="email" name="email" class="form-control" required placeholder="you@example.com">
                            </div>
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>
                    <?php endif; ?>
                    <p class="mt-3 small text-muted">We will only use your email for election 2026 updates (e.g. poll day reminder).</p>
                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
