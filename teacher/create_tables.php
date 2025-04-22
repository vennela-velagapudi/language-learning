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

    // Create lessons table
    $pdo->exec("CREATE TABLE IF NOT EXISTS lessons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lesson_number INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        language VARCHAR(50) NOT NULL,
        sub_lessons JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_lesson (language, lesson_number)
    )");

    // Create lesson_content table
    $pdo->exec("CREATE TABLE IF NOT EXISTS lesson_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lesson_number INT NOT NULL,
        language VARCHAR(50) NOT NULL,
        content_type VARCHAR(50) NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (lesson_number, language) REFERENCES lessons(lesson_number, language) ON DELETE CASCADE
    )");

    echo "Tables created successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 