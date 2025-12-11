<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'wdd_cloth';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['id'])) { // Changed from 'user_id' to 'id' to match form
        $user_id = intval($_POST['id']);
        
        if ($_POST['action'] === 'approve') {
            // First get user data
            $select_query = "SELECT username, password FROM users_register WHERE user_id = ?";
            $stmt = $conn->prepare($select_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user_data = $result->fetch_assoc();
                
                // Move to users table using prepared statement
                $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ss", $user_data['username'], $user_data['password']);
                
                if ($stmt->execute()) {
                    // Delete from register table
                    $delete_query = "DELETE FROM users_register WHERE user_id = ?";
                    $stmt = $conn->prepare($delete_query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $message = "User approved successfully!";
                } else {
                    $error = "Error approving user: " . $conn->error;
                }
            } else {
                $error = "User not found in registration table!";
            }
        } elseif ($_POST['action'] === 'reject') {
            // Just delete from register table using prepared statement
            $query = "DELETE FROM users_register WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $message = "User rejected successfully!";
            } else {
                $error = "Error rejecting user: " . $conn->error;
            }
        }
    }
}

// Get pending registrations
$registrations = $conn->query("SELECT * FROM users_register");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-weight: bold;
            margin: 2px;
        }
        .approve {
            background-color: #4CAF50;
        }
        .reject {
            background-color: #f44336;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Approval</h2>
        
        <?php if (isset($message)): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
            
            <?php if ($registrations && $registrations->num_rows > 0): ?>
                <?php while($row = $registrations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn approve">Approve</button>
                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No pending registrations</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>