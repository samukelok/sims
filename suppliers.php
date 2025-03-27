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
    // Add new supplier
    if (isset($_POST['add_supplier'])) {
        $product = $_POST['product'];
        $barcode = $_POST['barcode'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $supplier_name = $_POST['supplier_name'];
        $supplier_number = $_POST['supplier_number'];
        
        $stmt = $conn->prepare("INSERT INTO suppliers (product, barcode, quantity, price, supplier_name, supplier_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddsss", $product, $barcode, $quantity, $price, $supplier_name, $supplier_number, $logged_in_user);
        $stmt->execute();
    }
    
    // Update supplier
    if (isset($_POST['update_supplier'])) {
        $original_barcode = $_POST['original_barcode'];
        $product = $_POST['product'];
        $barcode = $_POST['barcode'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $supplier_name = $_POST['supplier_name'];
        $supplier_number = $_POST['supplier_number'];
        
        $stmt = $conn->prepare("UPDATE suppliers SET product=?, barcode=?, quantity=?, price=?, supplier_name=?, supplier_number=? WHERE barcode=? AND email=?");
        $stmt->bind_param("ssddssss", $product, $barcode, $quantity, $price, $supplier_name, $supplier_number, $original_barcode, $logged_in_user);
        $stmt->execute();
    }
    
    // Delete supplier
    if (isset($_POST['delete_supplier'])) {
        $barcode = $_POST['barcode'];
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE barcode=? AND email=?");
        $stmt->bind_param("s", $barcode, $logged_in_user);
        $stmt->execute();
    }
}

// Get all suppliers for the logged-in user
$suppliers_query = $conn->prepare("SELECT * FROM suppliers WHERE email = ? ORDER BY supplier_name ASC");
$suppliers_query->bind_param("s", $logged_in_user);
$suppliers_query->execute();
$suppliers_result = $suppliers_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - SIMS</title>
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
                <li><a href="./sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                <li><a href="./suppliers.php" class="active"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <div class="header-actions">
                    <h1>Suppliers</h1>
                    <button class="add-btn" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Add Supplier
                    </button>
                </div>
            </header>

            <!-- Suppliers Table -->
            <table class="suppliers-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Supplier Name</th>
                        <th>Supplier Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($supplier = $suppliers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($supplier['product']) ?></td>
                        <td><?= htmlspecialchars($supplier['barcode']) ?></td>
                        <td><?= $supplier['quantity'] ?></td>
                        <td>R<?= number_format($supplier['price'], 2) ?></td>
                        <td><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                        <td><?= htmlspecialchars($supplier['supplier_number']) ?></td>
                        <td class="action-btns">
                            <button class="edit-btn" onclick="openEditModal(
                                '<?= htmlspecialchars($supplier['barcode'], ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($supplier['product'], ENT_QUOTES) ?>', 
                                '<?= htmlspecialchars($supplier['barcode'], ENT_QUOTES) ?>', 
                                <?= $supplier['quantity'] ?>, 
                                <?= $supplier['price'] ?>,
                                '<?= htmlspecialchars($supplier['supplier_name'], ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($supplier['supplier_number'], ENT_QUOTES) ?>'
                            )">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="barcode" value="<?= htmlspecialchars($supplier['barcode']) ?>">
                                <button type="submit" name="delete_supplier" class="delete-btn">
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

    <!-- Add Supplier Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Supplier</h2>
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
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" step="0.01" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label for="supplier_name">Supplier Name:</label>
                    <input type="text" name="supplier_name" id="supplier_name" required>
                </div>
                <div class="form-group">
                    <label for="supplier_number">Supplier Number:</label>
                    <input type="text" name="supplier_number" id="supplier_number" required>
                </div>
                <button type="submit" name="add_supplier" class="submit-btn">Add Supplier</button>
            </form>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Supplier</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="original_barcode" id="originalBarcode">
                <input type="hidden" name="update_supplier" value="1">
                
                <div class="form-group">
                    <label for="editProduct">Product:</label>
                    <input type="text" name="product" id="editProduct" required>
                </div>
                <div class="form-group">
                    <label for="editBarcode">Barcode:</label>
                    <input type="text" name="barcode" id="editBarcode" required>
                </div>
                <div class="form-group">
                    <label for="editQuantity">Quantity:</label>
                    <input type="number" name="quantity" id="editQuantity" required>
                </div>
                <div class="form-group">
                    <label for="editPrice">Price:</label>
                    <input type="number" step="0.01" name="price" id="editPrice" required>
                </div>
                <div class="form-group">
                    <label for="editSupplierName">Supplier Name:</label>
                    <input type="text" name="supplier_name" id="editSupplierName" required>
                </div>
                <div class="form-group">
                    <label for="editSupplierNumber">Supplier Number:</label>
                    <input type="text" name="supplier_number" id="editSupplierNumber" required>
                </div>
                <button type="submit" class="submit-btn">Update Supplier</button>
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
        function openEditModal(originalBarcode, product, barcode, quantity, price, supplierName, supplierNumber) {
            document.getElementById('originalBarcode').value = originalBarcode;
            document.getElementById('editProduct').value = product;
            document.getElementById('editBarcode').value = barcode;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editPrice').value = price;
            document.getElementById('editSupplierName').value = supplierName;
            document.getElementById('editSupplierNumber').value = supplierNumber;
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