<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch cart items with book details
$stmt = $conn->prepare("
    SELECT c.*, b.title, b.price, b.cover_image, b.author 
    FROM cart_ebook c 
    JOIN books_ebook b ON c.book_id = b.book_id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - eBook Store</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 30px;
            background: #f8f9fa;
        }

        .cart-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }

        h1 {
            color: #2d3436;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 2px solid #f1f2f6;
            padding-bottom: 15px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .cart-item img {
            width: 100px;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-size: 18px;
            color: #2d3436;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .item-price {
            font-size: 20px;
            color: #e74c3c;
            font-weight: 600;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 25px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 8px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            color: #2d3436;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #2d3436;
            color: white;
        }

        .quantity-input {
            width: 45px;
            height: 35px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
        }

        .remove-btn {
            padding: 10px 20px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            background: #ff6b81;
            transform: scale(1.05);
        }

        .cart-summary {
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .total {
            font-size: 24px;
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .payment-method {
            margin: 25px 0;
        }

        .payment-method select {
            width: 100%;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 10px;
            background: white;
            cursor: pointer;
        }

        .checkout-btn {
            width: 100%;
            padding: 18px;
            background: #ffd32a;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            color: #2d3436;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 211, 42, 0.3);
        }

        .checkout-btn:hover {
            background: #ffd700;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 211, 42, 0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 18px;
        }

        /* Payment details styling */
        .payment-details {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 12px;
            background: white;
        }

        .payment-details h3 {
            color: #2d3436;
            margin-bottom: 15px;
        }

        .payment-details input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #eee;
            border-radius: 8px;
            font-size: 15px;
        }

        .bank-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px dashed #ddd;
        }

        .bank-info p {
            margin: 8px 0;
        }

        .user-input-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .user-input-section h5 {
            margin-bottom: 15px;
            color: #2d3436;
            font-size: 16px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .input-group input:focus {
            outline: none;
            border-color: #ffd32a;
            box-shadow: 0 0 0 2px rgba(255, 211, 42, 0.2);
        }

        .payment-methods {
            margin: 20px 0;
        }

        .payment-methods select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        .payment-methods h3 {
            margin-bottom: 15px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #f1f2f6;
            color: #2d3436;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #dfe4ea;
        }

        .payment-section {
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .details-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .account-info {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .account-info p {
            margin: 10px 0;
            font-size: 15px;
        }

        .account-info strong {
            display: inline-block;
            width: 140px;
            color: #555;
        }

        .transaction-input {
            margin-top: 20px;
        }

        .transaction-input label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .transaction-input input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: #ffd32a;
            color: #2d3436;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background: #ffd700;
            transform: translateY(-2px);
        }

        .checkout-btn:disabled {
            background: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <a href="index.php" class="back-btn">← Back</a>
        <h1>Your Shopping Cart</h1>
        <?php if(count($cart_items) > 0): ?>
            <?php foreach($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="uploads/<?php echo htmlspecialchars($item['cover_image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         onerror="this.src='placeholder.jpg'">
                    <div class="item-details">
                        <div class="item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="item-author">By <?php echo htmlspecialchars($item['author']); ?></div>
                        <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                    </div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['book_id']; ?>, 'decrease')">-</button>
                        <input type="text" value="<?php echo $item['quantity']; ?>" class="quantity-input" readonly>
                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['book_id']; ?>, 'increase')">+</button>
                    </div>
                    <button class="remove-btn" onclick="removeItem(<?php echo $item['book_id']; ?>)">Remove</button>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <form action="process_order.php" method="POST">
                    <div class="total">Total: $<?php echo number_format($total, 2); ?></div>
                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                    
                    <!-- Payment Method Selection -->
                    <div class="payment-section">
                        <div class="form-group">
                            <label>Select Payment Method:</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Select Payment Method</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="easypaisa">EasyPaisa</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="creditcard">Credit Card</option>
                            </select>
                        </div>

                        <!-- Bank Transfer Details -->
                        <div class="payment-details" id="bank" style="display: none;">
                            <div class="details-box">
                                <h3>Bank Transfer Details</h3>
                                <div class="account-info">
                                    <p><strong>Account Holder:</strong> Abdul Moid</p>
                                    <p><strong>Bank Name:</strong> HBL (Habib Bank Limited)</p>
                                    <p><strong>Account Number:</strong> 01234567890123</p>
                                    <p><strong>Branch Code:</strong> 1234</p>
                                    <p><strong>IBAN:</strong> PK12HBL0123456789012345</p>
                                </div>
                                <div class="transaction-input">
                                    <label>Enter Transaction ID:</label>
                                    <input type="text" name="bank_transaction" placeholder="Enter your transaction ID">
                                </div>
                            </div>
                        </div>

                        <!-- EasyPaisa Details -->
                        <div class="payment-details" id="easypaisa" style="display: none;">
                            <div class="details-box">
                                <h3>EasyPaisa Details</h3>
                                <div class="account-info">
                                    <p><strong>Account Name:</strong> Abdul Moid</p>
                                    <p><strong>Mobile Number:</strong> 0345-1234567</p>
                                </div>
                                <div class="transaction-input">
                                    <label>Enter Transaction ID:</label>
                                    <input type="text" name="easypaisa_transaction" placeholder="Enter your transaction ID">
                                </div>
                            </div>
                        </div>

                        <!-- JazzCash Details -->
                        <div class="payment-details" id="jazzcash" style="display: none;">
                            <div class="details-box">
                                <h3>JazzCash Details</h3>
                                <div class="account-info">
                                    <p><strong>Account Name:</strong> Abdul Moid</p>
                                    <p><strong>Mobile Number:</strong> 0301-7654321</p>
                                </div>
                                <div class="transaction-input">
                                    <label>Enter Transaction ID:</label>
                                    <input type="text" name="jazzcash_transaction" placeholder="Enter your transaction ID">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="checkout-btn" disabled>Proceed to Checkout</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                Your cart is empty. <a href="index.php">Continue shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(bookId, action) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    book_id: bookId,
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        function removeItem(bookId) {
            if(confirm('Are you sure you want to remove this item?')) {
                fetch('remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        book_id: bookId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('payment_method');
            const checkoutBtn = document.querySelector('.checkout-btn');
            
            paymentSelect.addEventListener('change', function() {
                // Hide all payment details first
                document.querySelectorAll('.payment-details').forEach(div => {
                    div.style.display = 'none';
                });
                
                // Show selected payment details
                const selectedMethod = this.value;
                if(selectedMethod) {
                    const detailsDiv = document.getElementById(selectedMethod);
                    if(detailsDiv) {
                        detailsDiv.style.display = 'block';
                        checkoutBtn.disabled = false;
                    }
                } else {
                    checkoutBtn.disabled = true;
                }
            });
        });
    </script>
</body>
</html> 