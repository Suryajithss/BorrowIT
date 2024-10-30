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
    <title>Contact Us</title>
    <link rel="stylesheet" href="./css/styles.css">
    <style>
        /* Basic styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #ffc107;
            padding: 10px 0;
        }
        .header .logo img {
            width: 150px;
        }
        .nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .nav ul li {
            margin: 0 15px;
        }
        .nav ul li a {
            text-decoration: none;
            color: #000000;
            transition: color 0.3s;
        }
        .nav ul li a:hover {
            color: #363636; /* Yellow on hover */
        }
        #contact {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h2 {
            text-align: center;
            color: #283C84; /* Same as header */
        }
        .contact-info {
            margin-top: 20px;
            font-size: 1.1em;
            line-height: 1.6;
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #ffc107;
            color: #fff;
        }
    </style>
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
        <section id="contact">
            <h2>Contact Us</h2>
            <div class="contact-info">
                <p>If you have any questions or inquiries, feel free to reach out to us through the following channels:</p>
                <p><strong>Email:</strong> support@yourcompany.com</p>
                <p><strong>Phone:</strong> +1 (123) 456-7890</p>
                <p><strong>Address:</strong> 123 Your Street, Your City, Your State, 12345</p>
            </div>
        </section>
    </main>

   
   
</body>
</html>
