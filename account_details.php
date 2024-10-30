<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['EMAIL'])) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);

$email = $_SESSION['EMAIL']; // Get the logged-in user's email

// Database connection settings
$host = "localhost";
$user = "root";
$pass = ""; // Your database password
$db = "testdata";

// Create a new connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query securely
$stmt = $conn->prepare("SELECT FNAME, LNAME, EMAIL, PHN FROM users WHERE EMAIL = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
if (!$result) {
    die("Error executing query: " . $stmt->error);
}

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found with the given email.";
    exit();
}

// Close the connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Header Styling */
        .header {
            background-color: #ffc107;
            padding: 15px 20px;
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
            gap: 20px;
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

        /* Account Container */
        .account-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto; /* Center the container */
            transition: transform 0.2s;
        }

        .account-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            color: #333; /* Updated color for better contrast */
        }

        /* Account Details */
        .account-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .account-details .detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e1e1e1;
            transition: background-color 0.3s;
        }

        .account-details .detail:hover {
            background-color: #eaeaea;
        }

        .account-details .detail label {
            font-weight: bold;
            font-size: 1.1em;
            color: #666;
        }

        .account-details .detail span {
            font-size: 1em;
            color: #333;
        }

        /* Buttons */
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .buttons .btn {
            padding: 12px 20px;
            background-color: #f7c02f;
            border: none;
            border-radius: 5px;
            color: #333;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1em;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .buttons .btn:hover {
            background-color: #d4a62b;
            transform: translateY(-2px);
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #ffffff; /* Change to white for contrast */
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5em;
            color: #f7c02f;
        }

        .modal-body p {
            font-size: 1.2em;
            color: #333;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .modal-footer .btn {
            padding: 12px 20px;
            background-color: #f7c02f;
            border: none;
            border-radius: 5px;
            color: #333;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1em;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .modal-footer .btn:hover {
            background-color: #d4a62b;
            transform: translateY(-2px);
        }

        .modal-footer .btn.cancel {
            background-color: #666666;
            color: #f7f7f7;
        }

        .modal-footer .btn.cancel:hover {
            background-color: #555555;
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
                    <li><a href="#about">About</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="account-container">
        <h2>Account Details</h2>
        <div class="account-details">
            <div class="detail">
                <label>First Name</label>
                <span id="fname"><?php echo htmlspecialchars($user['FNAME']); ?></span>
            </div>
            <div class="detail">
                <label>Last Name</label>
                <span id="lname"><?php echo htmlspecialchars($user['LNAME']); ?></span>
            </div>
            <div class="detail">
                <label>Email</label>
                <span id="email"><?php echo htmlspecialchars($user['EMAIL']); ?></span>
            </div>
            <div class="detail">
                <label>Phone Number</label>
                <span id="phn"><?php echo htmlspecialchars($user['PHN']); ?></span>
            </div>
        </div>
        <div class="buttons">
            <a href="edit-profile.php" class="btn">Edit</a>
            <button class="btn" onclick="showLogoutModal()">Log Out</button>
        </div>
    </div>

    <!-- Modal for Logout Confirmation -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Log Out Confirmation</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="logout()">Yes</button>
                <button class="btn cancel" onclick="closeLogoutModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = "flex"; // Change to flex to center modal
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = "none";
        }

        function logout() {
            window.location.href = 'logout.php'; // Redirect to logout page
        }

        // Close modal if user clicks anywhere outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
