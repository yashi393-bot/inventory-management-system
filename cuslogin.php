<?php
session_start();
include('connection.php'); // Database Connection

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Hashing Password (Same as Signup)
    $hashed_password = hash('sha256', $password);

    // Check Credentials Using Prepared Statements
    $stmt = $conn->prepare("SELECT * FROM cuser WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $message = "Login successful!";
        header("refresh:1; url=ca.php");
    } else {
        $message = "Invalid Username or Password!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Login</title>
  <style>
    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: url('rt9.jpg') no-repeat center center/cover;
      font-family: Arial, sans-serif;
      position: relative;
    }

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

    .login-container {
      background: rgba(255, 255, 255, 0.2);
      padding: 40px;
      border-radius: 10px;
      border: 5px solid black;
      backdrop-filter: blur(5px);
      width: 350px;
      text-align: center;
    }

    h2 {
      color: #fff;
    }

    label {
      color: #fff;
      display: block;
      text-align: left;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #333;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    button:hover {
      background: #555;
    }

    .login-container p,
    a {
      color: #fff;
      font-size: 14px;
    }

    a {
      text-decoration: underline;
    }

    a:hover {
      text-decoration: none;
    }

    .message-success {
      color: black;
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }

    .message-error {
      color: red;
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <!-- Stylish Back Button -->
  <a href="p1.html" class="back-btn">
    <img src="https://cdn-icons-png.flaticon.com/512/93/93634.png" alt="Back Icon">
    Back
  </a>

  <!-- Login Box -->
  <div class="login-container">
    <h2>Customer Login</h2>
    <form action="cuslogin.php" onSubmit="return validation()" method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required />

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" minlength="1" required />

      <button type="submit">Login</button>

      <p>Don't have an account? <a href="sign.php">Sign up</a></p>
      <p><a href="forgot_password.php">Forgot Password?</a></p>
    </form>
  </div>

  <!-- Message Display -->
  <?php if (!empty($message)) : ?>
    <p class="<?php echo ($message == 'Login successful!') ? 'message-success' : 'message-error'; ?>">
      <?php echo $message; ?>
    </p>
  <?php endif; ?>

  <script>
    function validation() {
      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;
      if (username === "" || password === "") {
        alert("All fields are mandatory.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
