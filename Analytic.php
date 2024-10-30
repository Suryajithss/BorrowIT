<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "testdata"; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total sales from products table
$sales_query = "SELECT name, sales FROM products";
$sales_result = $conn->query($sales_query);

$product_names = [];
$product_sales = [];
while ($row = $sales_result->fetch_assoc()) {
    $product_names[] = $row['name'];
    $product_sales[] = $row['sales'];
}

// Fetch bookings count by location
$location_query = "SELECT location, COUNT(*) as total_bookings FROM bookings GROUP BY location";
$location_result = $conn->query($location_query);

$locations = [];
$bookings_count = [];
while ($row = $location_result->fetch_assoc()) {
    $locations[] = $row['location'];
    $bookings_count[] = $row['total_bookings'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 16px;
        }

        .header nav a:hover {
            text-decoration: underline;
        }

        .container {
            display: grid;
            gap: 20px;
            margin: 40px auto;
            max-width: 900px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }

        select {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        select:hover {
            border-color: #007bff;
        }

        canvas {
            max-width: 100%;
            height: 350px;
            transition: transform 0.3s ease;
        }

        canvas:hover {
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Dashboard</h1>
        <nav>
            <a href="AdminPanel.php">Admin Panel</a>
        </nav>
    </div>

    <div class="container">
        <label for="chartType">Select Chart Type:</label>
        <select id="chartType" onchange="updateChartType()">
            <option value="bar">Bar</option>
            <option value="line">Line</option>
            <option value="pie">Pie</option>
        </select>

        <h2>Product Sales</h2>
        <canvas id="salesChart"></canvas>

        <h2>Bookings by Location</h2>
        <canvas id="bookingsChart"></canvas>
    </div>

    <script>
        // Data from PHP for product sales
        const productNames = <?php echo json_encode($product_names); ?>;
        const productSales = <?php echo json_encode($product_sales); ?>;

        // Data from PHP for bookings by location
        const locations = <?php echo json_encode($locations); ?>;
        const bookingsCount = <?php echo json_encode($bookings_count); ?>;

        let chartType = 'bar'; // Default chart type

        // Initialize Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        let salesChart = createChart(salesCtx, chartType, productNames, productSales, 'Sales');

        // Initialize Bookings Chart
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        let bookingsChart = createChart(bookingsCtx, chartType, locations, bookingsCount, 'Bookings');

        // Function to create and return a chart instance
        function createChart(ctx, type, labels, data, label) {
            const config = {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: type === 'pie' ? generateColors(data.length) : 'rgba(54, 162, 235, 0.2)',
                        borderColor: type === 'pie' ? 'rgba(255, 255, 255, 1)' : 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: type === 'line',
                        tension: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: type === 'pie' ? 'left' : 'top',
                        },
                    },
                    scales: type !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            };
            return new Chart(ctx, config);
        }

        // Function to generate random colors for pie chart segments
        function generateColors(length) {
            const colors = [];
            for (let i = 0; i < length; i++) {
                const r = Math.floor(Math.random() * 255);
                const g = Math.floor(Math.random() * 255);
                const b = Math.floor(Math.random() * 255);
                colors.push(`rgba(${r}, ${g}, ${b}, 0.6)`);
            }
            return colors;
        }

        // Function to update chart types
        function updateChartType() {
            chartType = document.getElementById('chartType').value;

            salesChart.destroy();
            salesChart = createChart(salesCtx, chartType, productNames, productSales, 'Sales');

            bookingsChart.destroy();
            bookingsChart = createChart(bookingsCtx, chartType, locations, bookingsCount, 'Bookings');
        }
    </script>
</body>
</html>
