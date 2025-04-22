<?php
include '../connection.php';

// Check if the users table exists
$check_table_query = "SHOW TABLES LIKE 'users'";
$result = $conn->query($check_table_query);

if ($result->num_rows == 0) {
    echo "Table 'users' does not exist. This is a problem as we need this table for login.<br>";
} else {
    echo "Table 'users' exists.<br>";
    
    // Check the structure of the table
    $describe_query = "DESCRIBE users";
    $result = $conn->query($describe_query);
    
    echo "Table structure:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . ", Type: " . $row['Type'] . "<br>";
    }
    
    // Check if there are any users in the table
    $count_query = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($count_query);
    $row = $result->fetch_assoc();
    echo "Number of users in the table: " . $row['count'] . "<br>";
}

$conn->close();
?> 