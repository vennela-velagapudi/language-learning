<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all grades for the user
$stmt = $conn->prepare("SELECT language, marks, attempt_date FROM grades WHERE user_id = ? ORDER BY attempt_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Grades</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(to right, #1a1a2e, #0f3460);
            color: #fff;
            text-align: center;
            padding-top: 70px;
            min-height: 100vh;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 50px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 0;
            z-index: 1000;
            background: linear-gradient(to right, #1a1a2e, #0f3460);
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

        nav a:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
        }

        h1 {
            color: #e94560;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .grades-container {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        .grade-item {
            background: rgba(255, 255, 255, 0.15);
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grade-info {
            text-align: left;
        }

        .grade-marks {
            font-size: 1.2em;
            font-weight: bold;
            color: #e94560;
        }

        .no-grades {
            padding: 20px;
            color: #ccc;
        }

        /* Add profile menu styles */
        .profile-container {
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: right;
            z-index: 9999; /* Increased z-index */
        }

        .profile-btn {
            font-size: 2rem;
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
            margin-top: -0px;
        }

        .profile-btn:hover {
            transform: scale(1.1);
        }

        .profile-menu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            color: #000;
            padding: 15px;
            border-radius: 10px;
            width: 200px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 1rem;
            text-align: left;
            z-index: 3;
        }

        .profile-menu p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-menu a {
            color: #e94560;
            text-decoration: none;
            display: block;
            margin-top: 12px;
            font-size: 1rem;
            font-weight: 600;
            padding: 8px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .profile-menu a:hover {
            background-color: #f1f1f1;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="#" onclick="confirmLogout()">Home</a>
            <a href="./student dashboard.html">Dashboard</a>
            <a href="./courses.html">Manage Courses</a>
            <a href="./quiz.html">Quizzes</a>
            <a href="grade.php">Grades</a>

            
        </nav>
        <!-- Profile Dropdown -->
        <div class="profile-container">
            <div class="profile-btn" onclick="toggleProfileMenu()">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="profile-menu" id="profileMenu">
                <p>username: <span id="displayUsername">Loading...</span></p>
                <a href="#" onclick="logout(event)">Logout</a>
            </div>
        </div>
    </header>

    <h1>Your Quiz Grades</h1>
    
    <div class="grades-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="grade-item">
                    <div class="grade-info">
                        <strong><?php echo htmlspecialchars($row['language']); ?></strong><br>
                        <small>Attempted on: <?php echo date('F j, Y g:i A', strtotime($row['attempt_date'])); ?></small>
                    </div>
                    <div class="grade-marks">
                        <?php echo $row['marks']; ?>/100
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-grades">
                No quiz attempts found. Take a quiz to see your grades here!
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Fetch and display username
        function fetchUsername() {
            fetch('get_username.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('displayUsername').textContent = data.username;
                    } else {
                        document.getElementById('displayUsername').textContent = 'Not logged in';
                    }
                })
                .catch(error => {
                    console.error('Error fetching username:', error);
                    document.getElementById('displayUsername').textContent = 'Error loading username';
                });
        }

        // Call fetchUsername when the page loads
        document.addEventListener('DOMContentLoaded', fetchUsername);

        // Toggle profile dropdown menu
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        // Hide dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('profileMenu');
            const button = document.querySelector('.profile-btn');
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.style.display = 'none';
            }
        });

        // Logout functionality
        function logout(event) {
            event.preventDefault();
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../index.html";
            }
        }
        function confirmLogout() {
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = "../index.html";
            }
        }

    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?> 