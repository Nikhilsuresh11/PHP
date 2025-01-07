<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'add') {
        // Add Customer
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $supplier_id = $_POST['supplier_id'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email, address, supplier_id, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $address, $supplier_id, $type]);
        header('Location: customers.php');
        exit();
    } elseif ($action == 'update') {
        // Update Customer
        $customer_id = $_POST['customer_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $supplier_id = $_POST['supplier_id'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("UPDATE customers SET name = ?, phone = ?, email = ?, address = ?, supplier_id = ?, type = ? WHERE customer_id = ?");
        $stmt->execute([$name, $phone, $email, $address, $supplier_id, $type, $customer_id]);
        header('Location: customers.php');
        exit();
    } elseif ($action == 'delete') {
        // Delete Customer
        $customer_id = $_POST['customer_id'];
        $stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
        header('Location: customers.php');
        exit();
    }
}

// Fetch the list of customers from the database
$query = "SELECT * FROM customers";
$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="css/customers.css">
    <script src="js/customers.js"></script> <!-- For handling modals -->
</head>
<body>
    <!-- Customer List Table -->
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
        <h2>Customer List</h2>
        <button onclick="openAddCustomerModal()">Add Customer</button>
        <table id="customerTable">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Supplier ID</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['supplier_id']) ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td>
                            <button onclick="openUpdateCustomerModal(<?= htmlspecialchars($row['customer_id']) ?>)">Edit</button>
                            <button onclick="deleteCustomer(<?= htmlspecialchars($row['customer_id']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Customer Modal -->
    <div id="addCustomerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addCustomerModal')">&times;</span>
            <h2>Add Customer</h2>
            <form action="customers.php" method="POST">
                <input type="hidden" name="action" value="add">
                <label for="name">Name</label>
                <input type="text" name="name" required>
                <label for="phone">Phone</label>
                <input type="text" name="phone" required>
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <label for="address">Address</label>
                <input type="text" name="address" required>
                <label for="supplier_id">Supplier ID</label>
                <input type="number" name="supplier_id" required>
                <label for="type">Type</label>
                <input type="text" name="type" required>
                <button type="submit">Add Customer</button>
            </form>
        </div>
    </div>

    <!-- Update Customer Modal -->
    <div id="updateCustomerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateCustomerModal')">&times;</span>
            <h2>Update Customer</h2>
            <form action="customers.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="customer_id" id="updateCustomerId">
                <label for="updateName">Name</label>
                <input type="text" name="name" id="updateName" required>
                <label for="updatePhone">Phone</label>
                <input type="text" name="phone" id="updatePhone" required>
                <label for="updateEmail">Email</label>
                <input type="email" name="email" id="updateEmail" required>
                <label for="updateAddress">Address</label>
                <input type="text" name="address" id="updateAddress" required>
                <label for="updateSupplierId">Supplier ID</label>
                <input type="number" name="supplier_id" id="updateSupplierId" required>
                <label for="updateType">Type</label>
                <input type="text" name="type" id="updateType" required>
                <button type="submit">Update Customer</button>
            </form>
        </div>
    </div>

    <!-- Delete Customer Confirmation Modal -->
    <div id="deleteCustomerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteCustomerModal')">&times;</span>
            <h2>Are you sure you want to delete this customer?</h2>
            <form action="customers.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="customer_id" id="deleteCustomerId">
                <button type="submit">Yes</button>
                <button type="button" onclick="closeModal('deleteCustomerModal')">No</button>
            </form>
        </div>
    </div>

    <script src="js/customers.js"></script>
</body>
</html>
