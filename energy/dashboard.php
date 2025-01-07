<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">Energy Brokerage Co.</div>
            <nav>
                <a href="#" class="active">Home</a>
                <a href="agents.php">Agents</a>
                <a href="customers.php">Customers</a>
                <a href="suppliers.php">Suppliers</a>
                <a href="consumption.php">Consumption</a>
            </nav>
        </div>
    </header>

    <main>
        <h1>Welcome to ERP Solutions</h1>
        <div class="kpi-container">
            <div class="kpi-card">
                <h2>Customers</h2>
                <p>150</p>
            </div>
            <div class="kpi-card">
                <h2>Suppliers</h2>
                <p>50</p>
            </div>
            <div class="kpi-card">
                <h2>Employees</h2>
                <p>25</p>
            </div>
        </div>
    </main>
</body>
</html>
