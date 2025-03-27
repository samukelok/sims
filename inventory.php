<?php
session_start();
include 'db_connection.php';

$logged_in_user = $_SESSION['email'];

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - SIMS</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>SIMS</h2>
            </div>
            <ul class="menu">
                <li><a href="./dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="./inventory.php" class="active"><i class="fas fa-box"></i> Inventory</a></li>
                <li><a href="./sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="./suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Inventory</h1>
            </header>

            <!-- Search Bar -->
            <div class="search-bar">
                <form method="GET" action="inventory.php">
                    <input type="text" name="search" placeholder="Search by product name or barcode" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <!-- Add Product Button -->
            <div class="add-product">
                <button onclick="openAddModal()"><i class="fas fa-plus"></i> Add Product</button>
            </div>

            <!-- Inventory Table -->
            <div class="inventory-table">
                <?php include 'fetch_inventory.php'; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php include 'pagination.php'; ?>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add Product</h2>
            <form method="POST" action="add_product.php">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" required>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <button type="submit">Add Product</button>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Product</h2> 
            <form method="POST" action="update_product.php">
                <input type="hidden" id="edit_product_id" name="product_id">
                <label for="edit_product_name">Product Name:</label>
                <input type="text" id="edit_product_name" name="product" required>
                <label for="edit_barcode">Barcode:</label>
                <input type="text" id="edit_barcode" name="barcode" required>
                <label for="edit_quantity">Quantity:</label>
                <input type="number" id="edit_quantity" name="quantity" required>
                <label for="edit_price">Price:</label>
                <input type="number" id="edit_price" name="price" step="0.1" required>
                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Open and close modals
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(barcode, product, quantity, price) {
        // Assign values to the correct input fields
        document.getElementById('edit_barcode').value = barcode;
        document.getElementById('edit_product_name').value = product;
        document.getElementById('edit_quantity').value = quantity;
        document.getElementById('edit_price').value = price;
        

        // Display the modal
        document.getElementById('editModal').style.display = 'block';
}

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>