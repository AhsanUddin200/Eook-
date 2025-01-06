<?php
session_start();
session_destroy();  // Destroy all session data
unset($_SESSION);   // Unset all session variables

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Redirect to login page
header('Location: login.php');
exit();
?> 