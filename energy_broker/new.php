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

    // Fetch total energy consumed per supplier
    $totalEnergyQuery = "SELECT supplier_id, SUM(energy_consumed) AS total_energy 
                         FROM consumption 
                         GROUP BY supplier_id";
    $stmt = $pdo->prepare($totalEnergyQuery);
    $stmt->execute();
    $totalEnergyPerSupplier = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch total cost per supplier
    $totalCostQuery = "SELECT supplier_id, SUM(cost) AS total_cost 
                       FROM consumption 
                       GROUP BY supplier_id";
    $stmt = $pdo->prepare($totalCostQuery);
    $stmt->execute();
    $totalCostPerSupplier = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch customers per supplier
    $customersPerSupplierQuery = "SELECT supplier_id, COUNT(DISTINCT customer_id) AS no_of_customers 
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
    <style>
        .charts-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        canvas {
            max-width: 500px;
            max-height: 400px;
        }
    </style>
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
                <a href="logout.php">Consumption</a>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- KPI Cards -->
        <div class="kpi-cards">
            <div class="card">
                <h3>Total Customers</h3>
                <p><?php echo number_format($totalCustomers); ?></p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-container">
            <!-- Total Energy Consumed Per Supplier Chart -->
            <canvas id="energyChart"></canvas>

            <!-- Total Cost Per Supplier Chart -->
            <canvas id="costChart"></canvas>

            <!-- Customers Per Supplier Chart -->
            <canvas id="customersChart"></canvas>
        </div>

        <!-- Chart.js Script -->
        <script>
            // Prepare data for charts
            const supplierLabels = <?php echo json_encode(array_column($totalEnergyPerSupplier, 'supplier_id')); ?>;
            const energyData = <?php echo json_encode(array_column($totalEnergyPerSupplier, 'total_energy')); ?>;
            const costData = <?php echo json_encode(array_column($totalCostPerSupplier, 'total_cost')); ?>;
            const customerCounts = <?php echo json_encode(array_column($customersPerSupplier, 'no_of_customers')); ?>;

            // Total Energy Consumed Per Supplier Chart
            new Chart(document.getElementById('energyChart'), {
                type: 'bar',
                data: {
                    labels: supplierLabels,
                    datasets: [{
                        label: 'Energy Consumed (kWh)',
                        data: energyData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Energy (kWh)'
                            }
                        }
                    }
                }
            });

            // Total Cost Per Supplier Chart
            new Chart(document.getElementById('costChart'), {
                type: 'bar',
                data: {
                    labels: supplierLabels,
                    datasets: [{
                        label: 'Cost ($)',
                        data: costData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cost ($)'
                            }
                        }
                    }
                }
            });

            // Customers Per Supplier Chart
            new Chart(document.getElementById('customersChart'), {
                type: 'bar',
                data: {
                    labels: supplierLabels,
                    datasets: [{
                        label: 'Number of Customers',
                        data: customerCounts,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Customers'
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
