<?php
include 'db.php';

// Ensure the product ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit();
}

// Fetch categories for the dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Update product details on form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $sku = $_POST['sku'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];

    $update_stmt = $pdo->prepare("
        UPDATE products 
        SET name = :name, sku = :sku, category_id = :category, stock_quantity = :stock 
        WHERE id = :id
    ");
    $update_stmt->execute([
        'name' => $name,
        'sku' => $sku,
        'category' => $category,
        'stock' => $stock,
        'id' => $product_id
    ]);

    $success_message = "Product updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/edit_products.css">
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?= $success_message ?></p>
        <?php endif; ?>

        <form method="POST" class="edit-form">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label for="sku">SKU:</label>
            <input type="text" name="sku" id="sku" value="<?= htmlspecialchars($product['sku']) ?>" required>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="stock">Stock Quantity:</label>
            <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock_quantity']) ?>" min="0" required>

            <button type="submit">Save Changes</button>
            <a href="manage_products.php" class="cancel-button">Cancel</a>
        </form>
    </div>
</body>
</html>
