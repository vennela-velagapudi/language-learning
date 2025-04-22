<?php
// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(0);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'webtech');
define('DB_USER', 'root');
define('DB_PASS', 'patamata@07');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Changed to 0 for local development

// Database connection test
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // Log the error but don't output anything
    error_log("Database connection failed: " . $e->getMessage());
    // Don't die() or output anything
}
?> 