<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $language = $_POST['language'];
    $marks = $_POST['marks'];

    $stmt = $conn->prepare("INSERT INTO grades (user_id, language, marks) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $language, $marks);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?> 