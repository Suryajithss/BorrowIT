<?php
session_start(); // Start the session

// Retrieve product ID and payment type from query string
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : "cod";

// Variables to store payment details
$card_name = $card_number = $expiry_date = $cvv = $paypal_id = $gpay_id = null;

// Handle payment details based on payment type
if ($type === "card") {
    $card_name = $_POST['name'] ?? null;
    $card_number = $_POST['card_number'] ?? null;
    $expiry_date = $_POST['expiry_date'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
} elseif ($type === 'paypal') {
    $paypal_id = $_POST['paypal_id'] ?? null;
} elseif ($type === 'gpay') {
    $gpay_id = $_POST['gpay_id'] ?? null;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "testdata");
$uid = $_SESSION['UID'] ?? 0; // Handle missing session UID
$amount = $_SESSION['amount_paid'];

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure product ID and user session are valid
if ($productId > 0 && $uid > 0) {
    // Update product status to 'not-available'
	// Prepare the first SQL query to get the sales value.
	$sql = "SELECT sales FROM products WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $productId);
	$stmt->execute();
	$stmt->bind_result($sale);
	$stmt->fetch();
	$stmt->close(); // Close the first statement
	echo $sale;
	$status = 'not-available';
	echo $status;
	
	$sales=$sale + 1;
	echo $sales;
	// Prepare the second SQL query to update the product.
	$sql = "UPDATE products SET status = ?, sales = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	
	$stmt->bind_param("sii", $status, $sales, $productId);
	$stmt->execute();
	$stmt->close(); // Close the statement
	
	// Geting Location
	$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
	$stmt->bind_param("i", $productId);
	$stmt->execute();
	$result = $stmt->get_result();

	// Check if there are booking records
	if ($result->num_rows > 0){
		// Output booking details
		while ($row = $result->fetch_assoc()) {
			$location = $row['location'];
		}
	}

    // Retrieve booking dates from session
    $b_sdate = $_SESSION['booking_start_date'] ?? null;
    $b_edate = $_SESSION['booking_end_date'] ?? null;

    // Validate booking dates
    if ($b_sdate && $b_edate) {
        $startDate = DateTime::createFromFormat('Y-m-d', $b_sdate);
        $endDate = DateTime::createFromFormat('Y-m-d', $b_edate);

        if ($startDate && $endDate && $startDate <= $endDate) {
            // Insert booking details into bookings table
            $sql2 = "INSERT INTO bookings (user_id, rental_item_id, start_date, end_date, location, created_at) 
                     VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("iisss", $uid, $productId, $b_sdate, $b_edate, $location);

            if ($stmt2->execute()) {
                // Get the booking ID
                $bookingId = $conn->insert_id;
                $_SESSION['bid'] = $bookingId;

                // Insert payment details into payment_details table
                $sql3 = "INSERT INTO payment_details 
                         (bookingID, type, card_name, card_number, expiry_date, CVV, paypal_id, gpay_id, amount_paid) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param(
                    "isssssssi", // 8 placeholders: 1 integer, 7 strings
                    $bookingId, $type, $card_name, $card_number, $expiry_date, $cvv, $paypal_id, $gpay_id, $amount);

                if ($stmt3->execute()) {
                    echo "Payment successfully recorded!";
                    header("Location: OrderWait.php"); // Redirect to order wait page
                    exit;
                } else {
                    echo "Error inserting payment details: " . htmlspecialchars($stmt3->error);
                }
                $stmt3->close();
            } else {
                echo "Error adding booking: " . htmlspecialchars($stmt2->error);
            }
            $stmt2->close();
        } else {
            echo "Invalid dates provided. Ensure they are in YYYY-MM-DD format and the start date is before the end date.";
        }
    } else {
        echo "Invalid booking dates provided.";
    }
} else {
    echo "Invalid product ID or user session.";
}

$conn->close(); // Close the database connection
?>
