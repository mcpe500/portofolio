<?php
session_start();
include_once 'config.php';

// Check if user is logged in (basic authentication)
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$type = $_GET['type'] ?? 'csv';

// Function to get database connection
function getDBConnection() {
    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS;
    
    $connection_string = "host=$DB_HOST port=$DB_PORT dbname=$DB_NAME user=$DB_USER password=$DB_PASS";
    $connection = pg_connect($connection_string);
    
    if (!$connection) {
        die('Could not connect to database: ' . pg_last_error());
    }
    
    return $connection;
}

// Function to get all tables from the database
function getTables($connection) {
    $result = pg_query($connection, "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
    if (!$result) {
        return [];
    }
    
    $tables = [];
    while ($row = pg_fetch_assoc($result)) {
        $tables[] = $row['table_name'];
    }
    
    return $tables;
}

// Function to get table data
function getTableData($connection, $table) {
    $result = pg_query($connection, "SELECT * FROM \"$table\";");
    if (!$result) {
        return [];
    }
    
    $data = [];
    while ($row = pg_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data;
}

// Get export type from URL parameter
$type = $_GET['type'] ?? 'csv';

// Set headers for download
$filename = 'export_' . date('Y-m-d_H-i-s') . '.' . $type;
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Get database connection
$connection = getDBConnection();

// Get all tables
$tables = getTables($connection);

if ($type === 'csv') {
    // CSV export
    foreach ($tables as $table) {
        $data = getTableData($connection, $table);
        if (!empty($data)) {
            // Write table name as comment
            echo "# Table: $table\n";
            
            // Write headers
            $headers = array_keys($data[0]);
            echo '"' . implode('","', $headers) . "\"\n";
            
            // Write data rows
            foreach ($data as $row) {
                $values = [];
                foreach ($headers as $header) {
                    $values[] = $row[$header];
                }
                echo '"' . implode('","', $values) . "\"\n";
            }
            
            echo "\n"; // Empty line between tables
        }
    }
} elseif ($type === 'sql') {
    // SQL export
    echo "-- Exported on " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        echo "-- Table: $table\n";
        
        // Get table structure
        $structure_result = pg_query($connection, "SELECT * FROM information_schema.columns WHERE table_name = '$table';");
        if ($structure_result) {
            echo "CREATE TABLE IF NOT EXISTS \"$table\" (\n";
            $first = true;
            while ($col = pg_fetch_assoc($structure_result)) {
                if (!$first) echo ",\n";
                echo "  \"{$col['column_name']}\" {$col['data_type']}";
                if ($col['is_nullable'] === 'NO') {
                    echo " NOT NULL";
                }
                $first = false;
            }
            echo "\n);\n\n";
        }
        
        // Get table data
        $data = getTableData($connection, $table);
        if (!empty($data)) {
            foreach ($data as $row) {
                $columns = array_keys($row);
                $values = [];
                foreach ($row as $value) {
                    $values[] = $value === null ? 'NULL' : "'" . pg_escape_string($connection, $value) . "'";
                }
                
                echo "INSERT INTO \"$table\" (\"" . implode('", "', $columns) . "\") VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        
        echo "\n";
    }
} elseif ($type === 'mysql') {
    // MySQL export format
    echo "-- MySQL Export\n";
    echo "-- Exported on " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        echo "-- Table: $table\n";
        
        // Get table structure
        $structure_result = pg_query($connection, "SELECT * FROM information_schema.columns WHERE table_name = '$table';");
        if ($structure_result) {
            echo "CREATE TABLE IF NOT EXISTS `$table` (\n";
            $first = true;
            while ($col = pg_fetch_assoc($structure_result)) {
                if (!$first) echo ",\n";
                echo "  `{$col['column_name']}` ";
                
                // Convert PostgreSQL types to MySQL types
                $mysql_type = $col['data_type'];
                switch ($col['data_type']) {
                    case 'character varying':
                    case 'varchar':
                        $mysql_type = 'VARCHAR(255)';
                        break;
                    case 'timestamp without time zone':
                        $mysql_type = 'TIMESTAMP';
                        break;
                    case 'timestamp with time zone':
                        $mysql_type = 'TIMESTAMP';
                        break;
                    case 'boolean':
                        $mysql_type = 'TINYINT(1)';
                        break;
                }
                
                echo "$mysql_type";
                if ($col['is_nullable'] === 'NO') {
                    echo " NOT NULL";
                }
                $first = false;
            }
            echo "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n";
        }
        
        // Get table data
        $data = getTableData($connection, $table);
        if (!empty($data)) {
            foreach ($data as $row) {
                $columns = array_keys($row);
                $values = [];
                foreach ($row as $value) {
                    $values[] = $value === null ? 'NULL' : "'" . str_replace("'", "\\'", $value) . "'";
                }
                
                echo "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        
        echo "\n";
    }
} elseif ($type === 'postgresql') {
    // PostgreSQL export format
    echo "-- PostgreSQL Export\n";
    echo "-- Exported on " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($tables as $table) {
        echo "-- Table: $table\n";
        
        // Get table structure
        $structure_result = pg_query($connection, "SELECT * FROM information_schema.columns WHERE table_name = '$table';");
        if ($structure_result) {
            echo "CREATE TABLE IF NOT EXISTS \"$table\" (\n";
            $first = true;
            while ($col = pg_fetch_assoc($structure_result)) {
                if (!$first) echo ",\n";
                echo "  \"{$col['column_name']}\" {$col['data_type']}";
                if ($col['is_nullable'] === 'NO') {
                    echo " NOT NULL";
                }
                $first = false;
            }
            echo "\n);\n\n";
        }
        
        // Get table data
        $data = getTableData($connection, $table);
        if (!empty($data)) {
            foreach ($data as $row) {
                $columns = array_keys($row);
                $values = [];
                foreach ($row as $value) {
                    $values[] = $value === null ? 'NULL' : "'" . pg_escape_string($connection, $value) . "'";
                }
                
                echo "INSERT INTO \"$table\" (\"" . implode('", "', $columns) . "\") VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        
        echo "\n";
    }
} elseif ($type === 'xlsx') {
    // XLSX export as JSON (would require a library like PhpSpreadsheet for actual XLSX)
    echo "{\n";
    echo "  \"export_info\": {\n";
    echo "    \"type\": \"xlsx\",\n";
    echo "    \"exported_on\": \"" . date('Y-m-d H:i:s') . "\"\n";
    echo "  },\n";
    echo "  \"tables\": {\n";
    
    $tableCount = count($tables);
    foreach ($tables as $index => $table) {
        $data = getTableData($connection, $table);
        echo "    \"$table\": {\n";
        echo "      \"columns\": [";
        
        if (!empty($data)) {
            $headers = array_keys($data[0]);
            $headerStrings = array_map(function($h) { return "\"$h\""; }, $headers);
            echo implode(', ', $headerStrings);
        }
        
        echo "],\n";
        echo "      \"data\": [\n";
        
        foreach ($data as $rowIndex => $row) {
            echo "        {";
            $pairs = [];
            foreach ($row as $col => $value) {
                $jsonValue = json_encode($value);
                $pairs[] = "\"$col\": $jsonValue";
            }
            echo implode(', ', $pairs);
            echo "}";
            if ($rowIndex < count($data) - 1) echo ",";
            echo "\n";
        }
        
        echo "      ]\n";
        echo "    }";
        if ($index < $tableCount - 1) echo ",";
        echo "\n";
    }
    
    echo "  }\n";
    echo "}";
} else {
    // Default to CSV
    foreach ($tables as $table) {
        $data = getTableData($connection, $table);
        if (!empty($data)) {
            // Write table name as comment
            echo "# Table: $table\n";
            
            // Write headers
            $headers = array_keys($data[0]);
            echo '"' . implode('","', $headers) . "\"\n";
            
            // Write data rows
            foreach ($data as $row) {
                $values = [];
                foreach ($headers as $header) {
                    $values[] = $row[$header];
                }
                echo '"' . implode('","', $values) . "\"\n";
            }
            
            echo "\n"; // Empty line between tables
        }
    }
}

// Close connection
pg_close($connection);
?>
