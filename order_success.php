<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id'])) {
    // Get the latest order for this user
    $stmt = $conn->prepare("SELECT * FROM orders_ebook 
                           WHERE user_id = ? 
                           ORDER BY order_date DESC 
                           LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $order = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Order Placed Successfully!</h1>
        <p class="thank-you">Thank you for your purchase. Your order has been confirmed.</p>
        
        <div class="order-details">
            <p><strong>Order #:</strong> <?php echo str_pad($order['order_id'], 8, '0', STR_PAD_LEFT); ?></p>
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
            <p><strong>Total Amount:</strong> Rs. <?php echo number_format($order['total_amount'], 2); ?></p>
        </div>

        <p class="email-note">A confirmation email has been sent to your registered email address.</p>
        
        <div class="buttons">
            <a href="index.php" class="continue-btn">Continue Shopping</a>
            <a href="orders.php" class="orders-btn">View Orders</a>
        </div>
    </div>

    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f8f9fa;
        margin: 0;
        padding: 20px;
    }

    .success-container {
        max-width: 600px;
        margin: 50px auto;
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        text-align: center;
    }

    .success-icon {
        color: #2ecc71;
        font-size: 80px;
        margin-bottom: 20px;
    }

    h1 {
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .thank-you {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    .order-details {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
        text-align: left;
    }

    .order-details p {
        margin: 10px 0;
        color: #2c3e50;
    }

    .email-note {
        color: #7f8c8d;
        font-size: 14px;
        margin: 20px 0;
    }

    .buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .continue-btn, .orders-btn {
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .continue-btn {
        background: #ffd32a;
        color: #2d3436;
    }

    .orders-btn {
        background: #2c3e50;
        color: white;
    }

    .continue-btn:hover {
        background: #ffd700;
    }

    .orders-btn:hover {
        background: #34495e;
    }
    </style>
</body>
</html>
<?php
} else {
    header('Location: login.php');
    exit();
}
?> 