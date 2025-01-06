<?php
session_start();
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

if(isset($_SESSION['user_id']) && isset($data['book_id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM cart_ebook WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$_SESSION['user_id'], $data['book_id']]);
        $response['success'] = true;
        $response['message'] = "Item removed from cart";
    } catch(PDOException $e) {
        $response['message'] = "Error removing item from cart";
    }
}

echo json_encode($response); 