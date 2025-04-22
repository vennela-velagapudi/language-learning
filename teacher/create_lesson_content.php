<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = 'patamata@07';
$db = 'webtech';

try {
    // Connect to database
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create lesson_content table
    $sql = "CREATE TABLE IF NOT EXISTS lesson_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        language VARCHAR(50) NOT NULL,
        lesson_number INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Lesson_content table created successfully!";
    } else {
        throw new Exception("Error creating lesson_content table: " . $conn->error);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 