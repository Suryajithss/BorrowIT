<?php
session_start(); // Start the session
$isLoggedIn = isset($_SESSION['EMAIL']); // Check if the user is logged in
$name = $isLoggedIn ? $_SESSION['FNAME'] : ''; // Get the user's name if logged in
include('StatusCheck.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="icon" href="Images/Logo/BorrowIT.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa; /* Light grey background */
            color: #333;
        }

        /* Header Styling */
        .header {
            background-color: #26a69a;
            padding: 15px 20px; /* Increased padding */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            position: relative;
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
            gap: 20px;
        }

        .nav a {
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            color: #fff;
            transition: color 0.3s ease;
        }

        .nav a:hover {
            color: #ffcc80;
        }

        /* Hero Section Styles */
        .hero {
            position: relative;
            height: 450px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center; 
        }

        .hero h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3em; /* Larger font size */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Stronger shadow */
        }

        .hero-images {
            display: flex;
            animation: slide 12s infinite; 
        }

        .hero-image {
            min-width: 100%; 
            transition: opacity 1s ease;
        }

        .hero img {
            width: 100%;
            height: 90%;
            opacity: 0.9; /* Slightly transparent */
            filter: brightness(0.8); /* Darken the image */
        }

        @keyframes slide {
            0% { transform: translateX(0); }
            20% { transform: translateX(0); }
            25% { transform: translateX(-100%); }
            45% { transform: translateX(-100%); }
            50% { transform: translateX(-200%); }
            70% { transform: translateX(-200%); }
            75% { transform: translateX(-300%); }
            100% { transform: translateX(-300%); }
        }

        /* Search Bar Styles */
        .search-container {
            position: absolute;
            bottom: 50px; 
            left: 50%; 
            transform: translateX(-50%);
            text-align: center; 
        }

        #search-bar {
            padding: 10px;
            width: 350px;
			height: auto;
            border: 2px solid #007BFF; /* Border styling */
            border-radius: 5px;
            margin-right: 10px;
            transition: border-color 0.3s;
        }

        #search-bar:focus {
            border-color: #0056b3; /* Darker border on focus */
            outline: none; /* Remove outline */
        }

        #search-button {
            padding: 10px 20px;
            background-color: #283C84;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        #search-button:hover {
            background-color: #7091E6;
            transform: translateY(-2px); /* Lift effect */
        }

        /* Services Section Styles */
        #services {
            background-color: #e0f7fa; 
            padding: 50px 15px;
        }

        .services-container {
            display: flex;
            align-items: center;
        }

        .services-image {
            flex: 1; 
            padding-right: 20px; 
        }

        .service-img {
            max-width: 100%; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        .services-content {
            flex: 1; 
        }

        .section-title {
            font-size: 3em; 
            color: #283C84; 
            margin-bottom: 20px; /* Space below title */
        }

        /* About Section Styles */
        #about {
            background-color: #e0f7fa;
            padding: 50px 15px;
        }

        .about-container {
            display: flex;
            align-items: center; 
            justify-content: space-between; 
        }

        .about-content {
            flex: 1;
            padding-right: 20px; 
        }

        .about-image {
            flex: 1;
        }

        .about-img {
            max-width: 100%; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .about-title {
            font-size: 3em; 
            color: #283C84; 
            margin-bottom: 20px; /* Space below title */
        }

        /* Contact Sections */
        #contact {
            background-color: #e0f7fa; 
            padding: 50px 15px;
        }

        .contact-container {
            display: flex;
            align-items: center; 
        }

        .contact-image {
            flex: 1; 
            padding-right: 20px; 
        }

        .contact-img {
            max-width: 100%; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-content {
            flex: 1; 
        }

        .contact-title {
            font-size: 3em; 
            color: #283C84; 
            margin-bottom: 20px; /* Space below title */
        }

        /* Footer Styles */
        footer {
            background-color: #283C84;
            color: white;
            text-align: center;
            padding: 20px 0; /* Increased padding */
        }

        footer a {
            color: #fff;
            margin: 0 15px; /* Space between links */
            transition: color 0.3s;
        }

        footer a:hover {
            color: #ffc107; /* Change link color on hover */
        }

    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="Images/Logo/Logo2.png" alt="Borrow IT Logo">
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="account.php"><?php echo $name; ?></a></li>
                    <?php else: ?>
                        <li><a href="login.html">Log In</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="hero">
        <div class="hero-images">
            <img class="hero-image" src="Images/Banner/House1.jpg" alt="Rental Product 1">
            <img class="hero-image" src="Images/Banner/House2.jpg" alt="Rental Product 2">
            <img class="hero-image" src="Images/Banner/House3.jpg" alt="Rental Product 3">
        </div>
        <h1>Welcome to Borrow IT</h1>
        <div class="search-container">
            <form action="search.php" method="post" onsubmit="return validateSearch()">
                <input type="text" id="search-bar" name="search" placeholder="Search for items..." required><br><br>
                <button id="search-button">Search</button>
            </form>            
        </div>
    </div>

    <section id="services">
        <div class="services-container">
            <div class="services-image">
                <img class="service-img" src="Images/Services/ServiceImage.jpg" alt="Our Services">
            </div>
            <div class="services-content">
                <h2 class="section-title">Our Services</h2>
                <p>At BorrowIT, we offer a diverse range of rental services designed to cater to your every need. Whether you're looking for a cozy home, stylish apartment, or essential household items, we have you covered. Our extensive selection includes various houses available for rent, perfect for families or individuals seeking comfort and space. For those in need of household essentials, we provide an array of furniture and appliances to enhance your living experience without the commitment of purchase. Additionally, our stylish apartments are tailored for all lifestyles, ensuring you find the perfect space that fits your preferences. With our commitment to quality and customer satisfaction, BorrowIT is your go-to platform for all your rental needs.</p>
				<center><a href="services.php" id="search-button" style="text-decoration: none">Explore Services</a></center>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="about-container">
            <div class="about-content">
                <h2 class="about-title">About Us</h2>
                <p>Welcome to Borrow IT, your trusted platform for renting houses, apartments, and essential household items. We aim to simplify your rental experience by connecting renters with owners, offering a wide range of properties and items to fit your needs. Whether you're looking for a temporary home or essential furnishings, our user-friendly platform ensures a seamless booking process with reliable service. At Borrow IT, we prioritize convenience, affordability, and sustainability by promoting shared usage. Let us help you find the perfect rental solution, hassle-free!</p>
			<center><a href="Aboutus.php" id="search-button" style="text-decoration: none">Learn More</a></center>
            </div>
            <div class="about-image">
                <img class="about-img" src="Images/Services/aboutUS.jpg" alt="About Us">
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="contact-container">
            <div class="contact-image">
                <img class="contact-img" src="Images/Services/contactUS.jpg" alt="Contact Us">
            </div>
            <div class="contact-content">
                <h2 class="contact-title">Contact Us</h2>
                <p>We value your feedback and inquiries! Whether you have questions about our services, need assistance, or want to share your thoughts with us, we’re here to help. Our dedicated team is committed to providing you with the best support possible. Feel free to reach out to us using the contact form below, and we will respond as soon as possible. Your satisfaction is our priority, and we look forward to hearing from you!</p>
				<center><a href="Contactus.php" id="search-button" style="text-decoration: none">Contact Us</a></center>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Borrow IT. All Rights Reserved.</p>
        <p >
            <a href="PrivacyPolicy.php" style="text-decoration: none">Privacy Policy</a> | 
            <a href="TermsOfService.php" style="text-decoration: none">Terms of Service</a> | 
			<a href="AdminLogin.html" style="text-decoration: none">Management</a>
        </p>
    </footer>
</body>
</html>
