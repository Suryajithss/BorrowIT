<?php
// Database configuration
$host = 'localhost'; // or your database host
$user = 'root';  // your database username
$password = ''; // your database password
$dbname = 'testdata'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
