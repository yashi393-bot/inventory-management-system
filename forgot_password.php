<?php
session_start();
include('connection.php'); // Database Connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Secure email input with mysqli

    // Check if email exists
    $query = mysqli_query($conn, "SELECT * FROM cuser WHERE email = '$email'");
    if (!$query) {
        die("Query Failed: " . mysqli_error($conn)); // Debugging ke liye
    }

    if (mysqli_num_rows($query) > 0) {
        // Generate token
        $token = md5(uniqid(rand(), true));

        // Save token in database
        $update_query = mysqli_query($conn, "UPDATE cuser SET reset_token='$token' WHERE email='$email'");
        if (!$update_query) {
            die("Update Failed: " . mysqli_error($conn));
        }

        // Display reset link (In real case, send via email)
        $reset_link = "reset_password.php?token=$token"; // This link will direct to reset_password.php with token
        echo "<script>alert('Password reset link has been sent. Click here to reset your password.'); window.location.href='$reset_link';</script>";
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    background-image: url('hgf.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}


        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .message a {
            color: #007bff;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            text-align: center;
            display: none;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        <?php if (isset($_GET['error'])) { ?>
            <div class="alert" id="alert-message">
                <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>

        <form method="POST" id="forgot-password-form">
            <label for="email">Enter your Email:</label>
            <input type="email" id="email" name="email" required placeholder="Your email address">
            <button type="submit">Submit</button>
        </form>

        <div class="message">
            <p>Remembered your password? <a href="cuslogin.php">Login here</a></p>
        </div>
    </div>

    <script>
        // Display the alert message if set
        window.onload = function () {
            var alertMessage = document.getElementById('alert-message');
            if (alertMessage) {
                alertMessage.style.display = 'block';
            }
        };
    </script>
</body>
</html>
