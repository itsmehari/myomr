<?php
if (isset($_SESSION['flash_success'])) {
  echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['flash_success']) . '</div>';
  unset($_SESSION['flash_success']);
}
if (isset($_SESSION['flash_error'])) {
  echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['flash_error']) . '</div>';
  unset($_SESSION['flash_error']);
}
?> 