<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

try {
    // Don't allow deleting own account
    if($user_id == $_SESSION['user_id']) {
        die(json_encode(['success' => false, 'message' => 'Cannot delete your own account']));
    }
    
    $stmt = $conn->prepare("DELETE FROM users_ebook WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 