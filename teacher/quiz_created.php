<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.html");
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? '';
if (empty($quiz_id)) {
    die("Invalid quiz ID");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Created Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .success-container {
            background-color: white;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            color: #4CAF50;
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Quiz Created Successfully!</h1>
        <p>Your quiz has been created and saved to the database. You can now view or edit it from your dashboard.</p>
        <div>
            <a href="dashboard.php" class="btn">Go to Dashboard</a>
            <a href="view_quiz.php?id=<?php echo htmlspecialchars($quiz_id); ?>" class="btn">View Quiz</a>
        </div>
    </div>
</body>
</html> 