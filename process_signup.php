<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];  // Get role from form
    
    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users_ebook WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetchColumn() > 0) {
            $_SESSION['signup_error'] = "Email already registered";
            header("Location: register.php");
            exit();
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user with selected role
        $stmt = $conn->prepare("INSERT INTO users_ebook (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);
        
        // Get the new user's ID
        $user_id = $conn->lastInsertId();
        
        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        // Redirect based on role
        if($role == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['signup_error'] = "Registration failed: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
?> 