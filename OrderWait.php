<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        }

        /* Container for spinner and text */
        .container {
            text-align: center;
        }

        /* Spinner animation */
        .spinner {
            width: 80px;
            height: 80px;
            border: 8px solid #f3f4f6;
            border-top: 8px solid #007bff;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Animated waiting text */
        .waiting-text {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            animation: fadeInOut 3s infinite;
        }

        @keyframes fadeInOut {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }

        /* Subtext for message */
        .subtext {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 300;
            color: #555;
        }
    </style>
    <script>
        // Redirect to another page after 30 seconds (30000ms)
        setTimeout(function() {
            window.location.href = "success.php"; // Replace with your target page
        }, 10000);
    </script>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <div class="waiting-text">Waiting for Order Confirmation...</div>
        <div class="subtext">Please do not refresh the page</div>
    </div>
</body>
</html>
