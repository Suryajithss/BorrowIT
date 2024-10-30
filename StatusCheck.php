<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'testdata';

// Establish connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date in 'YYYY-MM-DD' format
$today = date('Y-m-d');

// SQL query to fetch items where rental has ended
$sql = "SELECT rental_item_id FROM bookings WHERE end_date = '$today'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through the result and update the status of each item
    while ($row = $result->fetch_assoc()) {
        $itemId = $row['rental_item_id'];

        // Update the status to 'available' for the returned item
        $updateSql = "UPDATE products SET status='available' WHERE id = $itemId";
        
        
    }
}

// Close the connection
$conn->close();
?>
