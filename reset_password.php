<?php
session_start();
include('connection.php'); // Database Connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the token is passed via the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token is missing or invalid."); // If token is not present in the URL, stop the process.
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = hash('sha256', $new_password); // Hash password

    // Check if token is valid
    $query = mysqli_query($conn, "SELECT * FROM cuser WHERE reset_token='$token'");

    // Check if query was successful
    if (!$query) {
        die("Query failed: " . mysqli_error($conn)); // Debugging error message
    }

    if (mysqli_num_rows($query) > 0) {
        // Token is valid, update password
        $update_query = mysqli_query($conn, "UPDATE cuser SET password='$hashed_password' WHERE reset_token='$token'");

        // Check if password update was successful before clearing the token
        if ($update_query) {
            // Now set reset_token to NULL after updating the password
            $clear_token_query = mysqli_query($conn, "UPDATE cuser SET reset_token=NULL WHERE reset_token='$token'");

            if ($clear_token_query) {
                echo "<script>alert('Password reset successful! Please login.'); window.location.href='cuslogin.php';</script>";
            } else {
                echo "<script>alert('Failed to clear token!');</script>";
            }
        } else {
            die("Update failed: " . mysqli_error($conn)); // Debugging message
        }
    } else {
        echo "<script>alert('Invalid or expired token!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
			background-image: url('download.jpg');
            padding: 0;
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

        input[type="password"] {
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
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
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
        <h2>Reset Password</h2>

        <?php if (isset($_GET['error'])) { ?>
            <div class="alert" id="alert-message">
                <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>

        <form method="POST" id="reset-password-form">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>"> <!-- Sanitize the token -->
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required placeholder="Enter your new password">
            <button type="submit">Reset Password</button>
        </form>

        <div class="message">
            <p>Remember your password? <a href="cuslogin.php">Login here</a></p>
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
