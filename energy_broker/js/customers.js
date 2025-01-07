// Open Add Customer Modal
function openAddCustomerModal() {
    document.getElementById('addCustomerModal').style.display = 'block';
}

// Open Update Customer Modal with AJAX Fetch
function openUpdateCustomerModal(customerId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_customer.php?customer_id=' + customerId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                alert('Error: ' + response.error);
            } else {
                document.getElementById('updateCustomerId').value = response.customer_id;
                document.getElementById('updateName').value = response.name;
                document.getElementById('updatePhone').value = response.phone;
                document.getElementById('updateEmail').value = response.email;
                document.getElementById('updateAddress').value = response.address;
                document.getElementById('updateSupplierId').value = response.supplier_id;
                document.getElementById('updateType').value = response.type;
                document.getElementById('updateCustomerModal').style.display = 'block';
            }
        }
    };
    xhr.send();
}

// Close Modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Delete Customer
function deleteCustomer(customerId) {
    document.getElementById('deleteCustomerId').value = customerId;
    document.getElementById('deleteCustomerModal').style.display = 'block';
}
