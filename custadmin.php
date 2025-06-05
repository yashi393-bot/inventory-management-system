<?php
include "connection.php"; // Include the database connection file

// Query to fetch all customers, excluding the password
$sql = "SELECT email, mobile, username, id FROM cuser ORDER BY id DESC";
$result = $conn->query($sql);

// Initialize an empty array to store customer data
$customers = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add the customer to the array, excluding the password
        $customers[] = array(
            'id' => $row['id'],
            'Email' => $row['email'],
            'Mobile' => $row['mobile'],
            'Username' => $row['username']
        );
    }
}
$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Customer Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background: url('supp.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .total-customers {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #ff758c;
        }
        .back-button {
            text-align: left;
            margin-bottom: 20px;
        }
        .back-button button {
            background-color: #ff758c;
            color: white;
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button button:hover {
            background-color: #ff5775;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button">
            <button onClick="goBack()"> Back</button>
        </div>

        <h2 style="color: #ff758c; text-align: center;">Admin Customer Management</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($customers as $customer) {
                        echo "<tr>
                                <td>" . $customer['id'] . "</td>
                                <td>" . $customer['Username'] . "</td>
                                <td>" . $customer['Email'] . "</td>
                                <td>" . $customer['Mobile'] . "</td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>

        <div class="total-customers">
            Total Customers: <?php echo count($customers); ?>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
