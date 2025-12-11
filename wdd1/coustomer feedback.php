<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "wdd_cloth"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Get all messages
$sql = "SELECT * FROM messages ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Feedback</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        header h1 {
            margin: 0;
            padding: 0 20px;
        }
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .feedback-table th, .feedback-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .feedback-table th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }
        .feedback-table tr:hover {
            background-color: #f9f9f9;
        }
        .message-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .message-cell.expanded {
            white-space: normal;
            max-width: none;
        }
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .expand-btn {
            background-color: #3498db;
            color: white;
            margin-right: 5px;
        }
        .expand-btn:hover {
            background-color: #2980b9;
        }
        .no-feedback {
            text-align: center;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            font-size: 18px;
            color: #777;
        }
        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-box {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .logout-btn {
            padding: 10px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Customer Feedback </h1>
        </div>
    </header>

    <div class="container">
        <div class="search-container">
            <input type="text" id="searchInput" class="search-box" placeholder="Search feedback...">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="message-cell"><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($row['submitted_at'])); ?></td>
                            <td>
                                <button class="action-btn expand-btn" onclick="toggleMessage(this)">Expand</button>
                                <button class="action-btn delete-btn" onclick="deleteFeedback(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-feedback">
                No customer feedback found.
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Toggle message expansion
        function toggleMessage(button) {
            const row = button.closest('tr');
            const messageCell = row.querySelector('.message-cell');
            messageCell.classList.toggle('expanded');
            button.textContent = messageCell.classList.contains('expanded') ? 'Collapse' : 'Expand';
        }

        // Delete feedback
        function deleteFeedback(id) {
            if (confirm('Are you sure you want to delete this feedback?')) {
                const formData = new FormData();
                formData.append('delete_id', id);
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error deleting feedback');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting feedback');
                });
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.feedback-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>