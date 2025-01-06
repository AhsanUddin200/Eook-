<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        // Get user from database
        $stmt = $conn->prepare("SELECT * FROM users_ebook WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Verify password and user exists
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] == 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid email or password';
            header('Location: login.php');
            exit();
        }
        
    } catch(PDOException $e) {
        $_SESSION['login_error'] = 'Login failed: ' . $e->getMessage();
        header('Location: login.php');
        exit();
    }
}
?> 