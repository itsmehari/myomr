<?php
/**
 * English Article: Find Your BLO Officer - Shozhinganallur
 * This file loads from database via article.php router system
 * OR can be used as standalone if needed
 */

// If accessed directly, redirect to article.php router
if (!isset($_GET['direct'])) {
    header('Location: /local-news/article.php?slug=find-your-blo-officer-shozhinganallur-electoral-roll-revision');
    exit;
}

// Direct access content (fallback)
include '../weblog/log.php';
include '../core/omr-connect.php';

$page_title = 'Find Your Block Level Officer (BLO) - AC Shozhinganallur Electoral Roll Revision | MyOMR';
$page_description = 'Complete BLO contact details for AC Shozhinganallur now available online. Search by location, part number, or officer name to find your polling station BLO.';
$page_keywords = 'BLO search, Shozhinganallur, election officer, polling station, AC 27, OMR election, electoral roll revision';

// Try to load from database
$slug = 'find-your-blo-officer-shozhinganallur-electoral-roll-revision';
$stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ? AND status = 'published'");
if ($stmt) {
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $stmt->close();
    
    if ($article) {
        // Redirect to article.php for proper display
        header('Location: /local-news/article.php?slug=' . $slug);
        exit;
    }
}

// Fallback content if not in database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/meta.php'; ?>
    <?php include '../components/analytics.php'; ?>
    <?php include '../components/head-resources.php'; ?>
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../components/main-nav.php'; ?>
    <div class="container" style="max-width: 900px; padding: 2rem 1rem;">
        <h1>Article not found in database. Please use the article router system.</h1>
        <p><a href="/local-news/">Back to News</a></p>
    </div>
    <?php include '../components/footer.php'; ?>
</body>
</html>
<?php
if ($conn) {
    $conn->close();
}
?>

