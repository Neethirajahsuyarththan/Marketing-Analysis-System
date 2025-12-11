<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* CSS Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .contact-form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea {
            height: 150px;
            resize: vertical;
        }
        
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #2980b9;
        }
        
        .status-message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            display: block;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            display: block;
        }
        
        .required:after {
            content: " *";
            color: red;
        }

        nav {
    background-color: rgba(235, 214, 26, 0.607);
    padding: 1rem 1.5rem; /* py-4 px-6 equivalent */
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md equivalent */
}

nav a {
    font-size: 1.5rem; /* text-2xl equivalent */
    font-weight: 700; /* font-bold equivalent */
    color: #1a202c; /* text-gray-800 equivalent */
    background-color: azure;
    text-decoration: none;
}

nav ul {
    display: flex;
    gap: 1.5rem; /* space-x-6 equivalent */
    background-color: azure;
    list-style: none;
    padding: 0;
    margin: 0;
}

nav ul li a {
    font-size: 1rem; /* default text size */
    font-weight: 400; /* normal weight */
    color: #718096; /* text-gray-600 equivalent */
    background-color: transparent; /* override the azure background */
    transition: color 0.3s ease;
}

nav ul li a:hover {
    color: #000; /* hover:text-black equivalent */
}
    </style>
</head>
<body>

                <!-- Navbar -->
                <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center" style="background-color: rgba(235, 214, 26, 0.607);">
                    <a href="#" class="text-2xl font-bold text-gray-800" style="background-color: azure;"><b>Velvet Vogue</b></a>
                    <ul class="flex space-x-6" style="background-color: azure;">
                        <li><a href="Home Page.php" class="text-gray-600 hover:text-black">Home</a></li>
                        <li><a href="homeproduct.php" class="text-gray-600 hover:text-black">product</a></li>
                    </ul>
                </nav>

    <div class="container">
        <h1>Contact Us</h1>
        
        <form id="contactForm" class="contact-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="name" class="required">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="required">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="message" class="required">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            
            <button type="submit" name="submit">Send Message</button>
            
            <div id="statusMessage" class="status-message"></div>
        </form>
    </div>

    <?php
    // PHP Code for Form Processing
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        // Database configuration
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'wdd_cloth';
        
        // Connect to database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        // Check connection
        if ($conn->connect_error) {
            die("<script>showStatusMessage('Failed to connect to database: " . $conn->connect_error . "', 'error');</script>");
        }
        
        // Sanitize and validate input
        $name = trim(htmlspecialchars($_POST['name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $message = trim(htmlspecialchars($_POST['message']));
        
        // Basic validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        if (empty($message)) {
            $errors[] = "Message is required";
        }
        
        if (count($errors) === 0) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);
            
            if ($stmt->execute()) {
                echo "<script>showStatusMessage('Thank you for your message! We will get back to you soon.', 'success');</script>";
                echo "<script>document.getElementById('contactForm').reset();</script>";
            } else {
                echo "<script>showStatusMessage('Error: " . $stmt->error . "', 'error');</script>";
            }
            
            $stmt->close();
        } else {
            $error_msg = implode("<br>", $errors);
            echo "<script>showStatusMessage('$error_msg', 'error');</script>";
        }
        
        $conn->close();
    }
    ?>
    
    <script>
        // JavaScript for enhanced form handling
        function showStatusMessage(message, type) {
            const statusElement = document.getElementById('statusMessage');
            statusElement.textContent = message;
            statusElement.className = 'status-message ' + type;
            
            // Scroll to the status message
            statusElement.scrollIntoView({ behavior: 'smooth' });
            
            // Hide message after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    statusElement.style.display = 'none';
                }, 5000);
            }
        }
        
        // Form validation before submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = this.querySelectorAll('input[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '#ddd';
                }
            });
            
            // Validate email format
            const emailInput = this.querySelector('input[type="email"]');
            if (emailInput.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                isValid = false;
                emailInput.style.borderColor = 'red';
                showStatusMessage('Please enter a valid email address', 'error');
            }
            
            if (!isValid) {
                e.preventDefault();
                if (!document.getElementById('statusMessage').textContent) {
                    showStatusMessage('Please fill in all required fields', 'error');
                }
            }
        });
    </script>
</body>
</html>