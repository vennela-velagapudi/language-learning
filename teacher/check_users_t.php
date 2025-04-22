<?php
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Users Table Check</h2>";

// Check if the users table exists
$check_table = "SHOW TABLES LIKE 'users'";
$table_result = $conn->query($check_table);

if ($table_result->num_rows > 0) {
    echo "<p>users table exists.</p>";
    
    // Show table structure
    $describe = "DESCRIBE users";
    $desc_result = $conn->query($describe);
    
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $desc_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show all users
    $users_query = "SELECT * FROM users";
    $users_result = $conn->query($users_query);
    
    echo "<h3>All Users:</h3>";
    if ($users_result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
        while ($user = $users_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['password'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found in the database.</p>";
    }
} else {
    echo "<p>users table does not exist.</p>";
    
    // Create the users table
    echo "<p>Creating users table...</p>";
    $create_table = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($create_table) === TRUE) {
        echo "<p>users table created successfully.</p>";
        
        // Add a test user
        $add_user = "INSERT INTO users (username, password) VALUES ('test', 'test')";
        if ($conn->query($add_user) === TRUE) {
            echo "<p>Test user added successfully.</p>";
        } else {
            echo "<p>Error adding test user: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Error creating users table: " . $conn->error . "</p>";
    }
}

$conn->close();
?> 