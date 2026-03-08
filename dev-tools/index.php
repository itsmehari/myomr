<?php
/**
 * Development Tools Dashboard
 * Central hub for all database development tools
 */
define('DEV_TOOLS_ACCESS', true);
require_once 'config-remote.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Tools - MyOMR Database</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .dashboard-container { max-width: 1200px; margin: 0 auto; }
        .tool-card { 
            margin: 15px 0; 
            transition: transform 0.2s, box-shadow 0.2s; 
            cursor: pointer;
            height: 100%;
        }
        .tool-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .tool-icon { font-size: 3rem; margin-bottom: 15px; }
        .header-card { background: white; border-radius: 10px; padding: 30px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .status-badge { font-size: 0.9rem; padding: 5px 15px; border-radius: 20px; }
        .category-header { color: white; margin: 30px 0 15px 0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-tools text-primary"></i> Development Tools Dashboard</h1>
                    <p class="mb-0 text-muted">MyOMR.in Database Management & Development Tools</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="mb-2">
                        <span class="badge badge-success status-badge">
                            <i class="fas fa-check-circle"></i> Connected to: <?php echo $GLOBALS['dev_db_name']; ?>
                        </span>
                    </div>
                    <div>
                        <span class="badge badge-info status-badge">
                            <i class="fas fa-server"></i> Localhost Only
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-bar"></i> Quick Database Stats</h5>
                <div class="row text-center mt-3">
                    <?php
                    $stats = [
                        ['table' => 'news_bulletin', 'label' => 'News Articles', 'icon' => 'newspaper', 'color' => 'primary'],
                        ['table' => 'events', 'label' => 'Events', 'icon' => 'calendar', 'color' => 'success'],
                        ['table' => 'omr_restaurants', 'label' => 'Restaurants', 'icon' => 'utensils', 'color' => 'danger'],
                        ['table' => 'omrschoolslist', 'label' => 'Schools', 'icon' => 'school', 'color' => 'warning'],
                        ['table' => 'omrbankslist', 'label' => 'Banks', 'icon' => 'university', 'color' => 'info'],
                    ];
                    
                    foreach ($stats as $stat) {
                        $result = $dev_conn->query("SELECT COUNT(*) as count FROM `{$stat['table']}`");
                        $count = $result ? $result->fetch_assoc()['count'] : 0;
                        echo "
                        <div class='col'>
                            <i class='fas fa-{$stat['icon']} text-{$stat['color']} fa-2x mb-2'></i>
                            <h3 class='mb-0'>{$count}</h3>
                            <small class='text-muted'>{$stat['label']}</small>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Database Management Tools -->
        <h3 class="category-header"><i class="fas fa-database"></i> Database Management</h3>
        <div class="row">
            <div class="col-md-4">
                <a href="crud-operations.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-primary">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h5>CRUD Operations</h5>
                            <p class="text-muted">Create, Read, Update, Delete records with forms</p>
                            <span class="badge badge-primary">Most Used</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="db-quick-query.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-warning">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h5>Quick Query</h5>
                            <p class="text-muted">Run SQL queries instantly with syntax highlighting</p>
                            <span class="badge badge-warning">Power Users</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="db-test-connection.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-success">
                                <i class="fas fa-plug"></i>
                            </div>
                            <h5>Test Connection</h5>
                            <p class="text-muted">Verify SSH tunnel and database connection</p>
                            <span class="badge badge-success">Diagnostics</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Export & Backup Tools -->
        <h3 class="category-header"><i class="fas fa-download"></i> Export & Backup</h3>
        <div class="row">
            <div class="col-md-4">
                <a href="db-export-schema.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-info">
                                <i class="fas fa-file-code"></i>
                            </div>
                            <h5>Export Schema</h5>
                            <p class="text-muted">Get CREATE TABLE statements for all tables</p>
                            <span class="badge badge-info">Schema</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="db-backup-tool.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-danger">
                                <i class="fas fa-save"></i>
                            </div>
                            <h5>Backup Tool</h5>
                            <p class="text-muted">Create full database backups with data</p>
                            <span class="badge badge-danger">Important</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="db-export-data.php" style="text-decoration: none; color: inherit;">
                    <div class="card tool-card">
                        <div class="card-body text-center">
                            <div class="tool-icon text-secondary">
                                <i class="fas fa-file-export"></i>
                            </div>
                            <h5>Export Data</h5>
                            <p class="text-muted">Export tables to CSV/Excel format</p>
                            <span class="badge badge-secondary">Data</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Documentation & Links -->
        <h3 class="category-header"><i class="fas fa-book"></i> Documentation & Resources</h3>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="../docs/DATABASE_STRUCTURE.md" target="_blank" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-database"></i> Database Structure
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="../docs/DATABASE_QUICK_REFERENCE.md" target="_blank" class="btn btn-outline-success btn-block">
                            <i class="fas fa-book-open"></i> Quick Reference
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="../docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md" target="_blank" class="btn btn-outline-info btn-block">
                            <i class="fas fa-cogs"></i> Setup Guide
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="../admin/" target="_blank" class="btn btn-outline-warning btn-block">
                            <i class="fas fa-user-shield"></i> Admin Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="alert alert-warning mt-4">
            <h5><i class="fas fa-exclamation-triangle"></i> Important Notes</h5>
            <ul class="mb-0">
                <li>These tools connect to the <strong>LIVE production database</strong></li>
                <li>Always <strong>backup before making changes</strong></li>
                <li>Test queries on a small dataset first</li>
                <li>SSH tunnel must be running (port 3307)</li>
                <li>Never deploy this folder to production server!</li>
            </ul>
        </div>
    </div>
</body>
</html>
