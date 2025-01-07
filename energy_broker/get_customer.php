<?php
// Include necessary files and initialize DB connection
include 'db.php';
session_start();

// Check if customer_id is passed as a parameter
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Fetch the customer details from the database
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the customer exists
    if ($customer) {
        // Return the customer data as a JSON response
        echo json_encode($customer);
    } else {
        // Return an error if the customer does not exist
        echo json_encode(['error' => 'Customer not found']);
    }
} else {
    // Return an error if customer_id is not provided
    echo json_encode(['error' => 'Customer ID not provided']);
}
?>
