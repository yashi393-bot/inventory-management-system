<?php
session_start();
include 'connection.php'; // Database connection file

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM adminlogin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        $message = "Login successful!";
        header("refresh:1; url=admindash.html"); // Redirect after 1 second
    } else {
        $message = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: url('adlogin.avif') no-repeat center center/cover;
      margin: 0;
      font-family: Arial, sans-serif;
      position: relative;
    }

    .login-container {
      width: 350px;
      background: rgba(255, 255, 255, 0.25);
      padding: 30px 25px;
      border-radius: 15px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.25);
      border: 5px solid black;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #222;
    }

    .login-container label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      text-align: left;
    }

    .login-container input {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button.btn {
      width: 100%;
      padding: 12px;
      background: #333;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    button.btn:hover {
      background: #555;
    }

    /* Back Button Styling */
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      display: flex;
      align-items: center;
      background: #222;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: background 0.3s, transform 0.2s;
      z-index: 999;
    }

    .back-btn:hover {
      background: #444;
      transform: scale(1.05);
    }

    .back-btn img {
      width: 20px;
      height: 20px;
      margin-right: 8px;
      filter: invert(1);
    }

    .message-success {
      color: black;
      font-weight: bold;
      font-size: 18px;
      text-align: center;
      margin-top: 20px;
      width: 100%;
    }

    .message-error {
      color: red;
      font-weight: bold;
      font-size: 18px;
      text-align: center;
      margin-top: 20px;
      width: 100%;
    }
  </style>
</head>
<body>

  <!-- Back Button -->
  <a href="p1.html" class="back-btn">
    <img src="zx1.png" alt="Back Icon">
    Back
  </a>

  <!-- Login Box -->
  <div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" minlength="6" required>

        <button class="btn">Login</button>
    </form>
  </div>

  <!-- Message Display Outside the Box -->
  <?php if (!empty($message)) : ?>
    <p class="<?php echo ($message == 'Login successful!') ? 'message-success' : 'message-error'; ?>">
      <?php echo $message; ?>
    </p>
  <?php endif; ?>

</body>
</html>
