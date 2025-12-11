create DATABASE wdd_cloth;

CREATE TABLE users_register (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    password VARCHAR(255) NOT NULL
    
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL 
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    sizes VARCHAR(100),
    colors VARCHAR(100)
);

CREATE TABLE products_women (
 id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    sizes VARCHAR(100),
    colors VARCHAR(100)
);

CREATE TABLE products_kid (
 id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    sizes VARCHAR(100),
    colors VARCHAR(100)
);





CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




INSERT INTO products (category, name, description, price, image, sizes, colors) VALUES
('jeans', 'Slim Fit Jeans', 'Classic slim fit jeans with stretch comfort', 4500.00, 'img/men/jeans/IMG-20250130-WA0002.jpg', '28-40', 'Blue, Black, Gray'),
('jeans', 'Men''s Denim Suit', 'Premium denim suit jacket with matching jeans', 9800.00, 'img/men/jeans/IMG-20250130-WA0004.jpg', 'S,M,L,XL', 'Dark Blue, Black'),
('jeans', 'Denim Shoes', 'Casual shoes perfect with jeans', 3200.00, 'img/men/jeans/IMG-20250130-WA0007.jpg', '38-45', 'Brown, Beige'),
('jeans', 'Denim Shirt', 'Stylish denim shirt for casual wear', 2800.00, 'img/men/jeans/IMG-20250130-WA0008.jpg', 'S,M,L,XL', 'Light Blue, Dark Blue'),
('jeans', 'Denim Cap', 'Trendy denim summer hat', 1500.00, 'img/men/jeans/IMG-20250130-WA0010.jpg', 'One Size', 'Blue, Black'),
('jeans', 'Denim Jacket', 'Classic denim jacket with premium finish', 12000.00, 'img/men/jeans/IMG-20250130-WA0038.jpg', 'S,M,L,XL', 'Blue, Black'),
('jeans', 'Bootcut Jeans', 'Traditional bootcut style jeans', 3200.00, 'img/men/jeans/IMG-20250130-WA0039.jpg', '28-40', 'Dark Blue, Light Blue'),
('jeans', 'Denim Overshirt', 'Versatile denim overshirt', 2800.00, 'img/men/jeans/IMG-20250130-WA0042.jpg', 'S,M,L,XL', 'Medium Blue'),
('jeans', 'Straw Hat', 'Summer hat perfect with jeans', 1500.00, 'img/men/jeans/IMG-20250130-WA0058.jpg', 'One Size', 'Natural, Black'),
('jeans', 'Biker Jacket', 'Stylish leather jacket for jeans', 12000.00, 'img/men/jeans/IMG-20250130-WA0059.jpg', 'S,M,L,XL', 'Black, Brown'),
('jeans', 'Baseball Cap', 'Casual cap for everyday wear', 150.00, 'img/men/jeans/IMG-20250130-WA0064.jpg', 'One Size', 'Various Colors'),
('jeans', 'Faux Leather Jacket', 'Affordable leather-style jacket', 12000.00, 'img/men/jeans/IMG-20250130-WA0068.jpg', 'S,M,L,XL', 'Black, Burgundy');

INSERT INTO products (category, name, description, price, image, sizes, colors) VALUES
('shirt', 'Formal Dress Shirt', 'Premium cotton dress shirt', 4500.00, 'img/men/shirt/IMG-20250130-WA0016.jpg', 'S,M,L,XL', 'White, Blue, Pink'),
('shirt', 'Men''s Business Suit', 'Professional office suit', 9800.00, 'img/men/shirt/IMG-20250130-WA0017.jpg', 'S,M,L,XL', 'Navy, Gray, Black'),
('shirt', 'Oxford Shoes', 'Classic formal shoes', 3200.00, 'img/men/shirt/IMG-20250130-WA0018.jpg', '38-45', 'Black, Brown'),
('shirt', 'Casual Button-Down', 'Comfortable everyday shirt', 2800.00, 'img/men/shirt/IMG-20250130-WA0019.jpg', 'S,M,L,XL', 'Various Colors'),
('shirt', 'Panama Hat', 'Elegant summer hat', 1500.00, 'img/men/shirt/IMG-20250130-WA0020.jpg', 'One Size', 'Beige, White'),
('shirt', 'Blazer', 'Smart casual blazer', 12000.00, 'img/men/shirt/IMG-20250130-WA0022.jpg', 'S,M,L,XL', 'Navy, Gray, Black'),
('shirt', 'Loafers', 'Comfortable slip-on shoes', 3200.00, 'img/men/shirt/IMG-20250130-WA0025.jpg', '38-45', 'Black, Brown'),
('shirt', 'Linen Shirt', 'Breathable summer shirt', 2800.00, 'img/men/shirt/IMG-20250130-WA0026.jpg', 'S,M,L,XL', 'Light Colors');

INSERT INTO products (category, name, description, price, image, sizes, colors) VALUES
('T-shirt', 'Premium Cotton Tee', 'Soft 100% cotton T-shirt', 4500.00, 'img/men/t-shirt/IMG-20250130-WA0009.jpg', 'S,M,L,XL', 'White, Black, Gray'),
('T-shirt', 'Graphic Print Tee', 'Trendy graphic T-shirt', 9800.00, 'img/men/t-shirt/IMG-20250130-WA0013.jpg', 'S,M,L,XL', 'Various Designs'),
('T-shirt', 'Sneakers', 'Casual sports shoes', 3200.00, 'img/men/t-shirt/IMG-20250130-WA0014.jpg', '38-45', 'White, Black'),
('T-shirt', 'Polo Shirt', 'Classic polo shirt', 2800.00, 'img/men/t-shirt/IMG-20250130-WA0015.jpg', 'S,M,L,XL', 'Solid Colors'),
('T-shirt', 'Snapback Cap', 'Adjustable trendy cap', 1500.00, 'img/men/t-shirt/IMG-20250130-WA0021.jpg', 'One Size', 'Various Colors'),
('T-shirt', 'Bomber Jacket', 'Lightweight jacket', 12000.00, 'img/men/t-shirt/IMG-20250130-WA0029.jpg', 'S,M,L,XL', 'Black, Olive'),
('T-shirt', 'Canvas Shoes', 'Casual footwear', 3200.00, 'img/men/t-shirt/IMG-20250130-WA0032.jpg', '38-45', 'White, Navy'),
('T-shirt', 'V-Neck Tee', 'Comfortable V-neck T-shirt', 2800.00, 'img/men/t-shirt/IMG-20250130-WA0033.jpg', 'S,M,L,XL', 'Multiple Colors');

INSERT INTO products (category, name, description, price, image, sizes, colors) VALUES
('short', 'Chino Shorts', 'Smart casual shorts', 4500.00, 'img/men/short/IMG-20250130-WA0001.jpg', '28-40', 'Khaki, Navy, Gray'),
('short', 'Swim Trunks', 'Quick-dry beach shorts', 9800.00, 'img/men/short/IMG-20250130-WA0003.jpg', 'S,M,L,XL', 'Various Colors'),
('short', 'Sandals', 'Comfortable summer footwear', 3200.00, 'img/men/short/IMG-20250130-WA0006.jpg', '38-45', 'Black, Brown'),
('short', 'Cargo Shorts', 'Utility shorts with pockets', 2800.00, 'img/men/short/IMG-20250130-WA0034.jpg', '28-40', 'Green, Black'),
('short', 'Bucket Hat', 'Casual summer hat', 1500.00, 'img/men/short/IMG-20250130-WA0040.jpg', 'One Size', 'Various Colors'),
('short', 'Athletic Shorts', 'Sports performance shorts', 3200.00, 'img/men/short/IMG-20250130-WA0002.jpg', 'S,M,L,XL', 'Black, Gray'),
('short', 'Denim Shorts', 'Classic cutoff denim', 2800.00, 'img/men/short/IMG-20250130-WA0002.jpg', '28-36', 'Blue, Black'),
('short', 'Running Shorts', 'Lightweight active shorts', 1500.00, 'img/men/short/IMG-20250130-WA0002.jpg', 'S,M,L,XL', 'Bright Colors');

INSERT INTO products (category, name, description, price, image, sizes, colors) VALUES
('other', 'Men''s Kaftan', 'Traditional elegant dress', 4500.00, 'img/men/other dress/dress for men.webp', 'S,M,L,XL', 'White, Blue'),
('other', 'Tuxedo', 'Formal evening suit', 9800.00, 'img/men/other dress/IMG-20250130-WA0011.jpg', 'S,M,L,XL', 'Black, Navy'),
('other', 'Dress Shoes', 'Premium formal footwear', 3200.00, 'img/men/other dress/IMG-20250130-WA0023.jpg', '38-45', 'Black, Brown'),
('other', 'Hawaiian Shirt', 'Vibrant vacation shirt', 2800.00, 'img/men/other dress/IMG-20250130-WA0024.jpg', 'S,M,L,XL', 'Colorful Prints'),
('other', 'Fedora Hat', 'Classic style hat', 1500.00, 'img/men/other dress/IMG-20250130-WA0027.jpg', 'One Size', 'Black, Brown'),
('other', 'Suede Jacket', 'Luxurious outerwear', 12000.00, 'img/men/other dress/IMG-20250130-WA0028.jpg', 'S,M,L,XL', 'Brown, Black'),
('other', 'Chelsea Boots', 'Stylish ankle boots', 3200.00, 'img/men/other dress/IMG-20250130-WA0030.jpg', '38-45', 'Black, Brown'),
('other', 'Silk Shirt', 'Luxury evening shirt', 2800.00, 'img/men/other dress/IMG-20250130-WA0031.jpg', 'S,M,L,XL', 'Rich Colors');





































































INSERT INTO products_kid (category, name, description, price, image, sizes, colors) VALUES
('boys', 'Elegant Dress', 'whatch', 4500, 'img/kid/boy/01.jpeg', 'S,M,L', 'Blue,Black,White'),
('boys', 'Men''s Suit', 'whatch', 9800, 'img/kid/boy/02.jpeg', 'S,M,L', 'Black,Gray'),
('boys', 'Stylish Shoes', 'shoes', 3200, 'img/kid/boy/03.jpeg', '28-32', 'Black,Brown'),
('boys', 'Casual Shirt', 'shoes', 2800, 'img/kid/boy/04.jpeg', 'S,M,L', 'Red,Blue,White'),
('boys', 'Summer Hat', 'Lightweight hat for sunny days', 15000, 'img/kid/boy/05.jpeg', 'One Size', 'Beige,White'),
('boys', 'Leather Jacket', 'Premium quality leather jacket', 12000, 'img/kid/boy/06.jpeg', 'S,M,L', 'Black,Brown'),
('boys', 'Stylish Shoes', 'Trendy shoes for boys', 3200, 'img/kid/boy/04.jpeg', '28-32', 'White,Blue'),
('boys', 'Summer Hat', 'Protective summer headwear', 1500, 'img/kid/boy/07.jpeg', 'One Size', 'Blue,Red');

INSERT INTO products_kid (category, name, description, price, image, sizes, colors) VALUES
('girls', 'Elegant Dress', 'whatch', 4500, 'img/kid/girls/01.jpeg', 'S,M,L', 'Pink,Purple,White'),
('girls', 'Men''s Suit', 'whatch', 9800, 'img/kid/girls/02.jpeg', 'S,M,L', 'Black,Blue'),
('girls', 'Stylish Shoes', 'Fashionable footwear for girls', 3200, 'img/kid/girls/03.jpeg', '28-32', 'Pink,White'),
('girls', 'cap', 'cap', 2800, 'img/kid/girls/04.jpeg', 'S,M,L', 'Yellow,Blue'),
('girls', 'Summer Hat', 'Sun protective hat', 1500, 'img/kid/girls/05.jpeg', 'One Size', 'Pink,White'),
('girls', 'Leather Jacket', 'Stylish leather jacket', 12000, 'img/kid/girls/06.jpeg', 'S,M,L', 'Black,Red');













































































































































































































INSERT INTO products_women (category, name, description, price, image, sizes, colors) VALUES
('saree', 'Elegant Dress', 'Beautiful handwoven saree with intricate designs', 4500.00, 'img/women/saree/01.webp', 'Free Size', 'Multicolor'),
('saree', 'Men''s Suit', 'Premium quality suit for formal occasions', 9800.00, 'img/women/saree/02.jpg', 'S,M,L,XL', 'Black, Navy, Gray'),
('saree', 'Stylish Shoes', 'Comfortable footwear perfect for traditional wear', 3200.00, 'img/women/saree/03.jpg', '36-42', 'Black, Brown, Beige'),
('saree', 'Casual Shirt', 'Lightweight shirt for everyday wear', 2800.00, 'img/women/saree/04.webp', 'S,M,L,XL', 'White, Blue, Pink'),
('saree', 'Summer Hat', 'Elegant hat to complement your ethnic look', 150000.00, 'img/women/saree/05.webp', 'One Size', 'Beige, White'),
('saree', 'Leather Jacket', 'Premium leather jacket for stylish looks', 12000.00, 'img/women/saree/06.webp', 'S,M,L,XL', 'Black, Brown'),
('saree', 'Stylish Shoes', 'Traditional footwear with modern comfort', 3200.00, 'img/women/saree/07.webp', '36-42', 'Gold, Silver'),
('saree', 'Casual Shirt', 'Breathable fabric for all-day comfort', 2800.00, 'img/women/saree/08.webp', 'S,M,L,XL', 'Pastel Colors'),
('saree', 'Summer Hat', 'Handcrafted hat for summer elegance', 1500.00, 'img/women/saree/09.jpg', 'One Size', 'White, Cream'),
('saree', 'Leather Jacket', 'Fashionable outerwear for any occasion', 12000.00, 'img/women/saree/10.jpg', 'S,M,L,XL', 'Black, Burgundy'),
('saree', 'Summer Hat', 'Lightweight summer accessory', 150.00, 'img/women/saree/011.webp', 'One Size', 'Straw, White');

INSERT INTO products_women (category, name, description, price, image, sizes, colors) VALUES
('shirt', 'Elegant Dress', 'Chic and comfortable dress shirt', 4500.00, 'img/women/shirt/01.webp', 'XS,S,M,L', 'White, Blue, Stripes'),
('shirt', 'Men''s Suit', 'Professional office wear shirt', 9800.00, 'img/women/shirt/02.jpg', 'S,M,L,XL', 'White, Light Blue'),
('shirt', 'Stylish Shoes', 'Comfortable formal shoes', 3200.00, 'img/women/shirt/03.webp', '36-42', 'Black, Brown'),
('shirt', 'Casual Shirt', 'Everyday comfortable shirt', 2800.00, 'img/women/shirt/04.jpge', 'S,M,L,XL', 'Various Colors'),
('shirt', 'Summer Hat', 'Sun-protective stylish hat', 1500.00, 'img/women/shirt/05.jpeg', 'One Size', 'Beige, White'),
('shirt', 'Leather Jacket', 'Classic leather jacket', 12000.00, 'img/women/shirt/06.webp', 'S,M,L,XL', 'Black, Brown'),
('shirt', 'Stylish Shoes', 'Versatile footwear option', 3200.00, 'img/women/shirt/07.webp', '36-42', 'Black, Navy'),
('shirt', 'Casual Shirt', 'Breathable cotton shirt', 2800.00, 'img/women/shirt/08.webp', 'S,M,L,XL', 'Plaid, Solid Colors');

INSERT INTO products_women (category, name, description, price, image, sizes, colors) VALUES
('t-shirt', 'Elegant Dress', 'Soft and comfortable cotton T-shirt', 4500.00, 'img/women/tshairt/01.jpeg', 'XS,S,M,L,XL', 'Multiple Colors'),
('t-shirt', 'Men''s Suit', 'Premium quality T-shirt', 9800.00, 'img/women/t shairt/02.webp', 'S,M,L,XL', 'Black, White, Gray'),
('t-shirt', 'Stylish Shoes', 'Casual sneakers for everyday wear', 3200.00, 'img/women/t shairt/03.webp', '36-42', 'White, Black'),
('t-shirt', 'Casual Shirt', 'Graphic print T-shirt', 2800.00, 'img/women/t shairt/04.webp', 'S,M,L,XL', 'Various Designs'),
('t-shirt', 'Summer Hat', 'Casual summer cap', 1500.00, 'img/women/t shairt/05.webp', 'One Size', 'Black, Navy'),
('t-shirt', 'Leather Jacket', 'Biker-style leather jacket', 12000.00, 'img/women/t shairt/06.jpg', 'S,M,L,XL', 'Black, Brown'),
('t-shirt', 'Stylish Shoes', 'Sporty casual shoes', 3200.00, 'img/women/t shairt/07.webp', '36-42', 'White, Gray'),
('t-shirt', 'Casual Shirt', 'Oversized comfortable T-shirt', 2800.00, 'img/women/t shairt/08.webp', 'S,M,L,XL', 'Pastel Colors');

INSERT INTO products_women (category, name, description, price, image, sizes, colors) VALUES
('short', 'Elegant Dress', 'Stylish denim shorts', 4500.00, 'img/women/short/01.webp', '24-32', 'Blue, Black'),
('short', 'Men''s Suit', 'Comfortable cotton shorts', 9800.00, 'img/women/short/02.webp', 'S,M,L,XL', 'Khaki, Gray'),
('short', 'Stylish Shoes', 'Sporty sandals for summer', 3200.00, 'img/women/short/03.webp', '36-42', 'Black, Brown'),
('short', 'Casual Shirt', 'Beach-ready comfortable shorts', 2800.00, 'img/women/short/04.webp', 'S,M,L', 'Various Colors'),
('short', 'Summer Hat', 'Wide-brim summer hat', 1500.00, 'img/women/short/05.webp', 'One Size', 'White, Beige'),
('short', 'Leather Jacket', 'Cropped leather jacket', 12000.00, 'img/women/short/06.webp', 'XS,S,M,L', 'Black, Red'),
('short', 'Stylish Shoes', 'Casual flip-flops', 3200.00, 'img/women/short/07.webp', '36-42', 'Various Colors'),
('short', 'Casual Shirt', 'High-waisted stylish shorts', 2800.00, 'img/women/short/08.jpg', '24-32', 'Denim, Black');

INSERT INTO products_women (category, name, description, price, image, sizes, colors) VALUES
('other', 'Elegant Dress', 'Versatile maxi dress', 4500.00, 'img/women/other dress/01.jpeg', 'S,M,L,XL', 'Floral Prints'),
('other', 'Men''s Suit', 'Formal evening dress', 9800.00, 'img/women/other dress/02.webp', 'S,M,L', 'Black, Navy'),
('other', 'Stylish Shoes', 'Evening wear heels', 3200.00, 'img/women/other dress/03.webp', '36-42', 'Black, Nude'),
('other', 'Casual Shirt', 'Bohemian style dress', 2800.00, 'img/women/other dress/04.jpg', 'Free Size', 'Earth Tones'),
('other', 'Summer Hat', 'Elegant fascinator', 1500.00, 'img/women/other dress/05.jpg', 'One Size', 'Black, White'),
('other', 'Leather Jacket', 'Designer leather dress', 12000.00, 'img/women/other dress/06.jpg', 'S,M,L', 'Black, Brown'),
('other', 'Stylish Shoes', 'Party wear footwear', 3200.00, 'img/women/other dress/07.jpg', '36-42', 'Metallic Colors'),
('other', 'Casual Shirt', 'Comfy lounge dress', 2800.00, 'img/women/other dress/08.jpg', 'S,M,L,XL', 'Solid Colors');