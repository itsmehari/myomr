<?php /* Discover MyOMR Navigation Bar - Green Palette */ ?>
<style>
.discover-navbar {
  background: #14532d; /* Deep green */
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(20, 83, 45, 0.12);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0.5rem 0;
  margin: 2rem auto 2rem auto;
  max-width: 900px;
}
.discover-navbar ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
}
.discover-navbar li {
  position: relative;
}
.discover-navbar a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 1rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 500;
  border-radius: 6px;
  transition: background 0.2s, color 0.2s;
}
.discover-navbar a:hover,
.discover-navbar .active > a {
  background: #22c55e; /* Lighter green */
  color: #14532d;
}
.discover-navbar .dropdown:hover > a,
.discover-navbar .dropdown:focus-within > a {
  background: #22c55e;
  color: #14532d;
}
.discover-navbar .dropdown-content {
  display: none;
  position: absolute;
  left: 0;
  top: 100%;
  min-width: 200px;
  background: #fff;
  box-shadow: 0 8px 24px rgba(20, 83, 45, 0.15);
  border-radius: 0 0 10px 10px;
  z-index: 100;
  margin-top: 0.5rem;
}
.discover-navbar .dropdown-content a {
  color: #14532d;
  background: #fff;
  border-radius: 0;
  padding: 0.75rem 1.5rem;
}
.discover-navbar .dropdown-content a:hover {
  background: #bbf7d0;
  color: #14532d;
}
.discover-navbar .dropdown:hover .dropdown-content,
.discover-navbar .dropdown:focus-within .dropdown-content {
  display: block;
}
@media (max-width: 700px) {
  .discover-navbar {
    flex-direction: column;
    max-width: 100%;
    padding: 0.5rem 0.5rem;
  }
  .discover-navbar ul {
    flex-direction: column;
    width: 100%;
  }
  .discover-navbar li {
    width: 100%;
  }
  .discover-navbar a {
    width: 100%;
    text-align: left;
  }
  .discover-navbar .dropdown-content {
    position: static;
    min-width: 100%;
    box-shadow: none;
    border-radius: 0 0 10px 10px;
    margin-top: 0;
  }
}
</style>
<nav class="discover-navbar">
  <ul>
    <li><a href="/" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>">Home</a></li>
    <li><a href="/discover-myomr/overview.php" class="<?php if(strpos($_SERVER['PHP_SELF'],'overview.php')!==false) echo 'active'; ?>">About</a></li>
    <li><a href="/discover-myomr/features.php" class="<?php if(strpos($_SERVER['PHP_SELF'],'features.php')!==false) echo 'active'; ?>">Features</a></li>
    <li><a href="/discover-myomr/pricing.php" class="<?php if(strpos($_SERVER['PHP_SELF'],'pricing.php')!==false) echo 'active'; ?>">Pricing</a></li>
    <li class="dropdown">
      <a href="#" tabindex="0">Community ▾</a>
      <div class="dropdown-content">
        <a href="/discover-myomr/community.php">Forums</a>
        <a href="/discover-myomr/community.php#events">Events & Meetups</a>
        <a href="/discover-myomr/community.php#groups">Local Groups</a>
        <a href="/discover-myomr/community.php#volunteer">Volunteering</a>
      </div>
    </li>
    <li><a href="/discover-myomr/support.php" class="<?php if(strpos($_SERVER['PHP_SELF'],'support.php')!==false) echo 'active'; ?>">Support</a></li>
    <li><a href="/contact-my-omr-team.php">Contact</a></li>
  </ul>
</nav> 