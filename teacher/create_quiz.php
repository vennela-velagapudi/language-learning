<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to get teacher_id
session_start();

// Check if user is logged in
if (!isset($_SESSION['teacher_id'])) {
    die("You must be logged in to create a quiz.");
}

$servername = "localhost";
$username = "root";
$password = "patamata@07";
$dbname = "webtech";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$language = $_POST['language'] ?? '';
$lesson_number = $_POST['lesson_number'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$teacher_id = $_SESSION['teacher_id'];

// Validate required fields
if (empty($language) || empty($lesson_number) || empty($title)) {
    die("Please fill in all required fields.");
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert quiz
    $sql = "INSERT INTO quizzes (teacher_id, language, lesson_number, title, description) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isiss", $teacher_id, $language, $lesson_number, $title, $description);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error creating quiz: " . mysqli_error($conn));
    }
    
    $quiz_id = mysqli_insert_id($conn);
    
    // Insert questions
    $question_sql = "INSERT INTO quiz_questions (quiz_id, question_text, option1, option2, option3, option4, correct_answer) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $question_stmt = mysqli_prepare($conn, $question_sql);
    
    // Process each question
    $question_count = 0;
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_count++;
            $question_text = $value;
            $option1 = $_POST["option1_$question_count"] ?? '';
            $option2 = $_POST["option2_$question_count"] ?? '';
            $option3 = $_POST["option3_$question_count"] ?? '';
            $option4 = $_POST["option4_$question_count"] ?? '';
            $correct_answer = $_POST["correct_answer_$question_count"] ?? '';
            
            // Validate question data
            if (empty($question_text) || empty($option1) || empty($option2) || 
                empty($option3) || empty($option4) || empty($correct_answer)) {
                throw new Exception("Please fill in all question fields.");
            }
            
            mysqli_stmt_bind_param($question_stmt, "isssssi", 
                $quiz_id, $question_text, $option1, $option2, $option3, $option4, $correct_answer);
            
            if (!mysqli_stmt_execute($question_stmt)) {
                throw new Exception("Error adding question: " . mysqli_error($conn));
            }
        }
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Redirect to success page
    header("Location: quiz_created.php?quiz_id=$quiz_id");
    exit();
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    die("Error: " . $e->getMessage());
} finally {
    mysqli_close($conn);
}
?> 