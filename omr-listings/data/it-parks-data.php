<?php
// Central data for IT Parks (temporary until DB is added)
// Assign stable numeric IDs for clean URLs
$omr_it_parks_all = [
  [
    'id' => 1,
    'name' => 'World Trade Center (WTC Chennai)',
    'location' => 'Perungudi',
    'address' => 'Brigade World Trade Center Sales office 5, 142, Rajiv Gandhi Salai, Chennai, Tamil Nadu 600096',
    'phone' => '1800 102 9977',
    'website' => 'https://www.brigaderesidenceswtc.com/',
    'inauguration_year' => '2020',
    'owner' => 'Brigade Group',
    'built_up_area' => '1.8 million sq ft',
    'total_area' => '41.32 acres',
    'companies' => 'Kissflow, Amazon',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 2,
    'name' => 'TIDEL Park',
    'location' => 'Taramani',
    'address' => '4, Rajiv Gandhi Salai, Taramani, Chennai, Tamil Nadu 600113',
    'phone' => '+91 44 2254 0500 / 0501',
    'website' => 'https://www.tidelpark.com/',
    'inauguration_year' => '2000',
    'owner' => 'TIDCO & ELCOT',
    'built_up_area' => '1.8 million sq ft',
    'total_area' => '8 acres',
    'companies' => 'Cisco, Sify, Tenneco, Trimble, Guardian, TCS, EY, CDAC',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 3,
    'name' => 'SIPCOT IT Park (Siruseri)',
    'location' => 'Siruseri',
    'address' => 'Mathematical Institute, OMR, Siruseri, Tamil Nadu 603103',
    'phone' => '044 2747 0031',
    'website' => '',
    'inauguration_year' => '1971',
    'owner' => 'TIDCO & ELCOT',
    'built_up_area' => '',
    'total_area' => '782.51 acres',
    'companies' => 'TCS, Cognizant, Hexaware, Sopra Steria, Atos, Changepond',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 4,
    'name' => 'Chennai One IT SEZ',
    'location' => 'Thoraipakkam',
    'address' => 'MCN Nagar Extension, Thoraipakkam, Tamil Nadu 600096',
    'phone' => '75500 55545',
    'website' => 'https://chennai1.in/',
    'inauguration_year' => '2006',
    'owner' => 'IG3 Infrastructure Ltd',
    'built_up_area' => '3.6 million sq ft',
    'total_area' => '20+ acres',
    'companies' => 'Comcast, TCS, Siemens (Atos), Sutherland, HCL, Polaris, Ford, Prodapt, Wells Fargo',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 5,
    'name' => 'RMZ Millenia',
    'location' => 'Perungudi',
    'address' => 'Campus-1A, MGR Main Rd, Kodandarama Nagar, Perungudi, Chennai, Tamil Nadu 600096',
    'phone' => '80 4000 4000',
    'website' => 'https://www.rmzcorp.com/',
    'inauguration_year' => '2018',
    'owner' => 'RMZ (Menda family)',
    'built_up_area' => '2.2 million sq ft',
    'total_area' => '22 acres',
    'companies' => 'Shell, Walmart, JRay, Caterpillar, World Bank, KLA, Ford India, GlobalLogic',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 6,
    'name' => 'ELCOT IT Park',
    'location' => 'Sholinganallur',
    'address' => 'ELCOT IT SEZ, Rajiv Gandhi Salai, Sholinganallur, Chennai 600119',
    'phone' => '',
    'website' => 'https://www.elcot.in/',
    'inauguration_year' => '',
    'owner' => 'ELCOT',
    'built_up_area' => '',
    'total_area' => '',
    'companies' => '',
    'image' => '/My-OMR-Logo.png'
  ],
  [
    'id' => 7,
    'name' => 'CeeDeeYes Business Park',
    'location' => 'OMR (Chennai)',
    'address' => '',
    'phone' => '',
    'website' => 'http://ceedeeyesbusinesspark.com/',
    'inauguration_year' => '1986',
    'owner' => 'CeeDeeYes Group',
    'built_up_area' => '4 million sq ft',
    'total_area' => '17.5 grounds',
    'companies' => '',
    'image' => '/My-OMR-Logo.png'
  ],
];

// Helper to fetch by id
function omr_it_parks_get_by_id($id) {
  global $omr_it_parks_all;
  foreach ($omr_it_parks_all as $p) { if ((int)$p['id'] === (int)$id) return $p; }
  return null;
}


