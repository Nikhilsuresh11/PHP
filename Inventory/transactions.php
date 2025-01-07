<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];

    // Insert transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (product_id, transaction_type, quantity) 
                           VALUES (:product_id, :type, :quantity)");
    $stmt->execute([
        'product_id' => $product_id, 
        'type' => $type, 
        'quantity' => $quantity
    ]);

    // Update stock quantity
    $updateStock = $type === 'Stock-In' ? "stock_quantity + :quantity" : "stock_quantity - :quantity";
    $stmt = $pdo->prepare("UPDATE products SET stock_quantity = $updateStock WHERE id = :product_id");
    $stmt->execute(['quantity' => $quantity, 'product_id' => $product_id]);
}

// Fetch all products
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Transactions</title>
    <link rel="stylesheet" href="css/transactions.css">
</head>
<body>
    <div class="container">
        <h1>Stock Transactions</h1>
        <form method="POST" class="transaction-form">
            <div class="form-group">
                <label for="product_id">Select Product:</label>
                <select name="product_id" id="product_id" required>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="transaction_type">Transaction Type:</label>
                <select name="transaction_type" id="transaction_type" required>
                    <option value="Stock-In">Stock-In</option>
                    <option value="Stock-Out">Stock-Out</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" required>
                <small>Enter the number of items being transacted.</small>
            </div>
            <button type="submit" class="btn-primary">Submit Transaction</button>
        </form>
    </div>
</body>
</html>
