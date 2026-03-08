<?php
/**
 * CRUD Helper for MyOMR Database
 * Safe operations on live database via SSH tunnel
 * 
 * REQUIREMENTS:
 * 1. SSH tunnel must be running (.\start-ssh-tunnel.ps1)
 * 2. Access via: http://localhost/crud-helper.php
 */

session_start();
require_once 'core/omr-connect-remote.php';

// Set output to display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Simple authentication (add your own password)
$admin_password = 'your_secure_password_here'; // CHANGE THIS!

if (!isset($_SESSION['crud_auth']) && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['crud_auth'] = true;
    } else {
        $error = "Invalid password!";
    }
}

if (!isset($_SESSION['crud_auth'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CRUD Helper - Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4>🔒 CRUD Helper Authentication</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle CRUD operations
$result_message = '';
$result_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    try {
        switch ($action) {
            case 'create_school':
                $stmt = $conn->prepare('INSERT INTO omrschoolslist (schoolname, address, contact, landmark) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('ssss', $_POST['schoolname'], $_POST['address'], $_POST['contact'], $_POST['landmark']);
                if ($stmt->execute()) {
                    $result_message = "✅ School added successfully! ID: " . $conn->insert_id;
                    $result_type = 'success';
                }
                break;
                
            case 'read_schools':
                $search = $_POST['search'] ?? '';
                $sql = "SELECT * FROM omrschoolslist WHERE schoolname LIKE ? OR address LIKE ? LIMIT 50";
                $stmt = $conn->prepare($sql);
                $search_term = "%$search%";
                $stmt->bind_param('ss', $search_term, $search_term);
                $stmt->execute();
                $result = $stmt->get_result();
                $result_message = "<h5>Found " . $result->num_rows . " schools:</h5>";
                $result_message .= "<table class='table table-striped'><thead><tr><th>ID</th><th>Name</th><th>Address</th><th>Contact</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    $result_message .= "<tr><td>{$row['slno']}</td><td>{$row['schoolname']}</td><td>{$row['address']}</td><td>{$row['contact']}</td></tr>";
                }
                $result_message .= "</tbody></table>";
                $result_type = 'info';
                break;
                
            case 'update_school':
                $stmt = $conn->prepare('UPDATE omrschoolslist SET schoolname = ?, address = ?, contact = ?, landmark = ? WHERE slno = ?');
                $stmt->bind_param('ssssi', $_POST['schoolname'], $_POST['address'], $_POST['contact'], $_POST['landmark'], $_POST['slno']);
                if ($stmt->execute()) {
                    $result_message = "✅ School updated successfully! Rows affected: " . $stmt->affected_rows;
                    $result_type = 'success';
                }
                break;
                
            case 'delete_school':
                // Safety check: require confirmation
                if (!isset($_POST['confirm']) || $_POST['confirm'] !== 'yes') {
                    $result_message = "⚠️ Please confirm deletion by checking the confirmation box!";
                    $result_type = 'warning';
                } else {
                    $stmt = $conn->prepare('DELETE FROM omrschoolslist WHERE slno = ?');
                    $stmt->bind_param('i', $_POST['slno']);
                    if ($stmt->execute()) {
                        $result_message = "✅ School deleted successfully! Rows affected: " . $stmt->affected_rows;
                        $result_type = 'success';
                    }
                }
                break;
                
            case 'execute_custom_sql':
                // WARNING: Only for experienced users!
                $sql = trim($_POST['custom_sql']);
                
                // Block dangerous operations without confirmation
                if (preg_match('/\b(DROP|TRUNCATE|ALTER)\b/i', $sql) && !isset($_POST['confirm'])) {
                    $result_message = "⚠️ DANGEROUS QUERY DETECTED! Please confirm to proceed.";
                    $result_type = 'danger';
                } else {
                    $result = $conn->query($sql);
                    if ($result === true) {
                        $result_message = "✅ Query executed successfully! Affected rows: " . $conn->affected_rows;
                        $result_type = 'success';
                    } elseif ($result instanceof mysqli_result) {
                        $result_message = "<h5>Query Results:</h5>";
                        $result_message .= "<table class='table table-striped table-sm'><thead><tr>";
                        
                        // Column headers
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            $result_message .= "<th>{$field->name}</th>";
                        }
                        $result_message .= "</tr></thead><tbody>";
                        
                        // Data rows
                        while ($row = $result->fetch_assoc()) {
                            $result_message .= "<tr>";
                            foreach ($row as $value) {
                                $result_message .= "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                            }
                            $result_message .= "</tr>";
                        }
                        $result_message .= "</tbody></table>";
                        $result_type = 'info';
                    }
                }
                break;
        }
    } catch (Exception $e) {
        $result_message = "❌ Error: " . $e->getMessage();
        $result_type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Helper - MyOMR Database</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .operation-card { margin-bottom: 20px; }
        .operation-card .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-operation { margin: 5px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <span class="navbar-brand">🗄️ MyOMR Database CRUD Helper</span>
        <span class="text-white">
            <i class="fas fa-database"></i> Connected to: <?php echo $database; ?>
            <a href="?logout=1" class="btn btn-sm btn-light ml-3">Logout</a>
        </span>
    </nav>

    <div class="container mt-4">
        <?php if ($result_message): ?>
            <div class="alert alert-<?php echo $result_type; ?> alert-dismissible fade show">
                <?php echo $result_message; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>📊 Quick Database Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $tables = ['news_bulletin' => 'News', 'events' => 'Events', 'omrschoolslist' => 'Schools', 
                                      'omr_restaurants' => 'Restaurants', 'omrbankslist' => 'Banks'];
                            foreach ($tables as $table => $label) {
                                $count_result = $conn->query("SELECT COUNT(*) as count FROM `$table`");
                                $count = $count_result ? $count_result->fetch_assoc()['count'] : 0;
                                echo "<div class='col-md-2 text-center'><h3>{$count}</h3><p>{$label}</p></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CRUD Operations Tabs -->
        <ul class="nav nav-tabs" id="crudTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#read">🔍 Read</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#create">✏️ Create</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#update">🔄 Update</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#delete">🗑️ Delete</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#custom">⚡ Custom SQL</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- READ Tab -->
            <div class="tab-pane fade show active" id="read">
                <div class="card operation-card">
                    <div class="card-header">
                        <h5>🔍 Read / Search Data</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="read_schools">
                            <div class="form-group">
                                <label>Search Schools:</label>
                                <input type="text" name="search" class="form-control" placeholder="Enter school name or address...">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CREATE Tab -->
            <div class="tab-pane fade" id="create">
                <div class="card operation-card">
                    <div class="card-header">
                        <h5>✏️ Create New School Entry</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="create_school">
                            <div class="form-group">
                                <label>School Name:</label>
                                <input type="text" name="schoolname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Address:</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Contact:</label>
                                <input type="text" name="contact" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Landmark:</label>
                                <input type="text" name="landmark" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success">Create School</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- UPDATE Tab -->
            <div class="tab-pane fade" id="update">
                <div class="card operation-card">
                    <div class="card-header">
                        <h5>🔄 Update Existing School</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_school">
                            <div class="form-group">
                                <label>School ID (slno):</label>
                                <input type="number" name="slno" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>School Name:</label>
                                <input type="text" name="schoolname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Address:</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Contact:</label>
                                <input type="text" name="contact" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Landmark:</label>
                                <input type="text" name="landmark" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-warning">Update School</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- DELETE Tab -->
            <div class="tab-pane fade" id="delete">
                <div class="card operation-card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5>🗑️ Delete School (Use with Caution!)</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>⚠️ Warning:</strong> This action cannot be undone! Make sure to backup before deleting.
                        </div>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this school?');">
                            <input type="hidden" name="action" value="delete_school">
                            <div class="form-group">
                                <label>School ID (slno) to Delete:</label>
                                <input type="number" name="slno" class="form-control" required>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="confirm" value="yes" class="form-check-input" id="confirmDelete" required>
                                <label class="form-check-label" for="confirmDelete">
                                    I confirm I want to delete this record
                                </label>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete School</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CUSTOM SQL Tab -->
            <div class="tab-pane fade" id="custom">
                <div class="card operation-card border-warning">
                    <div class="card-header bg-warning">
                        <h5>⚡ Execute Custom SQL Query</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <strong>⚠️ Advanced Users Only!</strong> Incorrect queries can damage your database. Always backup first!
                        </div>
                        <form method="POST">
                            <input type="hidden" name="action" value="execute_custom_sql">
                            <div class="form-group">
                                <label>SQL Query:</label>
                                <textarea name="custom_sql" class="form-control" rows="5" placeholder="SELECT * FROM omrschoolslist LIMIT 10;" required></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="confirm" value="yes" class="form-check-input" id="confirmSQL">
                                <label class="form-check-label" for="confirmSQL">
                                    Confirm dangerous operations (DROP, TRUNCATE, ALTER)
                                </label>
                            </div>
                            <button type="submit" class="btn btn-warning">Execute Query</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h5>🚀 Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="test-remote-db-connection.php" class="btn btn-info" target="_blank">
                    <i class="fas fa-plug"></i> Test Connection
                </a>
                <a href="export-database-schema.php" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Export Schema
                </a>
                <a href="admin/" class="btn btn-success" target="_blank">
                    <i class="fas fa-user-shield"></i> Admin Panel
                </a>
                <a href="docs/DATABASE_STRUCTURE.md" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-book"></i> Documentation
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Logout handling
if (isset($_GET['logout'])) {
    unset($_SESSION['crud_auth']);
    header('Location: crud-helper.php');
    exit;
}
?>
