<?php
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the teacher_courses table exists
$check_table_query = "SHOW TABLES LIKE 'teacher_courses'";
$result = $conn->query($check_table_query);

if ($result->num_rows == 0) {
    echo "Table 'teacher_courses' does not exist. Creating it now...<br>";
    
    // Create the teacher_courses table
    $create_table_query = "CREATE TABLE teacher_courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        teacher_id INT NOT NULL,
        language VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_teacher_course (teacher_id, language)
    )";
    
    if ($conn->query($create_table_query) === TRUE) {
        echo "Table 'teacher_courses' created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
} else {
    echo "Table 'teacher_courses' already exists.<br>";
}

// Check if course_content table exists
$check_content_table = "SHOW TABLES LIKE 'course_content'";
$content_result = $conn->query($check_content_table);

if ($content_result->num_rows == 0) {
    echo "Table 'course_content' does not exist. Creating it now...<br>";
    
    // Create the course_content table
    $create_content_table = "CREATE TABLE IF NOT EXISTS course_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        teacher_id INT NOT NULL,
        language VARCHAR(50) NOT NULL,
        lesson_number INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_lesson_content (teacher_id, language, lesson_number)
    )";
    
    if ($conn->query($create_content_table) === TRUE) {
        echo "Table 'course_content' created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
        error_log("Error creating course_content table: " . $conn->error);
    }
} else {
    echo "Table 'course_content' already exists.<br>";
}

// Verify the table structure
$describe_query = "DESCRIBE course_content";
$desc_result = $conn->query($describe_query);

if ($desc_result) {
    echo "Table structure:<br>";
    while ($row = $desc_result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
    }
} else {
    echo "Error describing table: " . $conn->error . "<br>";
    error_log("Error describing course_content table: " . $conn->error);
}

$conn->close();
?>