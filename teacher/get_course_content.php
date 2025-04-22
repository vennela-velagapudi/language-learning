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
    $teacher_id = $_SESSION['user_id'];
    $language = $_POST['language'];
    
    try {
        // Get all lessons for this course
        $query = "SELECT lesson_number, content FROM course_content WHERE teacher_id = ? AND language = ? ORDER BY lesson_number";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $teacher_id, $language);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $lessons = [];
        while ($row = $result->fetch_assoc()) {
            $lessonData = json_decode($row['content'], true);
            if ($lessonData) {
                $lessons[] = $lessonData;
            }
        }
        
        // Get course title and description from the first lesson if available
        $title = '';
        $description = '';
        if (!empty($lessons)) {
            $title = $language . ' for Beginners';
            $description = 'Learn the basics of ' . $language . ' language including vocabulary, grammar, and pronunciation';
        }
        
        echo json_encode([
            'success' => true,
            'title' => $title,
            'description' => $description,
            'lessons' => $lessons
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error loading course content: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 