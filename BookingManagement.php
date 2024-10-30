<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'testdata';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve bookings
    $stmt = $pdo->prepare("SELECT * FROM bookings");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

// Handle different actions
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $bookingId = (int)$_GET['id']; // Ensure the ID is an integer

    // Validate action
    if (in_array($action, ['delete', 'confirm', 'decline'])) {
        switch ($action) {
            case 'delete':
			try {
				// Start transaction to ensure atomic operation
				$pdo->beginTransaction();

				// Delete payment details associated with the booking
				$stmt = $pdo->prepare("DELETE FROM payment_details WHERE bookingID = :id");
				$stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
				$stmt->execute();

				// Delete the booking details
				$stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
				$stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
				$stmt->execute();

				// Commit transaction
				$pdo->commit();

				// Redirect to avoid re-execution on refresh
				header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
				exit;
			} catch (Exception $e) {
				// Roll back the transaction if any operation fails
				$pdo->rollBack();
				echo "Error: " . $e->getMessage();
			}
			break;


            case 'confirm':
                // Get the product ID from the booking record
                $stmt = $pdo->prepare("SELECT rental_item_id FROM bookings WHERE id = :id");
                $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $pid = $result['rental_item_id'];

                    // Update the booking status to 'Confirmed'
                    $stmt = $pdo->prepare("UPDATE bookings SET status = 'Confirmed' WHERE id = :id");
                    $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
                    $stmt->execute();

                    // Update the product status to 'not-available'
                    $stmt = $pdo->prepare("UPDATE products SET status = 'not-available' AND sales = sales + 1 WHERE id = :pid");
                    $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
                    $stmt->execute();
                }
				header("Location: BookingManagement.php");
                break;

            case 'decline':
                $stmt = $pdo->prepare("UPDATE bookings SET status = 'Declined' WHERE id = :id");
                break;
        }

        $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to avoid re-execution on refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
        exit;
    } else {
        echo "Invalid action!";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        .header h1 {
            margin: 0;
        }
        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .header nav a:hover {
            text-decoration: underline;
        }
        .booking-table {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons a {
            margin-right: 10px;
        }
        .btn {
            padding: 5px 10px;
            color: white;
        }
        .btn-danger { background-color: red; }
        .btn-success { background-color: green; }
        .btn-warning { background-color: orange; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Management</h1>
            <nav>
                <a href="AdminPanel.php">Admin Panel</a>
            </nav>
        </div>

        <div class="booking-table">
            <h2 class="text-center">Booking List</h2>

            <?php if (!empty($bookings)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Product ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['rental_item_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                <td class="action-buttons">
                                    <?php if ($booking['status'] === 'Pending'): ?>
                                        <button class="btn btn-success"
                                                onclick="confirmAction('confirm', <?php echo $booking['id']; ?>)">
                                            Confirm
                                        </button>
                                        <button class="btn btn-warning"
                                                onclick="confirmAction('decline', <?php echo $booking['id']; ?>)">
                                            Decline
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($booking['status'] !== 'Pending'): ?>
                                        <button class="btn btn-danger"
                                                onclick="confirmAction('delete', <?php echo $booking['id']; ?>)">
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmAction(action, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this booking?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + action + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?action=${action}&id=${id}`;
                }
            });
        }
    </script>
</body>
</html>
