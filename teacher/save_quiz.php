<?php
header('Content-Type: application/json');
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "patamata@07";
$dbname = "webtech";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception("Invalid input data");
    }
    
    // Get teacher ID from session
    $teacher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    if ($teacher_id == 0) {
        throw new Exception("Teacher not logged in");
    }
    
    // Start transaction
    $conn->beginTransaction();
    
    try {
        // Check if quiz exists
        $stmt = $conn->prepare("SELECT id FROM quizzes WHERE teacher_id = ? AND language = ?");
        $stmt->execute([$teacher_id, $data['language']]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quiz) {
            // Update existing quiz
            $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$data['title'], $data['description'], $quiz['id']]);
            $quiz_id = $quiz['id'];
            
            // Delete existing questions
            $stmt = $conn->prepare("DELETE FROM quiz_questions WHERE quiz_id = ?");
            $stmt->execute([$quiz_id]);
        } else {
            // Insert new quiz
            $stmt = $conn->prepare("INSERT INTO quizzes (teacher_id, language, title, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$teacher_id, $data['language'], $data['title'], $data['description']]);
            $quiz_id = $conn->lastInsertId();
        }
        
        // Insert questions
        $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question_number, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($data['questions'] as $index => $question) {
            $stmt->execute([
                $quiz_id,
                $index + 1,
                $question['text'],
                $question['options']['A'],
                $question['options']['B'],
                $question['options']['C'],
                $question['options']['D'],
                $question['correct']
            ]);
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Quiz saved successfully'
        ]);
        
    } catch(Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        throw $e;
    }
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 