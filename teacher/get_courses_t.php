<?php
session_start();
include '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$teacher_id = $_SESSION['user_id'];

// Get courses for the logged-in teacher
$query = "SELECT language FROM teacher_courses WHERE teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row['language'];
}

echo json_encode(['success' => true, 'courses' => $courses]);

$stmt->close();
$conn->close();
?> 