<?php
session_start();

// Check if session data exists
if (!isset($_SESSION['email']) || !isset($_SESSION['otp'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$otp = $_SESSION['otp'];

// Handle OTP verification
if (isset($_POST['verify'])) {
    $user_otp = $_POST['otp_code']; 

    if ($user_otp == $otp) {
        unset($_SESSION['otp']);

        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FAuth - SIMS</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            background-color: #4C8055;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        h1 {
            margin: 0 0 20px;
            padding: 0;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        input {
            width: 92.7%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4C8055;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <div class="message">
                <p>An OTP Code has been sent to <?php echo htmlspecialchars($email); ?>, Kindly verify your authenticity.</p>
            </div>
            <h1>OTP Check</h1>

            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <label for="otp_code">OTP Code:</label>
            <input type="text" name="otp_code" placeholder="OTP Code" required>

            <button type="submit" name="verify">Verify</button>
        </form>
    </div>
</body>
</html>