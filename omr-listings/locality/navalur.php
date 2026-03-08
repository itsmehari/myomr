<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../core/omr-connect.php';
$locality = 'Navalur';
$page_title = $locality . ' on OMR | Locality Hub | MyOMR';
$page_description = 'Explore ' . $locality . ' on OMR: IT companies, banks, hospitals, restaurants, schools, parks and more.';
$og_title = $page_title; $og_description = $page_description; $og_url = 'https://myomr.in/omr-listings/locality/navalur.php';
?>
<!DOCTYPE html>
<html lang="en"><head>
<?php include '../../components/meta.php'; ?>
<?php \ = ['locality' => \]; include '../../components/analytics.php'; ?>
<?php include '../../components/head-resources.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body>
<?php include '../../components/main-nav.php'; ?>
<div class="container" style="max-width:1280px;">
  <nav aria-label="breadcrumb" class="mt-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="/">Home</a></li><li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($locality); ?></li></ol></nav>
  <h1 style="color:#0583D2;">Explore <?php echo htmlspecialchars($locality); ?> on OMR</h1>
  <p>Discover listings in and around <?php echo htmlspecialchars($locality); ?>.</p>
  <div class="row mb-3">
    <?php $cards=[['IT Companies','/it-companies?locality='.urlencode($locality)],['Banks','/omr-listings/banks.php'],['Hospitals','/omr-listings/hospitals.php'],['Restaurants','/omr-listings/restaurants.php'],['Schools','/omr-listings/schools.php'],['Parks','/omr-listings/parks.php']];foreach($cards as $c){echo '<div class="col-sm-6 col-md-4 mb-3"><a class="card h-100" href="'.htmlspecialchars($c[1],ENT_QUOTES,'UTF-8').'"><div class="card-body"><h5 class="card-title">'.htmlspecialchars($c[0]).'</h5><p class="card-text">View '.htmlspecialchars($locality).' listings</p></div></a></div>'; }?>
  </div>
  <h3 class="mt-4">Top IT companies in <?php echo htmlspecialchars($locality); ?></h3>
  <?php $stmt=$conn->prepare('SELECT slno, company_name, address FROM omr_it_companies WHERE (locality = ? OR address LIKE ?) ORDER BY company_name ASC LIMIT 12');$like='%'.$locality.'%';$stmt->bind_param('ss',$locality,$like);$stmt->execute();$res=$stmt->get_result();if($res&&$res->num_rows>0){echo '<ul class="list-unstyled">';while($row=$res->fetch_assoc()){ $nm=$row['company_name'];$id=(int)$row['slno'];$slugBase=strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$nm));$slugBase=trim($slugBase,'-');$url='/it-companies/'.$slugBase.'-'.$id;echo '<li class="mb-1"><a href="'.htmlspecialchars($url,ENT_QUOTES,'UTF-8').'">'.htmlspecialchars($nm,ENT_QUOTES,'UTF-8').'</a></li>'; } echo '</ul>'; } else { echo '<p>No companies found yet.</p>'; } $stmt->close(); $conn->close(); ?>
  <script type="application/ld+json"><?php $breadcrumbs=['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[[ '@type'=>'ListItem','position'=>1,'item'=>['@id'=>'https://myomr.in/','name'=>'Home']],['@type'=>'ListItem','position'=>2,'item'=>['@id'=>$og_url,'name'=>$locality]],]]; echo json_encode($breadcrumbs,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);?></script>
  <?php include '../../components/subscribe.php'; ?>
</div>
<?php include '../../components/footer.php'; ?>
</body></html>


