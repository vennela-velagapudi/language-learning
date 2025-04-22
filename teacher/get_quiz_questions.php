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
    
    // Get language from query parameter
    $language = isset($_GET['language']) ? $_GET['language'] : '';
    
    if (empty($language)) {
        throw new Exception("Language parameter is required");
    }
    
    // Get teacher ID from session
    $teacher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    if ($teacher_id == 0) {
        throw new Exception("Teacher not logged in");
    }
    
    // Fetch quiz data
    $stmt = $conn->prepare("SELECT * FROM quizzes WHERE teacher_id = ? AND language = ?");
    $stmt->execute([$teacher_id, $language]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$quiz) {
        echo json_encode([
            'success' => true,
            'quiz' => null
        ]);
        exit;
    }
    
    // Fetch questions for this quiz
    $stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY question_number");
    $stmt->execute([$quiz['id']]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the response
    $formattedQuestions = [];
    foreach ($questions as $question) {
        $formattedQuestions[] = [
            'id' => $question['question_number'],
            'text' => $question['question_text'],
            'options' => [
                'A' => $question['option_a'],
                'B' => $question['option_b'],
                'C' => $question['option_c'],
                'D' => $question['option_d']
            ],
            'correct' => $question['correct_answer']
        ];
    }
    
    $response = [
        'success' => true,
        'quiz' => [
            'title' => $quiz['title'],
            'description' => $quiz['description'],
            'questions' => $formattedQuestions
        ]
    ];
    
    echo json_encode($response);
    
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