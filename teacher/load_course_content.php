<?php
session_start();
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $teacher_id = $_SESSION['user_id'];
    $language = $_GET['language'];
    $lesson_number = $_GET['lesson_number'];

    // Log received data
    error_log("Loading content - Teacher ID: $teacher_id, Language: $language, Lesson: $lesson_number");

    // Get content for this lesson
    $query = "SELECT content FROM course_content WHERE teacher_id = ? AND language = ? AND lesson_number = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("isi", $teacher_id, $language, $lesson_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        error_log("Content found: " . $row['content']);
        echo json_encode(['success' => true, 'content' => $row['content']]);
    } else {
        error_log("No content found for this lesson");
        echo json_encode(['success' => true, 'content' => '']); // Return empty content if no saved content exists
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 