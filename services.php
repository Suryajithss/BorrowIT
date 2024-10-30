<?php
session_start(); // Start the session
$isLoggedIn = isset($_SESSION['EMAIL']); // Check if the user is logged in
$userName = $isLoggedIn ? $_SESSION['FNAME'] : ''; // Get the user's name if logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="Images/Logo/BorrowIT.png" type="image/x-icon">
	<link rel="stylesheet" href="CSS/header.css">
    <title>Our Services</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Navigation Styles */
        nav {
            background-color: #ffc107; /* Match header color */
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        nav .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav .nav-links {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            margin: 0 20px;
            transition: color 0.3s;
            font-size: 16px; /* Adjusted font size */
        }

        nav .nav-links a:hover {
            color: #e0a800;
        }

        /* Services Section */
        .services-section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .services-section h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem; /* Increased font size for emphasis */
            color: #333;
        }

        .services {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .service {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden; /* Prevents overflow */
            position: relative; /* Allows absolute positioning of elements */
        }

        .service:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .service img {
            max-width: 100%;
            border-radius: 8px;
            transition: transform 0.3s; /* Smooth image hover effect */
        }

        .service:hover img {
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        .service h3 {
            margin: 15px 0;
            font-size: 1.8rem; /* Increased heading size for better readability */
            color: #333;
        }

        .service p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .service .btn {
            background-color: #ffc107; /* Same as header */
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
            border: 2px solid transparent; /* For a button effect */
            position: relative;
            overflow: hidden;
        }

        .service .btn:hover {
            background-color: #e0a800;
            border-color: #333; /* Add border on hover */
            transform: translateY(-3px); /* Slight lift effect */
        }

        /* Footer Section */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .services {
                flex-direction: column;
                align-items: center;
            }

            .services-section {
                padding: 40px 10px; /* Less padding on smaller screens */
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
                    <li><a href="Index.php"><b>Home</b></a></li>
                    <li><a href="#about"><b>About</b></a></li>
                    <li><a href="services.php"><b>Services</b></a></li>
                    <li><a href="#contact"><b>Contact</b></a></li>
                    <li><a id="account-link" href="<?php echo $isLoggedIn ? 'account.php' : 'login.html'; ?>">
                        <b><?php echo $isLoggedIn ? 'Account' : 'Log In'; ?></b>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <h2>What We Offer</h2>
        <div class="services">
            <div class="service">
                <img src="Images/Services/House.jpg" alt="Service 1">
                <h3>Houses</h3>
                <p>Explore a wide range of houses for rent, from cozy apartments to spacious family homes.</p>
                <a href="products.php?type=House" class="btn">Search</a>
            </div>
            <div class="service">
                <img src="Images/Services/Household_Essentials.jpeg" alt="Service 2">
                <h3>Household Essentials</h3>
                <p>Browse essential household items available for rent, from furniture to appliances.</p>
                <a href="products.php?type=Household" class="btn">Search</a>
            </div>
            <div class="service">
                <img src="Images/Services/Appartment.jpg" alt="Service 3">
                <h3>Apartments</h3>
                <p>We offer a variety of stylish apartments for rent, perfect for all lifestyles.</p>
                <a href="products.php?type=Apartment" class="btn">Search</a>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 BorrowIT. All Rights Reserved.</p>
    </footer>

</body>
</html>
