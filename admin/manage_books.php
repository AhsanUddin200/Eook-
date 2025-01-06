<!DOCTYPE html>
<html>
<head>
    <title>Manage Books - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Enhanced Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 24px;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
        }

        .sidebar h2 {
            font-size: 24px;
            font-weight: 600;
            padding-bottom: 20px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar nav {
            margin-top: 20px;
        }

        .sidebar nav a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar nav a i {
            margin-right: 12px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .sidebar nav a.active {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            font-weight: 600;
        }

        .sidebar nav a.active i {
            opacity: 1;
        }

        .sidebar nav a:hover i {
            opacity: 1;
        }

        /* Logout button special styling */
        .sidebar nav a:last-child {
            margin-top: 30px;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
        }

        .sidebar nav a:last-child:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fee2e2;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-header h1 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 5px;
        }

        /* Form Styles */
        .book-form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .book-form h2 {
            color: #1e293b;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1e293b;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #60a5fa;
            outline: none;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        .add-btn {
            background: linear-gradient(45deg, #60a5fa, #3b82f6);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }

        /* Table Styles */
        .books-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .books-table th {
            background: #f8fafc;
            padding: 15px;
            color: #1e293b;
            font-weight: 600;
            text-align: left;
        }

        .books-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .books-table tr:last-child td {
            border-bottom: none;
        }

        .books-table img {
            border-radius: 5px;
            object-fit: cover;
        }

        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
            margin: 0 5px;
        }

        .edit-btn {
            background: #fbbf24;
            color: #fff;
        }

        .delete-btn {
            background: #ef4444;
            color: #fff;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Enhanced Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="manage_books.php" class="active">
                    <i class="fas fa-book"></i>
                    Manage Books
                </a>
                <a href="manage_users.php">
                    <i class="fas fa-users"></i>
                    Manage Users
                </a>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    Orders
                </a>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1>Manage Books</h1>
            </div>
            
            <!-- Add Book Form -->
            <div class="book-form">
                <h2>Add New Book</h2>
                <form action="process_book.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Cover Image</label>
                        <input type="file" name="cover_image" accept="image/*" required>
                    </div>
                    
                    <button type="submit" class="add-btn">Add Book</button>
                </form>
            </div>
            
            <!-- Books List -->
            <h2>Current Books</h2>
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../db.php';
                    $stmt = $conn->query("SELECT * FROM books_ebook ORDER BY book_id DESC");
                    while($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td><img src='../uploads/".$book['cover_image']."' height='50'></td>";
                        echo "<td>".$book['title']."</td>";
                        echo "<td>".$book['author']."</td>";
                        echo "<td>$".$book['price']."</td>";
                        echo "<td>".$book['stock']."</td>";
                        echo "<td>
                                <button class='action-btn edit-btn' onclick='editBook(".$book['book_id'].")'>
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <button class='action-btn delete-btn' onclick='deleteBook(".$book['book_id'].")'>
                                    <i class='fas fa-trash'></i> Delete
                                </button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editBook(bookId) {
            window.location.href = 'edit_book.php?id=' + bookId;
        }

        function deleteBook(bookId) {
            if(confirm('Are you sure you want to delete this book?')) {
                fetch('delete_book.php', {
                    method: 'POST',
                    body: JSON.stringify({ book_id: bookId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting book');
                    }
                });
            }
        }
    </script>
</body>
</html>