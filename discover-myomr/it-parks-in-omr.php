<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title          = 'IT Parks in OMR (Old Mahabalipuram Road), Chennai | MyOMR';
$page_description    = 'A practical guide to major IT Parks on OMR, Chennai - significance, economic impact, and detailed park profiles with locations and key tenants.';
$canonical_url       = 'https://myomr.in/discover-myomr/it-parks-in-omr.php';
$og_title            = 'IT Parks in OMR (Old Mahabalipuram Road), Chennai | MyOMR';
$og_description      = 'A practical guide to major IT Parks on OMR - significance, economic impact, and detailed park profiles.';
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = 'https://myomr.in/discover-myomr/it-parks-in-omr.php';
?>
<?php include ROOT_PATH . '/weblog/log.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>

<meta name="robots" content="index, follow">
<style>
body { font-family: 'Poppins', sans-serif; }
.maxw-1280 { max-width: 1280px; }
.lead-intro { font-size: 1.05rem; color:#444; }
.section-title { color:#0583D2; }
.muted { color:#6c757d; }
.park-section h3 { color:#0583D2; }
.badge-locality { background:#4c516D; color:#fff; margin-left:6px; }
</style>

<script type="application/ld+json">
<?php echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'NewsArticle',
  'headline' => 'IT Parks in OMR (Old Mahabalipuram Road), Chennai — Guide',
  'description' => 'A practical guide to major IT Parks on OMR, Chennai — significance, impact, and detailed park profiles.',
  'image' => ['https://myomr.in/My-OMR-Logo.png'],
  'datePublished' => date('c'),
  'dateModified' => date('c'),
  'author' => [
    '@type' => 'Organization',
    'name' => 'MyOMR.in'
  ],
  'publisher' => [
    '@type' => 'Organization',
    'name' => 'MyOMR.in',
    'logo' => [
      '@type' => 'ImageObject',
      'url' => 'https://myomr.in/My-OMR-Logo.png'
    ]
  ],
  'mainEntityOfPage' => [
    '@type' => 'WebPage',
    '@id' => 'https://myomr.in/discover-myomr/it-parks-in-omr.php'
  ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    [ 'position' => 1, '@type' => 'ListItem', 'item' => [ '@id' => 'https://myomr.in/', 'name' => 'Home' ]],
    [ 'position' => 2, '@type' => 'ListItem', 'item' => [ '@id' => 'https://myomr.in/discover-myomr/overview.php', 'name' => 'Discover' ]],
    [ 'position' => 3, '@type' => 'ListItem', 'item' => [ '@id' => 'https://myomr.in/discover-myomr/it-parks-in-omr.php', 'name' => 'IT Parks in OMR' ]]
  ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => [
    [
      '@type' => 'Question',
      'name' => 'Which are the major IT Parks on OMR, Chennai?',
      'acceptedAnswer' => [ '@type' => 'Answer', 'text' => 'Key IT Parks include WTC Chennai (Perungudi), TIDEL Park (Taramani), SIPCOT IT Park (Siruseri), Chennai One IT SEZ (Thoraipakkam), RMZ Millenia (Perungudi), and more.' ]
    ],
    [
      '@type' => 'Question',
      'name' => 'How do I find companies near an IT Park?',
      'acceptedAnswer' => [ '@type' => 'Answer', 'text' => 'Open the IT Park detail page and use the “Companies nearby” link to see IT companies filtered by the park’s locality.' ]
    ]
  ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

</head>
<body>
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content" class="container maxw-1280 py-4">
  <h1 class="text-center section-title">IT Parks in OMR (Rajiv Gandhi Salai), Chennai — A Practical Guide</h1>
  <p class="lead-intro text-center mb-3">Old Mahabalipuram Road (OMR), Chennai’s IT corridor, hosts several major IT Parks and SEZ campuses that power the city’s technology ecosystem.</p>
  <p class="text-center mb-4">
    Want a quick list? Visit the <a href="/omr-listings/it-parks-in-omr.php">IT Parks Directory</a>.
  </p>
  <?php omr_ad_slot('discover-top', '728x90'); ?>

  <h2 class="section-title mt-4">Why IT Parks Matter</h2>
  <ul>
    <li><strong>Infrastructure</strong>: High-speed internet, reliable power, modern workspaces.</li>
    <li><strong>Clustering benefits</strong>: Collaboration, networking, and knowledge-sharing across firms.</li>
    <li><strong>Managed services</strong>: Security, maintenance, and onsite support to reduce operational overhead.</li>
    <li><strong>Talent magnet</strong>: Professional amenities attract and retain skilled workforce.</li>
    <li><strong>Sustainability</strong>: Scalable campuses often adopting green practices.</li>
  </ul>

  <h2 class="section-title mt-4">Economic Contribution</h2>
  <ul>
    <li><strong>Jobs</strong>: Thousands of direct IT/ITES roles plus ancillary employment.</li>
    <li><strong>Investment</strong>: Domestic and foreign capital fueling regional development.</li>
    <li><strong>Revenues</strong>: Corporate and income taxes contributing to public finances.</li>
    <li><strong>Urban growth</strong>: Better connectivity, amenities, and living standards in OMR.</li>
  </ul>

  <hr>

  <?php
  $parks = [
    [
      'name' => 'World Trade Center (WTC Chennai)',
      'location' => 'Perungudi',
      'address' => 'Brigade World Trade Center Sales office 5, 142, Rajiv Gandhi Salai, Chennai, Tamil Nadu 600096',
      'website' => 'https://www.brigaderesidenceswtc.com/',
      'inauguration_year' => '2020',
      'owner' => 'Brigade Group',
      'built_up_area' => '1.8 million sq ft',
      'total_area' => '41.32 acres',
      'companies' => 'Kissflow, Amazon'
    ],
    [
      'name' => 'TIDEL Park',
      'location' => 'Taramani',
      'address' => '4, Rajiv Gandhi Salai, Taramani, Chennai, Tamil Nadu 600113',
      'website' => 'https://www.tidelpark.com/',
      'inauguration_year' => '2000',
      'owner' => 'TIDCO & ELCOT',
      'built_up_area' => '1.8 million sq ft',
      'total_area' => '8 acres',
      'companies' => 'Cisco, Sify, Tenneco, Trimble, Guardian, TCS, EY, CDAC'
    ],
    [
      'name' => 'SIPCOT IT Park (Siruseri)',
      'location' => 'Siruseri',
      'address' => 'Mathematical Institute, OMR, Siruseri, Tamil Nadu 603103',
      'website' => '',
      'inauguration_year' => '1971',
      'owner' => 'TIDCO & ELCOT',
      'built_up_area' => '',
      'total_area' => '782.51 acres',
      'companies' => 'TCS, Cognizant, Hexaware, Sopra Steria, Atos, Changepond'
    ],
    [
      'name' => 'Chennai One IT SEZ',
      'location' => 'Thoraipakkam',
      'address' => 'MCN Nagar Extension, Thoraipakkam, Tamil Nadu 600096',
      'website' => 'https://chennai1.in/',
      'inauguration_year' => '2006',
      'owner' => 'IG3 Infrastructure Ltd',
      'built_up_area' => '3.6 million sq ft',
      'total_area' => '20+ acres',
      'companies' => 'Comcast, TCS, Siemens (Atos), Sutherland, HCL, Polaris, Ford, Prodapt, Wells Fargo'
    ],
    [
      'name' => 'RMZ Millenia',
      'location' => 'Perungudi',
      'address' => 'Campus-1A, MGR Main Rd, Kodandarama Nagar, Perungudi, Chennai, Tamil Nadu 600096',
      'website' => 'https://www.rmzcorp.com/',
      'inauguration_year' => '2018',
      'owner' => 'RMZ (Menda family)',
      'built_up_area' => '2.2 million sq ft',
      'total_area' => '22 acres',
      'companies' => 'Shell, Walmart, JRay, Caterpillar, World Bank, KLA, Ford India, GlobalLogic'
    ],
    [
      'name' => 'ELCOT IT Park',
      'location' => 'Sholinganallur',
      'address' => '',
      'website' => '',
      'inauguration_year' => '',
      'owner' => 'ELCOT',
      'built_up_area' => '',
      'total_area' => '',
      'companies' => ''
    ],
    [
      'name' => 'CeeDeeYes Business Park',
      'location' => 'OMR (Chennai)',
      'address' => '',
      'website' => 'http://ceedeeyesbusinesspark.com/',
      'inauguration_year' => '1986',
      'owner' => 'CeeDeeYes Group',
      'built_up_area' => '4 million sq ft',
      'total_area' => '17.5 grounds',
      'companies' => ''
    ],
  ];
  ?>

  <?php foreach ($parks as $p): $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$p['name'])); ?>
  <section id="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" class="park-section mb-4">
    <h3><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?> 
      <?php if (!empty($p['location'])): ?><span class="badge badge-locality"><?php echo htmlspecialchars($p['location'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
    </h3>
    <?php if (!empty($p['address'])): ?><p class="muted mb-1"><?php echo htmlspecialchars($p['address'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
    <ul class="mb-2">
      <?php if (!empty($p['inauguration_year'])): ?><li><strong>Year of inauguration</strong>: <?php echo htmlspecialchars($p['inauguration_year'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($p['owner'])): ?><li><strong>Owner</strong>: <?php echo htmlspecialchars($p['owner'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($p['built_up_area'])): ?><li><strong>Built-up area</strong>: <?php echo htmlspecialchars($p['built_up_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($p['total_area'])): ?><li><strong>Total area</strong>: <?php echo htmlspecialchars($p['total_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($p['companies'])): ?><li><strong>Major companies</strong>: <?php echo htmlspecialchars($p['companies'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
    </ul>
    <div class="mb-3">
      <?php if (!empty($p['website'])): ?>
        <a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($p['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Website</a>
      <?php endif; ?>
      <?php $mapQuery = urlencode(($p['name'] ?? '') . ' ' . ($p['address'] ?? 'OMR Chennai')); $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.$mapQuery; ?>
      <a class="btn btn-sm btn-primary ms-2" href="<?php echo htmlspecialchars($mapUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View on Map</a>
    </div>
  </section>
  <hr>
  <?php endforeach; ?>

  <div class="alert alert-light">
    Source reference: <a href="https://www.roofandfloor.com/chennai/blog/it-parks-in-omr" target="_blank" rel="noopener">RoofandFloor: IT Parks in OMR</a>.
  </div>

  <div class="mt-4">
    <a class="btn btn-success" href="/omr-listings/it-parks-in-omr.php">Open the Directory View</a>
  </div>

</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>

</body>
</html>


