<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wdd_cloth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle quantity update or item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $key => $quantity) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                $_SESSION['cart'][$key]['quantity'] = (int)$quantity;
            }
        }
    } elseif (isset($_POST['remove_item']) && isset($_POST['item_key'])) {
        unset($_SESSION['cart'][$_POST['item_key']]);
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Get cart items and calculate total
$cartItems = [];
$subtotal = 0;
$total = 0;
$shippingCost = 500; // Flat rate shipping

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItemKey => $cartItem) {
        $productId = $cartItem['product_id'];
        $stmt = $conn->prepare("SELECT * FROM products_women WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($product = $result->fetch_assoc()) {
            $product['quantity'] = $cartItem['quantity'];
            $product['selected_size'] = $cartItem['size'];
            $product['selected_color'] = $cartItem['color'];
            $product['subtotal'] = $product['price'] * $cartItem['quantity'];
            $subtotal += $product['subtotal'];
            $cartItems[$cartItemKey] = $product;
        }
    }
}
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItemKey => $cartItem) {
        $productId = $cartItem['product_id'];
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($product = $result->fetch_assoc()) {
            $product['quantity'] = $cartItem['quantity'];
            $product['selected_size'] = $cartItem['size'];
            $product['selected_color'] = $cartItem['color'];
            $product['subtotal'] = $product['price'] * $cartItem['quantity'];
            $subtotal += $product['subtotal'];
            $cartItems[$cartItemKey] = $product;
        }
    }
}
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItemKey => $cartItem) {
        $productId = $cartItem['product_id'];
        $stmt = $conn->prepare("SELECT * FROM products_kid WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($product = $result->fetch_assoc()) {
            $product['quantity'] = $cartItem['quantity'];
            $product['selected_size'] = $cartItem['size'];
            $product['selected_color'] = $cartItem['color'];
            $product['subtotal'] = $product['price'] * $cartItem['quantity'];
            $subtotal += $product['subtotal'];
            $cartItems[$cartItemKey] = $product;
        }
    }
}

$total = $subtotal + $shippingCost;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th {
            text-align: left;
            padding: 12px;
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
        }
        .cart-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-btn {
            color: #ff3e3e;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .checkout-btn {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background-color: #333;
        }
        .continue-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f5f5f5;
            color: #333;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .continue-btn:hover {
            background-color: #e0e0e0;
        }
        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }
        .empty-cart-icon {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center" style="background-color: rgba(235, 214, 26, 0.607);">
        <a href="Home Page.php" class="text-2xl font-bold text-gray-800" style="background-color: azure;"><b>Velvet Vogue</b></a>
        <ul class="flex space-x-6" style="background-color: azure;">
            <li><a href="Home Page.php" class="text-gray-600 hover:text-black">Home</a></li>
            <li><a href="Dashboard.php" class="text-gray-600 hover:text-black">Dashboard</a></li>
            <li><a href="Product for men.php" class="text-gray-600 hover:text-black">MEN</a></li>
            <li><a href="product for kid.php" class="text-gray-600 hover:text-black">KID</a></li>
        </ul>
        <button id="cartButton" class="bg-black text-white px-4 py-2 rounded"><a href="cart.php">Cart</a> <?php echo isset($_SESSION['cart']) ? '('.array_sum(array_column($_SESSION['cart'], 'quantity')).')' : ''; ?></button>
    </nav>

    <div class="cart-container">
        <h1 class="text-3xl font-bold mb-6">Your Shopping Cart</h1>
        
        <?php if (!empty($cartItems)): ?>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-2/3">
                    <form method="post">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image mr-4">
                                                    <div>
                                                        <h3 class="font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
                                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($item['category']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>LKR <?php echo number_format($item['price'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($item['selected_size']); ?></td>
                                            <td>
                                                <span class="inline-block w-4 h-4 rounded-full bg-<?php echo htmlspecialchars($item['selected_color']); ?>-500"></span>
                                                <span class="ml-1"><?php echo ucfirst($item['selected_color']); ?></span>
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $key; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                            </td>
                                            <td>LKR <?php echo number_format($item['subtotal'], 2); ?></td>
                                            <td>
                                                <button type="submit" name="remove_item" value="1" class="remove-btn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <input type="hidden" name="item_key" value="<?php echo $key; ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="p-4 flex justify-between items-center bg-gray-50">
                                <a href="Dashboard.php" class="continue-btn">
                                    <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                                </a>
                                <button type="submit" name="update_cart" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Update Cart
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="lg:w-1/3">
                    <div class="summary-card">
                        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>LKR <?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span>LKR <?php echo number_format($shippingCost, 2); ?></span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>LKR <?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                        <a href="checkout.php" class="checkout-btn">
                            Proceed to Checkout
                        </a>
                        <p class="text-sm text-gray-500 mt-3 text-center">
                            Free shipping on orders over LKR 10,000
                        </p>
                    </div>
                    
                    <div class="summary-card mt-6">
                        <h3 class="font-semibold mb-3">Accepted Payment Methods</h3>
                        <div class="flex flex-wrap gap-2">
                            <div class="p-2 border rounded-md">
                                <i class="fab fa-cc-visa text-2xl text-blue-800"></i>
                            </div>
                            <div class="p-2 border rounded-md">
                                <i class="fab fa-cc-mastercard text-2xl text-red-800"></i>
                            </div>
                            <div class="p-2 border rounded-md">
                                <i class="fab fa-cc-amex text-2xl text-blue-600"></i>
                            </div>
                            <div class="p-2 border rounded-md">
                                <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart bg-white rounded-lg shadow p-8">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="Product for women.php" class="inline-block bg-black text-white px-6 py-3 rounded hover:bg-gray-800">
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-gray-900 text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2025 Velvet Vogue. All rights reserved.</p>
    </footer>

    <script>
        // Update cart count in navbar
        function updateCartCount(count) {
            const cartButton = document.getElementById('cartButton');
            if (cartButton) {
                cartButton.innerHTML = `<a href="cart.php">Cart</a> (${count})`;
            }
        }

        // Handle quantity changes
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value < 1) {
                    this.value = 1;
                }
            });
        });

        // If cart is empty, update the count to 0
        <?php if (empty($cartItems)): ?>
            updateCartCount(0);
        <?php endif; ?>
    </script>
</body>
</html>