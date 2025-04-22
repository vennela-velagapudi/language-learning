<?php
session_start();
include '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language = $_POST['language'];
    $student_id = $_SESSION['user_id'];
    
    // Delete the course enrollment
    $query = "DELETE FROM student_courses WHERE student_id = ? AND language = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $student_id, $language);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Successfully unenrolled from course']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error unenrolling from course']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 