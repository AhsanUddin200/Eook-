<?php
session_start();
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

if(isset($_SESSION['user_id']) && isset($data['book_id']) && isset($data['action'])) {
    try {
        $stmt = $conn->prepare("SELECT quantity FROM cart_ebook WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$_SESSION['user_id'], $data['book_id']]);
        $current = $stmt->fetch();
        
        if($current) {
            $new_quantity = $data['action'] == 'increase' ? $current['quantity'] + 1 : $current['quantity'] - 1;
            
            if($new_quantity > 0) {
                $stmt = $conn->prepare("UPDATE cart_ebook SET quantity = ? WHERE user_id = ? AND book_id = ?");
                $stmt->execute([$new_quantity, $_SESSION['user_id'], $data['book_id']]);
                $response['success'] = true;
            } else {
                // If quantity becomes 0, remove the item
                $stmt = $conn->prepare("DELETE FROM cart_ebook WHERE user_id = ? AND book_id = ?");
                $stmt->execute([$_SESSION['user_id'], $data['book_id']]);
                $response['success'] = true;
            }
        }
    } catch(PDOException $e) {
        $response['message'] = "Error updating cart";
    }
}

echo json_encode($response); 