<?php
session_start(); // Ensure session starts at the very top of the file
include('db_conn.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);

    // SQL query to find the user
    $stmt = $conn->prepare("SELECT UID, FNAME, PASS FROM users WHERE EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Compare passwords
        if ($pass === $user['PASS']) { 
			
			$name=$user['FNAME'];
			$uid=$user['UID'];
            // Store user data in session variables
            $_SESSION['UID'] = $uid;
            $_SESSION['FNAME'] = $name;
            $_SESSION['EMAIL'] = $email;

            // Redirect to the index page
            header("Location: Index.php");
			
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please try again.'); window.history.back();</script>";
    }

    $stmt->close(); // Close statement
}

$conn->close(); // Close connection
?>
