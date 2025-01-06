<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'];
$status = $data['status'];

try {
    $stmt = $conn->prepare("UPDATE orders_ebook SET payment_status = ? WHERE order_id = ?");
    $stmt->execute([$status, $order_id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 