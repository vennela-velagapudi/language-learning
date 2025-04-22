<?php
session_start();
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You are not logged in. Please <a href='slogin.html'>login</a> first.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if student_courses table exists
$check_table = "SHOW TABLES LIKE 'student_courses'";
$table_result = $conn->query($check_table);

if ($table_result->num_rows == 0) {
    // Table doesn't exist, create it
    $create_table = "CREATE TABLE student_courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        language VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_student_course (student_id, language)
    )";

    if ($conn->query($create_table) === TRUE) {
        echo "<p>Table student_courses created successfully.</p>";
    } else {
        echo "<p>Error creating table: " . $conn->error . "</p>";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language = $_POST['language'];

    // Check if course already exists for this student
    $check_query = "SELECT * FROM student_courses WHERE student_id = $user_id AND language = '$language'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $message = "You are already enrolled in $language.";
    } else {
        // Add the course
        $insert_query = "INSERT INTO student_courses (student_id, language) VALUES ($user_id, '$language')";

        if ($conn->query($insert_query) === TRUE) {
            $message = "Course $language added successfully.";
        } else {
            $message = "Error adding course: " . $conn->error;
        }
    }
}

// Show existing courses for this user
$courses_query = "SELECT * FROM student_courses WHERE student_id = $user_id";
$courses_result = $conn->query($courses_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(to right, #1a1a2e, #0f3460),
                        url('https://via.placeholder.com/1500x1000/1a1a2e/ffffff?text=Language+Learning+App') no-repeat center center/cover;
            color: #fff;
            text-align: center;
            box-sizing: border-box;
            flex-direction: column;
            width: 100%;
            height: 100%;
        }

        header {
            width: 100%;
            position: absolute;
            top: 20px;
            left: 0;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 50px;
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: -15px;
        }

        nav::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        nav a {
            text-decoration: none;
            color: #fff;
            font-size: 1.1rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            transition: background 0.3s, transform 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
        }

        h1 {
            margin-bottom: 2rem;
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .content {
            width: 100%;
            max-width: 1200px;
            text-align: center;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: #fff;
        }

        .add-course-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
        }

        .add-course-form select,
        .add-course-form button {
            font-size: 1.1rem;
            padding: 10px;
            margin-bottom: 20px;
            width: 200px;
            border-radius: 5px;
            border: none;
            background-color: #fff;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .add-course-form button {
            background-color: #e94560;
            color: white;
        }

        .add-course-form select:hover,
        .add-course-form button:hover {
            background-color: #ffffff;
            transform: scale(1.05);
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }

        footer a {
            color: #00d9ff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        select[name="language"],
        input[type="submit"] {
            font-size: 1.1rem;
            padding: 10px;
            margin-top: 20px;
            width: 220px;
            border-radius: 5px;
            border: none;
            background-color: #fff;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        input[type="submit"] {
            background-color: #e94560;
            color: white;
            margin-top: 10px;
        }

        select[name="language"]:hover,
        input[type="submit"]:hover {
            background-color: #ffffff;
            transform: scale(1.05);
        }

        .back-button {
            display: inline-block;
            text-align: center;
            background-color: #0f3460;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .back-button:hover {
            background-color: #1a1a2e;
            transform: scale(1.05);
        }

        .profile-menu a:hover {
            background-color: #f1f1f1;
            text-decoration: none;
        }

        /* Popup styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #333;
            font-size: 1.1rem;
            max-width: 80%;
            animation: fadeIn 0.3s ease-in-out;
        }

        .alert-popup {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 1rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="#" onclick="confirmLogout()">Home</a>
        <a id="logoutLink" href="../index.html" style="display: none;"></a>
        <a href="./student dashboard.html">Dashboard</a>
        <a href="./courses.html">Manage Courses</a>
        <a href="./quiz.html">Quizzes</a>
        <a href="./grades.html">Grades</a>
    </nav>
</header>

<h2>Add a New Course</h2>

<form method="post" action="">
    <select name="language" required>
        <option value="" disabled selected>Select Course</option>
        <option value="Spanish">Spanish</option>
        <option value="French">French</option>
        <option value="German">German</option>
    </select>
    <br>
    <input type="submit" value="Add Course">
    <br>
    <br>
    <br>
    <a href="courses.html" class="back-button">Back to Courses</a>
</form>

<footer>
    <p>&copy; 2025 Language Learning Website. All rights reserved. <a href="../privacy.html">Privacy Policy</a></p>
</footer>

<script>
    function confirmLogout() {
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = "../index.html";
        }
    }
</script>

<?php if (!empty($message)): ?>
<?php if (strpos($message, 'already enrolled') !== false): ?>
<script>
    alert("<?php echo addslashes($message); ?>");
</script>
<?php else: ?>
<div class="popup-overlay" id="popupOverlay">
    <div class="popup">
        <?php echo htmlspecialchars($message); ?>
    </div>
</div>
<script>
    setTimeout(() => {
        document.getElementById('popupOverlay').style.display = 'none';
    }, 2000);
</script>
<?php endif; ?>
<?php endif; ?>

</body>
</html>
