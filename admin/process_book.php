<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    
    // Create uploads directory if it doesn't exist
    $upload_dir = "../uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Define allowed image formats
    $allowed_formats = array(
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp'
    );
    
    // Maximum file size (5MB)
    $max_file_size = 5 * 1024 * 1024; // 5MB in bytes

    if(isset($_FILES['cover_image'])) {
        $file = $_FILES['cover_image'];
        
        // Check file size
        if($file['size'] > $max_file_size) {
            die("Error: File size too large. Maximum size is 5MB.");
        }
        
        // Check file format
        if(!in_array($file['type'], $allowed_formats)) {
            die("Error: Invalid file format. Allowed formats are: JPG, JPEG, PNG, GIF, WebP, and BMP");
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $extension;
        
        // Upload path
        $upload_path = "../uploads/" . $new_filename;
        
        // Move uploaded file
        if(move_uploaded_file($file['tmp_name'], $upload_path)) {
            try {
                $stmt = $conn->prepare("INSERT INTO books_ebook (title, author, price, stock, description, cover_image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $author, $price, $stock, $description, $new_filename]);
                
                $_SESSION['success'] = "Book added successfully!";
                header("Location: manage_books.php");
                exit();
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            die("Error: Failed to upload file.");
        }
    }
}
?> 