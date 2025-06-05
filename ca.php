<?php include "connection.php"; // Include the database connection file 

// Query to fetch products from the database
$sql = "SELECT product_name, image_path, size, description, category, price, quantity, total_price FROM products"; // Include total_price in the SQL query
$result = $conn->query($sql); // Initialize an empty array to store product data
$products = array();

if ($result) { // Check if the query was successful
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { // Add the product to the array
            $products[] = array(
                'product_name' => $row['product_name'],
                'image_path' => $row['image_path'],
                'size' => $row['size'], // Keep size in the array
                'description' => $row['description'],
                'category' => strtolower($row['category']), // Convert category to lowercase
                'price' => $row['price'], // Add this line to include the price
                'quantity' => $row['quantity'], // Include quantity from the database
                'total_price' => $row['total_price'] // Include total_price from the database
            );
        }
    }
} else {
    echo "Error: " . $conn->error; // Output error if query fails
}
$conn->close(); // Close the database connection 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Website</title>
    <link rel="stylesheet" href="castyles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        nav { display: flex; justify-content: space-between; background-color: #333; padding: 15px; color: white; }
        ul { list-style: none; display: flex; }
        li { margin: 0 10px; }
        a { color: white; text-decoration: none; cursor: pointer; }
        .categories { display: flex; justify-content: center; gap: 15px; margin: 20px; }
        .category { padding: 10px 20px; background: #ff3f6c; color: white; border-radius: 5px; cursor: pointer; }
        .products { display: flex; flex-wrap: wrap; justify-content: space-around; margin: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; text-align: center; width: 22%; box-sizing: border-box; margin-bottom: 20px; }
        button { background-color: #28a745; color: white; border: none; padding: 10px; cursor: pointer; }
        .cart-sidebar { position: fixed; right: 0; top: 0; width: 300px; height: 100%; background: white; box-shadow: -2px 0 5px rgba(0,0,0,0.5); padding: 20px; display: none; }
        .cart-item { margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .cart-controls { display: flex; gap: 5px; }
        .checkout-form { display: none; margin-top: 20px; }
        .checkout-form input { width: 100%; padding: 8px; margin: 5px 0; }
        .confirmation-message { display: none; text-align: center; margin-top: 20px; }
        .confirmation-message h3 { color: green; }
        .cart-total { font-weight: bold; margin-top: 15px; padding-top: 10px; border-top: 1px solid #ddd; text-align: right; }
    </style>
</head>
<body>
<header>
    <nav>
        <h1>MyStore</h1>
        <ul>
            <li><a href="about.html">ABOUT</a></li>
            <li><a href="#" onClick="toggleCart()">Cart <span id="cart-count">0</span></a></li>
        </ul>
    </nav>
</header>

<!-- Back Button -->
<div style="margin: 20px;">
    <button onClick="history.back()" style="padding: 10px 20px; background-color: #555; color: white; border: none; cursor: pointer; border-radius: 5px;">
        Back
    </button>
</div>

<section class="categories">
    <div class="category" onClick="showCategory('ALL')">ALL PRODUCTS</div>
    <div class="category" onClick="showCategory('clothes')">Clothes</div>
    <div class="category" onClick="showCategory('footwear')">Footwear</div>
    <div class="category" onClick="showCategory('furniture')">Furniture</div>
</section>

<section class="products">
    <?php foreach ($products as $product): ?>
        <div class="product <?php echo htmlspecialchars($product['category']); ?>" 
             data-id="<?php echo htmlspecialchars($product['product_name']); ?>" 
             data-name="<?php echo htmlspecialchars($product['description']); ?>" 
             data-price="<?php echo htmlspecialchars($product['price']); ?>"
             data-category="<?php echo htmlspecialchars($product['category']); ?>">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['description']); ?>" height="350px" width="250px">
            <h2><?php echo htmlspecialchars($product['description']); ?></h2>
            <p>Size: <?php echo htmlspecialchars($product['size']); ?></p>
            <p>Price: Rs.<span class="unit-price"><?php echo htmlspecialchars($product['price']); ?></span></p>
            <p>Available Quantity: <span class="available-quantity"><?php echo htmlspecialchars($product['quantity']); ?></span></p>
            <p>Total Price: Rs.<span class="total-price"><?php echo htmlspecialchars($product['total_price']); ?></span></p>
            <button onClick="addToCart('<?php echo htmlspecialchars($product['product_name']); ?>', '<?php echo htmlspecialchars($product['description']); ?>', '<?php echo htmlspecialchars($product['total_price']); ?>', '<?php echo htmlspecialchars($product['quantity']); ?>')">Add to Cart</button>
        </div>
    <?php endforeach; ?>
</section>

<div class="cart-sidebar" id="cart-sidebar">
    <h2>Shopping Cart</h2>
    <div id="cart-items"></div>
    <button onClick="toggleCart()">Close</button>
    <button onClick="proceedToBuy()">Proceed to Buy</button>

    <div class="checkout-form" id="checkout-form">
        <h3>Enter your details</h3>
        <form id="order-form">
            <input type="text" id="name" placeholder="Full Name" required>
            <input type="text" id="mobile" placeholder="Mobile Number" required>
            <input type="email" id="email" placeholder="Email Address" required>
            <input type="text" id="address" placeholder="Shipping Address" required>
            <input type="text" id="state" placeholder="State" required>
            <input type="text" id="city" placeholder="City" required>
            <input type="number" id="pincode" placeholder="Pincode" required>
            <input type="hidden" id="total-price">
            <input type="hidden" id="total-items">
            <button type="submit">Submit Order</button>
        </form>
    </div>

    <div class="confirmation-message" id="confirmation-message" style="display: none;"></div>
</div>

<script>
    let cart = [];

    function addToCart(productId, productName, productTotalPrice, availableQuantity) {
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            if (existingItem.quantity < availableQuantity) {
                existingItem.quantity += 1;
            } else {
                alert("Cannot add more than available quantity.");
            }
        } else {
            cart.push({ id: productId, name: productName, price: parseFloat(productTotalPrice), quantity: 1, selected: false });
        }
        document.getElementById('cart-count').innerText = cart.length;
        updateCartItems();
    }

    function updateCartItems() {
        const cartItemsContainer = document.getElementById('cart-items');
        cartItemsContainer.innerHTML = '';

        let totalPrice = 0;
        let totalItems = 0;

        cart.forEach(item => {
            totalPrice += item.price * item.quantity;
            totalItems += item.quantity;

            const div = document.createElement('div');
            div.classList.add('cart-item');
            div.innerHTML = `<input type='checkbox' onChange="toggleSelection('${item.id}')" ${item.selected ? 'checked' : ''}> ${item.name} - Rs.${item.price} x ${item.quantity} = Rs.${(item.price * item.quantity).toFixed(2)}`;
            div.innerHTML += `<div class='cart-controls'>
                                <button onClick="changeQuantity('${item.id}', -1)">-</button>
                                <button onClick="changeQuantity('${item.id}', 1)">+</button>
                                <button onClick="removeFromCart('${item.id}')">Remove</button>
                              </div>`;
            cartItemsContainer.appendChild(div);
        });

        const totalDisplay = document.createElement('div');
        totalDisplay.classList.add('cart-total');
        totalDisplay.innerHTML = `Total Items: ${totalItems} | Total Price: Rs.${totalPrice.toFixed(2)}`;
        cartItemsContainer.appendChild(totalDisplay);
    }

    function changeQuantity(productId, change) {
        const item = cart.find(item => item.id === productId);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.id !== productId);
            }
            document.getElementById('cart-count').innerText = cart.length;
            updateCartItems();
        }
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        document.getElementById('cart-count').innerText = cart.length;
        updateCartItems();
    }

    function toggleSelection(productId) {
        const item = cart.find(item => item.id === productId);
        if (item) {
            item.selected = !item.selected;
        }
    }

    function toggleCart() {
        const cartSidebar = document.getElementById('cart-sidebar');
        cartSidebar.style.display = cartSidebar.style.display === 'none' || cartSidebar.style.display === '' ? 'block' : 'none';
    }

    function proceedToBuy() {
        const totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

        document.getElementById('total-price').value = totalPrice.toFixed(2);
        document.getElementById('total-items').value = totalItems;

        document.getElementById('checkout-form').style.display = 'block';
    }

    document.getElementById('order-form').onsubmit = function (e) {
        e.preventDefault();

        const totalPrice = document.getElementById('total-price').value;
        const totalItems = document.getElementById('total-items').value;

        const orderItems = cart.map(item => ({
            name: item.name,
            quantity: item.quantity,
            total: (item.price * item.quantity).toFixed(2)
        }));

        const orderData = {
            name: document.getElementById('name').value,
            mobile: document.getElementById('mobile').value,
            email: document.getElementById('email').value,
            address: document.getElementById('address').value,
            state: document.getElementById('state').value,
            city: document.getElementById('city').value,
            pincode: document.getElementById('pincode').value,
            totalPrice: totalPrice,
            totalItems: totalItems,
            orderItems: JSON.stringify(orderItems)
        };

        fetch('submit_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(orderData)
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            document.getElementById('checkout-form').style.display = 'none';
            document.getElementById('confirmation-message').style.display = 'block';
            document.getElementById('confirmation-message').innerHTML = 
                `<h3>Thank you for your order!</h3>
                <p>Your order has been successfully placed.</p>
                <p>Total Items: ${totalItems}</p>
                <p>Total Price: Rs.${parseFloat(totalPrice).toFixed(2)}</p>
                <p>We will contact you soon.</p>`;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    function showCategory(category) {
        const products = document.querySelectorAll('.product');
        products.forEach(product => {
            if (category === 'ALL' || product.getAttribute('data-category').toLowerCase() === category.toLowerCase()) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }
</script>
</body>
</html>
