<?php
session_start();

$logged_in_user = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $barcode = $_POST['barcode'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $conn = new mysqli('localhost', 'root', '', 'sims');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "INSERT INTO inventory (product, barcode, quantity, price, email) VALUES ('$product_name', '$barcode', $quantity, $price, '$logged_in_user')";
    if ($conn->query($query)) {
        header("Location: inventory.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>