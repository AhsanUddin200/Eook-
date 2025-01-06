<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];
    
    try {
        // Insert order into database
        $stmt = $conn->prepare("INSERT INTO orders_ebook (user_id, total_amount, payment_method, order_date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $total_amount, $payment_method]);
        $order_id = $conn->lastInsertId();

        // Get cart items
        $stmt = $conn->prepare("SELECT * FROM cart_ebook WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll();

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items_ebook (order_id, book_id, quantity) VALUES (?, ?, ?)");
        foreach($cart_items as $item) {
            $stmt->execute([$order_id, $item['book_id'], $item['quantity']]);
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart_ebook WHERE user_id = ?");
        $stmt->execute([$user_id]);

        // Redirect to success page
        header('Location: order_success.php');
        exit();
        
    } catch(Exception $e) {
        $_SESSION['error'] = "Error processing order. Please try again.";
        header('Location: cart.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?> 