<?php
/**
 * Create or update an admin_users row (password hashed with password_hash).
 *
 * Usage (do not commit passwords):
 *   $env:DB_HOST='myomr.in'
 *   $env:ADMIN_USERNAME='you@example.com'
 *   $env:ADMIN_PASSWORD='your-secure-password'
 *   php dev-tools/upsert-admin-user.php
 *
 * Optional: ADMIN_EMAIL, ADMIN_FULL_NAME, ADMIN_ROLE (default: super_admin)
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
$fullName = trim(getenv('ADMIN_FULL_NAME') ?: 'MyOMR Admin');
$role = trim(getenv('ADMIN_ROLE') ?: 'super_admin');

if ($username === '' || $password === '') {
    fwrite(STDERR, "Set ADMIN_USERNAME and ADMIN_PASSWORD environment variables.\n");
    exit(1);
}

if (strlen($password) < 8) {
    fwrite(STDERR, "Password must be at least 8 characters.\n");
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

$tbl = $conn->query("SHOW TABLES LIKE 'admin_users'");
if (!$tbl || $tbl->num_rows === 0) {
    fwrite(STDERR, "Table admin_users not found.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
if ($hash === false) {
    fwrite(STDERR, "password_hash failed.\n");
    exit(1);
}

$st = $conn->prepare(
    'INSERT INTO admin_users (username, password, email, full_name, role)
     VALUES (?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
       password = VALUES(password),
       email = VALUES(email),
       full_name = VALUES(full_name),
       role = VALUES(role)'
);
$st->bind_param('sssss', $username, $hash, $email, $fullName, $role);
$st->execute();
$st->close();

$idSt = $conn->prepare('SELECT id, username, role FROM admin_users WHERE username = ? LIMIT 1');
$idSt->bind_param('s', $username);
$idSt->execute();
$row = $idSt->get_result()->fetch_assoc();
$idSt->close();

echo "Admin user ready: id=" . (int) ($row['id'] ?? 0) . " username=" . ($row['username'] ?? $username) . " role=" . ($row['role'] ?? $role) . "\n";
echo "Login at: https://myomr.in/superadmin/login.php\n";
echo "Use the Username field (not a separate email field).\n";
