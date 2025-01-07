<?php
// Include necessary files and initialize DB connection
include 'db.php';
session_start();

// Handle actions (add, update, delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'add') {
        // Add Supplier
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("INSERT INTO suppliers (name, phone, email, address, type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $address, $type]);
        header('Location: suppliers.php');
        exit();
    } elseif ($action == 'update') {
        // Update Supplier
        $supplier_id = $_POST['supplier_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("UPDATE suppliers SET name = ?, phone = ?, email = ?, address = ?, type = ? WHERE supplier_id = ?");
        $stmt->execute([$name, $phone, $email, $address, $type, $supplier_id]);
        header('Location: suppliers.php');
        exit();
    } elseif ($action == 'delete') {
        // Delete Supplier
        $supplier_id = $_POST['supplier_id'];
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        header('Location: suppliers.php');
        exit();
    }
}

// Fetch the list of suppliers from the database
$query = "SELECT * FROM suppliers";
$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    <link rel="stylesheet" href="css/suppliers.css">
    <script src="js/suppliers.js"></script> <!-- For handling modals -->
</head>
<body>
    <!-- Supplier List Table -->
    <header>
        <div class="navbar">
            <div class="logo">Energy Brokerage Co.</div>
            <nav>
                <a href="dashboard.php" class="active">Home</a>
                <a href="agents.php">Agents</a>
                <a href="customers.php">Customers</a>
                <a href="suppliers.php">Suppliers</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <h2>Supplier List</h2>
        <button onclick="openAddSupplierModal()">Add Supplier</button>
        <table id="supplierTable">
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['supplier_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td>
                            <button onclick="openUpdateSupplierModal(<?= htmlspecialchars($row['supplier_id']) ?>)">Edit</button>
                            <button onclick="deleteSupplier(<?= htmlspecialchars($row['supplier_id']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Supplier Modal -->
    <div id="addSupplierModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addSupplierModal')">&times;</span>
            <h2>Add Supplier</h2>
            <form action="suppliers.php" method="POST">
                <input type="hidden" name="action" value="add">
                <label for="name">Name</label>
                <input type="text" name="name" required>
                <label for="phone">Phone</label>
                <input type="text" name="phone" required>
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <label for="address">Address</label>
                <input type="text" name="address" required>
                <label for="type">Type</label>
                <input type="text" name="type" required>
                <button type="submit">Add Supplier</button>
            </form>
        </div>
    </div>

    <!-- Update Supplier Modal -->
    <div id="updateSupplierModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateSupplierModal')">&times;</span>
            <h2>Update Supplier</h2>
            <form action="suppliers.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="supplier_id" id="updateSupplierId">
                <label for="updateName">Name</label>
                <input type="text" name="name" id="updateName" required>
                <label for="updatePhone">Phone</label>
                <input type="text" name="phone" id="updatePhone" required>
                <label for="updateEmail">Email</label>
                <input type="email" name="email" id="updateEmail" required>
                <label for="updateAddress">Address</label>
                <input type="text" name="address" id="updateAddress" required>
                <label for="updateType">Type</label>
                <input type="text" name="type" id="updateType" required>
                <button type="submit">Update Supplier</button>
            </form>
        </div>
    </div>

    <!-- Delete Supplier Confirmation Modal -->
    <div id="deleteSupplierModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteSupplierModal')">&times;</span>
            <h2>Are you sure you want to delete this supplier?</h2>
            <form action="suppliers.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="supplier_id" id="deleteSupplierId">
                <button type="submit">Yes</button>
                <button type="button" onclick="closeModal('deleteSupplierModal')">No</button>
            </form>
        </div>
    </div>

    <script src="js/suppliers.js"></script>
</body>
</html>
