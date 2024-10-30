<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'testdata';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the product ID from the query parameter
    $productId = $_GET['id'];

    // Fetch the product details for the given ID
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }

    // Handle form submission to update product details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        // Handle image upload if a new image is provided
        $imagePath = $product['image']; // Default to existing image
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/";
            $imagePath = $targetDir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        }

        // Update the product details in the database
        $updateStmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, location = :location, type = :type, price = :price, status = :status, image = :image WHERE id = :id");
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':location', $location);
        $updateStmt->bindParam(':type', $type);
        $updateStmt->bindParam(':price', $price);
        $updateStmt->bindParam(':status', $status);
        $updateStmt->bindParam(':image', $imagePath);
        $updateStmt->bindParam(':id', $productId);
        $updateStmt->execute();

        // Redirect to the product list page after update
        header("Location: PropertyManagement.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($product['location']); ?>" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="House" <?php if ($product['type'] == 'House') echo 'selected'; ?>>House</option>
                    <option value="Household" <?php if ($product['type'] == 'Household') echo 'selected'; ?>>Household</option>
                    <option value="Apartment" <?php if ($product['type'] == 'Apartment') echo 'selected'; ?>>Apartment</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" name="price" id="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="available" <?php if ($product['status'] == 'available') echo 'selected'; ?>>Available</option>
                    <option value="not-available" <?php if ($product['status'] == 'not-available') echo 'selected'; ?>>Not Available</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image</label><br>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width: 150px; margin-bottom: 10px;"><br>
                <input type="file" name="image" id="image" class="form-control-file">
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="product_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
