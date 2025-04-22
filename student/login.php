<?php
session_start();

$host = 'localhost';
$db = 'webtech';  // Change this to your database name
$user = 'root';
$pass = 'patamata@07';  // Update this to your DB password if necessary

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];  // Use name attributes for POST values
    $password = $_POST['password'];

    // Query to check user in the database
    $sql = "SELECT * FROM users WHERE username = ?";  // Adjust table and column names accordingly
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check password (this example doesn't use hashing yet)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session
            header('Location: student dashboard.html');  // Redirect to dashboard or the home page
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No user found with that username.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
