<?php
	$host="localhost";
	$user="root";
	$db="testdata";
	$conn=mysqli_connect($host,$user,"",$db);
	if($conn->connect_error)
	{
		die("Connection Error ".$conn->error);
	}
	echo "<b>CONNECTION SUCCESSFUL<br></b>";
	$sql="CREATE TABLE USERS(UID INT AUTO_INCREMENT PRIMARY KEY,FNAME VARCHAR(20), LNAME VARCHAR(20),EMAIL VARCHAR(70) NOT NULL UNIQUE, PHN VARCHAR(10) UNIQUE,DOB DATE, PASS VARCHAR(30))";
	if($conn->query($sql))
	{
		echo "<b>DATABASE CREATED SUCCESSFUL<br></b>";
	}
	else
	{
		echo "<b>CREATION UNSUCCESSFUL<br></b>";
	}
?>