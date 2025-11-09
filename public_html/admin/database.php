<?php
session_start();
include_once 'config.php';

// Check if user is logged in (basic authentication)
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Database Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fas fa-cogs"></i> Admin Panel</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Export Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="export.php?type=csv"><i class="fas fa-file-csv"></i> Export to CSV</a></li>
                            <li><a class="dropdown-item" href="export.php?type=xlsx"><i class="fas fa-file-excel"></i> Export to XLSX</a></li>
                            <li><a class="dropdown-item" href="export.php?type=sql"><i class="fas fa-file-code"></i> Export to SQL</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="export.php?type=mysql"><i class="fas fa-database"></i> Export to MySQL</a></li>
                            <li><a class="dropdown-item" href="export.php?type=postgresql"><i class="fas fa-database"></i> Export to PostgreSQL</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="database.php"><i class="fas fa-database"></i> Database</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1><i class="fas fa-database"></i> Database Management</h1>
                <p class="lead">View and manage your database</p>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-server"></i> Database Connection</h5>
                    </div>
                    <div class="card-body">
                        <form id="db-connect-form" method="POST" action="connect-database.php">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="db_type" class="form-label">Database Type</label>
                                        <select class="form-select" id="db_type" name="db_type" required>
                                            <option value="postgresql" selected>PostgreSQL</option>
                                            <option value="mysql">MySQL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="db_host" class="form-label">Host</label>
                                        <input type="text" class="form-control" id="db_host" name="db_host" 
                                               value="<?php echo htmlspecialchars($_ENV['DB_HOST'] ?? 'localhost', ENT_QUOTES, 'UTF-8'); ?>" 
                                               required autocomplete="server">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="db_port" class="form-label">Port</label>
                                        <input type="text" class="form-control" id="db_port" name="db_port" 
                                               value="<?php echo htmlspecialchars($_ENV['DB_PORT'] ?? '5432', ENT_QUOTES, 'UTF-8'); ?>" 
                                               required autocomplete="server">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="db_name" class="form-label">Database Name</label>
                                        <input type="text" class="form-control" id="db_name" name="db_name" 
                                               value="<?php echo htmlspecialchars($_ENV['DB_NAME'] ?? 'testdb', ENT_QUOTES, 'UTF-8'); ?>" 
                                               required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_user" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="db_user" name="db_user" 
                                               value="<?php echo htmlspecialchars($_ENV['DB_USER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                               required autocomplete="username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_pass" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="db_pass" name="db_pass" 
                                               value="<?php echo htmlspecialchars($_ENV['DB_PASS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                               autocomplete="current-password">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plug"></i> Connect
                            </button>
                        </form>
                        
                        <div id="connection-message" class="mt-3"></div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-table"></i> Database Tables</h5>
                    </div>
                    <div class="card-body">
                        <div id="tables-content">
                            <p class="text-muted">Connect to a database to view tables</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('db-connect-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('connection-message');
            const tablesDiv = document.getElementById('tables-content');
            
            // Show loading state
            messageDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Connecting to database...</div>';
            tablesDiv.innerHTML = '';
            
            // Make AJAX request
            fetch('connect-database.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                    
                    // Display tables
                    if (data.tables && data.tables.length > 0) {
                        let tableHtml = `
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Table Name</th>
                                            <th>Records</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        
                        data.tables.forEach(table => {
                            tableHtml += `
                                <tr>
                                    <td>${table}</td>
                                    <td><span class="badge bg-secondary">N/A</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="viewTableData('${data.dbType}', '${table}')">View Data</button>
                                        <button class="btn btn-sm btn-info" onclick="exportTable('${data.dbType}', '${table}')">Export</button>
                                    </td>
                                </tr>
                            `;
                        });
                        
                        tableHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                        
                        tablesDiv.innerHTML = tableHtml;
                    } else {
                        tablesDiv.innerHTML = '<p class="text-muted">No tables found in the database.</p>';
                    }
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</div>';
                    tablesDiv.innerHTML = '<p class="text-muted">Connect to a database to view tables</p>';
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Connection error: ' + error.message + '</div>';
                tablesDiv.innerHTML = '<p class="text-muted">Connect to a database to view tables</p>';
            });
        });
        
        function viewTableData(dbType, tableName) {
            alert('Viewing data for table: ' + tableName + ' (Database: ' + dbType + ')');
            // In a real implementation, this would fetch and display the table data
        }
        
        function exportTable(dbType, tableName) {
            alert('Exporting table: ' + tableName + ' (Database: ' + dbType + ')');
            // In a real implementation, this would export the table data
        }
    </script>
</body>
</html>