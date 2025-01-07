<?php
// dashboard.php

include 'db.php';
session_start();

try {
    // Fetch total customers
    $totalCustomerQuery = "SELECT COUNT(DISTINCT customer_id) AS total_customers FROM consumption";
    $stmt = $pdo->prepare($totalCustomerQuery);
    $stmt->execute();
    $totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

    // Fetch total energy consumed
    $totalEnergyQuery = "SELECT SUM(energy_consumed) AS total_energy FROM consumption";
    $stmt = $pdo->prepare($totalEnergyQuery);
    $stmt->execute();
    $totalEnergy = $stmt->fetch(PDO::FETCH_ASSOC)['total_energy'];

    // Fetch total cost
    $totalCostQuery = "SELECT SUM(cost) AS total_cost FROM consumption";
    $stmt = $pdo->prepare($totalCostQuery);
    $stmt->execute();
    $totalCost = $stmt->fetch(PDO::FETCH_ASSOC)['total_cost'];

    // Fetch customers per supplier
    $customersPerSupplierQuery = "SELECT supplier_id, COUNT(customer_id) AS no_of_customers 
                                  FROM consumption 
                                  GROUP BY supplier_id";
    $stmt = $pdo->prepare($customersPerSupplierQuery);
    $stmt->execute();
    $customersPerSupplier = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Consumption Dashboard</title>
    <link rel="stylesheet" href="css/consumption.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">Energy Brokerage Co.</div>
            <nav>
                <a href="dashboard.php" class="active">Home</a>
                <a href="agents.php">Agents</a>
                <a href="customers.php">Customers</a>
                <a href="suppliers.php">Suppliers</a>
                <a href="consumption.php">Consumption</a>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- KPI Cards -->
        <div class="kpi-cards">
            <div class="card">
                <h3>Total Customers</h3>
                <p><?php echo number_format($totalCustomers); ?> </p>
            </div>
            <div class="card">
                <h3>Total Energy Consumed</h3>
                <p><?php echo number_format($totalEnergy, 2); ?> kWh</p>
            </div>
            <div class="card">
                <h3>Total Revenue</h3>
                <p><?php echo number_format($totalCost, 2); ?> $</p>
            </div>

            <?php foreach ($customersPerSupplier as $supplier) : ?>
                <div class="card">
                    <h4>Supplier ID: <?php echo $supplier['supplier_id']; ?></h4>
                    <p>Count: <?php echo number_format($supplier['no_of_customers'], 2); ?> customers</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="js/consumption.js"></script>
</body>
</html>
