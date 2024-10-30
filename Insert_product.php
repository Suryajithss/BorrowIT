<?php
// Start the session
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $sts = 'available';  // Default status set to available
    $loc = $_POST['location'];

    // Directory where the file will be uploaded
    $target_dir = "uploads/";

    // Check if the directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Get file information
    $target_file = $target_dir . basename($_FILES["images"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["images"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        $errorMessages[] = "File is not an image.";
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
        $errorMessages[] = "Sorry, file already exists: " . htmlspecialchars(basename($_FILES["images"]["name"]));
    }

    // Check file size (max 5MB)
    if ($_FILES["images"]["size"] > 5000000) {
        $uploadOk = 0;
        $errorMessages[] = "Sorry, your file is too large: " . htmlspecialchars(basename($_FILES["images"]["name"]));
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $uploadOk = 0;
        $errorMessages[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // If any upload error occurs, show error messages
    if ($uploadOk == 0) {
        $_SESSION['error_messages'] = $errorMessages;
        header("Location: error.php");  // Redirect to error page
        exit;  // Ensure no further code is executed after redirect
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;  // Set the path of the uploaded image

            // Check if the same image path already exists in the database
            $checkSql = "SELECT * FROM products WHERE image = '$imagePath'";
            $result = $conn->query($checkSql);

            if ($result->num_rows > 0) {
                $errorMessages[] = "Error: This image is already associated with an existing product.";
                $_SESSION['error_messages'] = $errorMessages;
                header("Location: error.php");  // Redirect to error page
                exit;
            } else {
                // Insert product data into the database
                $sql = "INSERT INTO products (name, price, image, description, created_at, type, status, sales) 
                        VALUES ('$name', '$price', '$imagePath', '$description', NOW(), '$type', '$sts', NULL)";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['success_message'] = "New product added successfully!";
                    header("Location: AdminPanel.php");  // Redirect to admin panel
                    exit;
                } else {
                    $errorMessages[] = "Error: " . $sql . "<br>" . $conn->error;
                    $_SESSION['error_messages'] = $errorMessages;
                    header("Location: error.php");  // Redirect to error page
                    exit;
                }
            }
        } else {
            $errorMessages[] = "Sorry, there was an error uploading your file.";
            $_SESSION['error_messages'] = $errorMessages;
            header("Location: error.php");  // Redirect to error page
            exit;
        }
    }

    // Close the connection
    $conn->close();
}
?>