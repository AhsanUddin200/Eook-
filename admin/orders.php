<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
include '../db.php';

// Calculate grand total logic remains the same
$grand_total = 0;
try {
    $query = "SELECT total_amount FROM orders_ebook";
    $stmt = $conn->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($orders as $order) {
        $grand_total += floatval($order['total_amount']);
    }
} catch(PDOException $e) {
    $grand_total = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Main Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Updated Sidebar with your color */
        .sidebar {
            width: 280px;
            background: #223040;
            color: white;
            padding: 25px;
            min-height: 100vh;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            color: white;
            margin-bottom: 35px;
            font-size: 26px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 14px 20px;
            margin: 8px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 18px;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 35px;
            background: #f8f9fa;
        }

        .main-content h1 {
            color: #223040;
            margin-bottom: 30px;
            font-size: 32px;
        }

        /* Filter Section */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 25px;
            margin: 25px 0;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        #statusFilter, #searchOrder {
            padding: 10px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        #statusFilter {
            min-width: 150px;
        }

        #searchOrder {
            width: 350px;
        }

        #statusFilter:focus, #searchOrder:focus {
            border-color: #223040;
            box-shadow: 0 0 0 2px rgba(34, 48, 64, 0.1);
        }

        /* Updated Table */
        .orders-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .orders-table th {
            background: #223040;
            color: white;
            padding: 18px;
            text-align: left;
            font-weight: 500;
            font-size: 15px;
        }

        .orders-table td {
            padding: 16px 18px;
            border-bottom: 1px solid #eef2f7;
            font-size: 14px;
            color: #223040;
        }

        .orders-table tr:hover td {
            background: #f8f9ff;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff8e1;
            color: #223040;
        }

        .status-completed {
            background: #e8f5e9;
            color: #223040;
        }

        .status-failed {
            background: #ffebee;
            color: #223040;
        }

        /* Updated Action Buttons */
        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .action-btn i {
            margin-right: 6px;
        }

        .view-btn {
            background: #223040;
            color: white;
        }

        .view-btn:hover {
            background: #2c3e50;
            transform: translateY(-1px);
        }

        .update-btn {
            background: #34495e;
            color: white;
        }

        .update-btn:hover {
            background: #2c3e50;
            transform: translateY(-1px);
        }

        /* Footer Total */
        .grand-total-row {
            background: #f8f9fa;
            font-weight: 600;
        }

        .grand-total-row td {
            padding: 20px 18px;
            font-size: 16px;
            color: #223040;
        }

        .amount-cell {
            color: #223040;
            font-weight: 600;
        }
        /* Table Header */
.orders-table th {
    background: #223040;
    color: white;
    padding: 18px;
    text-align: left;
    font-weight: 500;
    font-size: 15px;
}

/* Table Rows */
.orders-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.orders-table tr:nth-child(odd) {
    background-color: #ffffff;
}

.orders-table tr:hover td {
    background-color: #e8f0fe;
}

/* Table Cells */
.orders-table td {
    padding: 16px;
    border-bottom: 1px solid #dee2e6;
}

/* Order ID Column */
.orders-table td:first-child {
    font-weight: 500;
    color: #223040;
}

/* Amount Column */
.amount-cell {
    color: #2c5282;
    font-weight: 600;
}

/* Payment Method */
.payment-method {
    color: #2d3748;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-pending {
    background: #fefcbf;
    color: #744210;
}

.status-completed {
    background: #c6f6d5;
    color: #22543d;
}

.status-failed {
    background: #fed7d7;
    color: #742a2a;
}

/* Action Buttons */
.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
}

.view-btn {
    background: #223040;
    color: white;
    margin-right: 8px;
}

.view-btn:hover {
    background: #2c3e50;
}

.update-btn {
    background: #34495e;
    color: white;
}

.update-btn:hover {
    background: #2c3e50;
}

/* Customer Links */
.customer-link {
    color: #2b6cb0;
    text-decoration: none;
}

.customer-link:hover {
    text-decoration: underline;
}
/* Action Buttons */
.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    margin-right: 8px;
}

.action-btn i {
    margin-right: 6px;
}

.view-btn {
    background: #FFD700;  /* Bright blue color */
    color: white;
}

.view-btn:hover {
    background: #4A7BD4;  /* Slightly darker on hover */
}

.update-btn {
    background: #6C92F4;  /* Different shade of blue */
    color: white;
}

.update-btn:hover {
    background: #5A7FDB;  /* Slightly darker on hover */
}
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2><i class=""></i> Admin Panel</h2>
            <nav>
                <a href="dashboard.php"><i class="fas fa-home"></i>Dashboard</a>
                <a href="manage_books.php"><i class="fas fa-book"></i>Manage Books</a>
                <a href="manage_users.php"><i class="fas fa-users"></i>Manage Users</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i>Orders</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <h1><i class="fas fa-shopping-cart"></i> Manage Orders</h1>

            <div class="filter-section">
                <select id="statusFilter" onchange="filterOrders()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>

                <div class="search-box">
                    <input type="text" id="searchOrder" placeholder="Search orders..." onkeyup="searchOrders()">
                </div>
            </div>

            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $query = $conn->prepare("
                            SELECT o.*, 
                                   u.username,
                                   u.role
                            FROM orders_ebook o
                            LEFT JOIN users_ebook u ON o.user_id = u.user_id
                        ");
                        $query->execute();
                        $all_orders = $query->fetchAll(PDO::FETCH_ASSOC);

                        if(empty($all_orders)) {
                            echo "<tr><td colspan='7' style='text-align: center;'>No orders found</td></tr>";
                        } else {
                            foreach($all_orders as $order) {
                                echo "<tr>";
                                echo "<td>#" . $order['order_id'] . "</td>";
                                echo "<td>" . htmlspecialchars($order['username']) . "</td>";
                                echo "<td class='amount-cell'>$" . number_format($order['total_amount'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($order['payment_method']) . "</td>";
                                echo "<td><span class='status-badge status-" . strtolower($order['payment_status']) . "'>" 
                                     . htmlspecialchars($order['payment_status']) . "</span></td>";
                                echo "<td>" . date('Y-m-d H:i', strtotime($order['order_date'])) . "</td>";
                                echo "<td>
                                        <a href='view.php?id=".$order['order_id']."' class='action-btn view-btn'>
                                            <i class='fas fa-eye'></i> View
                                        </a>
                                        <button onclick='updateStatus(".$order['order_id'].")' class='action-btn update-btn'>
                                            <i class='fas fa-sync-alt'></i> Update
                                        </button>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                    } catch(PDOException $e) {
                        echo "<tr><td colspan='7' style='text-align: center; color: red;'>Error: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td colspan="2" style="text-align: right;"><i class="fas fa-calculator"></i> Grand Total:</td>
                        <td class="amount-cell">$<?php echo number_format($grand_total, 2); ?></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
        // Your existing JavaScript functions remain the same
        function filterOrders() {
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.orders-table tbody tr');
            
            rows.forEach(row => {
                if(!row.id.startsWith('details-')) {
                    const statusCell = row.querySelector('.status-badge');
                    if(status === '' || statusCell.textContent.toLowerCase() === status) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function searchOrders() {
            const searchText = document.getElementById('searchOrder').value.toLowerCase();
            const rows = document.querySelectorAll('.orders-table tbody tr');
            
            rows.forEach(row => {
                if(!row.id.startsWith('details-')) {
                    const text = row.textContent.toLowerCase();
                    if(text.includes(searchText)) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function updateStatus(orderId) {
            const newStatus = prompt('Enter new status (pending/completed/failed):');
            if (newStatus && ['pending', 'completed', 'failed'].includes(newStatus.toLowerCase())) {
                fetch('update_order_status.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        order_id: orderId,
                        status: newStatus.toLowerCase()
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Error updating order status');
                    }
                });
            }
        }
    </script>
</body>
</html>