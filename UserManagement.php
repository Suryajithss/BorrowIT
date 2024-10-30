<?php
// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'testdata'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL statement to retrieve users
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();

    // Fetch all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle deletion of a user
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];

    // Prepare and execute the SQL statement to delete the user
    $deleteStmt = $pdo->prepare("DELETE FROM users WHERE UID = :UID");
    $deleteStmt->bindParam(':UID', $userId, PDO::PARAM_INT);
    $deleteStmt->execute();

    // Redirect to the same page to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        .header h1 {
            margin: 0;
        }
        /* Style for header nav links */
        .header nav {
            margin-top: 10px;
        }
        .header nav a {
            color: white; /* Ensure links are visible */
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .header nav a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
        .user-table {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons a {
            margin-right: 10px;
        }
        .action-buttons a.btn {
            padding: 5px 10px;
            color: white;
        }
        .btn-danger {
            background-color: red;
        }
        .btn-primary {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Product Management</h1>
            <nav>
                <a href="AdminPanel.php">Admin Panel</a>
            </nav>
        </div>

        <div class="user-table">
            <h2 class="text-center">User List</h2>

            <?php if (!empty($users)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>UID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['UID']); ?></td>
                                <td><?php echo htmlspecialchars($user['FNAME']); ?></td>
                                <td><?php echo htmlspecialchars($user['LNAME']); ?></td>
                                <td><?php echo htmlspecialchars($user['EMAIL']); ?></td>
                                <td><?php echo htmlspecialchars($user['PHN']); ?></td>
                                <td><?php echo htmlspecialchars($user['DOB']); ?></td>
                                <td><?php echo htmlspecialchars($user['PASS']); ?></td>
                                <td class="action-buttons">
                                    <a href="?delete=<?php echo $user['UID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No users found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

