<?php
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Select the webtech database
if (!$conn->select_db('webtech')) {
    die("Could not select database: " . $conn->error);
}

// Drop the table if it exists
$drop_table = "DROP TABLE IF EXISTS course_content";
if ($conn->query($drop_table) === TRUE) {
    echo "Table dropped successfully<br>";
} else {
    echo "Error dropping table: " . $conn->error . "<br>";
}

// Create the course_content table
$create_table = "CREATE TABLE course_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    language VARCHAR(50) NOT NULL,
    lesson_number INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_lesson_content (teacher_id, language, lesson_number)
)";

if ($conn->query($create_table) === TRUE) {
    echo "Table 'course_content' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Verify the table structure
$describe = "DESCRIBE course_content";
$result = $conn->query($describe);

if ($result) {
    echo "<br>Table structure:<br>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error describing table: " . $conn->error . "<br>";
}

$conn->close();
?> 