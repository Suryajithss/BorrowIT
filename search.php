<?php
session_start();
$isLoggedIn = isset($_SESSION['EMAIL']);
$userName = $isLoggedIn ? $_SESSION['FNAME'] : '';
include('db_conn.php');
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$status = 'available';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        /* Header Section */
        .header {
            background-color: #26a69a;
            padding: 10px 20px;
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
            height: 40px;
        }

        .nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav a {
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            color: #fff;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav a:hover {
            color: #ffcc80;
            transform: scale(1.1);
        }

        /* Search Bar Section */
        .search-container {
            margin-top: 30px;
            text-align: center;
        }

        .search-container input[type="text"] {
            width: 60%;
            padding: 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .search-container button {
            margin-top: 10px;
            padding: 12px 24px;
            font-size: 1rem;
            background-color: #26a69a;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-container button:hover {
            background-color: #1d8274;
        }

        /* Product Card Styling */
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 40px;
            margin-top: 20px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-name {
            font-size: 1.5rem;
            margin: 15px;
            color: #333;
            text-align: center;
        }

        .product-description {
            padding: 0 15px;
            font-size: 1rem;
            color: #666;
            text-align: center;
        }

        .product-price {
            margin: 15px;
            font-size: 1.25rem;
            color: #26a69a;
            text-align: center;
            font-weight: bold;
        }

        .product-btn {
            display: block;
            margin: 15px auto;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #26a69a;
            color: #fff;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .product-btn:hover {
            background-color: #1d8274;
        }

        /* No Products Found Styling */
        .no-products {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
        }

        .no-products h2 {
            font-size: 2rem;
            color: #26a69a;
        }

        .no-products p {
            font-size: 1.1rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .search-container input[type="text"] {
                width: 80%;
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
                    <li>
                        <a href="<?php echo $isLoggedIn ? 'account.php' : 'login.html'; ?>">
                            <?php echo $isLoggedIn ? 'Account' : 'Log In'; ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- SEARCH SECTION -->
    <div class="search-container">
        <form action="search.php" method="post">
            <input type="text" name="search" placeholder="Search for items..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- PRODUCT SHOWCASE -->
    <div class="product-container">
        <?php
        $stmt = $conn->prepare("SELECT * FROM products WHERE 
            (name LIKE ? OR description LIKE ? OR location LIKE ?) 
            AND status = ?");
        $searchTermWildcard = "%" . $searchTerm . "%";
        $stmt->bind_param("ssss", $searchTermWildcard, $searchTermWildcard, $searchTermWildcard, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='product-card'>
                    <img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "' class='product-image'>
                    <h2 class='product-name'>" . htmlspecialchars($row["name"]) . "</h2>
                    <p class='product-description'> 📍" . htmlspecialchars($row["location"]) . "</p>
                    <div class='product-price'>₹" . htmlspecialchars($row["price"]) . "</div>
                    <a href='ProductPage.php?id=" . $row["id"] . "' class='product-btn'>View Details</a>
                </div>";
            }
        } else {
            echo "
            <div class='no-products'>
                <h2>No Products Found</h2>
                <p>Try adjusting your search or come back later.</p>
            </div>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>
</html>
