<?php
session_start();
$isLoggedIn = isset($_SESSION['EMAIL']);
$userName = $isLoggedIn ? $_SESSION['FNAME'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
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
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Header Styling */
        .header {
            background-color: #ffc107; /* Same as homepage */
            padding: 10px 20px; /* Compact header */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease; /* Animation for header */
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo img {
            height: 40px; /* Consistent logo size */
        }

        .nav ul {
            display: flex;
            list-style: none;
            gap: 20px; /* Compact spacing between links */
        }

        .nav a {
            text-decoration: none;
            font-weight: 500;
            font-size: 16px; /* Adjusted font size */
            color: #333;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav a:hover {
            color: #007BFF;
            transform: scale(1.1); /* Scale effect on hover */
        }

        /* Product Container */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 40px;
            margin-top: 70px; /* To account for the fixed header */
        }

        .product-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.1); /* Zoom effect on hover */
        }

        .product-name {
            font-size: 1.5rem;
            margin: 15px 0 10px;
            color: #333;
            font-weight: bold;
            animation: fadeIn 0.5s ease; /* Animation for product name */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            opacity: 0.8; /* Slightly transparent for effect */
        }

        .product-price {
            font-size: 1.2rem;
            color: #ffca28;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .product-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffca28;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-100%);
            transition: transform 0.4s ease;
            border-radius: 8px;
        }

        .product-btn:hover:before {
            transform: translateY(0);
        }

        .product-btn:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        /* No Products Found Styling */
        .no-products {
            background-color: #fff; /* Background color for the message container */
            border: 1px solid #e0e0e0; /* Subtle border */
            border-radius: 12px; /* Rounded corners */
            padding: 20px; /* Padding around the content */
            text-align: center; /* Center the text */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow effect */
            margin: 20px auto; /* Margin to separate it from other elements */
            width: 80%; /* Width of the container */
            animation: fadeIn 0.5s ease; /* Animation for no products message */
        }

        .no-products h2 {
            font-size: 2rem; /* Larger font size for the heading */
            color: #333; /* Heading color */
            margin-bottom: 10px; /* Margin below the heading */
        }

        .no-products p {
            font-size: 1rem; /* Standard font size for the paragraph */
            color: #666; /* Text color */
        }

        @media (max-width: 768px) {
            .product-container {
                padding: 20px;
            }

            .product-card {
                width: 90%;
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

    <div class="product-container">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "testdata");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : ''; // Sanitize user input

        $status = 'available';
        // Fetch products based on the type
        $sql = "SELECT id, name, price, image, location FROM products WHERE type = ? AND status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $type, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='product-card'>
                    <img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "' class='product-image'>
                    <h2 class='product-name'>" . htmlspecialchars($row["name"]) . "</h2>
                    <p class='product-description'>📍" . htmlspecialchars($row["location"]) . "</p>
                    <div class='product-price'>₹" . htmlspecialchars($row["price"]) . "</div>
                    <a href='ProductPage.php?id=" . $row["id"] . "' class='product-btn'>View Details</a>
                </div>
                ";
            }
        } else {
            echo "
            <div class='no-products'>
                <h2>No Products Found</h2>
                <p>Sorry, there are no products available in this category.</p>
            </div>
            ";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
