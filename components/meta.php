<!-- Meta/SEO Tags Component -->
<?php
// Load URL helper functions if not already loaded
if (!function_exists('get_canonical_url')) {
    require_once __DIR__ . '/../core/url-helpers.php';
}
if (!function_exists('myomr_get_primary_hub_links')) {
    require_once __DIR__ . '/../core/site-navigation.php';
}

// Generate canonical URL - use provided canonical_url or generate from current page
$canonical_url = isset($canonical_url) ? $canonical_url : get_canonical_url();
?>
<title><?php echo isset($page_title) ? $page_title : 'My OMR - Old Mahabalipuram Road News, Events, Images, Happenings, Search, Business Website'; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo isset($page_description) ? $page_description : 'News, Events, Happenings in and around Old Mahabalipuram Road, Chennai.'; ?>">
<meta name="keywords" content="<?php echo isset($page_keywords) ? $page_keywords : 'Old Mahabalipuram Road, OMR Road, OMR News, My OMR, Perungudi, SRP Tools, Kandhanchavadi, Thuraipakkam, Karapakkam, Mettukuppam, Dollar Stop, Sholinganallur, Navalur, Kelambakkam.'; ?>">
<meta name="author" content="Krishnan">
<!-- Canonical URL - Prevents duplicate content issues -->
<link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
<meta property="og:type" content="<?php echo isset($og_type) ? htmlspecialchars($og_type) : 'article'; ?>" />
<meta property="og:title" content="<?php echo isset($og_title) ? $og_title : 'Old Mahabalipuram Road news, Search, Events, Happenings, Photographs'; ?>" />
<meta property="og:description" content="<?php echo isset($og_description) ? $og_description : 'home page of old mahabalipuram road, OMR website,which hosts several features for its user base, especially from chennai, Tamilnadu.'; ?>" />
<meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'https://myomr.in/My-OMR-Logo.png'; ?>" />
<meta property="og:url" content="<?php echo isset($og_url) ? $og_url : $canonical_url; ?>" />
<meta property="og:site_name" content="My OMR Old Mahabalipuram Road." />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="ta_IN" />
<meta name="twitter:card" content="<?php echo isset($twitter_card) ? htmlspecialchars($twitter_card) : 'summary_large_image'; ?>">
<meta name="twitter:title" content="<?php echo isset($twitter_title) ? $twitter_title : 'My OMR - Old Mahabalipuram Road News, Events, Images, Happenings, Search, Business Website'; ?>">
<meta name="twitter:description" content="<?php echo isset($twitter_description) ? $twitter_description : 'in this page you can find news, events, images, happenings, updates, local business information of OMR Road, Old Mahabalipuram Road and its Surroundings'; ?>">
<meta name="twitter:image" content="<?php echo isset($twitter_image) ? $twitter_image : 'https://myomr.in/My-OMR-Logo.png'; ?>">
<meta name="twitter:site" content="@MyomrNews">
<meta name="twitter:creator" content="@MyomrNews">
<!-- End Meta/SEO Tags --> 
<script type="application/ld+json">
<?php
$org = [
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => 'MyOMR',
  'url' => 'https://myomr.in/',
  'logo' => 'https://myomr.in/My-OMR-Logo.png',
  'sameAs' => [
    'https://www.facebook.com/myomrCommunity',
    'https://www.instagram.com/myomrcommunity/',
    'https://x.com/MyomrNews'
  ]
];
echo json_encode($org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>
<script type="application/ld+json">
<?php
$navGraph = ['@context' => 'https://schema.org', '@graph' => []];
foreach (myomr_get_primary_hub_links() as $hubLink) {
  $path = (string)($hubLink['path'] ?? '');
  if ($path === '') {
    continue;
  }
  $navGraph['@graph'][] = [
    '@type' => 'SiteNavigationElement',
    'name' => (string)($hubLink['label'] ?? ''),
    'url' => 'https://myomr.in' . ($path === '/' ? '/' : $path)
  ];
}
echo json_encode($navGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>
<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
<script type="application/ld+json">
<?php
$items = [];
$pos = 1;
foreach ($breadcrumbs as $crumb) {
  // Accept [url, name] or ['@id'=>url, 'name'=>name]
  if (is_array($crumb)) {
    if (isset($crumb['@id']) && isset($crumb['name'])) {
      $items[] = ['@type'=>'ListItem','position'=>$pos++,'item'=>['@id'=>$crumb['@id'],'name'=>$crumb['name']]];
    } elseif (isset($crumb[0]) && isset($crumb[1])) {
      $items[] = ['@type'=>'ListItem','position'=>$pos++,'item'=>['@id'=>$crumb[0],'name'=>$crumb[1]]];
    }
  }
}
if (!empty($items)) {
  $crumbs = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $items
  ];
  echo json_encode($crumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
?>
</script>
<?php endif; ?>