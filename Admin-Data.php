<?php
    $host = "localhost";
    $user = "root";
    $db = "testdata";

    // Establish the database connection
    $conn = mysqli_connect($host, $user, "", $db);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection Error: " . $conn->connect_error);
    }
    echo "<b>CONNECTION SUCCESSFUL<br></b>";

    // SQL query to insert data
    $sql = "INSERT INTO ADMIN VALUES (null,'Suryajith', 'suryajithss07@gmail.com', 'Pass2864')";

    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "<b>DATA INSERTION SUCCESSFUL<br></b>";
    } else {
        echo "<b>DATA INSERTION UNSUCCESSFUL: " . $conn->error . "<br></b>";
    }

    // Close the connection
    $conn->close();
?>
