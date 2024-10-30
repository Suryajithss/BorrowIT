<?php
// Start session to retrieve booking ID
session_start();

// Check if either session 'bid' or GET 'id' is available
$bookingId = isset($_SESSION['bid']) ? $_SESSION['bid'] : (isset($_GET['id']) ? $_GET['id'] : null);

if ($bookingId === null) {
    die("Booking ID not found!");
}

// Database connection parameters
$host = 'localhost';
$dbname = 'testdata';
$username = 'root';
$password = '';

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to retrieve booking and payment details
    $stmt = $pdo->prepare("
        SELECT b.id, b.user_id, b.rental_item_id, b.start_date, b.end_date, 
               b.location, b.created_at, p.type, p.card_name, p.card_number, 
               p.expiry_date, p.paypal_id, p.gpay_id, p.amount_paid
        FROM bookings b
        JOIN payment_details p ON b.id = p.bookingID
        WHERE b.id = :bookingId
    ");
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receipt) {
        die("Receipt not found!");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* CSS styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
        }
        .details-section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .notice {
            font-size: 0.9em;
            color: #888;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="Images/Logo/LogoBlack.png" alt="Company Logo">
            <div>
                <h2>Rental Service Pvt. Ltd.</h2>
                <p>123 Street, City, India - 560001</p>
                <p>Email: support@rentalservice.com</p>
                <p>Phone: +91-9876543210</p>
            </div>
        </div>

        <div class="details-section">
            <h3>Booking Details</h3>
            <table>
                <tr><th>Booking ID</th><td><?php echo htmlspecialchars($receipt['id']); ?></td></tr>
                <tr><th>User ID</th><td><?php echo htmlspecialchars($receipt['user_id']); ?></td></tr>
                <tr><th>Product ID</th><td><?php echo htmlspecialchars($receipt['rental_item_id']); ?></td></tr>
                <tr><th>Start Date</th><td><?php echo htmlspecialchars($receipt['start_date']); ?></td></tr>
                <tr><th>End Date</th><td><?php echo htmlspecialchars($receipt['end_date']); ?></td></tr>
                <tr><th>Location</th><td><?php echo htmlspecialchars($receipt['location']); ?></td></tr>
                <tr><th>Booking Date</th><td><?php echo htmlspecialchars($receipt['created_at']); ?></td></tr>
            </table>
        </div>

        <div class="details-section">
            <h3>Payment Details</h3>
            <table>
                <tr><th>Payment Type</th><td><?php echo htmlspecialchars($receipt['type']); ?></td></tr>
                <?php if ($receipt['type'] === 'card') { ?>
                    <tr><th>Card Name</th><td><?php echo htmlspecialchars($receipt['card_name']); ?></td></tr>
                    <tr><th>Card Number</th><td>**** **** **** <?php echo substr(htmlspecialchars($receipt['card_number']), -4); ?></td></tr>
                    <tr><th>Expiry Date</th><td><?php echo htmlspecialchars($receipt['expiry_date']); ?></td></tr>
                <?php } elseif ($receipt['type'] === 'paypal') { ?>
                    <tr><th>PayPal ID</th><td><?php echo htmlspecialchars($receipt['paypal_id']); ?></td></tr>
                <?php } elseif ($receipt['type'] === 'gpay') { ?>
                    <tr><th>GPay ID</th><td><?php echo htmlspecialchars($receipt['gpay_id']); ?></td></tr>
                <?php } ?>
                <tr><th>Amount Paid</th><td>₹<?php echo htmlspecialchars($receipt['amount_paid']); ?></td></tr>
            </table>
        </div>

        <div class="total">Total Amount: ₹<?php echo htmlspecialchars($receipt['amount_paid']); ?></div>

        <div class="footer">
            <p>Thank you for choosing Rental Service Pvt. Ltd.!</p>
            <a href="index.php">Go to Homepage</a>
            <p class="notice">* Please retain this receipt for future reference.</p>
        </div>
    </div>
</body>
</html>
