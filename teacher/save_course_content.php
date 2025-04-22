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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log all POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    $teacher_id = $_SESSION['user_id'];
    $language = isset($_POST['language']) ? $_POST['language'] : '';
    $lesson_number = isset($_POST['lesson_number']) ? $_POST['lesson_number'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';

    // Validate input
    if (empty($language) || empty($lesson_number) || empty($content)) {
        error_log("Missing required fields - Language: $language, Lesson: $lesson_number, Content: " . substr($content, 0, 100) . "...");
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    // Log received data
    error_log("Processing save request - Teacher ID: $teacher_id, Language: $language, Lesson: $lesson_number");
    error_log("Content length: " . strlen($content));

    try {
        // Check if content already exists for this lesson
        $check_query = "SELECT * FROM course_content WHERE teacher_id = ? AND language = ? AND lesson_number = ?";
        $stmt = $conn->prepare($check_query);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            exit();
        }
        
        $stmt->bind_param("isi", $teacher_id, $language, $lesson_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing content
            $update_query = "UPDATE course_content SET content = ? WHERE teacher_id = ? AND language = ? AND lesson_number = ?";
            $stmt = $conn->prepare($update_query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            $stmt->bind_param("sisi", $content, $teacher_id, $language, $lesson_number);
        } else {
            // Insert new content
            $insert_query = "INSERT INTO course_content (teacher_id, language, lesson_number, content) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit();
            }
            $stmt->bind_param("isis", $teacher_id, $language, $lesson_number, $content);
        }

        if ($stmt->execute()) {
            error_log("Content saved successfully");
            echo json_encode(['success' => true, 'message' => 'Content saved successfully']);
        } else {
            error_log("Execute failed: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error saving content: ' . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception occurred: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 