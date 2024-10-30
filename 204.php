<?php
session_start();

$isLoggedIn = isset($_SESSION['EMAIL']);
$userName = $isLoggedIn ? $_SESSION['FNAME'] : '';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops!</title>
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
            background-color: #e0f7fa; /* Light blue background */
            color: #333;
        }

        .header {
            background-color: #26a69a; /* Seafoam green */
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease;
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
            color: #fff; /* White text color */
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav a:hover {
            color: #ffcc80; /* Light orange on hover */
            transform: scale(1.1);
        }

        .error-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 60px);
            text-align: center;
            animation: fadeIn 0.5s ease;
            padding: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .error-page h1 {
            font-size: 4rem; /* Reduced size for better fit */
            font-weight: bold;
            color: #26a69a; /* Seafoam green */
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }

        .error-page img {
            width: 300px;
            max-width: 100%;
            margin-bottom: 30px;
            animation: rotate 2s ease infinite;
        }

        .error-page p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-page a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffcc80; /* Light orange */
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease 0.2s;
            animation-fill-mode: both;
        }

        .error-page a:before {
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

        .error-page a:hover:before {
            transform: translateY(0);
        }

        .error-page a:hover {
            background-color: #e0a800; /* Darker orange on hover */
            transform: translateY(-2px);
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

<div class="error-page">
    <img src="Animations/no-products-found.gif" alt="No Products Found">
    <h1>Oops!</h1>
    <p>It looks like no results found for your search.</p>
    <a href="index.php">Go Back to Home</a>
</div>

</body>
</html>
