<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if ($new_pass !== $confirm_pass) {
        echo "<script>
                alert('Passwords do not match.');
                window.history.back();
              </script>";
        exit();
    }

    // Check if username exists
    $check_user_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>
                alert('Username not found.');
                window.history.back();
              </script>";
    } else {
        // Update password
        $update_query = "UPDATE users SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $new_pass, $username);

        if ($update_stmt->execute()) {
            echo "<script>
                    alert('Password updated successfully!');
                    window.location.href = 'index.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating password.');
                    window.history.back();
                  </script>";
        }

        $update_stmt->close();
    }

    $stmt->close();
}

$conn->close();
?>
