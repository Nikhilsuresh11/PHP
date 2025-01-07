<?php
include 'db.php';

if (isset($_GET['id'])) {
    $agent_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM agents WHERE agent_id = ?");
    $stmt->execute([$agent_id]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($agent) {
        echo json_encode($agent);
    } else {
        echo json_encode(['error' => 'Agent not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid agent ID']);
}
