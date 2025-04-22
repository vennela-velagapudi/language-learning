<?php
// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once 'config.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access');
    }

    // Get JSON data
    $rawData = file_get_contents('php://input');
    if ($rawData === false) {
        throw new Exception('Failed to read request data');
    }

    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data received');
    }

    // Validate required fields
    if (!isset($data['language']) || !isset($data['lesson_number'])) {
        throw new Exception('Missing required fields');
    }

    // Convert lesson_number to integer
    $lessonNumber = (int)$data['lesson_number'];
    $language = $data['language'];

    // Connect to database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Start transaction
    $pdo->beginTransaction();

    try {
        // First, check if the lesson exists
        $stmt = $pdo->prepare("SELECT id FROM lessons WHERE language = ? AND lesson_number = ?");
        $stmt->execute([$language, $lessonNumber]);
        $lessonId = $stmt->fetchColumn();

        if (!$lessonId) {
            throw new Exception("Lesson not found");
        }

        // Delete lesson content (this will be handled by the foreign key cascade)
        $stmt = $pdo->prepare("DELETE FROM lesson_content WHERE language = ? AND lesson_number = ?");
        $stmt->execute([$language, $lessonNumber]);

        // Delete the lesson
        $stmt = $pdo->prepare("DELETE FROM lessons WHERE language = ? AND lesson_number = ?");
        $stmt->execute([$language, $lessonNumber]);

        // Update lesson numbers for remaining lessons
        $stmt = $pdo->prepare("UPDATE lessons SET lesson_number = lesson_number - 1 WHERE language = ? AND lesson_number > ?");
        $stmt->execute([$language, $lessonNumber]);

        // Commit transaction
        $pdo->commit();

        echo json_encode([
            'success' => true, 
            'message' => 'Lesson deleted successfully',
            'popupMessage' => 'Lesson deleted successfully!'
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?> 