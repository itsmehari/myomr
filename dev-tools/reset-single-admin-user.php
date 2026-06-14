<?php
/**
 * Keep one admin_users row; remove all others.
 *
 * Usage:
 *   $env:DB_HOST='myomr.in'
 *   $env:ADMIN_USERNAME='you@example.com'
 *   $env:ADMIN_PASSWORD='your-password'
 *   php dev-tools/reset-single-admin-user.php
 */
declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$username = trim(getenv('ADMIN_USERNAME') ?: '');
$password = getenv('ADMIN_PASSWORD') ?: '';
$email = trim(getenv('ADMIN_EMAIL') ?: $username);
$fullName = trim(getenv('ADMIN_FULL_NAME') ?: 'MyOMR Superadmin');
$role = trim(getenv('ADMIN_ROLE') ?: 'super_admin');

if ($username === '' || $password === '') {
    fwrite(STDERR, "Set ADMIN_USERNAME and ADMIN_PASSWORD environment variables.\n");
    exit(1);
}

$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
$db_pass = getenv('DB_PASS') ?: 'myomr@123';
$db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';
$db_port = (int) (getenv('DB_PORT') ?: 3306);

if ($db_host !== false && $db_host !== '') {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
} else {
    require_once __DIR__ . '/../core/omr-connect.php';
}

if (!isset($conn) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed.\n");
    exit(1);
}
$conn->set_charset('utf8mb4');

$hash = password_hash($password, PASSWORD_DEFAULT);
if ($hash === false) {
    fwrite(STDERR, "password_hash failed.\n");
    exit(1);
}

$upsert = $conn->prepare(
    'INSERT INTO admin_users (username, password, email, full_name, role)
     VALUES (?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
       password = VALUES(password),
       email = VALUES(email),
       full_name = VALUES(full_name),
       role = VALUES(role)'
);
$upsert->bind_param('sssss', $username, $hash, $email, $fullName, $role);
$upsert->execute();
$upsert->close();

$delete = $conn->prepare('DELETE FROM admin_users WHERE username != ?');
$delete->bind_param('s', $username);
$delete->execute();
$removed = $delete->affected_rows;
$delete->close();

$check = $conn->prepare('SELECT id, username, role, email FROM admin_users ORDER BY id');
$check->execute();
$result = $check->get_result();

echo "Removed {$removed} other admin user(s).\n";
echo "Remaining admin_users:\n";
while ($row = $result->fetch_assoc()) {
    echo sprintf(
        "  id=%d username=%s role=%s email=%s\n",
        (int) $row['id'],
        $row['username'] ?? '',
        $row['role'] ?? '',
        $row['email'] ?? ''
    );
}
$check->close();

echo "Login: https://myomr.in/superadmin/login.php\n";
