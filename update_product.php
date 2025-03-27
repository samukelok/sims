<?php
session_start();

include 'db_connection.php';

$logged_in_user = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barcode = $_POST['barcode'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $query = "UPDATE inventory SET product='$product', quantity=$quantity, price=$price WHERE barcode='$barcode' AND email='$logged_in_user'";
    if ($conn->query($query)) {
        header("Location: inventory.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>