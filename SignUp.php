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

    // Retrieving POST data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phn = $_POST['phn'];
    $dob = $_POST['dob'];
    $pass = $_POST['pass'];
	
	echo $fname;
	echo "<br>";
	echo $lname;
	echo "<br>";
	echo $email;
	echo "<br>";
	echo $phn;
	echo "<br>";
	echo $dob;
	echo "<br>";
	echo $pass;
	echo "<br>";

    // Corrected SQL query
    $sql = "INSERT INTO users (FNAME, LNAME, EMAIL, PHN, DOB, PASS) VALUES ('$fname', '$lname', '$email', '$phn', '$dob', '$pass')";
    
     //Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<b>DATA INSERTED SUCCESSFULLY<br></b>";
    } else {
        echo "<b>INSERTION UNSUCCESSFUL: " . $conn->error . "<br></b>";
    }
	// Prepare and execute the SQL query to check user credentials
    $sql1 = "SELECT * FROM USERS WHERE EMAIL = '$email' AND PASS = '$pass'";
    $result = $conn->query($sql1);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['EMAIL'] = $email; 
        $_SESSION['FNAME'] = $fname;
		$_SESSION['UID'] = $uid;
        echo "<b>LOGIN SUCCESSFUL<br></b>";
        header("Location: index.php");
        exit();
    } else {
        //header("Location: login.html");
    }

    // Close the connection
    $conn->close();
?>

