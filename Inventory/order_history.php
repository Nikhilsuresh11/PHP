<?php
include 'db.php';
session_start();

// Fetch orders with product details
$query = "
    SELECT 
        orders.id AS order_id, 
        products.name AS product_name, 
        products.sku AS product_sku, 
        orders.quantity, 
        orders.order_date 
    FROM orders 
    JOIN products ON orders.product_id = products.id 
    ORDER BY orders.order_date DESC
";
$orders = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="css/order_history.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Order History</h1>

        <?php if (count($orders) > 0): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Product SKU</th>
                        <th>Quantity</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td><?= htmlspecialchars($order['product_sku']) ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td><?= $order['order_date'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders have been placed yet.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
