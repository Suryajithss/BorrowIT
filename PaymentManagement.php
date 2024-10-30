<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'testdata';
$username = 'root';
$password = '';

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize variables
    $payments = [];
    $type = ''; // Store the selected payment type

    // Check if a payment type is selected
    if (isset($_POST['payment_type'])) {
        $payment_type = $_POST['payment_type'];

        // Prepare the query based on the selected payment type
        if ($payment_type == 'credit_card') {
            $type = "card";
            $query = "SELECT bookingID, type, card_name, card_number, expiry_date, CVV 
                      FROM payment_details WHERE type = 'card'";
        } elseif ($payment_type == 'paypal') {
            $type = "paypal";
            $query = "SELECT bookingID, type, '' AS card_name, '' AS card_number, '' AS expiry_date, '' AS CVV, 
                      paypal_id, gpay_id FROM payment_details WHERE type = 'PayPal'";
        } elseif ($payment_type == 'all') {
            $type = "all";
            $query = "SELECT bookingID, type, card_name, card_number, expiry_date, CVV, 
                      paypal_id, gpay_id FROM payment_details";
        } else {
            // Default to no results
            $query = "SELECT * FROM payment_details WHERE 1=0";
        }

        // Execute the query
        $stmt = $pdo->query($query);
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Show modal if no payments are found
        if (!$payments) {
            echo "<script>
                $(document).ready(function(){
                    $('#noPaymentsModal').modal('show');
                });
            </script>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0;
        }

        .header a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .header a:hover {
            color: #ffcc00;
        }

        .card {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Details</h1>
        <nav>
            <a href="AdminPanel.php">Admin Panel</a>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Payment Type Selection Form -->
            <form method="POST" class="mb-4 text-center">
				<label><strong>Sort :</strong></label>
                <select name="payment_type" required class="form-control w-50 mx-auto mb-2">
                    <option value="">Select Payment Type</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
                <button type="submit" class="btn btn-primary">Show Details</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Payment Method</th>
                <?php
                // Display relevant headers based on the selected type
                if ($type === "card" || $type === "all") {
                    echo '<th>Card Holder Name</th>';
                    echo '<th>Card Number</th>';
                    echo '<th>Expiry Date</th>';
                    echo '<th>CVV</th>';
                }
                if ($type === "paypal" || $type === "all") {
                    echo '<th>PayPal ID</th>';
                }
                if ($type === "gpay" || $type === "all") {
                    echo '<th>GPay ID</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments): ?>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['bookingID']); ?></td>
                        <td><?php echo htmlspecialchars($payment['type']); ?></td>
                        <?php
                        if (($type === "card" || $type === "all") && !empty($payment['card_name'])) {
                            echo '<td>' . (isset($payment['card_name']) ? htmlspecialchars($payment['card_name']) : " ") . '</td>';

                            echo '<td>' . htmlspecialchars($payment['card_number']) . '</td>';
                            echo '<td>' . htmlspecialchars($payment['expiry_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($payment['CVV']) . '</td>';
                        }
                        if (($type === "paypal" || $type === "all") && !empty($payment['paypal_id'])) {
                            echo '<td>' . htmlspecialchars($payment['paypal_id']) . '</td>';
                        }
                        if (($type === "gpay" || $type === "all") && !empty($payment['gpay_id'])) {
                            echo '<td>' . htmlspecialchars($payment['gpay_id']) . '</td>';
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No payments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal for No Payments Found -->
    <div class="modal fade" id="noPaymentsModal" tabindex="-1" aria-labelledby="noPaymentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noPaymentsModalLabel">No Payments Found</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    There are no payments found for the selected payment type. Please try again with a different type.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
