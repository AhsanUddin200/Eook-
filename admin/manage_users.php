<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
include '../db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin Panel</title>
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
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 24px;
            min-height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 24px;
            font-weight: 600;
            padding-bottom: 20px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

        .sidebar nav a i {
            margin-right: 12px;
            width: 20px;
            opacity: 0.8;
        }

        .sidebar nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar nav a.active {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            background: #f8fafc;
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            color: #1e293b;
            font-size: 24px;
            font-weight: 600;
        }

        /* Enhanced Search Box */
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .search-box input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            outline: none;
        }

        /* Enhanced Table */
        .users-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .users-table th {
            background: #f8fafc;
            color: #1e293b;
            padding: 16px;
            font-weight: 600;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .users-table td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }

        .users-table tr:hover {
            background: #f8fafc;
        }

        /* Enhanced Role Badges */
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .role-admin {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .role-customer {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        /* Enhanced Action Buttons */
        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 8px;
        }

        .edit-btn {
            background: #60a5fa;
            color: white;
        }

        .edit-btn:hover {
            background: #3b82f6;
            transform: translateY(-2px);
        }

        .delete-btn {
            background: #ef4444;
            color: white;
        }

        .delete-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Messages */
        .message {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .action-btn {
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>Dashboard
                </a>
                <a href="manage_books.php">
                    <i class="fas fa-book"></i>Manage Books
                </a>
                <a href="manage_users.php" class="active">
                    <i class="fas fa-users"></i>Manage Users
                </a>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>Orders
                </a>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </nav>
        </div>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Manage Users</h1>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search users by name, email or role..." onkeyup="searchUsers()">
            </div>

            <table class="users-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conn->query("SELECT * FROM users_ebook ORDER BY created_at DESC");
                        while($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $roleClass = $user['role'] == 'admin' ? 'role-admin' : 'role-customer';
                            
                            echo "<tr>";
                            echo "<td>".$user['user_id']."</td>";
                            echo "<td>".$user['username']."</td>";
                            echo "<td>".$user['email']."</td>";
                            echo "<td><span class='role-badge ".$roleClass."'>".$user['role']."</span></td>";
                            echo "<td>".date('Y-m-d H:i', strtotime($user['created_at']))."</td>";
                            echo "<td>
                                    <button class='action-btn edit-btn' onclick='editUser(".$user['user_id'].")'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                    <button class='action-btn delete-btn' onclick='deleteUser(".$user['user_id'].")'>
                                        <i class='fas fa-trash'></i> Delete
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                    } catch(PDOException $e) {
                        echo "<div class='message error-message'><i class='fas fa-exclamation-circle'></i> Error: " . $e->getMessage() . "</div>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function searchUsers() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            let match = false;
            const cells = rows[i].getElementsByTagName('td');
            
            for (let j = 0; j < cells.length - 1; j++) {
                const cell = cells[j];
                if (cell) {
                    const text = cell.textContent || cell.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }
            
            rows[i].style.display = match ? '' : 'none';
        }
    }

    function editUser(userId) {
        window.location.href = `edit_user.php?id=${userId}`;
    }

    function deleteUser(userId) {
        if(confirm('Are you sure you want to delete this user?')) {
            fetch('delete_user.php', {
                method: 'POST',
                body: JSON.stringify({ user_id: userId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error deleting user');
                }
            });
        }
    }
    </script>
</body>
</html>