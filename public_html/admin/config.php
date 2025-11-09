<?php
// Load environment variables from .env file
$envFile = __DIR__ . '/../../.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!empty($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Get admin secret key from environment
define('ADMIN_SECRET_KEY', $_ENV['ADMIN_SECRET_KEY'] ?? 'default_secret_key');

// Database configuration
$DB_HOST = $_ENV['DB_HOST'] ?? 'localhost';
$DB_PORT = $_ENV['DB_PORT'] ?? '5432';
$DB_NAME = $_ENV['DB_NAME'] ?? 'testdb';
$DB_USER = $_ENV['DB_USER'] ?? 'user';
$DB_PASS = $_ENV['DB_PASS'] ?? 'password';

// Load main site configuration
$siteConfig = include __DIR__ . '/../config.php';

// Make site configuration available globally if needed
$GLOBALS['siteConfig'] = $siteConfig;
?>