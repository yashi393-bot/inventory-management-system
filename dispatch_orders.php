<?php
include 'connection.php'; // Database connection

$query = "SELECT * FROM dispatched_orders";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dispatched Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background: url('supp.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
        }

        .back-btn {
            position: absolute;
            top: 30px;
            left: 50px;
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
            z-index: 10;
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

        .container {
            max-width: 1400px;
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
            color: white;
            backdrop-filter: blur(8px);
        }

        h2 {
            margin-bottom: 20px;
            color: #ff758c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            overflow: hidden;
            color: white;
        }

        th, td {
            border: 1px solid #444;
            padding: 12px;
            text-align: center;
        }

        th {
            background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
            color: #ff758c;
            font-size: 16px;
        }

        td {
            font-size: 15px;
        }
    </style>
</head>
<body>

<a href="admindash.html" class="back-btn">
    <img src="wer1.png" alt="Back">Back
</a>

<div class="container">
  <center>  <h2>Dispatched Orders</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Dispatched At</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Address</th>
                <th>State</th>
                <th>City</th>
                <th>Pincode</th>
                <th>Total Price</th>
                <th>Total Items</th>
                <th>Product Name</th>
                <th>Quantity</th>
              
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['order_id']}</td>
                        <td>{$row['dispatched_at']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['mobile']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['state']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['pincode']}</td>
                        <td>{$row['total_price']}</td>
                        <td>{$row['total_items']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['quantity']}</td>
                   
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='15'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
