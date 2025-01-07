<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get product count
$stmt = $pdo->query("SELECT COUNT(*) AS product_count FROM products");
$productCount = $stmt->fetch()['product_count'];

// Get low stock count
$stmt = $pdo->query("SELECT COUNT(*) AS low_stock FROM products WHERE stock_quantity < min_stock");
$lowStockCount = $stmt->fetch()['low_stock'];

// Fetch all products
$stmt = $pdo->query("SELECT name, stock_quantity, min_stock FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #444;
        }

        p {
            text-align: center;
            font-size: 18px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #007bff;
            color: white;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .low-stock {
            color: red;
            font-weight: bold;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button-container button {
            padding: 10px 20px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        .button-container form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Total Products: <?= $productCount ?></p>
    <p>Low Stock Alerts: <?= $lowStockCount ?></p>

    <h2>All Products</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Stock Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td class="<?= $product['stock_quantity'] < $product['min_stock'] ? 'low-stock' : '' ?>">
                        <?= htmlspecialchars($product['stock_quantity']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="button-container">
        <form action="products.php">
            <button type="submit">Manage Products</button>
        </form>
        <form action="new_order.php">
            <button type="submit">New Order</button>
        </form>
        <form action="order_history.php">
            <button type="submit">Order History</button>
        </form>
        <form action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
