<?php
include '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$book_id = $data['book_id'];

try {
    // Get image filename first
    $stmt = $conn->prepare("SELECT cover_image FROM books_ebook WHERE book_id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();
    
    // Delete the image file
    if($book && $book['cover_image']) {
        unlink("../uploads/" . $book['cover_image']);
    }
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM books_ebook WHERE book_id = ?");
    $stmt->execute([$book_id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 