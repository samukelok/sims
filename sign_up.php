<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Initialize variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Process form submission
if (isset($_POST['sign_up'])) {
    // Validate and sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation checks
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 4) {
        $errors['username'] = 'Username must be at least 4 characters';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $check_user = $conn->prepare("SELECT * FROM admin_logins WHERE username = ? OR email = ?");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $result = $check_user->get_result();
        
        if ($result->num_rows > 0) {
            $existing_user = $result->fetch_assoc();
            if ($existing_user['username'] === $username) {
                $errors['username'] = 'Username already taken';
            }
            if ($existing_user['email'] === $email) {
                $errors['email'] = 'Email already registered';
            }
        }
    }

    // If no errors, insert new user
    if (empty($errors)) {
        // Store plain text password (not recommended for production)
        $plain_password = $password;
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO admin_logins (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $plain_password);
        
        if ($stmt->execute()) {
            $_SESSION['signup_success'] = true;
            header("Location: index.php"); // Redirect to login page
            exit();
        } else {
            $errors['database'] = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - SIMS</title>
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

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        input {
            width: 92.7%;
            padding: 10px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4C8055;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
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
        <form action="" method="post">
            <h1>Sign Up</h1>

            <?php if (!empty($errors['database'])): ?>
                <div class="error"><?php echo $errors['database']; ?></div>
            <?php endif; ?>

            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <?php if (!empty($errors['username'])): ?>
                <div class="error"><?php echo $errors['username']; ?></div>
            <?php endif; ?>

            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <?php if (!empty($errors['email'])): ?>
                <div class="error"><?php echo $errors['email']; ?></div>
            <?php endif; ?>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <?php if (!empty($errors['password'])): ?>
                <div class="error"><?php echo $errors['password']; ?></div>
            <?php endif; ?>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <?php if (!empty($errors['confirm_password'])): ?>
                <div class="error"><?php echo $errors['confirm_password']; ?></div>
            <?php endif; ?>

            <button type="submit" name="sign_up">Sign Up</button>

            <div class="lost">
                <p>Already have an account? <a href="./index.php">Login Here</a></p>
            </div>
        </form>
    </div>
</body>
</html>