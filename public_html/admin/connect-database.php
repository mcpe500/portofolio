<?php
session_start();
include_once 'config.php';

// Check if user is logged in (basic authentication)
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die('CSRF token validation failed');
}

// Get database connection parameters from POST
$dbType = $_POST['db_type'] ?? 'postgresql';
$dbHost = $_POST['db_host'] ?? '';
$dbPort = $_POST['db_port'] ?? '';
$dbName = $_POST['db_name'] ?? '';
$dbUser = $_POST['db_user'] ?? '';
$dbPass = $_POST['db_pass'] ?? '';

// Validate required fields
if (empty($dbHost) || empty($dbPort) || empty($dbName) || empty($dbUser)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields except password are required']);
    exit;
}

// Attempt database connection based on type
$connection = null;
$error = null;

try {
    if ($dbType === 'postgresql') {
        // PostgreSQL connection
        $connection_string = "host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPass";
        $connection = pg_connect($connection_string);
        
        if (!$connection) {
            $error = 'Could not connect to PostgreSQL database: ' . pg_last_error();
        }
    } elseif ($dbType === 'mysql') {
        // MySQL connection
        $connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
        
        if ($connection->connect_error) {
            $error = 'Could not connect to MySQL database: ' . $connection->connect_error;
        }
    } else {
        $error = 'Unsupported database type: ' . $dbType;
    }
} catch (Exception $e) {
    $error = 'Database connection error: ' . $e->getMessage();
}

if ($error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $error]);
    exit;
}

// If connection successful, get tables
$tables = [];
if ($dbType === 'postgresql' && $connection) {
    $result = pg_query($connection, "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $tables[] = $row['table_name'];
        }
    }
} elseif ($dbType === 'mysql' && $connection) {
    $result = $connection->query("SHOW TABLES;");
    if ($result) {
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    }
}

// Return success response with tables
echo json_encode([
    'success' => true,
    'message' => 'Connected successfully!',
    'dbType' => $dbType,
    'tables' => $tables
]);

// Close connection
if ($dbType === 'postgresql' && $connection) {
    pg_close($connection);
} elseif ($dbType === 'mysql' && $connection) {
    $connection->close();
}
?>