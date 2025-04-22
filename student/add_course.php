<?php
session_start();
include '../connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log session information
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log POST data
    error_log("POST data: " . print_r($_POST, true));
    
    $language = $_POST['language'];
    $student_id = $_SESSION['user_id'];
    
    // Log the received data
    error_log("Adding course: Language = $language, Student ID = $student_id");
    
    // Simple direct query to check if the table exists
    $check_table = "SHOW TABLES LIKE 'student_courses'";
    $table_result = $conn->query($check_table);
    
    if ($table_result->num_rows == 0) {
        // Table doesn't exist, create it
        $create_table = "CREATE TABLE student_courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            language VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_student_course (student_id, language)
        )";
        
        if ($conn->query($create_table) === TRUE) {
            error_log("Table student_courses created successfully");
        } else {
            error_log("Error creating table: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Error creating table: ' . $conn->error]);
            exit();
        }
    }
    
    // Check if course already exists for this student
    $check_query = "SELECT * FROM student_courses WHERE student_id = ? AND language = ?";
    $stmt = $conn->prepare($check_query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("is", $student_id, $language);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You are already enrolled in this course']);
        exit();
    }
    
    // Add the course using a direct query for simplicity
    $insert_query = "INSERT INTO student_courses (student_id, language) VALUES ($student_id, '$language')";
    error_log("Executing query: " . $insert_query);
    
    if ($conn->query($insert_query) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Course added successfully']);
    } else {
        error_log("Error adding course: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Error adding course: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 