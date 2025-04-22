<?php
$servername = "localhost";
$username = "root";
$password = "patamata@07";
$dbname = "webtech";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 