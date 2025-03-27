<?php
session_start();

$logged_in_user = $_SESSION['email'];

//database connection
include 'db_connection.php';

// Fetch Sales Report Data (Last 30 Days)
$sales_labels = [];
$sales_data = [];
$sql = "SELECT DATE(day_last_bought) AS date, SUM(total_sales) AS total_sales 
        FROM sales 
        WHERE day_last_bought >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND email = '$logged_in_user'
        GROUP BY DATE(day_last_bought) 
        ORDER BY DATE(day_last_bought)";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales_labels[] = $row['date'];
        $sales_data[] = (float)$row['total_sales'];
    }
}

// Fetch Revenue Trend Data (Last 30 Days)
$revenue_labels = [];
$revenue_data = [];
$sql = "SELECT DATE(day_last_bought) AS date, SUM(total_revenue) AS total_revenue 
        FROM sales 
        WHERE day_last_bought >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND email = '$logged_in_user'
        GROUP BY DATE(day_last_bought) 
        ORDER BY DATE(day_last_bought)";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $revenue_labels[] = $row['date'];
        $revenue_data[] = (float)$row['total_revenue']; 
    }
}

// Return JSON response
echo json_encode([
    'sales_labels' => $sales_labels,
    'sales_data' => $sales_data,
    'revenue_labels' => $revenue_labels,
    'revenue_data' => $revenue_data
]);
?>