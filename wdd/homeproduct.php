<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Haven - Clothing Store</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #e83e8c;
        }

        .logo span {
            color: #333;
        }

        .cart-icon {
            position: relative;
            font-size: 22px;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #e83e8c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('img/dash board/retail-big_Big.webp');
            background-size: cover;
            background-position: center;
            height: 600px;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .hero-content {
            width: 100%;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background-color: #e83e8c;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #d2337d;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 40px;
            text-align: center;
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .filter-btn {
            background-color: white;
            border: 1px solid #ddd;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: #e83e8c;
            color: white;
            border-color: #e83e8c;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e83e8c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .product-image {
            height: 300px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-info {
            padding: 20px;
        }

        .product-category {
            color: #e83e8c;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .original-price {
            font-size: 16px;
            color: #999;
            text-decoration: line-through;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
        }

        .add-to-cart, .quick-view {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart {
            background-color: #333;
            color: white;
            border: none;
            flex-grow: 1;
            margin-right: 10px;
        }

        .add-to-cart:hover {
            background-color: #e83e8c;
        }

        .quick-view {
            background-color: white;
            color: #333;
            border: 1px solid #ddd;
        }

        .quick-view:hover {
            background-color: #f8f9fa;
            border-color: #e83e8c;
            color: #e83e8c;
        }

        /* Quick View Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 50px auto;
            max-width: 900px;
            border-radius: 10px;
            overflow: hidden;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
        }

        .close-modal {
            font-size: 28px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .modal-body {
                flex-direction: row;
            }
        }

        .modal-image {
            flex: 1;
            padding: 20px;
        }

        .modal-image img {
            width: 100%;
            border-radius: 5px;
        }

        .modal-details {
            flex: 1;
            padding: 20px;
        }

        .modal-category {
            color: #e83e8c;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .modal-price {
            font-size: 24px;
            font-weight: 700;
            margin: 15px 0;
        }

        .modal-description {
            margin-bottom: 20px;
            color: #666;
        }

        .size-selector {
            margin-bottom: 20px;
        }

        .size-options {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .size-option {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .size-option:hover, .size-option.selected {
            background-color: #e83e8c;
            color: white;
            border-color: #e83e8c;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .modal-add-to-cart {
            flex: 1;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-add-to-cart:hover {
            background-color: #e83e8c;
        }

        .wishlist-btn {
            width: 50px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .wishlist-btn:hover {
            background-color: #f8f9fa;
            border-color: #e83e8c;
            color: #e83e8c;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #e83e8c;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 20px;
        }

        .social-icons a {
            color: white;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #e83e8c;
        }

        .copyright {
            color: #aaa;
            font-size: 14px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .hero {
                height: 300px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .product-actions {
                flex-direction: column;
                gap: 10px;
            }

            .add-to-cart, .quick-view {
                width: 100%;
                margin-right: 0;
            }
        }
        nav {
    background-color: rgba(235, 214, 26, 0.607);
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.1);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav a {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1a1a1a;
    background-color: azure;
    text-decoration: none;
}

nav ul {
    display: flex;
    gap: 1.5rem;
    background-color: azure;
    list-style: none;
    padding: 0;
    margin: 0;
}

nav ul li a {
    color: #4a4a4a;
    font-weight: normal;
    background-color: transparent;
}

nav ul li a:hover {
    color: #000000;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center" style="background-color: rgba(235, 214, 26, 0.607);">
                    <a href="#" class="text-2xl font-bold text-gray-800" style="background-color: azure;"><b>Velvet Vogue</b></a>
                    <ul class="flex space-x-6" style="background-color: azure;">
                        <li><a href="Home Page.php" class="text-gray-600 hover:text-black">Home</a></li>
                        <li><a href="contact us1.php" class="text-gray-600 hover:text-black">contact us</a></li>
                    </ul>
                </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>New Summer Collection</h1>
                <p>Discover our latest trends for the season</p>
                <a href="Login page.php" class="btn">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Filter Section -->
        <section class="filter-section">
            <h2>Our Products</h2>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="men">Men</button>
                <button class="filter-btn" data-filter="women">Women</button>
                <button class="filter-btn" data-filter="kids">accessories</button>
            </div>
        </section>

        <!-- Products Grid -->
        <section class="products-grid">
            <!-- Product 1 -->
            <div class="product-card" data-category="women">
                <div class="product-badge">New</div>
                <div class="product-image">
                    <img src="img/women/saree/05.webp" alt="Floral Summer Dress">
                </div>
                <div class="product-info">
                    <div class="product-category">Women</div>
                    <h3 class="product-title">Floral Summer Dress</h3>
                    <div class="product-price">
                        <span class="current-price">$39.99</span>
                        <span class="original-price">$59.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="1">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-card" data-category="men">
                <div class="product-image">
                    <img src="img/men/jeans/IMG-20250130-WA0007.jpg" alt="Classic Denim Jeans">
                </div>
                <div class="product-info">
                    <div class="product-category">Men</div>
                    <h3 class="product-title">Classic Denim Jeans</h3>
                    <div class="product-price">
                        <span class="current-price">$49.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="2">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="product-card" data-category="women">
                <div class="product-badge">Sale</div>
                <div class="product-image">
                    <img src="img/women/saree/10.jpg" alt="Elegant Blouse">
                </div>
                <div class="product-info">
                    <div class="product-category">Women</div>
                    <h3 class="product-title">Elegant Blouse</h3>
                    <div class="product-price">
                        <span class="current-price">$29.99</span>
                        <span class="original-price">$39.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="3">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="product-card" data-category="kids">
                <div class="product-image">
                    <img src="img/kid/boy/WhatsApp Image 2025-01-30 at 13.04.48_f927a65b.jpg" alt="Kids T-Shirt Set">
                </div>
                <div class="product-info">
                    <div class="product-category">Kids</div>
                    <h3 class="product-title">Kids T-Shirt Set</h3>
                    <div class="product-price">
                        <span class="current-price">$24.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="4">Quick View</button>
                    </div>
                </div>
            </div>
            <div class="product-card" data-category="kids">
                <div class="product-image">
                    <img src="img/kid/boy/WhatsApp Image 2025-01-30 at 13.04.51_a5b09f88.jpg" alt="Kids T-Shirt Set">
                </div>
                <div class="product-info">
                    <div class="product-category">Kids</div>
                    <h3 class="product-title">Kids T-Shirt Set</h3>
                    <div class="product-price">
                        <span class="current-price">$44.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="4">Quick View</button>
                    </div>
                </div>
            </div>
            <div class="product-card" data-category="kids">
                <div class="product-image">
                    <img src="img/kid/boy/WhatsApp Image 2025-01-30 at 13.04.58_ebe4cb10.jpg" alt="Kids T-Shirt Set">
                </div>
                <div class="product-info">
                    <div class="product-category">Kids</div>
                    <h3 class="product-title">Kids T-Shirt Set</h3>
                    <div class="product-price">
                        <span class="current-price">$34.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="4">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product 5 -->
            <div class="product-card" data-category="men">
                <div class="product-image">
                    <img src="img/men/t shart/IMG-20250130-WA0015.jpg" alt="Casual Polo Shirt">
                </div>
                <div class="product-info">
                    <div class="product-category">Men</div>
                    <h3 class="product-title">Casual Polo Shirt</h3>
                    <div class="product-price">
                        <span class="current-price">$34.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="5">Quick View</button>
                    </div>
                </div>
            </div>

            <div class="product-card" data-category="men">
                <div class="product-image">
                    <img src="img/men/jeans/IMG-20250130-WA0007.jpg" alt="Casual Polo Shirt">
                </div>
                <div class="product-info">
                    <div class="product-category">Men</div>
                    <h3 class="product-title">Casual Polo jeans</h3>
                    <div class="product-price">
                        <span class="current-price">$34.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="5">Quick View</button>
                    </div>
                </div>
            </div>


            <!-- Product 7 -->
            <div class="product-card" data-category="women">
                <div class="product-image">
                    <img src="img/women/shirt/05.jpeg" alt="Summer Skirt">
                </div>
                <div class="product-info">
                    <div class="product-category">Women</div>
                    <h3 class="product-title">Summer Skirt</h3>
                    <div class="product-price">
                        <span class="current-price">$35.99</span>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart">view product</button>
                        <button class="quick-view" data-id="7">Quick View</button>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <!-- Quick View Modal -->
    <div class="modal" id="quickViewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Product Details</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-image">
                    <img src="" alt="" id="modalProductImage">
                </div>
                <div class="modal-details">
                    <div class="modal-category" id="modalProductCategory"></div>
                    <h3 id="modalProductTitle"></h3>
                    <div class="modal-price" id="modalProductPrice"></div>
                    <div class="modal-description" id="modalProductDescription">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                    </div>
                    <div class="size-selector">
                        <h4>Size</h4>
                        <div class="size-options">
                            <div class="size-option">XS</div>
                            <div class="size-option">S</div>
                            <div class="size-option selected">M</div>
                            <div class="size-option">L</div>
                            <div class="size-option">XL</div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button class="modal-add-to-cart">View product</button>
                        <button class="wishlist-btn">❤</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="logo">Fashion<span>Haven</span></div>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#">login</a>
                    <a href="#">Contact</a>
                </div>
                <div class="social-icons">
                    <a href="#">📱</a>
                    <a href="#">📘</a>
                    <a href="#">📸</a>
                    <a href="#">🐦</a>
                </div>
                <div class="copyright">
                    &copy; 2023 FashionHaven. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter products
            const filterButtons = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const filter = button.getAttribute('data-filter');

                    // Filter products
                    productCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Quick View Modal
            const quickViewButtons = document.querySelectorAll('.quick-view');
            const modal = document.getElementById('quickViewModal');
            const closeModal = document.querySelector('.close-modal');
            const modalProductImage = document.getElementById('modalProductImage');
            const modalProductCategory = document.getElementById('modalProductCategory');
            const modalProductTitle = document.getElementById('modalProductTitle');
            const modalProductPrice = document.getElementById('modalProductPrice');

            // Sample product data (in a real app, this would come from a database)
            const products = {
                1: {
                    image: 'img/women/saree/05.webp',
                    category: 'Women',
                    title: 'Floral Summer Dress',
                    price: '$39.99',
                    originalPrice: '$59.99',
                    description: 'Beautiful floral summer dress made from lightweight, breathable fabric. Perfect for warm days and special occasions.'
                },
                2: {
                    image: 'img/men/jeans/IMG-20250130-WA0007.jpg',
                    category: 'Men',
                    title: 'Classic Denim Jeans',
                    price: '$49.99',
                    description: 'Premium quality denim jeans with a classic fit. Durable and comfortable for everyday wear.'
                },
                3: {
                    image: 'img/women/saree/10.jpg',
                    category: 'Women',
                    title: 'Elegant Blouse',
                    price: '$29.99',
                    originalPrice: '$39.99',
                    description: 'Elegant blouse with delicate details. Perfect for both office wear and evening occasions.'
                },
                4: {
                    image: 'img/kid/boy/WhatsApp Image 2025-01-30 at 13.04.48_f927a65b.jpg',
                    category: 'Kids',
                    title: 'Kids T-Shirt Set',
                    price: '$24.99',
                    description: 'Comfortable cotton t-shirt set for kids. Available in various colors and prints.'
                },
                5: {
                    image: 'img/men/jeans/IMG-20250130-WA0007.jpg',
                    category: 'Men',
                    title: 'Casual Polo jeans',
                    price: '$34.99',
                    description: 'Classic polo shirt made from premium cotton. Perfect for casual and smart-casual occasions.'
                },
                6: {
                    image: '',
                    category: 'Accessories',
                    title: 'Leather Handbag',
                    price: '$59.99',
                    originalPrice: '$79.99',
                    description: 'Genuine leather handbag with multiple compartments. Stylish and practical for everyday use.'
                },
                7: {
                    image: 'img/women/shirt/05.jpeg',
                    category: 'Women',
                    title: 'Summer Skirt',
                    price: '$35.99',
                    description: 'Light and airy summer skirt with a comfortable elastic waistband. Perfect for hot summer days.'
                },
                8: {
                    image: '',
                    category: 'Accessories',
                    title: 'Stylish Sunglasses',
                    price: '$24.99',
                    description: 'UV-protection sunglasses with a trendy frame design. Available in multiple colors.'
                }
            };

            quickViewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-id');
                    const product = products[productId];

                    // Update modal content
                    modalProductImage.src = product.image;
                    modalProductImage.alt = product.title;
                    modalProductCategory.textContent = product.category;
                    modalProductTitle.textContent = product.title;
                    
                    if (product.originalPrice) {
                        modalProductPrice.innerHTML = `${product.price} <span style="text-decoration: line-through; color: #999; font-size: 18px; margin-left: 10px;">${product.originalPrice}</span>`;
                    } else {
                        modalProductPrice.textContent = product.price;
                    }

                    // Show modal
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            // Close modal
            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });

            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Size selection
            const sizeOptions = document.querySelectorAll('.size-option');
            sizeOptions.forEach(option => {
                option.addEventListener('click', () => {
                    sizeOptions.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                });
            });

            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.add-to-cart, .modal-add-to-cart');
            const cartCount = document.querySelector('.cart-count');
            let count = 0;

            addToCartButtons.forEach(button => {
                button.addEventListener('click', () => {
                    count++;
                    cartCount.textContent = count;
                    
                    // Show confirmation (in a real app, this would be more sophisticated)
                    alert('Product added to cart!');
                    
                    // Close modal if it's open
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
            });

            // Wishlist button
            const wishlistBtn = document.querySelector('.wishlist-btn');
            wishlistBtn.addEventListener('click', function() {
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    alert('Added to wishlist!');
                } else {
                    alert('Removed from wishlist!');
                }
            });
        });
    </script>
</body>
</html>