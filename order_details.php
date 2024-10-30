<?php
session_start();

if (!isset($_SESSION['EMAIL'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['EMAIL'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "testdata";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare statement to fetch user ID
$stmt = $conn->prepare("SELECT UID FROM users WHERE EMAIL = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $uid = $row['UID'];
} else {
    echo "No user found with this email.";
    exit();
}

// Prepare SQL query to join bookings with products to get the property name
$sql = "
    SELECT bookings.id, bookings.start_date, bookings.end_date, 
           bookings.location, bookings.status, 
           products.name AS property_name, products.id AS product_id
    FROM bookings 
    JOIN products ON bookings.rental_item_id = products.id 
    WHERE bookings.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        .header nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }
        .header nav a:hover {
            background-color: #45a049;
        }
        .dashboard {
            max-width: 1200px;
            margin: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden; /* Ensure borders are rounded */
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-bookings {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>';

echo '<div class="header">';
echo '<h1>Welcome to Your Dashboard</h1>';
echo '<nav>';
echo '<a href="index.php">Home</a>';
echo '<a href="account.php">Account</a>';
echo '<a href="logout.php">Logout</a>';
echo '</nav>';
echo '</div>';

echo '<div class="dashboard">';
echo '<h1>Booking History</h1>';
echo '<table>';
echo '<tr>
        <th>Booking ID</th>
        <th>Starting Date</th>
        <th>Ending Date</th>
        <th>Property Name</th>
        <th>Location</th>
        <th>Status</th>
        <th>Bill</th>
      </tr>';

// Check if there are booking records
if ($result->num_rows > 0) {
    // Output booking details
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['start_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
        
        // Add a hyperlink on the property name
        echo '<td><a href="productpage.php?id=' . $row['product_id'] . '" style="text-decoration:none; color: #4CAF50;">' 
            . htmlspecialchars($row['property_name']) . '</a></td>';
        
        echo '<td>' . htmlspecialchars($row['location']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td><a href="Receipt.php?id=' . $row['id'] . '" style="text-decoration:none;">View</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7" class="no-bookings">No bookings found for this user.</td></tr>';
}

echo '</table>';
echo '</div>';
echo '</body></html>';

// Close the statement and connection
$stmt->close();
$conn->close();
?>
