<?php
require_once 'config.php';

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Database connection successful!<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "User: " . DB_USER . "<br><br>";

    // Check if tables exist
    $tables = ['lessons', 'lesson_content'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' exists<br>";
            
            // Show table structure
            $stmt = $pdo->query("DESCRIBE $table");
            echo "Structure of '$table':<br>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . ", Null: " . $row['Null'] . ", Key: " . $row['Key'] . ", Default: " . $row['Default'] . "<br>";
            }

            // Show sample data
            echo "<br>Sample data from '$table':<br>";
            $stmt = $pdo->query("SELECT * FROM $table LIMIT 5");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($row) . "<br>";
            }
        } else {
            echo "Table '$table' does not exist<br>";
        }
        echo "<br>";
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
    echo "Error Info: " . print_r($e->errorInfo, true) . "<br>";
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "<br>";
}
?> 