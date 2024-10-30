<?php
	$conn = new mysqli("localhost", "root", "", "testdata");

	// Check database connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$productId = 15;
	$sale = 1;
	echo $sale;
	$status = 'not-available';
	echo $status;
	
	$sales=$sale + 1;
	echo $sales;
	// Prepare the second SQL query to update the product.
	$sql = "UPDATE products SET status = ?, sales = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	
	$stmt->bind_param("sii", $status, $sales, $productId);
	$stmt->execute();
	$stmt->close();
?>