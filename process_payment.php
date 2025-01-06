<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_POST['place_order'])) {
    try {
        // Get the payment details from AJAX request
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user_id = $_SESSION['user_id'];
        $total_amount = $data['total_amount'];
        // Get the actual payment method from the AJAX request
        $payment_method = $data['payment_method'];
        
        // Debug line
        error_log("Payment Method Received: " . $payment_method);
        
        // Insert into orders table with correct payment method
        $sql = "INSERT INTO orders_ebook (user_id, total_amount, payment_method, payment_status, order_date) 
                VALUES (?, ?, ?, 'pending', NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ids", $user_id, $total_amount, $payment_method);
        
        if(!$stmt->execute()) {
            throw new Exception("Error saving order: " . $stmt->error);
        }
        
        $order_id = $conn->insert_id;
        
        // Return success response with order details
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order_id
        ]);
        
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
?> 