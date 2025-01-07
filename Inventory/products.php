<?php
include 'db.php';

$search = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $products = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search");
        $products->execute(['search' => '%' . $search . '%']);
        $products = $products->fetchAll();
    } else {
        $name = $_POST['name'];
        $sku = $_POST['sku'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $min_stock = $_POST['min_stock'];

        $stmt = $pdo->prepare("INSERT INTO products (name, sku, category_id, unit_price, stock_quantity, min_stock) 
                               VALUES (:name, :sku, :category, :price, 0, :min_stock)");
        $stmt->execute([
            'name' => $name,
            'sku' => $sku,
            'category' => $category,
            'price' => $price,
            'min_stock' => $min_stock
        ]);
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
if (!isset($products)) {
    $products = $pdo->query("SELECT * FROM products")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <div class="container">
        <h1>Manage Products</h1>
        <form method="POST" class="form-inline">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="text" name="sku" placeholder="SKU" required>
            <select name="category">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="price" placeholder="Price" required>
            <input type="number" name="min_stock" placeholder="Min Stock" required>
            <button type="submit">Add Product</button>
        </form>

        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search Products" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <h2>Product List</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['sku']) ?></td>
                        <td><?= htmlspecialchars($product['category_id']) ?></td>
                        <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                        <td><a href="edit_products.php?id=<?= $product['id'] ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
