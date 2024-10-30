<?php
session_start();
$errorMessages = isset($_SESSION['error_messages']) ? $_SESSION['error_messages'] : [];
session_unset();  // Clear session data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            text-align: center;
            padding: 50px;
        }
        .message {
            display: inline-block;
            padding: 20px;
            border: 1px solid #f5c6cb;
            background-color: #f8d7da;
            border-radius: 5px;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .countdown {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            animation: slideIn 1s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Error</h1>
    <?php if (!empty($errorMessages)): ?>
        <div class="message">
            <?php foreach ($errorMessages as $message): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Unknown error occurred.</p>
    <?php endif; ?>

    <div class="countdown" id="countdown">You will be redirected back to the admin panel in <span id="timer">5</span> seconds.</div>

    <script>
        let countdown = 5;
        const timerElement = document.getElementById('timer');
        
        const interval = setInterval(function() {
            countdown--;
            timerElement.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = 'BookingManagement.php';
            }
        }, 1000);
    </script>
</body>
</html>