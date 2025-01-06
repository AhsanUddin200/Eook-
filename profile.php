<!DOCTYPE html>
<html>
<head>
    <title>My Profile - eBook Store</title>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-section {
            margin-bottom: 30px;
        }
        .order-history {
            margin-top: 20px;
        }
        .order-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <?php
        include 'db.php';
        session_start();
        
        if(!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT * FROM users_ebook WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="profile-section">
            <h2>Personal Information</h2>
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>">
                </div>
                <button type="submit">Update Profile</button>
            </form>
        </div>

        <div class="profile-section">
            <h2>Change Password</h2>
            <form action="change_password.php" method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password">
                </div>
                <button type="submit">Change Password</button>
            </form>
        </div>

        <div class="order-history">
            <h2>Order History</h2>
            <?php
            $stmt = $conn->prepare("SELECT * FROM orders_ebook WHERE user_id = ? ORDER BY order_date DESC");
            $stmt->execute([$user_id]);
            
            while($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="order-item">
                    <h3>Order #<?php echo $order['order_id']; ?></h3>
                    <p>Date: <?php echo $order['order_date']; ?></p>
                    <p>Total: $<?php echo $order['total_amount']; ?></p>
                    <p>Status: <?php echo $order['payment_status']; ?></p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>
</html> 