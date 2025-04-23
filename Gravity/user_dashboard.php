<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "customer") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Gravity</h2>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <h2>Welcome, <?php echo $_SESSION["user_name"]; ?>!</h2>
                <input type="text" placeholder="Search..." class="search-box">
                <button class="search-btn">Search</button>
            </header>

            <section class="products">
                <h2>Available Products</h2>
                <div class="product-grid">
                    <?php
                    include "db_connect.php";
                    $sql = "SELECT * FROM products";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product">';
                        echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '" width="170px" height="200px">';
                        echo '<h3>' . $row["name"] . '</h3>';
                        echo '<p>$' . number_format($row["price"], 2) . '</p>';
                        echo '<form action="place_order.php" method="POST">';
                        echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                        echo '<input type="hidden" name="price" value="' . $row["price"] . '">';
                        echo '<label>Quantity:</label>';
                        echo '<input type="number" name="quantity" value="1" min="1" class="qty-input">';
                        echo '<button type="submit" class="order-btn">Order Now</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
