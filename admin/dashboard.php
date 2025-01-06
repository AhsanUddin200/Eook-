<?php
session_start();
include '../db.php';

// Get username from database
$stmt = $conn->prepare("SELECT username FROM users_ebook WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$username = $stmt->fetchColumn();

// If no username found, use a default
if (!$username) {
    $username = 'Admin';
}
?>
<!DOCTYPE html>
<html>
    
<head>
    <title>Admin Dashboard - eBook Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2C3E50 0%, #1a2532 100%);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 {
            padding: 15px 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            background: #f8f9fa;
        }

        .welcome-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .welcome-header h1 {
            color: #2C3E50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #2C3E50;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: 600;
            color: #3498db;
            margin: 0;
        }

        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .recent-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .recent-section h2 {
            color: #2C3E50;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f2f6;
        }

        .recent-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.3s;
        }

        .recent-item:hover {
            transform: translateX(5px);
            background: #f1f2f6;
        }

        .recent-item p {
            margin: 5px 0;
            color: #2C3E50;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .action-btn {
            padding: 15px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        /* Add icons to sidebar links */
        .sidebar a i {
            width: 20px;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a>
                <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>
        
        <div class="main-content">
            <div class="welcome-header">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
                <p>Here's your store overview for today</p>
            </div>

            <div class="stats-grid">
                <?php
                include '../db.php';
                
                // Total Books
                $stmt = $conn->query("SELECT COUNT(*) FROM books_ebook");
                $total_books = $stmt->fetchColumn();
                
                // Total Users
                $stmt = $conn->query("SELECT COUNT(*) FROM users_ebook WHERE role='customer'");
                $total_users = $stmt->fetchColumn();
                
                // Total Orders
                $stmt = $conn->query("SELECT COUNT(*) FROM orders_ebook");
                $total_orders = $stmt->fetchColumn();

                // Calculate Total Revenue properly
                $query = "SELECT * FROM orders_ebook";
                $stmt = $conn->query($query);
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Calculate total revenue
                $total_revenue = 0;  // Initialize the variable
                foreach ($orders as $order) {
                    $total_revenue += floatval($order['total_amount']);
                }
                ?>
                
                <div class="stat-card">
                    <h3>Total Books</h3>
                    <p><?php echo $total_books; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <p style="color: #2ecc71;">$<?php echo number_format($total_revenue, 2); ?></p>
                </div>
            </div>

            <div class="recent-grid">
                <div class="recent-section">
                    <h2>Recent Orders</h2>
                    <?php
                    $stmt = $conn->query("SELECT o.*, u.username FROM orders_ebook o 
                                        JOIN users_ebook u ON o.user_id = u.user_id 
                                        ORDER BY order_date DESC LIMIT 5");
                    while($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='recent-item'>";
                        echo "<p>Order #" . $order['order_id'] . " by " . $order['username'] . "</p>";
                        echo "<p>Amount: $" . $order['total_amount'] . "</p>";
                        echo "<p>Status: " . $order['payment_status'] . "</p>";
                        echo "</div>";
                    }
                    ?>
                </div>

                <div class="recent-section">
                    <h2>Recent Users</h2>
                    <?php
                    $stmt = $conn->query("SELECT * FROM users_ebook ORDER BY created_at DESC LIMIT 5");
                    while($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='recent-item'>";
                        echo "<p>User: " . $user['username'] . "</p>";
                        echo "<p>Email: " . $user['email'] . "</p>";
                        echo "<p>Joined: " . date('M d, Y', strtotime($user['created_at'])) . "</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="action-buttons">
                <a href="manage_books.php" class="action-btn">Add New Book</a>
                <a href="manage_users.php" class="action-btn">Manage Users</a>
                <a href="orders.php" class="action-btn">View All Orders</a>
            </div>
        </div>
    </div>
</body>
</html> 