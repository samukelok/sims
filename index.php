<?php
    session_start();
    include 'db_connection.php';

    // Initializations
    $message = "";

    // Handle login form submission
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Fetch user from the database
        $query = "SELECT * FROM admin_logins WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if ($password === $user['password']) {
               //OTP
                $otp = rand(100000, 999999); 
                $_SESSION['otp'] = $otp; 
                $_SESSION['email'] = $user['email']; 

                // Send OTP via PHPMailer
                require 'vendor/autoload.php'; 

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

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
                    $mail->addAddress($user['email']); 

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body    = "Your OTP code is: <b>$otp</b>";

                    $mail->send();
                } catch (Exception $e) {
                    echo "Failed to send OTP. Error: {$mail->ErrorInfo}";
                }

                //Save login time in the database
                $login_time = date('Y-m-d H:i:s');
                $query = "UPDATE admin_logins SET last_login = '$login_time' WHERE username = '$username'";
                $conn->query($query);

                // Redirect to security_q.php
                header("Location: security_q.php");

                //Save the user's email in a session variable
                $_SESSION['email'] = $user['email'];
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMS</title>

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

        h1 {
            margin: 0 0 20px;
            padding: 0;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        label{
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
            ;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .lost {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .lost a {
            color: #4C8055;
            text-decoration: none;
        }

        .lost a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="index.php" method="post">
            <div class="message">
                <p style = "text-align: center;"><?php echo $message?></p>
            </div>
            <h1>Login</h1>
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Username">

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password">

            <button type="submit" name="login" >Login</button>

            <div class="lost">
                <p>Don't have an account? <a href="./sign_up.php">Sign Up Here</a></p>
            </div>
        </form>
    </div>
</body>
</html>