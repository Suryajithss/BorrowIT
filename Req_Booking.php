<?php
session_start(); 
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Database connection
$conn = new mysqli("localhost", "root", "", "testdata");
$uid = $_SESSION['UID'] ?? 0; // Handle missing session UID

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($productId > 0 && $uid > 0) {
    // Define start and end dates (example: today to 7 days later)
    $b_sdate = date('Y-m-d'); 
    $b_edate = date('Y-m-d', strtotime('+7 days'));

    // Insert booking request with status 'Pending'
    $sql2 = "INSERT INTO bookings (user_id, rental_item_id, start_date, end_date, location, status, created_at) 
             VALUES (?, ?, ?, ?, 'none', 'Pending', NOW())";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("iiss", $uid, $productId, $b_sdate, $b_edate);
    $stmt2->execute();
    $stmt2->close();

    echo "Your booking request has been submitted!";
} else {
    echo "Invalid product ID or user session.";
}

$conn->close();
?>
