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

// Prepare and execute the SQL query securely to fetch user data
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

// Handle form submission to update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phn = trim($_POST['phn']);

    // Prepare and execute the SQL update query
    $updateStmt = $conn->prepare("UPDATE users SET FNAME = ?, LNAME = ?, PHN = ? WHERE EMAIL = ?");
    if (!$updateStmt) {
        die("Error preparing update statement: " . $conn->error);
    }
    $updateStmt->bind_param("ssss", $fname, $lname, $phn, $email);

    if ($updateStmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='account.php';</script>";
    } else {
        echo "Error updating profile: " . $updateStmt->error;
    }
    
    // Close the update statement
    $updateStmt->close();
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
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 12px 20px;
            background-color: #ffc107;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1em;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #d4a62b;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user['FNAME']); ?>" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($user['LNAME']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="phn">Phone Number</label>
                <input type="tel" id="phn" name="phn" value="<?php echo htmlspecialchars($user['PHN']); ?>" required>
            </div>
            <input type="submit" value="Update Profile">
        </form>
    </div>
</body>
</html>
