<?php
include "connection.php"; // Database connection file include karein

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form se data lena
    $customer_name = mysql_real_escape_string($_POST['name']);
    $mobile_number = mysql_real_escape_string($_POST['mobile']);
    $email = mysql_real_escape_string($_POST['email']);
    $address = mysql_real_escape_string($_POST['address']);
    $state = mysql_real_escape_string($_POST['state']);
    $city = mysql_real_escape_string($_POST['city']);
    $pincode = mysql_real_escape_string($_POST['pincode']);
    $total_price = (int) $_POST['totalPrice'];
    $total_items = (int) $_POST['totalItems'];
    
    // Cart items ko decode karna
    $cart_items = json_decode($_POST['cartItems'], true);

    if (!empty($cart_items)) {
        foreach ($cart_items as $item) {
            $product_name = mysql_real_escape_string($item['name']);
            $quantity = (int) $item['quantity'];
            
            // SQL query to insert order into database
            $sql = "INSERT INTO orders (customer_name, mobile_number, email, address, state, city, pincode, product_name, quantity, total_price, order_date) 
                    VALUES ('$customer_name', '$mobile_number', '$email', '$address', '$state', '$city', '$pincode', '$product_name', '$quantity', '$total_price', NOW())";
            
            $result = mysql_query($sql, $conn);
            
            if ($result) {
                $response = array("status" => "success", "message" => "Order placed successfully!");
            } else {
                $response = array("status" => "error", "message" => "Error placing order: " . mysql_error());
            }
        }
    } else {
        $response = array("status" => "error", "message" => "Cart is empty!");
    }
    
    echo json_encode($response);
    mysql_close($conn); // Connection close karna
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    <h2>Place Your Order</h2>
    <form action="" method="post">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required><br>
        
        <label for="mobile">Mobile Number:</label>
        <input type="text" id="mobile" name="mobile" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea><br>
        
        <label for="state">State:</label>
        <input type="text" id="state" name="state" required><br>
        
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br>
        
        <label for="pincode">Pincode:</label>
        <input type="text" id="pincode" name="pincode" required><br>
        
        <label for="totalPrice">Total Price:</label>
        <input type="text" id="totalPrice" name="totalPrice" required><br>
        
        <label for="totalItems">Total Items:</label>
        <input type="text" id="totalItems" name="totalItems" required><br>
        
        <input type="hidden" id="cartItems" name="cartItems" value='[{"name":"Sample Product","quantity":1}]'>
        
        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
