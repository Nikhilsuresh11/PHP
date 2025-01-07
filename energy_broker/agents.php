<?php
// Include necessary files and initialize DB connection
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle actions based on the 'action' parameter
    $action = $_POST['action'];

    if ($action == 'add') {
        // Add Agent
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date_of_joining = $_POST['date_of_joining'];
        $manager_id = $_POST['manager_id'];
        $stmt = $pdo->prepare("INSERT INTO agents (name, phone, email, address, date_of_joining, manager_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $address, $date_of_joining, $manager_id]);
        header('Location: agents.php');
        exit();
    } elseif ($action == 'update') {
        // Update Agent
        $agent_id = $_POST['agent_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date_of_joining = $_POST['date_of_joining'];
        $manager_id = $_POST['manager_id'];
        $stmt = $pdo->prepare("UPDATE agents SET name = ?, phone = ?, email = ?, address = ?, date_of_joining = ?, manager_id = ? WHERE agent_id = ?");
        $stmt->execute([$name, $phone, $email, $address, $date_of_joining, $manager_id, $agent_id]);
        header('Location: agents.php');
        exit();
    } elseif ($action == 'delete') {
        // Delete Agent
        $agent_id = $_POST['agent_id'];
        $stmt = $pdo->prepare("DELETE FROM agents WHERE id = ?");
        $stmt->execute([$agent_id]);
        header('Location: agents.php');
        exit();
    }
}

// Fetch the list of agents from the database
$query = "SELECT * FROM agents";
$result = $pdo->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Management</title>
    <link rel="stylesheet" href="css/agents.css">
    <script src="js/agents.js"></script> <!-- for handling modals -->
    
</head>
<body>
    <!-- Navigation Bar, Sidebar, etc. -->
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

    <!-- Agent List Table -->
    <div class="container">
        <h2>Agent List</h2>
        <div class="button-container">
            <button type="button" onclick="openAddAgentModal()">Add Agent</button>
        </div>
        <table id="agentTable">
            <thead>
                <tr>
                    <th>Agent ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date of Joining</th>
                    <th>Manager ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['agent_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['date_of_joining']) ?></td>
                        <td><?= htmlspecialchars($row['manager_id']) ?></td>
                        <td>
                            <button onclick="openUpdateAgentModal(<?= htmlspecialchars($row['agent_id']) ?>)">Edit</button>
                            <button onclick="deleteAgent(<?= htmlspecialchars($row['agent_id']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Agent Modal -->
    <div id="addAgentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addAgentModal')">&times;</span>
            <h2>Add Agent</h2>
            <form action="agents.php" method="POST">
                <input type="hidden" name="action" value="add">
                <label for="name">Name</label>
                <input type="text" name="name" required>
                <label for="phone">Phone</label>
                <input type="text" name="phone" required>
                <label for="email">Email</label>
                <input type="email" name="email" required>
                <label for="address">Address</label>
                <input type="text" name="address" required>
                <label for="date_of_joining">Date of Joining</label>
                <input type="date" name="date_of_joining" required>
                <label for="manager_id">Manager ID</label>
                <input type="number" name="manager_id" required>
                <button type="submit">Add Agent</button>
            </form>
        </div>
    </div>

    <!-- Update Agent Modal -->
    <div id="updateAgentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateAgentModal')">&times;</span>
            <h2>Update Agent</h2>
            <form action="agents.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="agent_id" id="updateAgentId">
                <label for="updateName">Name</label>
                <input type="text" name="name" id="updateName" required>
                <label for="updatePhone">Phone</label>
                <input type="text" name="phone" id="updatePhone" required>
                <label for="updateEmail">Email</label>
                <input type="email" name="email" id="updateEmail" required>
                <label for="updateAddress">Address</label>
                <input type="text" name="address" id="updateAddress" required>
                <label for="updateDateOfJoining">Date of Joining</label>
                <input type="date" name="date_of_joining" id="updateDateOfJoining" required>
                <label for="updateManagerId">Manager ID</label>
                <input type="number" name="manager_id" id="updateManagerId" required>
                <button type="submit">Update Agent</button>
            </form>
        </div>
    </div>

    <!-- Delete Agent Confirmation Modal -->
    <div id="deleteAgentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteAgentModal')">&times;</span>
            <h2>Are you sure you want to delete this agent?</h2>
            <form action="agents.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="agent_id" id="deleteAgentId">
                <button type="submit">Yes</button>
                <button type="button" onclick="closeModal('deleteAgentModal')">No</button>
            </form>
        </div>
    </div>

    <script>
        // Function to open the update agent modal and populate fields with data
        function openUpdateAgentModal(agentId) {
            // Fetch agent data from the database using AJAX
            fetch(`get_agent.php?id=${agentId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal with the fetched agent data
                    document.getElementById('updateAgentId').value = data.agent_id;
                    document.getElementById('updateName').value = data.name;
                    document.getElementById('updatePhone').value = data.phone;
                    document.getElementById('updateEmail').value = data.email;
                    document.getElementById('updateAddress').value = data.address;
                    document.getElementById('updateDateOfJoining').value = data.date_of_joining;
                    document.getElementById('updateManagerId').value = data.manager_id;

                    // Show the modal
                    document.getElementById('updateAgentModal').style.display = 'block';
                })
                .catch(error => console.error('Error fetching agent data:', error));
        }

        // Function to open the delete agent modal
        function deleteAgent(agentId) {
            document.getElementById('deleteAgentId').value = agentId;
            document.getElementById('deleteAgentModal').style.display = 'block';
        }

        // Close the modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>
