<?php
    session_start(); 

    $host = "localhost";
    $user = "root";
    $db = "testdata";
    
    // Establish the connection
    $conn = mysqli_connect($host, $user, "", $db);
    if ($conn->connect_error) {
        die("Connection Error: " . $conn->connect_error);
    }
    echo "<b>CONNECTION SUCCESSFUL<br></b>";

    // Retrieve POST data
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Prepare and execute the SQL query to check user credentials
    $sql = "SELECT * FROM ADMIN WHERE EMAIL = '$email' AND PASS = '$pass'";
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email; 
        $_SESSION['fname'] = $fname; 
        echo "<b>LOGIN SUCCESSFUL<br></b>";
        header("Location: AdminPanel.php");
        exit();
    } else {
        header("Location: login.html");
    }

    // Close the connection
    $conn->close();
?>
