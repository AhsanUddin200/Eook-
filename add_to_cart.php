<?php
session_start();
include 'db.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$book_id = $data['book_id'];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Check if book already exists in cart
    $stmt = $conn->prepare("SELECT * FROM cart_ebook WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);
    
    if ($stmt->rowCount() > 0) {
        // Update quantity if book already in cart
        $stmt = $conn->prepare("UPDATE cart_ebook SET quantity = quantity + 1 WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$user_id, $book_id]);
    } else {
        // Add new book to cart
        $stmt = $conn->prepare("INSERT INTO cart_ebook (user_id, book_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $book_id]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Book added to cart successfully']);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding to cart: ' . $e->getMessage()]);
}
?> 