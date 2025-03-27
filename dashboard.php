<?php
session_start();

$logged_in_user = $_SESSION['email'];

if (!isset($logged_in_user)) {
    header("Location: index.php");
    exit();
}

//database connection
include 'db_connection.php';

// Fetch data for stats blocks
$total_revenue = 0;
$total_suppliers = 0;
$products_sold = 0;
$low_stock_items = 0;

// Fetch Total Revenue
$sql = "SELECT SUM(total_revenue) AS total_revenue FROM sales WHERE email = '$logged_in_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_revenue = $row['total_revenue'];
}

// Fetch Total Suppliers
$sql = "SELECT COUNT(DISTINCT supplier_name) AS total_suppliers FROM suppliers WHERE email = '$logged_in_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_suppliers = $row['total_suppliers'];
}

// Fetch Products Sold
$sql = "SELECT SUM(total_sales) AS products_sold FROM sales WHERE email = '$logged_in_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $products_sold = $row['products_sold'];
}

// Fetch Low Stock products
$sql = "SELECT COUNT(*) AS low_stock_items FROM inventory WHERE quantity < 10 AND email = '$logged_in_user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $low_stock_items = $row['low_stock_items'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIMS</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2><span class="first_half">SI</span><span class="first_half">MS</span></h2>
            </div>
            <ul class="menu">
                <li><a href="./dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="./inventory.php"><i class="fas fa-box"></i> Inventory</a></li>
                <li><a href="./sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="./suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
            </header>

            <!-- Stats Blocks -->
            <div class="blocks">
                <div class="block">
                    <h3>Total Revenue</h3>
                    <p>R<?php echo number_format($total_revenue, 2); ?></p>
                </div>
                <div class="block">
                    <h3>Total Suppliers</h3>
                    <p><?php echo $total_suppliers; ?></p>
                </div>
                <div class="block">
                    <h3>Products Sold</h3>
                    <p><?php echo $products_sold; ?></p>
                </div>
                <div class="block">
                    <h3>Low Stock</h3>
                    <p><?php echo $low_stock_items; ?> Items</p>
                </div>
            </div>

            <!-- Analytics Graphs -->
            <div class="analytics">
            <div class="graph">
    <h2>Sales Report (Last 30 Days)</h2>
            <div class="graph-placeholder">
                    <canvas id="salesChart"></canvas> 
                </div>
            </div>
            <div class="graph">
                <h2>Revenue Trend</h2>
                <div class="graph-placeholder">
                    <canvas id="revenueChart"></canvas> 
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Chart.js for Graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script> 

    <script>
    // Fetch data for graphs
    fetch('fetch_graph_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched Data:", data); 

            // Sales Report Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: data.sales_labels,
                    datasets: [{
                        label: 'Sales',
                        data: data.sales_data,
                        borderColor: '#4C8055',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Revenue Trend Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: data.revenue_labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.revenue_data,
                        backgroundColor: '#4C8055'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error("Error fetching graph data:", error); // Log errors
        });
</script>
</body>
</html>