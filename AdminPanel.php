<?php
session_start(); // Start the session to access the success message

// Check if a success message exists in the session and display it
if (isset($_SESSION['success_message'])) {
    echo "<div style='background-color: green; color: white; padding: 10px; text-align: center; margin-bottom: 20px;'>";
    echo $_SESSION['success_message']; // Display the message
    echo "</div>";

    // Unset the success message after displaying
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .admin-panel {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #283C84;
            color: #EDE8F5;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #EDE8F5;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        header {
            background-color: #EDE8F5;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            color: #283C84;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info a {
            color: #283C84;
            text-decoration: none;
        }

        .user-info a:hover {
            text-decoration: underline;
        }

        section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="UserManagement.php">User Management</a></li>
                <li><a href="PropertyManagement.php">Property Management</a></li>
                <li><a href="BookingManagement.php">Booking Management</a></li>
				<li><a href="PaymentManagement.php">Payment Management</a></li>
                <li><a href="Analytic.php">Alaytic Assessment</a></li>
                <li><a href="#reports">Reports</a></li>
            </ul>
        </aside>
        <main class="content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <p>Welcome, Admin</p>
                    <a href="AdminLogout.php">Logout</a>
                </div>
            </header>
            <section id="dashboard">
                <!-- Dashboard content goes here -->
            </section>
            <section id="user-management">
                <!-- User management content goes here -->
            </section>
            <section id="item-management">
                <!-- Item management content goes here -->
            </section>
            <section id="booking-management">
                <!-- Booking management content goes here -->
            </section>
            <section id="settings">
                <!-- Settings content goes here -->
            </section>
            <section id="reports">
                <!-- Reports content goes here -->
            </section>
        </main>
    </div>
</body>
</html>
