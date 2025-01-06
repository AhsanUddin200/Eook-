<?php
session_start();
include 'db.php';

// Get user details if logged in
$user_name = '';
if(isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT username FROM users_ebook WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $user_name = $user['username'];
}

try {
    // Initialize the statement
    $stmt = $conn->prepare("SELECT * FROM books_ebook");
    $stmt->execute();
    
    // Fetch all books
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if books exist
    if (!$books) {
        $books = []; // Set empty array if no books found
    }
    
} catch(PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
    $books = []; // Set empty array in case of error
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to eBook Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        header {
            background: linear-gradient(135deg, #232F3E, #37475A);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .welcome-user {
            color: #FFD700;
            font-weight: 500;
        }

        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .books-container {
            max-width: 1200px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
        }

        .book-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            width: 220px;
            margin: 0 auto;
        }

        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }

        .book-image {
            width: 100%;
            height: 280px;
            position: relative;
        }

        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-info {
            padding: 1rem;
        }

        .book-title {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            color: #2d3436;
            height: 2.4em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .book-author {
            color: #636e72;
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
        }

        .book-rating {
            margin-bottom: 0.5rem;
        }

        .stars {
            font-size: 0.8rem;
        }

        .rating-count {
            font-size: 0.7rem;
        }

        .book-price {
            color: #2ecc71;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .add-to-cart {
            width: 100%;
            padding: 0.8rem;
            background: #2d3436;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .add-to-cart:hover {
            background: #404b4f;
            transform: translateY(-2px);
        }

        .add-to-cart i {
            margin-right: 8px;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2ecc71;
            color: white;
            padding: 1rem 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <h1>eBook Store</h1>
            </div>
            <nav>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="welcome-user">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="books-container">
        <?php foreach($books as $book): ?>
            <div class="book-card">
                <div class="book-image">
                    <img src="uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" 
                         alt="<?php echo htmlspecialchars($book['title']); ?>">
                </div>
                <div class="book-info">
                    <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>
                    <p class="book-price">$<?php echo number_format($book['price'], 2); ?></p>
                    <button class="add-to-cart" onclick="addToCart(<?php echo $book['book_id']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        function addToCart(bookId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    book_id: bookId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast('Book added to cart successfully!');
                    updateCartCount();
                } else {
                    showToast(data.message || 'Error adding book to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding book to cart');
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function updateCartCount() {
            // Add logic to update cart count
            const cartCount = document.querySelector('.cart-count');
            let currentCount = parseInt(cartCount.textContent);
            cartCount.textContent = currentCount + 1;
        }
    </script>
</body>
</html> 