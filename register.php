<?php
$servername = "localhost";
$username = "root";
$password = "patamata@07";
$dbname = "webtech";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $name = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if ($name === "" || $pass === "") {
        echo "<script>alert('Username and password cannot be empty.'); window.history.back();</script>";
        exit();
    }

    // Check if username already exists
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $name);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Username already exists. Please choose another one.');
                window.history.back();
              </script>";
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $pass);

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'loginas.html';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Form not submitted correctly.";
}

$conn->close();
?>
