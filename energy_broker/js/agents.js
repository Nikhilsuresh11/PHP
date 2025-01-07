// Open Add Agent Modal
function openAddAgentModal() {
    document.getElementById('addAgentModal').style.display = 'block';
}

// Open Update Agent Modal
function openUpdateAgentModal(agentId) {
    document.getElementById('updateAgentId').value = agentId;
    document.getElementById('updateAgentModal').style.display = 'block';
}

// Open Delete Agent Modal
function deleteAgent(agentId) {
    document.getElementById('deleteAgentId').value = agentId;
    document.getElementById('deleteAgentModal').style.display = 'block';
}

// Close Modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
