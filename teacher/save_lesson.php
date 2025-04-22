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

    // Get POST data
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);
    
    // Log received data
    error_log("Received data: " . $rawData);
    
    if (!$data) {
        throw new Exception('Invalid input data: ' . json_last_error_msg());
    }

    // Validate required fields
    if (empty($data['lesson_number']) || empty($data['name']) || empty($data['language'])) {
        throw new Exception('Missing required fields. Received: ' . print_r($data, true));
    }

    // Check if lesson number already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM lessons WHERE lesson_number = :lesson_number AND language = :language");
    $checkStmt->bindParam(':lesson_number', $data['lesson_number']);
    $checkStmt->bindParam(':language', $data['language']);
    $checkStmt->execute();
    
    $subLessonsJson = json_encode($data['sub_lessons']);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error encoding sub_lessons: ' . json_last_error_msg());
    }
    
    if ($checkStmt->fetchColumn() > 0) {
        // Update existing lesson
        $stmt = $conn->prepare("UPDATE lessons SET name = :name, sub_lessons = :sub_lessons 
                               WHERE lesson_number = :lesson_number AND language = :language");
    } else {
        // Insert new lesson
        $stmt = $conn->prepare("INSERT INTO lessons (lesson_number, name, language, sub_lessons) 
                               VALUES (:lesson_number, :name, :language, :sub_lessons)");
    }
    
    // Bind parameters
    $stmt->bindParam(':lesson_number', $data['lesson_number']);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':language', $data['language']);
    $stmt->bindParam(':sub_lessons', $subLessonsJson);
    
    // Execute statement
    $result = $stmt->execute();
    
    if (!$result) {
        throw new Exception('Failed to execute query: ' . print_r($stmt->errorInfo(), true));
    }
    
    echo json_encode(['success' => true, 'message' => 'Lesson saved successfully']);
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    error_log("SQL State: " . $e->getCode());
    error_log("Error Info: " . print_r($conn->errorInfo(), true));
    echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
} catch(Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?> 