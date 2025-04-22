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
    // Get the raw POST data
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid data format']);
        exit();
    }
    
    $teacher_id = $_SESSION['user_id'];
    $language = $data['language'];
    $title = $data['title'];
    $description = $data['description'];
    $lessons = $data['lessons'];
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Delete existing content for this course
        $delete_query = "DELETE FROM course_content WHERE teacher_id = ? AND language = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("is", $teacher_id, $language);
        $stmt->execute();
        
        // Save each lesson
        foreach ($lessons as $lessonNumber => $lesson) {
            $lessonData = [
                'title' => $lesson['title'],
                'content' => $lesson['content'],
                'subLessons' => $lesson['subLessons']
            ];
            
            $content = json_encode($lessonData);
            
            $insert_query = "INSERT INTO course_content (teacher_id, language, lesson_number, content) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isis", $teacher_id, $language, $lessonNumber, $content);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Course saved successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error saving course: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 