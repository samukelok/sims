<?php
// Database connection
include 'db_connection.php';

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = $search ? "WHERE (product LIKE '%$search%' OR barcode LIKE '%$search%') AND email = '$logged_in_user'" : "WHERE email = '$logged_in_user'";

// Pagination
$results_per_page = 2;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Fetch total number of products
$total_query = "SELECT COUNT(*) AS total FROM inventory $where";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $results_per_page);

// Fetch products for the current page
$query = "SELECT * FROM inventory $where LIMIT $results_per_page OFFSET $offset";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Product</th>
                <th>Barcode</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['product']}</td>
                <td>{$row['barcode']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['price']}</td>
                <td>
                    <button class = 'edit-btn' onclick='openEditModal(\"{$row['barcode']}\", \"{$row['product']}\", {$row['quantity']}, {$row['price']})'>Edit</button>
                    <a class='delete-btn' href='delete_product.php?barcode={$row['barcode']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No products found.</p>";
}

$conn->close();
?>