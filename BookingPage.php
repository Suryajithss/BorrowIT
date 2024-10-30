<?php
session_start(); 
$isLoggedIn = isset($_SESSION['EMAIL']); 
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Database connection
$conn = new mysqli("localhost", "root", "", "testdata");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$product = null;
if ($productId > 0) {
    $sql = "SELECT id, name, price, image,location, description FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
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
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        /* Header Styling */
        .header {
            background-color: #ffc107;
            padding: 20px 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        .product-details {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 40px auto;
            max-width: 800px;
        }

        .product-details img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-name {
            font-size: 2.5rem;
            margin: 15px 0;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }

        .product-price {
            font-size: 1.8rem;
            color: #ffca28;
            margin: 10px 0;
            font-weight: 700;
        }

        .product-description {
            font-size: 1.1rem;
            margin: 10px 0 20px 0;
            line-height: 1.8;
            color: #666;
        }

        .booking-container {
            display: flex;
            flex-direction: column;
            margin-top: 30px;
            background: #f1f1f1;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .booking-label {
            font-weight: bold;
            margin-top: 10px;
        }

        .booking-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .booking-input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .booking-button {
            padding: 12px;
            background-color: #ffc107;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            font-size: 1.1rem;
            width: 100%;
            margin-top: 20px;
        }

        .booking-button:hover {
            background-color: #e0a800;
        }

        .date-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .date-input {
            flex: 1; /* Make inputs take equal space */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header .container {
                flex-direction: column;
                align-items: flex-start;
            }

            .header nav ul {
                flex-direction: column;
                margin-top: 10px;
            }

            .product-details {
                padding: 20px;
            }

            .product-name {
                font-size: 1.8rem;
            }

            .product-price {
                font-size: 1.5rem;
            }

            .date-container {
                flex-direction: column; /* Stack inputs on small screens */
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="HomePage.php">
                    <img src="Images/Logo/Logo2.png" alt="Company Logo" height="40">
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

    <div class="product-details">
        <?php if ($product): ?>
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h2 class="product-name"><?php echo $product['name']; ?></h2>
            <div class="product-price">₹<?php echo $product['price']; ?></div>
			<div class="product-price">₹<?php echo $product['location']; ?></div>
            <p class="product-description"><?php echo $product['description']; ?></p>
            
            <div class="booking-container">
                <center><h2><span class="booking-label">Booking Information</span></h2></center>
                
				<form action="Payment.php?id=<?php echo $productId; ?>" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <label for="name" class="booking-label">Name</label>
                    <input type="text" id="name" name="name" class="booking-input" required>

                    <label for="email" class="booking-label">Email</label>
                    <input type="email" id="email" name="email" class="booking-input" required>

                    <label for="phone" class="booking-label">Phone</label>
                    <input type="text" id="phone" name="phone" class="booking-input" required>

                    <center><label class="booking-label">Booking Dates</label></center>
                    <div class="date-container">
						<label for="start_date" class="booking-label">Starting Date</label><br>
                        <input type="date" id="start_date" name="start_date" class="booking-input date-input" required>
						<label for="end_date" class="booking-label">Ending Date</label><br>
                        <input type="date" id="end_date" name="end_date" class="booking-input date-input" required>
                    </div>
	
                    <button type="submit" class="booking-button">Confirm Booking</button>
                </form>
            </div>
        <?php else: ?>
            <p style="color: red; font-weight: bold;">Product not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

