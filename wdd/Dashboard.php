

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    

    <style>
        /* Custom Styling */
        body {
            background: #f4f4f4;
        }
        .sidebar {
            background: linear-gradient(135deg, #000, #333);
        }
        .sidebar a {
            transition: color 0.3s ease;
        }
        .sidebar a:hover {
            color: #f8b400;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .marquee {
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
        }
        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 8s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .btn-primary {
            background-color: #f8b400;
            color: black;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background-color: #d19a00;
        }

       
    
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .image-container {
            width: 400px;
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .sweep-image {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: -100%;
            animation: sweep 5s forwards;
        }

        @keyframes sweep {
            0% {
                left: -100%;
                opacity: 0;
            }
            50% {
                left: 50%;
                opacity: 1;
            }
            100% {
                left: 100%;
                opacity: 0;
            }
        }
    
    </style>
    


    
    <style>
        body {
            background-image: url('https://via.placeholder.com/1920x1080');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-200" style="background-color: deeppink;">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 p-6 shadow-xl flex flex-col text-white">
            <h2 class="text-3xl font-extrabold">Velvet Vogue</h2>
            <nav class="mt-6 flex-1">
                <ul class="space-y-4 text-lg">
                    <li ><a href="Product for men.php" class="cursor-pointer hover:text-yellow-400"><b>MEN Clouth</b></a></li>
                    <li><a href="product for women.php" class="cursor-pointer hover:text-yellow-400"><b>Women Clouth</b></a></li>
                    <li><a href="product for kid.php" class="cursor-pointer hover:text-yellow-400"><b>Accessories</b></a></li>
                    <li><a href="contact us.php" class="cursor-pointer hover:text-yellow-400"><b>Contact us</b></a></li>
                    <li><a href="About us.php" class="cursor-pointer hover:text-yellow-400"><b>About us</b></a></li>
                    <li><a href="customet profile setting.php" class="cursor-pointer hover:text-yellow-400"><b>setting</b></li>
                    
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-white rounded-lg shadow-lg" style="background-color: lightsalmon;">
            <!-- Navbar -->
            <div class="flex justify-between items-center mb-6 bg-white p-5 rounded-lg shadow-lg" style="background-color: pink;">
                <div>
                <button class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-6 py-2 rounded-lg shadow-md hover:opacity-80 transition"><a href="Home page.php">log out</a></button>
                    
                </div>
                <input type="text" class="border px-4 py-2 w-96 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400" placeholder="Search for items, brands...">
                <div class="flex items-center space-x-6">
                    <i class="fas fa-user-circle text-3xl text-gray-700 hover:text-blue-500 cursor-pointer"></i>
                    <i class="fas fa-shopping-cart text-3xl text-gray-700 hover:text-blue-500 cursor-pointer"></i>
                </div>
            </div>


           
            <header class="relative w-full h-[560px] bg-cover bg-center">
                <!-- Slideshow Container -->
                <div class="relative w-full h-full overflow-hidden">
                    <!-- Images -->
                    <div class="absolute inset-0 bg-black opacity-50"></div>
                    <img src="img/dash board/premium_photo-1661578539859-68fa288e7606.jpeg" class="absolute inset-0 w-full h-full object-cover fade slideshow">
                    <img src="img/dash board/retail-big_Big.webp" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                    <img src="img/dash board/thumb_1655403938_945417.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                    <img src="img/dash board/istockphoto-1293366109-612x612.jpg" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                    <img src="img/dash board/stylish-bearded-shop-assistant-dressed-blue-elegant-suit-working-menswear-store_613910-19766.avif" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                    <img src="img/dash board/medium-shot-smiley-woman-looking-ukranian-shirt_23-2149318812.avif" class="absolute inset-0 w-full h-full object-cover fade slideshow hidden">
                   

                </div>
            
                <!-- Text Overlay -->
                <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center">
                    <h1 class="text-5xl font-extrabold"><b>New Collection 2025</b></h1>
                    <p class="mt-4 text-lg">Explore the latest trends in fashion</p>
                    <a href="#shop" class="mt-6 bg-white text-black px-6 py-3 rounded shadow-lg hover:bg-gray-300">Shop Now</a>
                </div>
            
                <!-- Navigation Buttons -->
                <button id="prev" class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75">&lt;</button>
                <button id="next" class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-75">&gt;</button>
            </header>

            <script>
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
            </script>
            
<br>
<br>
            <!-- Rolling Advertisement -->
           <div class="marquee bg-gray-900 text-white py-3 mb-6 text-lg font-semibold">
            <span>🔥 New Arrivals Now Available! | 🚀 Free Shipping on Orders Over $50! | 🎉 Limited Time Discounts!</span>
        </div>
        
            <!-- Banners -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-yellow-400 p-8 text-2xl font-bold rounded-lg text-center shadow-md hover:shadow-lg transition transform hover:scale-105">GET UP TO 50%<br>For the holiday season</div>
                <div class="bg-blue-600 text-white p-8 text-2xl font-bold rounded-lg text-center shadow-md hover:shadow-lg transition transform hover:scale-105">GET UP TO 30% OFF SHIRTS</div>
            </div>

            <!-- New-In Sports Wear -->
            <h3 class="text-2xl font-extrabold mt-8">New-In Sports Wear</h3>
            <div class="grid grid-cols-4 gap-6 mt-6">

<a href="Product for men .php">
                <div class="bg-white p-5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-110"  >
                    <img src="img/men/other dress/IMG-20250130-WA0041.jpg" class="w-full h-48 object-cover rounded-md">
                    <p class="text-center mt-3 text-lg font-semibold">men product</p>
                </div>
</a>
<a href="product for women.php">

                <div class="bg-white p-5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-110">
                    <img src="img/men/other dress/IMG-20250130-WA0027.jpg" class="w-full h-48 object-cover rounded-md">
                    <p class="text-center mt-3 text-lg font-semibold">women product</p>
                </div>
</a>
<a href="product for kid .php">
                <div class="bg-white p-5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-110">
                    <img src="img/men/other dress/IMG-20250130-WA0028.jpg" class="w-full h-48 object-cover rounded-md">
                    <p class="text-center mt-3 text-lg font-semibold">Accessories</p>
                </div>
</a>
<a href="product for women.php">
                <div class="bg-white p-5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-110">
                    <img src="img/men/other dress/IMG-20250130-WA0041.jpg" class="w-full h-48 object-cover rounded-md">
                    <p class="text-center mt-3 text-lg font-semibold">Jordan Court-To-Runway</p>
                </div>
</a>                


            </div>
        </div>
    </div>

    <script>
        const adImages = [
            "img/men/other dress/IMG-20250130-WA0041.jpg",
            "img/men/other dress/IMG-20250130-WA0027.jpg",
            "img/men/other dress/IMG-20250130-WA0028.jpg",
            "img/men/other dress/IMG-20250130-WA0041.jpg"
        ];
        let currentAdIndex = 0;
        function changeAd() {
            currentAdIndex = (currentAdIndex + 1) % adImages.length;
            document.getElementById("adImage").src = adImages[currentAdIndex];
            document.getElementById("adImage2").src = adImages[(currentAdIndex + 1) % adImages.length];
        }
        setInterval(changeAd, 20000);
    </script>
</body>
</html>
