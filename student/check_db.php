<?php
include '../connection.php';

// Check if the student_courses table exists
$check_table_query = "SHOW TABLES LIKE 'student_courses'";
$result = $conn->query($check_table_query);

if ($result->num_rows == 0) {
    echo "Table 'student_courses' does not exist. Creating it now...<br>";
    
    // Create the student_courses table
    $create_table_query = "CREATE TABLE student_courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        language VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_student_course (student_id, language)
    )";
    
    if ($conn->query($create_table_query) === TRUE) {
        echo "Table 'student_courses' created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
} else {
    echo "Table 'student_courses' already exists.<br>";
    
    // Check the structure of the table
    $describe_query = "DESCRIBE student_courses";
    $result = $conn->query($describe_query);
    
    echo "Table structure:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
    }
}

$conn->close();
?> 