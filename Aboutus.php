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
    <title>About Us</title>
    <link rel="stylesheet" href="./CSS/styles.css">
</head>
<body>
    <!-- HEADER -->
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
                    <li><a href="Aboutus.php">About</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="Contactus.php">Contact</a></li>
                    <li><a id="account-link" href="<?php echo $isLoggedIn ? 'account.php' : 'login.html'; ?>">
                        <?php echo $isLoggedIn ? 'Account' : 'Log In'; ?>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="about">
            <h2>About Us</h2>
            <p>At Your Company Name, we are committed to excellence and innovation. Our dedicated team works tirelessly to deliver outstanding results and solutions for our clients.</p>
            <p>With years of experience in the industry, we pride ourselves on our ability to adapt and evolve, ensuring we stay at the forefront of market trends and technologies.</p>
            <p>Join us in our journey to make a positive impact and achieve great things together!</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Your Company Name. All rights reserved.</p>
    </footer>
</body>
</html>
