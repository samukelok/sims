<?php
//Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

session_start();

$logged_in_user = $_SESSION['email'];

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];

    $query = "DELETE FROM inventory WHERE barcode='$barcode' AND email='$logged_in_user'";
    if ($conn->query($query)) {
        header("Location: inventory.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>