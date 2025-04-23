<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "customer") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT o.id, p.name AS product_name, o.quantity, o.total_price, o.status 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        WHERE o.user_id = ? 
        ORDER BY o.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Gravity</title>
    <link rel="stylesheet" href="orders.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Gravity</h2>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="orders.php" class="active">My Orders</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <header class="topbar">
                <h2>My Orders</h2>
                <input type="text" placeholder="Search Orders..." class="search-box">
                <button class="search-btn">Search</button>
            </header>
            
            <section class="orders">
                <h2 class="order-heading">Order History</h2>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row["id"]; ?></td>
                                <td><?php echo htmlspecialchars($row["product_name"]); ?></td>
                                <td><?php echo $row["quantity"]; ?></td>
                                <td>$<?php echo number_format($row["total_price"], 2); ?></td>
                                <td class="status <?php echo strtolower($row["status"]); ?>">
                                    <?php echo ucfirst($row["status"]); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

<?php $stmt->close(); ?>
