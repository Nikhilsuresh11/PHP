<?php
// Include necessary files and initialize DB connection
include 'db.php';
session_start();

// Check if supplier_id is passed as a parameter
if (isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];

    // Fetch the supplier details from the database
    $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
    $stmt->execute([$supplier_id]);
    $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the supplier exists
    if ($supplier) {
        // Return the supplier data as a JSON response
        echo json_encode($supplier);
    } else {
        // Return an error if the supplier does not exist
        echo json_encode(['error' => 'Supplier not found']);
    }
} else {
    // Return an error if supplier_id is not provided
    echo json_encode(['error' => 'Supplier ID not provided']);
}
?>
