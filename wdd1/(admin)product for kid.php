<?php
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

// Create products_kid table if it doesn't exist (with new fields)
$createTable = "CREATE TABLE IF NOT EXISTS products_kid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    sizes VARCHAR(100),
    colors VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTable)) {
    die("Error creating table: " . $conn->error);
}

// Handle file uploads
function handleFileUpload($fieldName) {
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($_FILES[$fieldName]["name"]);
    $targetFile = $targetDir . uniqid() . "_" . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
    if($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }
    
    // Check file size (5MB max)
    if ($_FILES[$fieldName]["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return null;
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            return null;
        }
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new product
    if (isset($_POST['add_product'])) {
        $category = $conn->real_escape_string($_POST['category']);
        $name = $conn->real_escape_string($_POST['product_name']);
        $description = $conn->real_escape_string($_POST['product_description']);
        $price = floatval($_POST['product_price']);
        
        // Process sizes
        $sizes = isset($_POST['sizes']) ? implode(",", $_POST['sizes']) : "";
        $sizes = $conn->real_escape_string($sizes);
        
        // Process colors
        $colors = isset($_POST['colors']) ? implode(",", $_POST['colors']) : "";
        $colors = $conn->real_escape_string($colors);
        
        // Handle file upload
        $image = "https://via.placeholder.com/200"; // default image
        if (isset($_FILES['product_image_upload']) && $_FILES['product_image_upload']['error'] == UPLOAD_ERR_OK) {
            $uploadedImage = handleFileUpload('product_image_upload');
            if ($uploadedImage) {
                $image = $uploadedImage;
            }
        } elseif (!empty($_POST['product_image_url'])) {
            $image = $conn->real_escape_string($_POST['product_image_url']);
        }
        
        $stmt = $conn->prepare("INSERT INTO products_kid (category, name, description, price, image, sizes, colors) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdsss", $category, $name, $description, $price, $image, $sizes, $colors);
        
        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding product: " . $conn->error . "');</script>";
        }
        $stmt->close();
    }
    
    // Update product
    if (isset($_POST['update_product'])) {
        $id = intval($_POST['product_id']);
        $category = $conn->real_escape_string($_POST['category']);
        $name = $conn->real_escape_string($_POST['product_name']);
        $description = $conn->real_escape_string($_POST['product_description']);
        $price = floatval($_POST['product_price']);
        
        // Process sizes
        $sizes = isset($_POST['sizes']) ? implode(",", $_POST['sizes']) : "";
        $sizes = $conn->real_escape_string($sizes);
        
        // Process colors
        $colors = isset($_POST['colors']) ? implode(",", $_POST['colors']) : "";
        $colors = $conn->real_escape_string($colors);
        
        // First get current image
        $currentImage = "";
        $result = $conn->query("SELECT image FROM products_kid WHERE id = $id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentImage = $row['image'];
        }
        
        // Handle file upload for update
        $image = $currentImage; // keep current image by default
        if (isset($_FILES['edit_product_image_upload']) && $_FILES['edit_product_image_upload']['error'] == UPLOAD_ERR_OK) {
            $uploadedImage = handleFileUpload('edit_product_image_upload');
            if ($uploadedImage) {
                $image = $uploadedImage;
                // Optionally delete old image file here if desired
            }
        } elseif (!empty($_POST['edit_product_image_url'])) {
            $image = $conn->real_escape_string($_POST['edit_product_image_url']);
        }
        
        $stmt = $conn->prepare("UPDATE products_kid SET category=?, name=?, description=?, price=?, image=?, sizes=?, colors=? WHERE id=?");
        $stmt->bind_param("sssdsssi", $category, $name, $description, $price, $image, $sizes, $colors, $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Product updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
        }
        $stmt->close();
    }
}

// Handle delete requests
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // First get image path to delete the file
    $imagePath = "";
    $result = $conn->query("SELECT image FROM products_kid WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image'];
        // Delete the image file if it's not the placeholder and exists in uploads folder
        if (strpos($imagePath, 'uploads/') !== false && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    $stmt = $conn->prepare("DELETE FROM products_kid WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!');</script>";
        echo "<script>window.location.href = window.location.pathname;</script>";
    } else {
        echo "<script>alert('Error deleting product: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

// Function to display products
function displayProducts($conn, $category, $productsPerPage = 4) {
    $page = isset($_GET['page_'.$category]) ? (int)$_GET['page_'.$category] : 1;
    $start = ($page - 1) * $productsPerPage;
    
    $stmt = $conn->prepare("SELECT * FROM products_kid WHERE category = ? LIMIT ?, ?");
    $stmt->bind_param("sii", $category, $start, $productsPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div id='products_$category' class='products-container'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='".htmlspecialchars($row['image'])."' alt='".htmlspecialchars($row['name'])."'>";
            echo "<h3>".htmlspecialchars($row['name'])."</h3>";
            echo "<p class='text-sm text-gray-600'>".nl2br(htmlspecialchars($row['description']))."</p>";
            echo "<p class='text-sm'><b>Sizes:</b> ".htmlspecialchars($row['sizes'])."</p>";
            echo "<p class='text-sm'><b>Colors:</b> ".htmlspecialchars($row['colors'])."</p>";
            echo "<p class='text-lg font-bold'>LKR ".number_format($row['price'], 2)."</p>";
            echo "<button class='buy-button' data-name='".htmlspecialchars($row['name'])."' data-price='".$row['price']."'>BUY NOW</button>";
            
            // Edit and Delete buttons
            echo "<div class='admin-actions mt-2'>";
            echo "<button onclick=\"showEditForm(".$row['id'].",'".$row['category']."','".htmlspecialchars($row['name'])."','".htmlspecialchars($row['description'])."','".$row['price']."','".htmlspecialchars($row['image'])."','".htmlspecialchars($row['sizes'])."','".htmlspecialchars($row['colors'])."')\" class='edit-button bg-yellow-500 text-white px-2 py-1 rounded text-sm'>Edit</button> ";
            echo "<button onclick=\"if(confirm('Are you sure you want to delete this product?')){window.location.href='?delete=".$row['id']."'}\" class='delete-button bg-red-500 text-white px-2 py-1 rounded text-sm'>Delete</button>";
            echo "</div>";
            
            echo "</div>";
        }
        echo "</div>";
        
        // Pagination
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products_kid WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $totalResult = $stmt->get_result();
        $totalRow = $totalResult->fetch_assoc();
        $totalProducts = $totalRow['total'];
        $totalPages = ceil($totalProducts / $productsPerPage);
        
        echo "<div class='navigation-buttons'>";
        echo "<table><tr>";
        echo "<td width='90%'>";
        if ($page > 1) {
            echo "<button id='prevButton_$category' onclick=\"window.location.href='?page_$category=".($page-1)."'\">Previous</button>";
        } else {
            echo "<button id='prevButton_$category' disabled>Previous</button>";
        }
        echo "</td>";
        echo "<td width='100%'></td>";
        echo "<td width='90%'>";
        if ($page < $totalPages) {
            echo "<button id='nextButton_$category' onclick=\"window.location.href='?page_$category=".($page+1)."'\">Next</button>";
        } else {
            echo "<button id='nextButton_$category' disabled>Next</button>";
        }
        echo "</td>";
        echo "</tr></table>";
        echo "</div>";
    } else {
        echo "<p>No products found in this category.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Online Clothing Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:rgb(246, 240, 149);
        }
        
        .filters {
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color:rgba(8, 245, 166, 0.59);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
            height: 800px;
        }
        
        .products-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .product {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            width: 250px;
            background: white;
            transition: transform 0.3s;
        }
        
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(131, 244, 225, 0.77);
        }
        
        .product img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .navigation-buttons {
            margin: 20px 0;
            text-align: center;
            width: 29cm;
        }
        
        .navigation-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
        }
        
        .navigation-buttons button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        
        .admin-panel {
            background-color: #ff69b4;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .buy-button {
            background-color:rgb(255, 255, 255);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 5px;
        }
        
        .buy-button:hover {
            background-color:rgb(255, 255, 255);
        }
        
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }
        
        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }
        
        .hidden {
            display: none;
        }
        
        .selected {
            background-color:rgb(165, 231, 240);
        }
        
        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        
        .edit-form-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .edit-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .image-upload-container {
            margin-top: 10px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .tab-button {
            padding: 10px 20px;
            background: #ddd;
            border: none;
            cursor: pointer;
        }
        
        .tab-button.active {
            background: #007BFF;
            color: white;
        }
        
        .size-options, .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }
        
        .size-option, .color-option {
            display: flex;
            align-items: center;
        }
        
        .color-box {
            width: 20px;
            height: 20px;
            border: 1px solid #ccc;
            margin-right: 5px;
            display: inline-block;
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
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center" style="background-color: rgba(235, 214, 26, 0.607);">
        <a href="#" class="text-2xl font-bold text-gray-800" style="background-color: azure;"><b>Velvet Vogue</b></a>
        <ul class="flex space-x-6" style="background-color: azure;">
            <li><a href="Home Page.php" class="text-gray-600 hover:text-black">Home</a></li>
            <li><a href="Dashboard.php" class="text-gray-600 hover:text-black">Dashboard</a></li>
            <li><a href="(admin)Product for women .php" class="text-gray-600 hover:text-black">women</a></li>
            <li><a href="(admin)Product for men .php" class="text-gray-600 hover:text-black">Man</a></li>
        </ul>
      
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <table width="100%" align="center" border="0">
            <tr>
                <table>
                <td width="20%" valign="top">
                    <!-- Filters Sidebar -->
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
                </td>
                
                <td width="80%" valign="top">
                    <!-- Hero Section -->
                    <header class="relative w-full h-[650px] bg-cover bg-center mb-8">
                        <div class="relative w-full h-full overflow-hidden">
                        <img src="img/kid/pic for dis/01.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow">
                            <img src="img/kid/pic for dis/02.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                            <img src="img/kid/pic for dis/03.avif" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                            <img src="img/kid/pic for dis/04.webp" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                         </div>
                    
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center">
                            <h1 class="text-5xl font-extrabold"><b>New Collection 2025</b></h1>
                            <p class="mt-4 text-lg">Explore the latest trends in fashion</p>
                            <a href="#shop" class="mt-6 bg-white text-black px-6 py-3 rounded shadow-lg hover:bg-gray-300">Shop Now</a>
                        </div>
                    
                        <button id="prev" class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75" >&lt;</button>
                        <button id="next" class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75">&gt;</button>
                    </header>
                </td>
              </table>
                    <!-- Product Sections -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                        <center>
                            <h1 class="text-2xl font-bold mb-6"><b>Accessories for boys</b></h1>
                        </center>
                        <?php displayProducts($conn, 'boys'); ?>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                        <center>
                            <h1 class="text-2xl font-bold mb-6"><b>Accessories for girls</b></h1>
                        </center>
                        <?php displayProducts($conn, 'girls'); ?>
                    </div>
                    
                    <!-- Admin Panel -->
                    <div class="admin-panel">
                        <h3 class="text-xl font-bold mb-4">🛠️ Admin: Add New Product</h3>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="add_product" value="1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" class="p-2 border rounded" required>
                                        <option value="">Select Category</option>
                                        <option value="boys">boys</option>
                                        <option value="girls">girls</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" name="product_name" placeholder="Product Name" class="p-2 border rounded" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_description">Description</label>
                                    <textarea name="product_description" placeholder="Product description" class="p-2 border rounded"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_price">Price (LKR)</label>
                                    <input type="number" name="product_price" placeholder="Price" step="0.01" class="p-2 border rounded" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Available Sizes</label>
                                    <div class="size-options">
                                        <label class="size-option"><input type="checkbox" name="sizes[]" value="XS"> XS</label>
                                        <label class="size-option"><input type="checkbox" name="sizes[]" value="S"> S</label>
                                        <label class="size-option"><input type="checkbox" name="sizes[]" value="M"> M</label>
                                        <label class="size-option"><input type="checkbox" name="sizes[]" value="L"> L</label>
                                        <label class="size-option"><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Available Colors</label>
                                    <div class="color-options">
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="Red"> <span class="color-box" style="background-color: #ff0000;"></span> Red</label>
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="Blue"> <span class="color-box" style="background-color: #0000ff;"></span> Blue</label>
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="Green"> <span class="color-box" style="background-color: #00ff00;"></span> Green</label>
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="Yellow"> <span class="color-box" style="background-color: #ffff00;"></span> Yellow</label>
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="Black"> <span class="color-box" style="background-color: #000000;"></span> Black</label>
                                        <label class="color-option"><input type="checkbox" name="colors[]" value="White"> <span class="color-box" style="background-color: #ffffff; border: 1px solid #000;"></span> White</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_image_url">Image URL</label>
                                    <input type="text" name="product_image_url" placeholder="Image URL" class="p-2 border rounded">
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_image_upload">Or upload image</label>
                                    <input type="file" id="product_image_upload" name="product_image_upload" accept="image/*" class="w-full">
                                </div>
                            </div>
                            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Product</button>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    </table>
    
    <!-- Edit Form (hidden by default) -->
    <div id="editFormContainer" class="edit-form-container hidden">
        <div class="edit-form">
            <h2 class="text-xl font-bold mb-4">Edit Product</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="update_product" value="1">
                <input type="hidden" id="edit_product_id" name="product_id">
                
                <div class="form-group">
                    <label for="edit_category">Category</label>
                    <select id="edit_category" name="category" class="p-2 border rounded" required>
                        <option value="boys">boys</option>
                        <option value="girls">girls</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_product_name">Product Name</label>
                    <input type="text" id="edit_product_name" name="product_name" class="p-2 border rounded" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_product_description">Description</label>
                    <textarea id="edit_product_description" name="product_description" class="p-2 border rounded"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_product_price">Price (LKR)</label>
                    <input type="number" id="edit_product_price" name="product_price" step="0.01" class="p-2 border rounded" required>
                </div>
                
                <div class="form-group">
                    <label>Available Sizes</label>
                    <div class="size-options" id="edit_size_options">
                        <label class="size-option"><input type="checkbox" name="sizes[]" value="XS"> XS</label>
                        <label class="size-option"><input type="checkbox" name="sizes[]" value="S"> S</label>
                        <label class="size-option"><input type="checkbox" name="sizes[]" value="M"> M</label>
                        <label class="size-option"><input type="checkbox" name="sizes[]" value="L"> L</label>
                        <label class="size-option"><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Available Colors</label>
                    <div class="color-options" id="edit_color_options">
                        <label class="color-option"><input type="checkbox" name="colors[]" value="Red"> <span class="color-box" style="background-color: #ff0000;"></span> Red</label>
                        <label class="color-option"><input type="checkbox" name="colors[]" value="Blue"> <span class="color-box" style="background-color: #0000ff;"></span> Blue</label>
                        <label class="color-option"><input type="checkbox" name="colors[]" value="Green"> <span class="color-box" style="background-color: #00ff00;"></span> Green</label>
                        <label class="color-option"><input type="checkbox" name="colors[]" value="Yellow"> <span class="color-box" style="background-color: #ffff00;"></span> Yellow</label>
                        <label class="color-option"><input type="checkbox" name="colors[]" value="Black"> <span class="color-box" style="background-color: #000000;"></span> Black</label>
                        <label class="color-option"><input type="checkbox" name="colors[]" value="White"> <span class="color-box" style="background-color: #ffffff; border: 1px solid #000;"></span> White</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_product_image_url">Image URL</label>
                    <input type="text" id="edit_product_image_url" name="edit_product_image_url" class="p-2 border rounded">
                </div>
                
                <div class="image-upload-container">
                    <label for="edit_product_image_upload" class="block mb-2">Or upload a new image:</label>
                    <input type="file" id="edit_product_image_upload" name="edit_product_image_upload" accept="image/*" class="w-full">
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="hideEditForm()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Product</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-4">
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
        showSlides(slideIndex);

        // Multi-select functionality
        document.addEventListener('DOMContentLoaded', () => {
            const multiSelectLabels = document.querySelectorAll('.multi-select label');

            multiSelectLabels.forEach(label => {
                label.addEventListener('click', () => {
                    label.classList.toggle('selected');
                });
            });

            // Buy button functionality
            document.querySelectorAll('.buy-button').forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const price = parseFloat(this.getAttribute('data-price'));
                    buyItem(name, price);
                });
            });
        });

        // Purchase history functions
        function buyItem(name, price) {
            let history = JSON.parse(localStorage.getItem("purchaseHistory")) || [];
            history.push({ name, price, date: new Date().toLocaleString() });
            localStorage.setItem("purchaseHistory", JSON.stringify(history));
            alert(`Added ${name} to your cart!`);
        }

        function displayHistory() {
            let history = JSON.parse(localStorage.getItem("purchaseHistory")) || [];
            let historyDiv = document.getElementById("history");
            let totalSpent = history.reduce((sum, item) => sum + item.price, 0);

            historyDiv.innerHTML = history.length > 0 ? 
                history.map(item => 
                    `<div class="history">${item.date} - <strong>${item.name}</strong> (LKR ${item.price.toFixed(2)})</div>`
                ).join("") 
                : "<p>No purchases yet!</p>";

            document.getElementById("total-spent").textContent = `Total Spent: LKR ${totalSpent.toFixed(2)}`;
        }

        function clearHistory() {
            localStorage.removeItem("purchaseHistory");
            displayHistory();
        }

        // Load history on page load
        displayHistory();
        
        // Edit product functions
        function showEditForm(id, category, name, description, price, image, sizes, colors) {
            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_product_name').value = name;
            document.getElementById('edit_product_description').value = description;
            document.getElementById('edit_product_price').value = price;
            document.getElementById('edit_product_image_url').value = image;
            
            // Set sizes checkboxes
            const sizeCheckboxes = document.querySelectorAll('#edit_size_options input[type="checkbox"]');
            const sizesArray = sizes ? sizes.split(',') : [];
            sizeCheckboxes.forEach(checkbox => {
                checkbox.checked = sizesArray.includes(checkbox.value);
            });
            
            // Set colors checkboxes
            const colorCheckboxes = document.querySelectorAll('#edit_color_options input[type="checkbox"]');
            const colorsArray = colors ? colors.split(',') : [];
            colorCheckboxes.forEach(checkbox => {
                checkbox.checked = colorsArray.includes(checkbox.value);
            });
            
            document.getElementById('editFormContainer').classList.remove('hidden');
        }
        
        function hideEditForm() {
            document.getElementById('editFormContainer').classList.add('hidden');
        }
        
        // Tab functionality
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }
            
            const tabButtons = document.getElementsByClassName("tab-button");
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>




BUYNOW