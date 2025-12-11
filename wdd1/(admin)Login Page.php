<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'wdd_cloth';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? 1 : 0;
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        echo '<div class="error-message">Please fill in all fields.</div>';
    } else {
        // Prepare SQL to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password (assuming passwords are hashed)
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                
                // Set cookie if "Remember me" is checked (1 week expiration)
                if ($remember) {
                    setcookie('username', $user['username'], time() + (7 * 24 * 60 * 60), '/');
                    // Note: For better security, consider using a token system instead
                }
                
                // Redirect to dashboard or home page
                header("Location: Dashboard.php");
                exit();
            } else {
                echo '<div class="error-message">Invalid username or password.</div>';
            }
        } else {
            echo '<div class="error-message">Invalid username or password.</div>';
        }
        
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Velvet Vogue | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right,rgb(249, 238, 240),rgb(244, 236, 240));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        .login-container h2 {
            margin-bottom: 10px;
            color: #333;
        }
        .input-group {
            margin: 10px 0;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .remember-me {
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        .remember-me input {
            margin-right: 5px;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #ff758c;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-btn:hover {
            background: #ff527b;
        }
        .additional-options a {
            text-decoration: none;
            color: #ff758c;
            font-size: 14px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome to Velvet Vogue admin portal</h2>
        
        
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="input-group">
                <input type="username" id="username" name="username" placeholder="username" required 
                       value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>">
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required
                       value="<?php echo isset($_COOKIE['user_password']) ? htmlspecialchars($_COOKIE['user_password']) : ''; ?>">
            </div>
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['user_email']) ? 'checked' : ''; ?>>
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="additional-options">
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>