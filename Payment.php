<?php
session_start(); 
$isLoggedIn = isset($_SESSION['EMAIL']); 
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$b_name = isset($_POST['name']) ? (int)$_POST['name'] : 0;
$b_email = isset($_POST['email']) ? (int)$_POST['email'] : 0;
$b_sdate = isset($_POST['start_date']) ? $_POST['start_date'] : null; 
$b_edate = isset($_POST['end_date']) ? $_POST['end_date'] : null;


// Store values in session variables
if ($productId) {
    $_SESSION['booking_pid'] = $productId;
}
if ($b_name) {
    $_SESSION['booking_name'] = $b_name;
}
if ($b_email) {
    $_SESSION['booking_email'] = $b_email;
}
if ($b_sdate) {
    $_SESSION['booking_start_date'] = $b_sdate;
}
if ($b_edate) {
    $_SESSION['booking_end_date'] = $b_edate;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "testdata");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$product = null;
$price = null; // Initialize price variable
if ($productId > 0) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
	
    
    if ($stmt) { // Check if prepare was successful
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            $price = $product['price'];
			$_SESSION['amount_paid'] = $price;
        } else {
            echo "Product not found.";
        }

        $stmt->close(); // Close statement here
    } else {
        echo "Failed to prepare statement.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            line-height: 1.6;
        }

        /* Header Styling */
        .header {
            background-color: #ffc107;
            padding: 20px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo img {
            height: 50px;
        }

        .nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav a {
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            color: #333;
            transition: color 0.3s ease;
        }

        .nav a:hover {
            color: #007BFF;
        }

        /* Main content */
        .main-container {
            margin: 40px auto;
            max-width: 1200px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Payment form styling */
        .payment-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: none; /* Hide payment form by default */
        }

        .payment-form label {
            font-weight: 500;
            margin: 10px 0 5px;
            display: block;
        }

        .payment-form input, .payment-form select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .payment-form input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .payment-form button {
            padding: 12px;
            background-color: #ffc107;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 10px;
        }

        .payment-form button:hover {
            background-color: #e0a800;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
            }
        }

        /* Additional styling for improved aesthetics */
        .payment-form h3 {
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
        }

        /* Styles for the select dropdown */
        select {
            appearance: none; /* Remove default styling */
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABrUlEQVR42mJ8//8/AyYAfQ+CEGAAABnE5wDEQOEoAHMFSgBgwKzClCAvF4DG8AuD8BbGokJqREwABAgAYGAAZ8AwAAHBgBhCADIBTgyGABoAqgwAgCz+GHSYmQCAAADs8MD7YBhAMAAAADmA7NEuA4AAAAAElFTkSuQmCC') no-repeat right;
            background-size: 12px;
            padding-right: 30px; /* Space for dropdown arrow */
        }
    </style>
    <script>
        function togglePaymentForm() {
            const paymentType = document.getElementById('payment_type').value;
            const cardForm = document.getElementById('card_form');
            const paypalForm = document.getElementById('paypal_form');

            // Hide all forms initially
            cardForm.style.display = 'none';
            paypalForm.style.display = 'none';

            // Show the selected form
            if (paymentType === 'credit_card') {
                cardForm.style.display = 'block';
            } else if (paymentType === 'paypal') {
                paypalForm.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="HomePage.php">
                    <img src="Images/Logo/Logo2.png" alt="Company Logo">
                </a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="Index.php">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a id="account-link" href="<?php echo $isLoggedIn ? 'account.php' : 'login.html'; ?>">
                        <?php echo $isLoggedIn ? 'Account' : 'Log In'; ?>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <h2>Payment Details</h2>
        <label for="payment_type">Select Payment Type</label>
        <select id="payment_type" onchange="togglePaymentForm()">
            <option value="">-- Choose Payment Type --</option>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
        </select>

        <!-- Credit Card Payment Form -->
        <form class="payment-form" id="card_form" action="Processing.php?id=<?php echo $productId; ?>&type=card" method="POST">
            <h3>Credit Card Payment</h3>
            <label for="name">Name on Card</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name as on card">
            
            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" required placeholder="Enter your card number">
            
            <label for="expiry_date">Expiry Date</label>
            <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/YY">
            
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" required placeholder="Enter your CVV">
            
            <label for="amount">Amount</label>
            <input type="text" id="amount" name="amount" value="<?php echo '₹ ' . htmlspecialchars($price); ?>" readonly>
            
            <button type="submit">Pay Now</button>
        </form>

        <!-- PayPal Payment Form -->
        <form class="payment-form" id="paypal_form" action="Processing.php?id=<?php echo $productId; ?>&type=paypal"  method="POST">
            <h3>PayPal Payment</h3>
            <p>Amount: <strong><?php echo '₹ ' .htmlspecialchars($price); ?></strong></p>
			<label for="paypal_amount">Paypal ID</label>
			<input type="text" name="paypal_id" >
            <button type="submit">Pay with PayPal</button>
        </form>
    </div>
</body>
</html>