<?php
session_start();
$isLoggedIn = isset($_SESSION['EMAIL']);
$userName = $isLoggedIn ? $_SESSION['FNAME'] : '';

$host = 'localhost';
$db = 'testdata';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare("SELECT name, price, description, image, location FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found.");
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit;
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Rental Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Roboto', sans-serif;
			background-color: #f8f9fa;
			color: #333;
		}

		/* Header */
		.header {
			background-color: #ffc107;
			padding: 20px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			position: sticky;
			top: 0;
			z-index: 1000;
		}

		.header .container {
			max-width: 1200px;
			margin: 0 auto;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.logo img {
			height: 50px;
		}

		.nav ul {
			list-style: none;
			display: flex;
			gap: 20px;
		}

		.nav a {
			text-decoration: none;
			color: #333;
			font-weight: 500;
			transition: color 0.3s;
		}

		.nav a:hover {
			color: #0056b3;
		}

		/* Product Container */
		.product-container {
			max-width: 1200px;
			margin: 50px auto;
			display: flex;
			gap: 30px;
			padding: 20px;
			background-color: #fff;
			border-radius: 15px;
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
		}

		.product-image img {
			width: 100%;
			border-radius: 15px;
			max-width: 600px;
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
			transition: transform 0.3s;
		}

		.product-image img:hover {
			transform: scale(1.05);
		}

		.product-details {
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

		.product-title {
			font-size: 36px;
			font-weight: 700;
			margin-bottom: 5px;
		}

		.product-price {
			font-size: 28px;
			color: #28a745;
			margin-bottom: 15px;
		}

		.product-description {
			font-size: 20px;
			line-height: 1.6;
			margin-bottom: 20px;
		}

		/* Button Styles */
		.book-now {
			background-color: #007bff;
			color: white;
			padding: 15px 25px;
			border: none;
			border-radius: 30px;
			cursor: pointer;
			font-size: 20px;
			transition: background-color 0.3s, transform 0.3s;
			text-align: center;
			display: inline-block;
		}

		.book-now:hover {
			background-color: #0056b3;
			transform: translateY(-2px);
		}

		/* Location Section */
		.location {
			margin-top: 20px;
			font-size: 20px;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.location a {
			text-decoration: none;
			color: #007bff;
			font-weight: 500;
			padding: 10px 15px;
			border: 1px solid #007bff;
			border-radius: 30px;
			transition: background-color 0.3s, color 0.3s;
		}

		.location a:hover {
			background-color: #007bff;
			color: white;
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.product-container {
				flex-direction: column;
				align-items: center;
			}

			.product-image img {
				max-width: 100%;
			}
		}
    </style>
</head>
<body>

<header class="header">
    <div class="container">
        <div class="logo">
            <a href="Index.php">
                <img src="Images/Logo/Logo2.png" alt="Company Logo">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="Index.php">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li>
                    <a href="<?php echo $isLoggedIn ? 'account.php' : 'login.html?redirect=' . urlencode($_SERVER['REQUEST_URI']); ?>">
                        <?php echo $isLoggedIn ? 'Account' : 'Log In'; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="product-container">
    <div class="product-image">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="product-details">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-price">₹<?php echo number_format($product['price'], 2); ?>/month</p>
        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

        <div class="location">
            <i class="fas fa-map-marker-alt"></i>
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($product['location']); ?>" target="_blank">
                View on Google Maps
            </a>
        </div>

        <a href="<?php echo $isLoggedIn ? 'BookingPage.php?id=' . $productId : 'login.html?redirect=' . urlencode($_SERVER['REQUEST_URI']); ?>">
            <button class="book-now">Book Now</button>
        </a>
    </div>
</div>

</body>
</html>
