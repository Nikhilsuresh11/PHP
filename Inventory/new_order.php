<?php
include 'db.php';
session_start();

// Fetch products from the database
$products = $pdo->query("SELECT * FROM products WHERE stock_quantity > 0")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch the product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch();

    if ($product && $product['stock_quantity'] >= $quantity) {
        // Insert new order
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, quantity, order_date) VALUES (:product_id, :quantity, NOW())");
        $stmt->execute(['product_id' => $product_id, 'quantity' => $quantity]);

        // Reduce stock in products table
        $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :id");
        $stmt->execute(['quantity' => $quantity, 'id' => $product_id]);

        $success = "Order placed successfully!";
    } else {
        $error = "Insufficient stock or invalid product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
    <link rel="stylesheet" href="css/new_order.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Place a New Order</h1>

        <?php if (isset($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="order-form">
            <div class="form-group">
                <label for="product_id">Product:</label>
                <select name="product_id" id="product_id" required>
                    <option value="" disabled selected>Choose a product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>">
                            <?= htmlspecialchars($product['name']) ?> (Stock: <?= $product['stock_quantity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="1" required>
            </div>
            <button type="submit" class="btn-primary">Confirm Order</button>
        </form>

        <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
