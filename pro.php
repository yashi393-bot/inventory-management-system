<?php
include 'connection.php';

$id = $category = $product_name = $price = $image_path = $description = $size = $quantity = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = trim($_POST['ProductID']);
    $category = trim($_POST['Category']);
    $product_name = trim($_POST['ProductName']);
    $price = floatval($_POST['Price']);
    $quantity = intval($_POST['Quantity']);
    $image_path = trim($_POST['ImagePath']);
    $description = trim($_POST['Description']);
    $size = trim($_POST['Size']);
    $total_price = $price * $quantity;

    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare("INSERT INTO products (id, category, product_name, price, image_path, description, size, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdsssii", $id, $category, $product_name, $price, $image_path, $description, $size, $quantity, $total_price);
    } elseif ($_POST['action'] === 'edit') {
        $stmt = $conn->prepare("UPDATE products SET category=?, product_name=?, price=?, image_path=?, description=?, size=?, quantity=?, total_price=? WHERE id=?");
        $stmt->bind_param("ssdsssiss", $category, $product_name, $price, $image_path, $description, $size, $quantity, $total_price, $id);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("s", $id);
    }

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        error_log("Database Error: " . $stmt->error);
    }

    $stmt->close();
}

// Get products ordered by ID
$result = $conn->query("SELECT * FROM products ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Product Management</title>
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
        input, select, button {
            margin: 5px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: calc(18% - 10px);
        }
        button {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            border: none;
        }
        button:hover {
            background: linear-gradient(135deg, #ff5e78, #ff4f8b);
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
        td button {
            padding: 7px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
            min-width: 70px;
            margin: 2px;
        }
        td button:first-child {
            background-color: #ffb400;
            color: black;
        }
        td button:last-child {
            background-color: #ff4d4d;
            color: white;
        }
        td button:hover {
            opacity: 0.8;
        }
        .back-button {
            margin-bottom: 20px;
            float: left;
            background-color: #444;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-button" onClick="history.back()"> Back</button>
        <h2>Admin Product Management</h2>
        <form id="productForm" method="POST">
            <input type="hidden" name="action" id="action" value="add" />
            <input type="text" name="ProductID" id="ProductID" placeholder="Enter Product ID" required />
            <select name="Category" id="Category" required>
                <option value="Category">Choose Category</option>
                <option value="Clothes">Clothes</option>
                <option value="Footwear">Footwear</option>
                <option value="Furniture">Furniture</option>
            </select>
            <input type="text" name="ProductName" id="ProductName" placeholder="Product Name" required />
            <input type="number" step="0.01" name="Price" id="Price" placeholder="Price" required />
            <input type="number" name="Quantity" id="Quantity" placeholder="Quantity" required />
            <input type="text" name="ImagePath" id="ImagePath" placeholder="Image Path" required />
            <input type="text" name="Description" id="Description" placeholder="Description" required />
            <input type="text" name="Size" id="Size" placeholder="Size" required />
            <button type="submit" id="submitButton">Add Product</button>
        </form>

        <h3>Products List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Size</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" width="100" /></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['size']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>
                            <button onclick='editProduct(<?php echo json_encode($row); ?>)'>Edit</button>
                            <button onclick='deleteProduct("<?php echo $row['id']; ?>")'>Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editProduct(product) {
            document.getElementById('ProductID').value = product.id;
            document.getElementById('ProductID').readOnly = true; // Make ID readonly
            document.getElementById('Category').value = product.category;
            document.getElementById('ProductName').value = product.product_name;
            document.getElementById('Price').value = product.price;
            document.getElementById('Quantity').value = product.quantity;
            document.getElementById('ImagePath').value = product.image_path;
            document.getElementById('Description').value = product.description;
            document.getElementById('Size').value = product.size;
            document.getElementById('action').value = 'edit';
            document.getElementById('submitButton').innerText = 'Update Product';
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('ProductID').value = id;
                document.getElementById('action').value = 'delete';
                document.getElementById('productForm').submit();
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
