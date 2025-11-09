<?php
session_start();
include_once 'config.php';

// Check if user is logged in (basic authentication)
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
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
                        <a class="nav-link" href="database.php"><i class="fas fa-database"></i> Database</a>
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
                <h1><i class="fas fa-cogs"></i> Admin Panel</h1>
                <p class="lead">Manage and export your data</p>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-database"></i> Data Export</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-csv fa-2x text-primary mb-3"></i>
                                        <h5>Export to CSV</h5>
                                        <p class="text-muted">Export data in CSV format</p>
                                        <a href="export.php?type=csv" class="btn btn-primary">Export</a>
                                    </div>
                                </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-excel fa-2x text-success mb-3"></i>
                                        <h5>Export to XLSX</h5>
                                        <p class="text-muted">Export data in Excel format</p>
                                        <a href="export.php?type=xlsx" class="btn btn-success">Export</a>
                                    </div>
                                </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-code fa-2x text-danger mb-3"></i>
                                        <h5>Export to SQL</h5>
                                        <p class="text-muted">Export data in SQL format</p>
                                        <a href="export.php?type=sql" class="btn btn-danger">Export</a>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database fa-2x text-info mb-3"></i>
                                        <h5>Export to MySQL</h5>
                                        <p class="text-muted">Export data in MySQL format</p>
                                        <a href="export.php?type=mysql" class="btn btn-info">Export</a>
                                    </div>
                                </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database fa-2x text-warning mb-3"></i>
                                        <h5>Export to PostgreSQL</h5>
                                        <p class="text-muted">Export data in PostgreSQL format</p>
                                        <a href="export.php?type=postgresql" class="btn btn-warning text-dark">Export</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-table"></i> Database Tables</h5>
                    </div>
                    <div class="card-body">
                        <div id="database-content">
                            <p>Loading database information...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>