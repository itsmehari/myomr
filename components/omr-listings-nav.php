<?php
// OMR Listings Sub-Navigation Component
$navItems = [
    ['Schools', 'schools.php'],
    ['Hospitals', 'hospitals.php'],
    ['Banks', 'banks.php'],
    ['Parks', 'parks.php'],
    ['ATMs', '/atms'],
    ['Government Offices', '/government-offices'],
    ['Restaurants', 'restaurants.php'],
    ['Industries', 'industries.php'],
    ['IT Parks', '/it-parks'],
    ['IT Companies', 'it-companies.php'],
    ['Best Schools', 'best-schools.php'],
];
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<div class="container">
  <div class="row">
    <ul style="list-style-type: none; margin: 0; padding: 0; overflow: hidden; background-color: #333; width: 100%;">
      <li><a class="<?php echo ($current == 'index.php') ? 'active' : ''; ?>" href="../index.php">Home</a></li>
      <?php foreach ($navItems as $item): ?>
        <li><a class="<?php echo ($current == $item[1]) ? 'active' : ''; ?>" href="<?php echo $item[1]; ?>"><?php echo $item[0]; ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}
li {
  float: left;
}
li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}
li a:hover:not(.active) {
  background-color: #111;
}
.active {
  background-color: #04AA6D;
}
</style> 