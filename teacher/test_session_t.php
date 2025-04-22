<?php
session_start();
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Session Test</h2>";

// Display all session data
echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check if user_id exists in session
if (isset($_SESSION['user_id'])) {
    echo "<p>User ID in session: " . $_SESSION['user_id'] . "</p>";
    
    // Check if this user exists in the database
    $user_id = $_SESSION['user_id'];
    $check_user = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($check_user);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<p>User found in database: " . $user['username'] . "</p>";
    } else {
        echo "<p>User ID $user_id not found in database!</p>";
    }
} else {
    echo "<p>No user_id in session. You are not logged in.</p>";
}

// Check if teacher_courses table exists
$check_table = "SHOW TABLES LIKE 'teacher_courses'";
$table_result = $conn->query($check_table);

if ($table_result->num_rows > 0) {
    echo "<p>teacher_courses table exists.</p>";
    
    // Show table structure
    $describe = "DESCRIBE teacher_courses";
    $desc_result = $conn->query($describe);
    
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $desc_result->fetch_assoc()) {
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
    
    // Show existing courses for this user
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $courses_query = "SELECT * FROM teacher_courses WHERE teacher_id = $user_id";
        $courses_result = $conn->query($courses_query);
        
        echo "<h3>Your Courses:</h3>";
        if ($courses_result->num_rows > 0) {
            echo "<ul>";
            while ($course = $courses_result->fetch_assoc()) {
                echo "<li>" . $course['language'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>You are not enrolled in any courses.</p>";
        }
    }
} else {
    echo "<p>teacher_courses table does not exist.</p>";
}

$conn->close();
?> 