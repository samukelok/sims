<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$logged_in_user = $_SESSION['email'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new sale
    if (isset($_POST['add_sale'])) {
        $product = $_POST['product'];
        $barcode = $_POST['barcode'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $total = $price * $quantity;
        $current_date = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("INSERT INTO sales (product, barcode, price, total_sales, total_revenue, day_last_bought, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiiss", $product, $barcode, $price, $quantity, $total, $current_date, $logged_in_user);
        $stmt->execute();
    }
    
    // Update sale
    if (isset($_POST['update_sale'])) {
        $original_barcode = $_POST['original_barcode'];
        $product = $_POST['product'];
        $barcode = $_POST['barcode'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $total = $price * $quantity;
        $current_date = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("UPDATE sales SET product=?, barcode=?, price=?, total_sales=?, total_revenue=?, day_last_bought=? WHERE barcode=? AND email=?");
        $stmt->bind_param("ssddddss", $product, $barcode, $price, $quantity, $total, $current_date, $original_barcode, $logged_in_user);
        $stmt->execute();
    }
    
    // Delete sale
    if (isset($_POST['delete_sale'])) {
        $barcode = $_POST['barcode'];
        $stmt = $conn->prepare("DELETE FROM sales WHERE barcode=? AND email=?");
        $stmt->bind_param("ss", $barcode, $logged_in_user);
        $stmt->execute();
    }
}

// Get all sales for the logged-in user
$sales_query = $conn->prepare("SELECT * FROM sales WHERE email = ? ORDER BY day_last_bought DESC");
$sales_query->bind_param("s", $logged_in_user);
$sales_query->execute();
$sales_result = $sales_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - SIMS</title>
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
                <li><a href="./inventory.php"><i class="fas fa-box"></i> Inventory</a></li>
                <li><a href="./sales.php" class="active"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="./suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <div class="header-actions">
                    <h1>Sales</h1>
                    <button class="add-btn" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Add Sale
                    </button>
                </div>
            </header>

            <!-- Sales Table -->
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Revenue</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sale = $sales_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($sale['product']) ?></td>
                        <td><?= htmlspecialchars($sale['barcode']) ?></td>
                        <td>R<?= number_format($sale['price'], 2) ?></td>
                        <td><?= $sale['total_sales'] ?></td>
                        <td>R<?= number_format($sale['total_revenue'], 2) ?></td>
                        <td><?= $sale['day_last_bought'] ?></td>
                        <td class="action-btns">
                            <button class="edit-btn" onclick="openEditModal(
                                '<?= htmlspecialchars($sale['barcode'], ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($sale['product'], ENT_QUOTES) ?>', 
                                '<?= htmlspecialchars($sale['barcode'], ENT_QUOTES) ?>', 
                                <?= $sale['price'] ?>, 
                                <?= $sale['total_sales'] ?>
                            )">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="barcode" value="<?= htmlspecialchars($sale['barcode']) ?>">
                                <button type="submit" name="delete_sale" class="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Sale Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Sale</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="product">Product:</label>
                    <input type="text" name="product" id="product" required>
                </div>
                <div class="form-group">
                    <label for="barcode">Barcode:</label>
                    <input type="text" name="barcode" id="barcode" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" step="0.01" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>
                <button type="submit" name="add_sale" class="submit-btn">Add Sale</button>
            </form>
        </div>
    </div>

    <!-- Edit Sale Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Sale</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="original_barcode" id="originalBarcode">
                <input type="hidden" name="update_sale" value="1">
                
                <div class="form-group">
                    <label for="editProduct">Product:</label>
                    <input type="text" name="product" id="editProduct" required>
                </div>
                <div class="form-group">
                    <label for="editBarcode">Barcode:</label>
                    <input type="text" name="barcode" id="editBarcode" required>
                </div>
                <div class="form-group">
                    <label for="editPrice">Price:</label>
                    <input type="number" step="0.01" name="price" id="editPrice" required>
                </div>
                <div class="form-group">
                    <label for="editQuantity">Quantity:</label>
                    <input type="number" name="quantity" id="editQuantity" required>
                </div>
                <button type="submit" class="submit-btn">Update Sale</button>
            </form>
        </div>
    </div>

    <script>
        // Add modal functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        // Edit modal functions
        function openEditModal(originalBarcode, product, barcode, price, quantity) {
            document.getElementById('originalBarcode').value = originalBarcode;
            document.getElementById('editProduct').value = product;
            document.getElementById('editBarcode').value = barcode;
            document.getElementById('editPrice').value = price;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) {
                closeAddModal();
            }
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>