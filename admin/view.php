<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
include '../db.php';

// Get order ID from URL
$order_id = isset($_GET['id']) ? $_GET['id'] : 0;

try {
    // Fetch order details with user info
    $stmt = $conn->prepare("
        SELECT o.*, u.username, u.email 
        FROM orders_ebook o
        JOIN users_ebook u ON o.user_id = u.user_id
        WHERE o.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Order not found");
    }

    // Fetch ordered books with book details
    $stmt = $conn->prepare("
        SELECT b.title, b.price, b.cover_image, b.book_id,
               oi.quantity, oi.price as item_price
        FROM order_items_ebook oi
        JOIN books_ebook b ON oi.book_id = b.book_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Order - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
            padding: 20px;
        }

        .order-container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .header h2 {
            color: #2d3436;
            font-size: 24px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: #ffd32a;
            color: #2d3436;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #ffd700;
            transform: translateY(-2px);
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-group label {
            display: block;
            color: #636e72;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-group span {
            color: #2d3436;
            font-size: 16px;
            font-weight: 500;
        }

        h3 {
            color: #2d3436;
            margin: 30px 0 20px;
            font-size: 20px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background: #f1f2f6;
            color: #2d3436;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 15px;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #2d3436;
        }

        .book-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .book-cover {
            width: 60px;
            height: 85px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: right;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 600;
            color: #2d3436;
        }

        .total-amount::before {
            content: 'Total: ';
            font-weight: 500;
            color: #636e72;
            font-size: 18px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .items-table {
                display: block;
                overflow-x: auto;
            }

            .book-info {
                min-width: 200px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .order-container {
                box-shadow: none;
                padding: 20px;
            }

            .back-btn {
                display: none;
            }
        }
    </style>
</head> 

<body>
    <div class="order-container">
        <div class="header">
            <a href="orders.php" class="back-btn">← Back to Orders</a>
            <h2>Order #<?php echo str_pad($order_id, 8, '0', STR_PAD_LEFT); ?></h2>
        </div>

        <!-- Order Info Section -->
        <div class="order-info">
            <div>
                <div class="info-group">
                    <label>Customer Name</label>
                    <span><?php echo htmlspecialchars($order['username']); ?></span>
                </div>
                <div class="info-group">
                    <label>Email</label>
                    <span><?php echo htmlspecialchars($order['email']); ?></span>
                </div>
            </div>
            <div>
                <div class="info-group">
                    <label>Order Date</label>
                    <span><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></span>
                </div>
                <div class="info-group">
                    <label>Payment Method</label>
                    <span><?php echo htmlspecialchars($order['payment_method']); ?></span>
                </div>
                <div class="info-group">
                    <label>Status</label>
                    <span class="status-badge status-<?php echo strtolower($order['payment_status']); ?>">
                        <?php echo htmlspecialchars($order['payment_status']); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Books Table Section -->
        <h3>Ordered Books</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($order_items)): ?>
                    <tr>
                        <td colspan="4">No items found in this order</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($order_items as $item): ?>
                        <tr>
                            <td>
                                <div class="book-info">
                                    <img src="../uploads/<?php echo htmlspecialchars($item['cover_image']); ?>" 
                                         class="book-cover" 
                                         alt="Book cover"
                                         onerror="this.src='../uploads/default-book-cover.jpg'">
                                    <div>
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </div>
                                </div>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-amount">
                Total: $<?php echo number_format($order['total_amount'], 2); ?>
            </div>
        </div>
    </div>
</body>
</html> 