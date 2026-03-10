<?php
// Organization JSON-LD schema for MyOMR (single output)
$org = [
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => 'MyOMR',
  'url' => 'https://myomr.in',
  'logo' => 'https://myomr.in/My-OMR-Logo.png',
  'sameAs' => [
    'https://www.facebook.com/myomrCommunity',
    'https://www.instagram.com/myomrcommunity/',
    'https://www.youtube.com/channel/UCyFrgbaQht7C-17m_prn0Rg'
  ]
];
?>
<script type="application/ld+json"><?php echo json_encode($org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>


