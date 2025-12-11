<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Velvet Vogue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #6d3d6d;
            --secondary-color: #f8e1f4;
            --accent-color: #d4a5c9;
            --text-color: #333;
            --light-text: #777;
            --white: #fff;
            --black: #000;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--accent-color);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: var(--transition);
        }
        
        .nav-links a:hover {
            color: var(--primary-color);
        }
        
        .mobile-menu {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Hero Section */
        .about-hero {
            background: linear-gradient(rgba(109, 61, 109, 0.8), rgba(109, 61, 109, 0.8)), url('https://images.unsplash.com/photo-1469334031218-e382a71b716b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: 80px;
            color: var(--white);
        }
        
        .about-hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        /* About Content */
        .about-content {
            padding: 80px 0;
        }
        
        .about-section {
            display: flex;
            align-items: center;
            margin-bottom: 60px;
        }
        
        .about-text {
            flex: 1;
            padding-right: 40px;
        }
        
        .about-text h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .about-text p {
            margin-bottom: 20px;
            color: var(--light-text);
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: var(--transition);
        }
        
        .about-image:hover img {
            transform: scale(1.05);
        }
        
        /* Team Section */
        .team-section {
            padding: 60px 0;
            background-color: var(--secondary-color);
            text-align: center;
        }
        
        .team-section h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: var(--primary-color);
        }
        
        .team-members {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .team-member {
            background-color: var(--white);
            border-radius: 10px;
            padding: 30px;
            width: 280px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        
        .team-member:hover {
            transform: translateY(-10px);
        }
        
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 5px solid var(--accent-color);
        }
        
        .team-member h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .team-member p {
            color: var(--light-text);
            margin-bottom: 15px;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-links a {
            color: var(--primary-color);
            font-size: 18px;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            color: var(--accent-color);
        }
        
        /* Values Section */
        .values-section {
            padding: 80px 0;
            text-align: center;
        }
        
        .values-section h2 {
            font-size: 36px;
            margin-bottom: 60px;
            color: var(--primary-color);
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .value-card {
            background-color: var(--white);
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
            font-size: 50px;
            color: var(--accent-color);
            margin-bottom: 20px;
        }
        
        .value-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .value-card p {
            color: var(--light-text);
        }
        
        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 60px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            font-size: 20px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .footer-column p {
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: var(--white);
            text-decoration: none;
            opacity: 0.8;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            opacity: 1;
            padding-left: 5px;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-bottom p {
            opacity: 0.7;
            font-size: 14px;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .about-section {
                flex-direction: column;
            }
            
            .about-text {
                padding-right: 0;
                margin-bottom: 40px;
            }
            
            .about-image {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .about-hero h1 {
                font-size: 36px;
            }
            
            .about-text h2, .team-section h2, .values-section h2 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="Home page.php" class="logo">Velvet <span>Vogue</span></a>
                <ul class="nav-links">
                    <li><a href="Home page.php">Home</a></li>
                    <li><a href="Login.php">Shop</a></li>
                    <li><a href="contact us1.php">Contact</a></li>
                    <li><a href="cart.html"><i class="fas fa-shopping-cart"></i></a></li>
                </ul>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>Our Story</h1>
            <p>Discover the passion behind Velvet Vogue</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-section">
                <div class="about-text">
                    <h2>Who We Are</h2>
                    <p>Velvet Vogue was founded in 2023 by John Finlo with a vision to create a fashion destination that celebrates individuality and self-expression. What started as a small boutique has grown into a thriving online platform that serves fashion-forward young adults across the country.</p>
                    <p>Our mission is to provide high-quality, trendsetting apparel that allows our customers to showcase their unique personalities through style. We believe that fashion is more than just clothing—it's a form of art and self-expression.</p>
                    <p>At Velvet Vogue, we carefully curate our collections to offer a perfect blend of casualwear and formal wear that transitions seamlessly from day to night, from work to weekend.</p>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Velvet Vogue Store">
                </div>
            </div>
            
            <div class="about-section">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Fashion Design">
                </div>
                <div class="about-text">
                    <h2>Our Philosophy</h2>
                    <p>We believe in fashion that's accessible, inclusive, and sustainable. Our designs are created with attention to detail, quality fabrics, and ethical production practices.</p>
                    <p>Every piece in our collection is thoughtfully designed to be versatile, comfortable, and stylish. We work with emerging designers and established brands to bring you a diverse range of options that cater to different tastes and budgets.</p>
                    <p>Customer satisfaction is at the heart of everything we do. From our easy-to-navigate website to our personalized customer service, we strive to make your shopping experience enjoyable and hassle-free.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Meet Our Team</h2>
            <div class="team-members">
                <div class="team-member">
                    <img src="img/dash board/WhatsApp Image 2025-03-30 at 21.29.15_9f2278d0.jpg" alt="John Finlo">
                    <h3>n.suyarththan</h3>
                    <p>Founder & CEO</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="team-member">
                    <img src="img/dash board/WhatsApp Image 2025-03-30 at 21.29.15_9f2278d0.jpg" alt="Sarah Johnson">
                    <h3>suyarththan</h3>
                    <p>Creative Director</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="team-member">
                    <img src="img/dash board/WhatsApp Image 2025-03-30 at 21.29.15_9f2278d0.jpg" alt="Michael Chen">
                    <h3>suyarththan</h3>
                    <p>Head of Operations</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Passion for Fashion</h3>
                    <p>We live and breathe fashion, constantly seeking new trends and timeless styles to bring to our customers.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Customer First</h3>
                    <p>Our customers are at the center of everything we do. We listen, we adapt, and we strive to exceed expectations.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>We're committed to ethical sourcing and sustainable practices to reduce our environmental footprint.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>We embrace change and continuously look for ways to improve our products and services.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Community</h3>
                    <p>We believe in building a fashion community that celebrates diversity and individuality.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Quality</h3>
                    <p>From fabric to finish, we maintain the highest standards in all our products.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Velvet Vogue</h3>
                    <p>Your destination for trendy casualwear and formal wear that helps you express your unique identity through style.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="shop.html">Shop</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="blog.html">Blog</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul class="footer-links">
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Fashion Ave, Style District</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@velvetvogue.com</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 Velvet Vogue. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        
        // Animation on scroll
        window.addEventListener('scroll', function() {
            const aboutSections = document.querySelectorAll('.about-section, .team-member, .value-card');
            
            aboutSections.forEach(section => {
                const sectionPosition = section.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;
                
                if (sectionPosition < screenPosition) {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }
            });
        });
        
        // Initialize elements with animation properties
        document.addEventListener('DOMContentLoaded', function() {
            const aboutSections = document.querySelectorAll('.about-section, .team-member, .value-card');
            aboutSections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
        });
    </script>
</body>
</html>