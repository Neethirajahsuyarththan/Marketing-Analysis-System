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

// Handle add to cart request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'])) {
    header('Content-Type: application/json');
    
    try {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        $productId = (int)$_POST['productId'];
        $selectedSize = isset($_POST['size']) ? $_POST['size'] : '';
        $selectedColor = isset($_POST['color']) ? $_POST['color'] : '';
        
        // Verify product exists
        $stmt = $conn->prepare("SELECT id FROM products_kid WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Invalid product ID");
        }
        
        // Create cart item key with product ID, size and color
        $cartItemKey = $productId . '_' . $selectedSize . '_' . $selectedColor;
        
        // Add product to cart or increment quantity
        if (isset($_SESSION['cart'][$cartItemKey])) {
            $_SESSION['cart'][$cartItemKey]['quantity']++;
        } else {
            $_SESSION['cart'][$cartItemKey] = [
                'product_id' => $productId,
                'quantity' => 1,
                'size' => $selectedSize,
                'color' => $selectedColor
            ];
        }
        
        echo json_encode(['success' => true, 'count' => array_sum(array_column($_SESSION['cart'], 'quantity'))]);
        exit;
    } catch (Exception $e) {
        error_log("Cart error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Function to fetch products with filtering
function getFilteredProducts($conn, $filters = []) {
    $products = array();
    try {
        $sql = "SELECT * FROM products_kid WHERE 1=1";
        $params = [];
        $types = "";
        
        // Apply category filter
        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
            $types .= "s";
        }
        
        // Apply price filter
        if (!empty($filters['price_range'])) {
            switch($filters['price_range']) {
                case 'low':
                    $sql .= " AND price < 2000";
                    break;
                case 'medium':
                    $sql .= " AND price >= 2000 AND price < 5000";
                    break;
                case 'high':
                    $sql .= " AND price >= 5000";
                    break;
            }
        }
        
        // Apply color filter
        if (!empty($filters['color'])) {
            $colorFilters = [];
            foreach ($filters['color'] as $color) {
                $colorFilters[] = "colors LIKE ?";
                $params[] = "%$color%";
                $types .= "s";
            }
            if (!empty($colorFilters)) {
                $sql .= " AND (" . implode(" OR ", $colorFilters) . ")";
            }
        }
        
        // Apply size filter
        if (!empty($filters['size'])) {
            $sizeFilters = [];
            foreach ($filters['size'] as $size) {
                $sizeFilters[] = "sizes LIKE ?";
                $params[] = "%$size%";
                $types .= "s";
            }
            if (!empty($sizeFilters)) {
                $sql .= " AND (" . implode(" OR ", $sizeFilters) . ")";
            }
        }
        
        // Apply sorting
        if (!empty($filters['sort'])) {
            switch($filters['sort']) {
                case 'newest':
                    $sql .= " ORDER BY id DESC";
                    break;
                case 'price_low':
                    $sql .= " ORDER BY price ASC";
                    break;
                case 'price_high':
                    $sql .= " ORDER BY price DESC";
                    break;
                case 'popular':
                    $sql .= " ORDER BY sales_count DESC";
                    break;
                default:
                    $sql .= " ORDER BY id ASC";
            }
        }
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            // Convert sizes and colors strings to arrays
            $row['sizes_array'] = !empty($row['sizes']) ? explode(',', $row['sizes']) : [];
            $row['colors_array'] = !empty($row['colors']) ? explode(',', $row['colors']) : [];
            $products[] = $row;
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
    }
    return $products;
}

// Handle filter submission
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['filter'])) {
    if (!empty($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }
    if (!empty($_GET['price_range'])) {
        $filters['price_range'] = $_GET['price_range'];
    }
    if (!empty($_GET['color'])) {
        $filters['color'] = $_GET['color'];
    }
    if (!empty($_GET['size'])) {
        $filters['size'] = $_GET['size'];
    }
    if (!empty($_GET['sort'])) {
        $filters['sort'] = $_GET['sort'];
    }
}

// Get filtered products
$filteredProducts = getFilteredProducts($conn, $filters);

// Get all products for category sections if no filters are applied
if (empty($filters)) {
    $boysProducts = getFilteredProducts($conn, ['category' => 'Boys']);
    $girlsProducts = getFilteredProducts($conn, ['category' => 'Girls']);
} else {
    // If filters are applied, show only filtered products
    $boysProducts = $girlsProducts = $filteredProducts;
}

// Get cart items for display
$cartItems = [];
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
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
            $total += $product['subtotal'];
            $cartItems[] = $product;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Kids Clothing Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .filters {
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }
        .filters:hover {
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .filter-group {
            margin-bottom: 25px;
        }
        .filter-group:last-child {
            margin-bottom: 0;
        }
        .filter-group h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
        }
        .filter-group label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            transition: color 0.2s;
        }
        .filter-group label:hover {
            color: #007BFF;
        }
        .filter-group input[type="radio"], .filter-group input[type="checkbox"] {
            margin-right: 10px;
            accent-color: #007BFF;
        }
        .filter-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s;
        }
        .filter-group select:focus {
            border-color: #007BFF;
            outline: none;
        }
        .filter-group .multi-select {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .filter-group .multi-select label {
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }
        .filter-group .multi-select label.selected {
            background-color: #007BFF;
            color: #fff;
        }
        .filter-group .multi-select label:hover {
            background-color: #e0e0e0;
        }
        .apply-filters {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .apply-filters button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .apply-filters button:hover {
            background-color: #0056b3;
        }
        .product {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 220px;
            margin: 10px;
            background: white;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff3e3e;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .fade {
            animation: fadeEffect 1s ease-in-out;
        }
        @keyframes fadeEffect {
            from { opacity: 0.5; }
            to { opacity: 1; }
        }
        header {
            position: relative;
            width: 100%;
            height: 650px;
            background-size: cover;
            background-position: center;
        }
        .slideshow {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hidden {
            display: none;
        }
        .filter-active {
            background-color: #007BFF;
            color: white;
        }
        .size-option {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .size-option.selected {
            background-color: #007BFF;
            color: white;
            border-color: #007BFF;
        }
        .size-option.unavailable {
            color: #ccc;
            border-color: #eee;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .color-option {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin: 2px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .color-option.selected {
            border-color: #007BFF;
        }
        .color-option.unavailable {
            opacity: 0.3;
            cursor: not-allowed;
        }
        .product-details {
            text-align: left;
            margin-top: 10px;
        }
        .product-description {
            font-size: 13px;
            color: #666;
            margin: 5px 0;
            height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-actions {
            margin-top: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .quick-view-btn {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .product:hover .quick-view-btn {
            opacity: 1;
        }
        .nav-link {
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <!-- Navbar -->
        <nav class="bg-yellow-200 shadow-md py-4 px-6 flex justify-between items-center mb-8">
            <a href="#" class="text-2xl font-bold text-gray-800 px-4 py-2 bg-white rounded"><b>Velvet Vogue</b></a>
            <ul class="flex space-x-6">
                <li><a href="Home Page.php" class="text-gray-600 hover:text-black nav-link">Home</a></li>
                <li><a href="Dashboard.php" class="text-gray-600 hover:text-black nav-link">Dashboard</a></li>
                <li><a href="Product for women.php" class="text-gray-600 hover:text-black nav-link">WOMEN</a></li>
                <li><a href="product for men.php" class="text-gray-600 hover:text-black nav-link">MEN</a></li>
                <li><a href="product for kid.php" class="text-gray-600 hover:text-black nav-link bg-white">Accessories</a></li>
            </ul>
            <button id="cartButton" class="bg-black text-white px-4 py-2 rounded">
                <a href="cart.php">Cart</a> <?php echo isset($_SESSION['cart']) ? '('.array_sum(array_column($_SESSION['cart'], 'quantity')).')' : ''; ?>
            </button>
        </nav>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="w-full md:w-1/4 lg:w-1/5">
                <form id="filterForm" method="GET" action="">
                    <div class="filters sticky top-4">
                        <div class="filter-group">
                            <h3><b>Categories</b></h3>
                            <label><input type="radio" name="category" value="Boys" <?= (!empty($_GET['category']) && $_GET['category'] == 'Boys') ? 'checked' : '' ?>> Boys</label>
                            <label><input type="radio" name="category" value="Girls" <?= (!empty($_GET['category']) && $_GET['category'] == 'Girls') ? 'checked' : '' ?>> Girls</label>
                            <label><input type="radio" name="category" value="" <?= empty($_GET['category']) ? 'checked' : '' ?>> All Categories</label>
                        </div>
                    
                        <div class="filter-group">
                            <h3>Price Range</h3>
                            <label><input type="radio" name="price_range" value="low" <?= (!empty($_GET['price_range']) && $_GET['price_range'] == 'low') ? 'checked' : '' ?>> Under LKR 2000</label>
                            <label><input type="radio" name="price_range" value="medium" <?= (!empty($_GET['price_range']) && $_GET['price_range'] == 'medium') ? 'checked' : '' ?>> LKR 2000 - 5000</label>
                            <label><input type="radio" name="price_range" value="high" <?= (!empty($_GET['price_range']) && $_GET['price_range'] == 'high') ? 'checked' : '' ?>> Over LKR 5000</label>
                            <label><input type="radio" name="price_range" value="" <?= empty($_GET['price_range']) ? 'checked' : '' ?>> All Prices</label>
                        </div>
                    
                        <div class="filter-group">
                            <h3>Size</h3>
                            <div class="multi-select">
                                <label class="<?= (!empty($_GET['size']) && in_array('S', $_GET['size'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="size[]" value="S" <?= (!empty($_GET['size']) && in_array('S', $_GET['size'])) ? 'checked' : '' ?>> S
                                </label>
                                <label class="<?= (!empty($_GET['size']) && in_array('M', $_GET['size'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="size[]" value="M" <?= (!empty($_GET['size']) && in_array('M', $_GET['size'])) ? 'checked' : '' ?>> M
                                </label>
                                <label class="<?= (!empty($_GET['size']) && in_array('L', $_GET['size'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="size[]" value="L" <?= (!empty($_GET['size']) && in_array('L', $_GET['size'])) ? 'checked' : '' ?>> L
                                </label>
                                <label class="<?= (!empty($_GET['size']) && in_array('XL', $_GET['size'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="size[]" value="XL" <?= (!empty($_GET['size']) && in_array('XL', $_GET['size'])) ? 'checked' : '' ?>> XL
                                </label>
                            </div>
                        </div>
                    
                        <div class="filter-group">
                            <h3>Color</h3>
                            <div class="multi-select">
                                <label class="<?= (!empty($_GET['color']) && in_array('red', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="red" <?= (!empty($_GET['color']) && in_array('red', $_GET['color'])) ? 'checked' : '' ?>> Red
                                </label>
                                <label class="<?= (!empty($_GET['color']) && in_array('blue', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="blue" <?= (!empty($_GET['color']) && in_array('blue', $_GET['color'])) ? 'checked' : '' ?>> Blue
                                </label>
                                <label class="<?= (!empty($_GET['color']) && in_array('black', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="black" <?= (!empty($_GET['color']) && in_array('black', $_GET['color'])) ? 'checked' : '' ?>> Black
                                </label>
                                <label class="<?= (!empty($_GET['color']) && in_array('white', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="white" <?= (!empty($_GET['color']) && in_array('white', $_GET['color'])) ? 'checked' : '' ?>> White
                                </label>
                                <label class="<?= (!empty($_GET['color']) && in_array('green', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="green" <?= (!empty($_GET['color']) && in_array('green', $_GET['color'])) ? 'checked' : '' ?>> Green
                                </label>
                                <label class="<?= (!empty($_GET['color']) && in_array('yellow', $_GET['color'])) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="color[]" value="yellow" <?= (!empty($_GET['color']) && in_array('yellow', $_GET['color'])) ? 'checked' : '' ?>> Yellow
                                </label>
                            </div>
                        </div>
                    
                        <div class="filter-group">
                            <h3>Sort By</h3>
                            <select name="sort">
                                <option value="">Default</option>
                                <option value="newest" <?= (!empty($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Newest</option>
                                <option value="price_low" <?= (!empty($_GET['sort']) && $_GET['sort'] == 'price_low') ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= (!empty($_GET['sort']) && $_GET['sort'] == 'price_high') ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="popular" <?= (!empty($_GET['sort']) && $_GET['sort'] == 'popular') ? 'selected' : '' ?>>Most Popular</option>
                            </select>
                        </div>
                    
                        <div class="apply-filters">
                            <button type="submit" name="filter" value="1">Apply Filters</button>
                            <?php if(!empty($_GET['filter'])): ?>
                                <a href="product for kid.php" class="ml-2 text-blue-600 hover:underline">Clear Filters</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main Content -->
            <div class="w-full md:w-3/4 lg:w-4/5">
                <!-- Hero Section -->
                <header class="relative w-full h-96 md:h-[500px] rounded-lg overflow-hidden mb-8">
                    <!-- Slideshow Container -->
                    <div class="relative w-full h-full overflow-hidden" >
                        <!-- Images -->
                        <div class="absolute inset-0 bg-black opacity-50"></div>
                            <img src="img/kid/pic for dis/01.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow">
                            <img src="img/kid/pic for dis/02.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                            <img src="img/kid/pic for dis/03.avif" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                            <img src="img/kid/pic for dis/04.webp" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                         </div>
                
                    <!-- Text Overlay -->
                    <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center px-4">
                        <h1 class="text-3xl md:text-5xl font-extrabold mb-4"><b>accessories Collection 2025</b></h1>
                        <p class="text-lg md:text-xl mb-6">Adorable styles for your little ones</p>
                        <a href="#shop" class="bg-white text-black px-6 py-3 rounded shadow-lg hover:bg-gray-300 transition duration-300">Shop Now</a>
                    </div>
                
                    <!-- Navigation Buttons -->
                    <button id="prev" class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75 transition duration-300">&lt;</button>
                    <button id="next" class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75 transition duration-300">&gt;</button>
                </header>

                <!-- Main Content -->
                <div class="w-full p-4" id="shop">
                    <?php if(!empty($filters)): ?>
                        <!-- Show filtered products in one section -->
                        <section class="mb-12">
                            <h1 class="text-2xl font-bold text-center mb-6">Filtered Products</h1>
                            <div class="flex flex-wrap justify-center">
                                <?php foreach($filteredProducts as $product): ?>
                                <div class="product">
                                    <?php if (!empty($product['is_new'])): ?>
                                        <div class="product-badge">NEW</div>
                                    <?php endif; ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <button class="quick-view-btn" onclick="showQuickView(<?php echo $product['id']; ?>)">Quick View</button>
                                    <h3 class="font-semibold mt-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <div class="product-details">
                                        <p class="text-gray-700 font-bold">LKR <?php echo number_format($product['price'], 2); ?></p>
                                        <div class="product-description">
                                            <?php echo htmlspecialchars($product['description']); ?>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-gray-500">Available sizes: <?php echo implode(', ', $product['sizes_array']); ?></small>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-gray-500">Colors: 
                                                <?php foreach($product['colors_array'] as $color): ?>
                                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?php echo $color; ?>; margin-right: 2px;"></span>
                                                <?php endforeach; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="product-actions">
                                        <button onclick="showQuickView(<?php echo $product['id']; ?>)" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-300">View Details</button>
                                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition duration-300">Add to Cart</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if(empty($filteredProducts)): ?>
                                    <p class="text-center w-full py-10">No products match your filters. Try adjusting your criteria.</p>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php else: ?>
                        <!-- Show all products in category sections -->
                        <!-- Boys Products -->
                        <section class="mb-12">
                            <h1 class="text-2xl font-bold text-center mb-6">accessories for Boys</h1>
                            <div id="boysProducts" class="flex flex-wrap justify-center">
                                <?php foreach(array_slice($boysProducts, 0, 4) as $product): ?>
                                <div class="product">
                                    <?php if (!empty($product['is_new'])): ?>
                                        <div class="product-badge">NEW</div>
                                    <?php endif; ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <button class="quick-view-btn" onclick="showQuickView(<?php echo $product['id']; ?>)">Quick View</button>
                                    <h3 class="font-semibold mt-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <div class="product-details">
                                        <p class="text-gray-700 font-bold">LKR <?php echo number_format($product['price'], 2); ?></p>
                                        <div class="product-description">
                                            <?php echo htmlspecialchars($product['description']); ?>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-gray-500">Available sizes: <?php echo implode(', ', $product['sizes_array']); ?></small>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-gray-500">Colors: 
                                                <?php foreach($product['colors_array'] as $color): ?>
                                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?php echo $color; ?>; margin-right: 2px;"></span>
                                                <?php endforeach; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="product-actions">
                                        <button onclick="showQuickView(<?php echo $product['id']; ?>)" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-300">View Details</button>
                                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition duration-300">Add to Cart</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-4">
                                <button id="prevBoysButton" onclick="prevProducts('boys')" class="bg-blue-600 text-white px-4 py-2 rounded mx-2 hover:bg-blue-700 transition duration-300" disabled>Previous</button>
                                <button id="nextBoysButton" onclick="nextProducts('boys')" class="bg-blue-600 text-white px-4 py-2 rounded mx-2 hover:bg-blue-700 transition duration-300" <?php echo count($boysProducts) <= 4 ? 'disabled' : ''; ?>>Next</button>
                            </div>
                        </section>

                        <!-- Girls Products -->
                        <section class="mb-12">
                            <h1 class="text-2xl font-bold text-center mb-6">accessories for Girls</h1>
                            <div id="girlsProducts" class="flex flex-wrap justify-center">
                                <?php foreach(array_slice($girlsProducts, 0, 4) as $product): ?>
                                <div class="product">
                                    <?php if (!empty($product['is_new'])): ?>
                                        <div class="product-badge">NEW</div>
                                    <?php endif; ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <button class="quick-view-btn" onclick="showQuickView(<?php echo $product['id']; ?>)">Quick View</button>
                                    <h3 class="font-semibold mt-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <div class="product-details">
                                        <p class="text-gray-700 font-bold">LKR <?php echo number_format($product['price'], 2); ?></p>
                                        <div class="product-description">
                                            <?php echo htmlspecialchars($product['description']); ?>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-gray-500">Available sizes: <?php echo implode(', ', $product['sizes_array']); ?></small>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-gray-500">Colors: 
                                                <?php foreach($product['colors_array'] as $color): ?>
                                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?php echo $color; ?>; margin-right: 2px;"></span>
                                                <?php endforeach; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="product-actions">
                                        <button onclick="showQuickView(<?php echo $product['id']; ?>)" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-300">View Details</button>
                                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition duration-300">Add to Cart</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-4">
                                <button id="prevGirlsButton" onclick="prevProducts('girls')" class="bg-blue-600 text-white px-4 py-2 rounded mx-2 hover:bg-blue-700 transition duration-300" disabled>Previous</button>
                                <button id="nextGirlsButton" onclick="nextProducts('girls')" class="bg-blue-600 text-white px-4 py-2 rounded mx-2 hover:bg-blue-700 transition duration-300" <?php echo count($girlsProducts) <= 4 ? 'disabled' : ''; ?>>Next</button>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeQuickView()">&times;</span>
            <div id="quickViewContent" class="mt-4"></div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2025 Velvet Vogue. All rights reserved.</p>
    </footer>

    <script>
        // Slideshow functionality
        let slideIndex = 0;
        const slides = document.querySelectorAll(".slideshow");
        
        function showSlides(n) {
            slides.forEach(slide => slide.classList.add("hidden"));
            slides[n].classList.remove("hidden");
        }
        
        function nextSlide() {
            slideIndex = (slideIndex + 1) % slides.length;
            showSlides(slideIndex);
        }
        
        function prevSlide() {
            slideIndex = (slideIndex - 1 + slides.length) % slides.length;
            showSlides(slideIndex);
        }
        
        document.getElementById("next").addEventListener("click", nextSlide);
        document.getElementById("prev").addEventListener("click", prevSlide);
        
        // Auto-slide every 5 seconds
        setInterval(nextSlide, 5000);
        
        // Show the first slide initially
        showSlides(slideIndex);

        // Product pagination
        const productsPerPage = 4;
        let currentPages = {
            boys: 0,
            girls: 0
        };

        // Product data from PHP
        const productData = {
            boys: <?php echo json_encode($boysProducts); ?>,
            girls: <?php echo json_encode($girlsProducts); ?>
        };

        // Display products for a category
        function displayProducts(category) {
            const products = productData[category];
            const start = currentPages[category] * productsPerPage;
            const end = start + productsPerPage;
            const visibleProducts = products.slice(start, end);
            
            const containerId = category + 'Products';
            const container = document.getElementById(containerId);
            
            if (container) {
                container.innerHTML = '';
                
                visibleProducts.forEach(product => {
                    const productElement = document.createElement('div');
                    productElement.className = 'product';
                    
                    let badge = '';
                    if (product.is_new) {
                        badge = '<div class="product-badge">NEW</div>';
                    }
                    
                    // Create color indicators
                    const colorIndicators = product.colors_array.map(color => 
                        `<span class="inline-block w-3 h-3 rounded-full" style="background-color: ${color}; margin-right: 2px;"></span>`
                    ).join('');
                    
                    productElement.innerHTML = `
                        ${badge}
                        <img src="${product.image}" alt="${product.name}">
                        <button class="quick-view-btn" onclick="showQuickView(${product.id})">Quick View</button>
                        <h3 class="font-semibold mt-2">${product.name}</h3>
                        <div class="product-details">
                            <p class="text-gray-700 font-bold">LKR ${parseFloat(product.price).toFixed(2)}</p>
                            <div class="product-description">
                                ${product.description}
                            </div>
                            <div class="mt-2">
                                <small class="text-gray-500">Available sizes: ${product.sizes_array.join(', ')}</small>
                            </div>
                            <div class="mt-1">
                                <small class="text-gray-500">Colors: ${colorIndicators}</small>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button onclick="showQuickView(${product.id})" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-300 transition duration-300">View Details</button>
                            <button onclick="addToCart(${product.id})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition duration-300">Add to Cart</button>
                        </div>
                    `;
                    container.appendChild(productElement);
                });
                
                // Update button states
                const prevButton = document.getElementById(`prev${category.charAt(0).toUpperCase() + category.slice(1)}Button`);
                const nextButton = document.getElementById(`next${category.charAt(0).toUpperCase() + category.slice(1)}Button`);
                    
                if (prevButton) prevButton.disabled = currentPages[category] === 0;
                if (nextButton) nextButton.disabled = end >= products.length;
            }
        }

        // Next products function
        function nextProducts(category) {
            if ((currentPages[category] + 1) * productsPerPage < productData[category].length) {
                currentPages[category]++;
                displayProducts(category);
            }
        }

        // Previous products function
        function prevProducts(category) {
            if (currentPages[category] > 0) {
                currentPages[category]--;
                displayProducts(category);
            }
        }

        // Quick View Modal functions
        function showQuickView(productId) {
            // Find the product in our data
            let product = null;
            for (const category in productData) {
                const found = productData[category].find(p => p.id == productId);
                if (found) {
                    product = found;
                    break;
                }
            }
            
            if (!product) return;
            
            // Create size options
            const sizeOptions = product.sizes_array.map(size => 
                `<span class="size-option" onclick="selectSize(this, '${size}')">${size}</span>`
            ).join('');
            
            // Create color options
            const colorOptions = product.colors_array.map(color => 
                `<span class="color-option" style="background-color: ${color}" onclick="selectColor(this, '${color}')" title="${color}"></span>`
            ).join('');
            
            // Populate modal content
            document.getElementById('quickViewContent').innerHTML = `
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                        <img src="${product.image}" alt="${product.name}" class="w-full rounded-lg">
                    </div>
                    <div class="w-full md:w-1/2">
                        <h2 class="text-2xl font-bold">${product.name}</h2>
                        <p class="text-xl font-bold text-blue-600 my-2">LKR ${parseFloat(product.price).toFixed(2)}</p>
                        
                        <div class="my-4">
                            <h3 class="font-semibold">Description</h3>
                            <p>${product.description}</p>
                        </div>
                        
                        <div class="my-4">
                            <h3 class="font-semibold">Available Sizes</h3>
                            <div class="mt-2">${sizeOptions}</div>
                        </div>
                        
                        <div class="my-4">
                            <h3 class="font-semibold">Available Colors</h3>
                            <div class="mt-2">${colorOptions}</div>
                        </div>
                        
                        <div class="mt-6">
                            <button onclick="addToCartWithOptions(${product.id})" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Show the modal
            document.getElementById('quickViewModal').style.display = 'block';
        }

        function closeQuickView() {
            document.getElementById('quickViewModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('quickViewModal');
            if (event.target == modal) {
                closeQuickView();
            }
        }

        let selectedSize = '';
        let selectedColor = '';

        function selectSize(element, size) {
            document.querySelectorAll('.size-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedSize = size;
        }

        function selectColor(element, color) {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedColor = color;
        }

        function addToCartWithOptions(productId) {
            if (!selectedSize) {
                alert('Please select a size');
                return;
            }
            if (!selectedColor) {
                alert('Please select a color');
                return;
            }
            
            addToCart(productId, selectedSize, selectedColor);
            closeQuickView();
        }

        // Add to cart function
        function addToCart(productId, size = '', color = '') {
            const formData = new FormData();
            formData.append('productId', productId);
            formData.append('size', size);
            formData.append('color', color);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                    updateCartCount(data.count);
                } else {
                    alert('Error: ' + (data.error || 'Failed to add to cart'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        // Update cart count display
        function updateCartCount(count) {
            const cartButton = document.getElementById('cartButton');
            if (cartButton) {
                cartButton.innerHTML = `<a href="cart.php">Cart</a> (${count})`;
            }
        }

        // Multi-select functionality
        document.querySelectorAll('.multi-select label').forEach(label => {
            label.addEventListener('click', () => {
                const checkbox = label.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                label.classList.toggle('selected', checkbox.checked);
            });
        });

        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            // No need to prevent default, let it submit normally
            // The PHP will handle the filtering
        });
    </script>
</body>
</html>