<?php
session_start(); 

if (!isset($_SESSION['EMAIL'])) {
    header("Location: login.html");
    exit();
}

session_regenerate_id(true);
$email = $_SESSION['EMAIL'];
$name = $_SESSION['FNAME'];

$host = "localhost";
$user = "root";
$pass = "";
$db = "testdata";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FFFFFF;
            color: #f1f1f1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #ffc107;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            margin-left: 20px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .dashboard-container {
            max-width: 1200px;
            width: 90%;
            margin: 50px auto;
            background-color: #CEC6A4;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .greeting {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .notifications {
            background-color: #ff9800;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .menu-item {
            background-color: #494946;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        .menu-item:hover {
            background-color: #393938;
            transform: scale(1.05);
        }
        .menu-item a {
            text-decoration: none;
            color: white;
            font-size: 18px;
        }
        .menu-item img {
            width: 40px;
            margin-bottom: 10px;
        }
        footer {
            background-color: #ff5722;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }
        .logout-btn {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 20px;
        }
        .logout-btn:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">
        <a href="index.php"><img src="Images/Logo/Logo2.png" alt="Logo" height="50"></a>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="#about">About</a>
        <a href="services.php">Services</a>
        <a href="#contact">Contact</a>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </nav>
</header>

<div class="dashboard-container">
    <div class="greeting">Welcome, <?php echo strtoupper(htmlspecialchars($name)); ?></div>
    
    

    <div class="menu">
        <div class="menu-item">
            
            <a href="order_details.php">Orders</a>
        </div>
        <div class="menu-item">
            
            <a href="account_details.php">Account Details</a>
        </div>
        <div class="menu-item">
            
            <a href="Contactus.php">Support</a>
        </div>
    </div>
</div>

<script>
    function logout() {
        alert("You have been logged out.");
        window.location.href = "login.html";
    }
</script>

<footer>
    <p>&copy; 2024 BorrowIT. All rights reserved.</p>
    <a href="privacy.html" style="color: white;">Privacy Policy</a> | 
    <a href="terms.html" style="color: white;">Terms of Service</a>
</footer>

</body>
</html>