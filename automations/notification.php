<?php
include '../db_connection.php'; 

//Check all users with low stock from the database - 'inventory' table and print them out
$low_stock_query = "SELECT * FROM inventory WHERE quantity < 10";
$low_stock_result = $conn->query($low_stock_query);

if ($low_stock_result->num_rows > 0) {
    while ($row = $low_stock_result->fetch_assoc()) {
        echo "Product: " . $row['product'] . " has low stock. Current stock: " . $row['quantity'] . "<br>";

        // User email
        $user_email = $row['email'];

        // Send email notification to the user using PHPMailer
        require '../vendor/autoload.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'mail.mmsolurgy.co.za'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sims@mmsolurgy.co.za'; 
            $mail->Password   = 'SIMS@Mails'; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port       = 587; 

            // Recipients
            $mail->setFrom('sims@mmsolurgy.co.za', 'SIMS');
            $mail->addAddress($user_email); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Low Stock Item In Your Inventory';
            $mail->Body    = "Product: " . $row['product'] . " has low stock. Current stock: " . $row['quantity'] . ". Kindly restock.";

            $mail->send();
        } catch (Exception $e) {
            echo "Failed to send OTP. Error: {$mail->ErrorInfo}";
        }

    }
} else {
    echo "No products with low stock.";
}
// Close the database connection
$conn->close();
?>


