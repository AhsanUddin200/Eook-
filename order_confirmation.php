<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get order details if order_id is set
if(isset($_GET['order_id'])) {
    $stmt = $conn->prepare("
        SELECT o.*, u.username, u.email 
        FROM orders_ebook o 
        JOIN users_ebook u ON o.user_id = u.user_id 
        WHERE o.order_id = ? AND o.user_id = ?
    ");
    $stmt->execute([$_GET['order_id'], $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get order items
    if($order) {
        $stmt = $conn->prepare("
            SELECT oi.*, b.title, b.author 
            FROM order_items_ebook oi 
            JOIN books_ebook b ON oi.book_id = b.book_id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$_GET['order_id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .order-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-details {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .order-items th, .order-items td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-items th {
            background: #232F3E;
            color: white;
        }

        .total-amount {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .status-pending {
            background: #FFF3CD;
            color: #856404;
        }

        .status-completed {
            background: #D4EDDA;
            color: #155724;
        }

        .status-cancelled {
            background: #F8D7DA;
            color: #721C24;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: #232F3E;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .back-button:hover {
            background: #1a2532;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <?php if(isset($order) && $order): ?>
            <div class="order-header">
                <h1>Order Confirmation</h1>
                <p>Thank you for your order!</p>
            </div>

            <div class="order-details">
                <h2>Order Details</h2>
                <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['order_id']); ?></p>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-<?php echo strtolower($order['payment_status']); ?>">
                        <?php echo htmlspecialchars($order['payment_status']); ?>
                    </span>
                </p>
            </div>

            <table class="order-items">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo htmlspecialchars($item['author']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-amount">
                <p>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>
            </div>

            <a href="index.php" class="back-button">Continue Shopping</a>
        <?php else: ?>
            <div class="order-header">
                <h1>Order Not Found</h1>
                <p>Sorry, we couldn't find the order you're looking for.</p>
                <a href="index.php" class="back-button">Go to Homepage</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 