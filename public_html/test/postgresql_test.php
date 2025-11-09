<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PostgreSQL Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .result {
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PostgreSQL Connection Test</h1>
        
        <form method="POST">
            <h2>Connection Parameters</h2>
            <label for="host">Host:</label>
            <input type="text" id="host" name="host" value="131.153.49.10" required>
            
            <label for="port">Port:</label>
            <input type="text" id="port" name="port" value="5432" required>
            
            <label for="dbname">Database Name:</label>
            <input type="text" id="dbname" name="dbname" value="testdb" required>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Test Connection">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host = $_POST['host'];
            $port = $_POST['port'];
            $dbname = $_POST['dbname'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Create connection string
            $connection_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

            echo "<h2>Test Results:</h2>";
            
            // Check if PostgreSQL extension is available
            if (!extension_loaded('pgsql')) {
                echo "<div class='result error'>";
                echo "<h3>❌ PostgreSQL Extension Not Enabled</h3>";
                echo "<p>The PostgreSQL (pgsql) extension is not enabled in your PHP installation.</p>";
                
                // Check if extension exists but is just not loaded
                if (!function_exists('pg_connect')) {
                    echo "<p>The pg_connect function is not available.</p>";
                }
                
                echo "<p>Make sure the following lines are uncommented in your php.ini file:</p>";
                echo "<code>extension=pdo_pgsql</code><br>";
                echo "<code>extension=pgsql</code><br>";
                echo "<p>Current PHP configuration file being used: <strong>" . php_ini_loaded_file() . "</strong></p>";
                echo "<p>Extension directory: <strong>" . ini_get('extension_dir') . "</strong></p>";
                echo "<p>After making changes to php.ini, restart your web server.</p>";
                echo "</div>";
            } else {
                // Attempt connection
                $connection = pg_connect($connection_string);
                
                if ($connection) {
                    echo "<div class='result success'>";
                    echo "<h3>✅ Connection Successful!</h3>";
                    echo "<p>Connected to PostgreSQL database at $host:$port</p>";
                    
                    // Get PostgreSQL version
                    $result = pg_query($connection, "SELECT version();");
                    if ($result) {
                        $row = pg_fetch_row($result);
                        echo "<p><strong>PostgreSQL Version:</strong> {$row[0]}</p>";
                    }
                    
                    // Get database name
                    echo "<p><strong>Database:</strong> $dbname</p>";
                    
                    // Get current user
                    $result = pg_query($connection, "SELECT current_user;");
                    if ($result) {
                        $row = pg_fetch_row($result);
                        echo "<p><strong>Connected as:</strong> {$row[0]}</p>";
                    }
                    
                    // List tables
                    $result = pg_query($connection, "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
                    if ($result) {
                        $tables = pg_fetch_all($result);
                        if ($tables) {
                            echo "<p><strong>Tables in database:</strong></p><ul>";
                            foreach ($tables as $table) {
                                echo "<li>{$table['table_name']}</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p><strong>Tables in database:</strong> No tables found in public schema</p>";
                        }
                    }
                    
                    echo "</div>";
                    
                    // Close connection
                    pg_close($connection);
                } else {
                    echo "<div class='result error'>";
                    echo "<h3>❌ Connection Failed!</h3>";
                    echo "<p>Could not connect to PostgreSQL database.</p>";
                    // Check if there's a connection resource to get the error from
                    if (function_exists('pg_last_error')) {
                        $error = pg_last_error();
                        if ($error) {
                            echo "<p><strong>Error:</strong> " . htmlspecialchars($error) . "</p>";
                        } else {
                            echo "<p><strong>Error:</strong> Connection failed with no specific error reported</p>";
                        }
                    } else {
                        echo "<p><strong>Error:</strong> PostgreSQL extension not available</p>";
                    }
                    echo "</div>";
                }
            }
        }
        ?>
         
        <h2>Instructions:</h2>
        <p>This test page allows you to verify your PostgreSQL connection settings.</p>
        <p>Fill in the form with your PostgreSQL credentials and click "Test Connection".</p>
    </div>
</body>
</html>