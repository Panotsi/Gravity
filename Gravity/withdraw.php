<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "supplier") {
    header("Location: login.php");
    exit();
}
include "db_connect.php";

$supplier_id = $_SESSION["user_id"];

// Get current profit
$query = "SELECT SUM(o.quantity * p.price) AS total_profit 
          FROM orders o 
          JOIN products p ON o.product_id = p.id 
          WHERE p.supplier_id = ? AND o.status = 'Delivered'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$stmt->bind_result($total_profit);
$stmt->fetch();
$stmt->close();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $withdraw_amount = floatval($_POST["amount"]);

    if ($withdraw_amount <= 0) {
        $message = "Invalid withdrawal amount.";
    } elseif ($withdraw_amount > $total_profit) {
        $message = "You cannot withdraw more than your available profit.";
    } else {
        // Optional: Insert into withdrawals table (not created yet)
        $message = "Withdrawal request of $$withdraw_amount submitted successfully.";
        // You could log this to a database table if needed
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw Profit</title>
    <link rel="stylesheet" href="supplier_dashboard.css">
    <style>
        .withdraw-container {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            background: #f5f5f5;
            border-radius: 10px;
        }

        .withdraw-container h2 {
            margin-bottom: 20px;
        }

        .withdraw-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .withdraw-container button {
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .withdraw-container button:hover {
            background-color: #333;
        }

        .message {
            margin-top: 15px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="withdraw-container">
        <h2>Withdraw Funds</h2>
        <p>Available Profit: <strong>$<?php echo number_format($total_profit, 2); ?></strong></p>

        <form method="POST" action="">
            <label for="amount">Amount to Withdraw:</label>
            <input type="number" step="0.01" name="amount" id="amount" required>

            <button type="submit">Submit Request</button>
        </form>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>
