<?php
// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'testdata'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a filter is applied
    $typeFilter = isset($_GET['type']) ? $_GET['type'] : '';

    // Prepare SQL query with optional filtering by type
    $sql = "SELECT * FROM products";
    if ($typeFilter) {
        $sql .= " WHERE type = :type";
    }

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    if ($typeFilter) {
        $stmt->bindParam(':type', $typeFilter);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle deletion of a product
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];

    // Prepare and execute the SQL statement to delete the product
    $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $deleteStmt->bindParam(':id', $productId);
    $deleteStmt->execute();

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0;
        }

        .header a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .header a:hover {
            color: #ffcc00;
        }

        .product-list {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 14px;
            word-break: break-word;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .available {
            background-color: #28a745;
        }

        .not-available {
            background-color: #dc3545;
        }

        .action-buttons a {
            margin: 5px;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .filter-container form {
            width: 80%;
        }

        @media (max-width: 768px) {
            th, td {
                font-size: 12px;
                padding: 10px;
            }

            .filter-container form {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Product Management</h1>
        <nav>
            <a href="ProductUpload.html">Add Product</a>
            <a href="AdminPanel.php">Admin Panel</a>
        </nav>
    </div>

    <div class="filter-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group row">
                <label for="typeFilter" class="col-sm-3 col-form-label text-right">Filter by Type:</label>
                <div class="col-sm-7">
                    <select name="type" id="typeFilter" class="form-control" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="House" <?php if ($typeFilter == 'House') echo 'selected'; ?>>House</option>
                        <option value="Household" <?php if ($typeFilter == 'Household') echo 'selected'; ?>>Household</option>
                        <option value="Apartment" <?php if ($typeFilter == 'Apartment') echo 'selected'; ?>>Apartment</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="product-list">
        <h2>Product List</h2>
        <?php if ($products): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo htmlspecialchars($product['location']); ?></td>
                            <td><?php echo htmlspecialchars($product['type']); ?></td>
                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                            <td>
                                <span class="status <?php echo ($product['status'] == 'available') ? 'available' : 'not-available'; ?>">
                                    <?php echo htmlspecialchars($product['status']); ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                <a href="#" onclick="confirmDelete(<?php echo $product['id']; ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No products found.</p>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = '?delete=' + productId;
            }
        }
    </script>
</body>
</html>
