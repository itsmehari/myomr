<?php
require_once __DIR__ . '/../../core/omr-connect.php';

$sql = "CREATE TABLE IF NOT EXISTS omr_it_parks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  locality VARCHAR(100) NULL,
  address VARCHAR(400) NULL,
  phone VARCHAR(100) NULL,
  website VARCHAR(255) NULL,
  inauguration_year VARCHAR(10) NULL,
  owner VARCHAR(160) NULL,
  built_up_area VARCHAR(80) NULL,
  total_area VARCHAR(80) NULL,
  image VARCHAR(255) NULL,
  lat DECIMAL(10,7) NULL,
  lng DECIMAL(10,7) NULL,
  verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$ok = $conn->query($sql);
if (!$ok) {
  header('Content-Type: text/plain');
  echo 'ERROR creating table: ' . $conn->error;
  exit(1);
}

// Indexes
$conn->query("ALTER TABLE omr_it_parks ADD INDEX idx_name (name)");
$conn->query("ALTER TABLE omr_it_parks ADD INDEX idx_locality (locality)");
$conn->query("ALTER TABLE omr_it_parks ADD INDEX idx_name_locality (name, locality)");

header('Content-Type: text/plain');
echo "omr_it_parks\tcreated_or_exists\n";


