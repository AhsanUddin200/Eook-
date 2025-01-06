<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="orders-container">
        <div class="header-section">
            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
        </div>

        <?php
        $stmt = $conn->prepare("SELECT * FROM orders_ebook 
                               WHERE user_id = ? 
                               ORDER BY order_date DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll();

        if(count($orders) > 0) {
            foreach($orders as $order) {
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <h3>Order #<?php echo str_pad($order['order_id'], 8, '0', STR_PAD_LEFT); ?></h3>
                        <p class="date"><i class="far fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                    </div>
                    <div class="order-status <?php echo $order['payment_status']; ?>">
                        <i class="fas fa-circle"></i> <?php echo ucfirst($order['payment_status']); ?>
                    </div>
                </div>
                
                <div class="order-details">
                    <p><i class="fas fa-money-bill-wave"></i> <strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                    <p><i class="fas fa-rupee-sign"></i> <strong>Total Amount:</strong> Rs. <?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
                
                <div class="order-items">
                    <h4><i class="fas fa-book"></i> Ordered Books</h4>
                    <?php
                    $stmt2 = $conn->prepare("SELECT oi.*, b.title, b.price 
                                           FROM order_items_ebook oi 
                                           JOIN books_ebook b ON oi.book_id = b.book_id 
                                           WHERE oi.order_id = ?");
                    $stmt2->execute([$order['order_id']]);
                    $items = $stmt2->fetchAll();
                    
                    foreach($items as $item) {
                    ?>
                        <div class="item">
                            <span class="item-title"><?php echo $item['title']; ?></span>
                            <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                            <span class="item-price">Rs. <?php echo number_format($item['price'], 2); ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php 
            }
        } else {
        ?>
            <div class="no-orders">
                <i class="fas fa-shopping-bag"></i>
                <p>You haven't placed any orders yet.</p>
                <a href="index.php" class="shop-now-btn">Start Shopping</a>
            </div>
        <?php } ?>
    </div>

    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 20px;
    }

    .orders-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .back-btn {
        text-decoration: none;
        color: #2c3e50;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        color: #ffd32a;
    }

    h1 {
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .order-info h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.2em;
    }

    .date {
        color: #7f8c8d;
        font-size: 14px;
        margin: 5px 0 0 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .order-status {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .order-status.pending {
        background: #fff3cd;
        color: #856404;
    }

    .order-status.completed {
        background: #d4edda;
        color: #155724;
    }

    .order-status.cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-details {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .order-details p {
        margin: 10px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .order-items {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .order-items h4 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .item:last-child {
        border-bottom: none;
    }

    .item-title {
        flex: 1;
        font-weight: 500;
    }

    .item-quantity {
        color: #7f8c8d;
        margin: 0 20px;
        background: #e9ecef;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .item-price {
        font-weight: 600;
        color: #2c3e50;
    }

    .no-orders {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .no-orders i {
        font-size: 50px;
        color: #7f8c8d;
        margin-bottom: 20px;
    }

    .shop-now-btn {
        display: inline-block;
        padding: 12px 25px;
        background: #ffd32a;
        color: #2d3436;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .shop-now-btn:hover {
        background: #ffd700;
        transform: translateY(-2px);
    }
    </style>
</body>
</html> 