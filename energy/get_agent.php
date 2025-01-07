<?php
include 'db.php';

// Check if the agent ID is passed
if (isset($_GET['id'])) {
    $agent_id = $_GET['id'];

    // Fetch the agent data from the database
    $stmt = $pdo->prepare("SELECT * FROM agents WHERE agent_id = ?");
    $stmt->execute([$agent_id]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($agent) {
        // Return the agent data as JSON
        echo json_encode($agent);
    } else {
        echo json_encode(['error' => 'Agent not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid agent ID']);
}
