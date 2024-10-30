<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "testdata");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have the booking ID stored in the session after the booking process
$bookingId = $_SESSION['bid'] ?? 0; // Replace with the actual key used for booking ID
$bookingStatus = 'unsuccessful'; // Default to unsuccessful

if ($bookingId > 0) {
    // Query to check booking status from the bookings table
    $sql = "SELECT status FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    
    if ($status) {
        $bookingStatus = $status; // Update the booking status
    }
    $stmt->close();
} else {
    echo "No booking ID found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <style>
        /* General styling */
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        /* Container for message */
        .container {
            text-align: center;
        }

        /* Animation for messages */
        .message {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            opacity: 0;
            animation: fadeIn 1.5s forwards;
            margin-bottom: 20px;
        }

        /* Success message styling */
        .success {
            color: #28a745; /* Green color for success */
        }

        /* Error message styling */
        .error {
            color: #dc3545; /* Red color for error */
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Animated subtext */
        .subtext {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 300;
            color: #555;
            animation: slideIn 1.5s forwards;
        }

        /* Slide-in animation for subtext */
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Countdown styling */
        .countdown {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($bookingStatus === 'Confirmed'): ?>
            <div class="message success">Booking Successful!</div>
            <div class="subtext">Thank you for your order!</div>
        <?php else: ?>
            <div class="message error">Booking Unsuccessful</div>
            <div class="subtext">There was a problem with your booking. Please try again.</div>
        <?php endif; ?>

        <div class="countdown" id="countdown">Redirecting in <span id="time">5</span> seconds...</div>
    </div>

    <script>
        // Countdown timer
        let countdownTime = 5;
        const countdownElement = document.getElementById('time');
        const redirectUrl = 'Receipt.php'; 

        const countdown = setInterval(() => {
            countdownTime--;
            countdownElement.textContent = countdownTime;

            if (countdownTime <= 0) {
                clearInterval(countdown);
                window.location.href = redirectUrl; // Redirect after countdown
            }
        }, 1000);
    </script>
</body>
</html>
