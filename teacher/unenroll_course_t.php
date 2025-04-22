<?php
session_start();
include '../connection.php';

// Get logged in user's ID
$teacher_id = $_SESSION['user_id'];

// Get the language to delete
$language = $_POST['language'];

// Simple delete query
$query = "DELETE FROM teacher_courses WHERE teacher_id = $teacher_id AND language = '$language'";
mysqli_query($conn, $query);

echo json_encode(['success' => true]);
?> 