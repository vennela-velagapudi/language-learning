<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "patamata@07";
$dbname = "webtech";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get language from query parameter
    $language = $_GET['language'] ?? '';
    
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM lessons WHERE language = :language ORDER BY lesson_number");
    $stmt->bindParam(':language', $language);
    $stmt->execute();
    
    $lessons = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lessons[] = [
            'lesson_number' => $row['lesson_number'],
            'name' => $row['name'],
            'sub_lessons' => json_decode($row['sub_lessons'], true)
        ];
    }
    
    echo json_encode(['success' => true, 'lessons' => $lessons]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?> 