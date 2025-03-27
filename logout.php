<?php
session_start();

$logged_in_user = $_SESSION['email'];

// Clear session data
$_SESSION = [];

// Destroy the session
session_destroy();

header("Location: index.php");
?>