<?php
/**
 * SEO Meta Tags Generator for Articles
 * Include this file in article.php to auto-generate SEO tags
 */

if (!isset($article)) {
    // If article is not set, try to get from database
    $slug = isset($_GET['slug']) ? $_GET['slug'] : '';
    if (empty($slug)) {
        http_response_code(404);
        exit;
    }
    
    require_once '../core/omr-connect.php';
    $stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ? AND status = 'published'");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $stmt->close();
}

// Get article data
$article_title = htmlspecialchars($article['title']);
$article_desc = htmlspecialchars($article['summary']);
$article_content = strip_tags(htmlspecialchars($article['content']));
// Use clean URL format for SEO (matches .htaccess rewrite rule)
$article_url = 'https://myomr.in/local-news/' . $article['slug'];
// Only prepend domain for relative paths; full URLs used as-is
$img = $article['image_path'] ?? '/My-OMR-Logo.jpg';
$article_image = (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0)
    ? $img
    : 'https://myomr.in' . (strpos($img, '/') === 0 ? $img : '/' . $img);
$article_date = $article['published_date'];
$article_author = htmlspecialchars($article['author'] ?? 'MyOMR Editorial Team');
$article_category = htmlspecialchars($article['category'] ?? 'Local News');
$article_tags = !empty($article['tags']) ? explode(',', $article['tags']) : [];
?>

<!-- Primary SEO Meta Tags -->
<title><?php echo $article_title; ?> | MyOMR Chennai</title>
<meta name="description" content="<?php echo substr($article_desc, 0, 155); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($article['tags'] ?? 'Chennai, OMR news'); ?>">
<meta name="author" content="<?php echo $article_author; ?>">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">

<!-- Geo / Local SEO (search + AI engines) -->
<meta name="geo.region" content="IN-TN">
<meta name="geo.placename" content="Chennai, Old Mahabalipuram Road, OMR">
<meta name="geo.position" content="12.9716;80.2206">
<meta name="ICBM" content="12.9716, 80.2206">

<!-- Canonical URL -->
<link rel="canonical" href="<?php echo $article_url; ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo $article_title; ?>">
<meta property="og:description" content="<?php echo substr($article_desc, 0, 200); ?>">
<meta property="og:image" content="<?php echo $article_image; ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?php echo $article_title; ?>">
<meta property="og:url" content="<?php echo $article_url; ?>">
<meta property="og:site_name" content="MyOMR">
<meta property="og:locale" content="en_IN">
<meta property="article:published_time" content="<?php echo date('c', strtotime($article_date)); ?>">
<meta property="article:modified_time" content="<?php echo isset($article['updated_at']) ? date('c', strtotime($article['updated_at'])) : date('c', strtotime($article_date)); ?>">
<meta property="article:author" content="<?php echo $article_author; ?>">
<meta property="article:section" content="<?php echo $article_category; ?>">
<?php if (!empty($article_tags)): ?>
<?php foreach ($article_tags as $tag): ?>
<meta property="article:tag" content="<?php echo htmlspecialchars(trim($tag)); ?>">
<?php endforeach; ?>
<?php endif; ?>

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo $article_title; ?>">
<meta name="twitter:description" content="<?php echo substr($article_desc, 0, 200); ?>">
<meta name="twitter:image" content="<?php echo $article_image; ?>">
<meta name="twitter:image:alt" content="<?php echo $article_title; ?>">
<meta name="twitter:site" content="@MyomrNews">
<meta name="twitter:creator" content="@MyomrNews">
<meta name="twitter:url" content="<?php echo $article_url; ?>">

<!-- Additional SEO -->
<meta name="news_keywords" content="<?php echo htmlspecialchars($article['tags'] ?? ''); ?>">
<meta name="article:tag" content="<?php echo htmlspecialchars($article['tags'] ?? ''); ?>">
<link rel="alternate" type="application/rss+xml" href="https://myomr.in/rss.xml">

<!-- Structured Data (JSON-LD) for Rich Snippets + Geo + AI-friendly -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "<?php echo addslashes($article_title); ?>",
  "description": "<?php echo addslashes(substr($article_desc, 0, 200)); ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo $article_image; ?>",
    "width": 1200,
    "height": 630
  },
  "datePublished": "<?php echo date('c', strtotime($article_date)); ?>",
  "dateModified": "<?php echo isset($article['updated_at']) ? date('c', strtotime($article['updated_at'])) : date('c', strtotime($article_date)); ?>",
  "author": {
    "@type": "Organization",
    "name": "<?php echo addslashes($article_author); ?>",
    "url": "https://myomr.in"
  },
  "publisher": {
    "@type": "Organization",
    "name": "MyOMR",
    "logo": {
      "@type": "ImageObject",
      "url": "https://myomr.in/My-OMR-Logo.jpg",
      "width": 600,
      "height": 60
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo $article_url; ?>"
  },
  "articleSection": "<?php echo addslashes($article_category); ?>",
  "keywords": "<?php echo !empty($article['tags']) ? addslashes($article['tags']) : 'Chennai, OMR news'; ?>",
  "articleBody": "<?php echo addslashes(substr($article_content, 0, 500)); ?>",
  "inLanguage": "en-IN",
  "copyrightYear": "<?php echo date('Y', strtotime($article_date)); ?>",
  "copyrightHolder": {
    "@type": "Organization",
    "name": "MyOMR"
  },
  "about": {
    "@type": "Place",
    "name": "Old Mahabalipuram Road, Chennai",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Chennai",
      "addressRegion": "Tamil Nadu",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "12.9716",
      "longitude": "80.2206"
    }
  },
  "dateline": "Chennai, Tamil Nadu",
  "backstory": "Local news and updates for residents and businesses along Old Mahabalipuram Road (OMR), Chennai."
}
</script>

<!-- Breadcrumb Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "https://myomr.in"
  }, {
    "@type": "ListItem",
    "position": 2,
    "name": "Local News",
    "item": "https://myomr.in/local-news/news-highlights-from-omr-road.php"
  }, {
    "@type": "ListItem",
    "position": 3,
    "name": "<?php echo addslashes($article_title); ?>",
    "item": "<?php echo $article_url; ?>"
  }]
}
</script>

<!-- Bing Verification (optional) -->
<meta name="msvalidate.01" content="">
<!-- Google Search Console Verification (add your code) -->
<meta name="google-site-verification" content="0Z9Td8zvnhZsgWaItiaCGgpQ3M3SsOr_oiAIkCcDmqE">

