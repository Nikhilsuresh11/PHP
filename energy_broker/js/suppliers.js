// Open Add Supplier Modal
function openAddSupplierModal() {
    document.getElementById('addSupplierModal').style.display = 'block';
}

// Open Update Supplier Modal with AJAX Fetch
function openUpdateSupplierModal(supplierId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_supplier.php?supplier_id=' + supplierId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                alert('Error: ' + response.error);
            } else {
                document.getElementById('updateSupplierId').value = response.supplier_id;
                document.getElementById('updateName').value = response.name;
                document.getElementById('updatePhone').value = response.phone;
                document.getElementById('updateEmail').value = response.email;
                document.getElementById('updateAddress').value = response.address;
                document.getElementById('updateType').value = response.type;
                document.getElementById('updateSupplierModal').style.display = 'block';
            }
        }
    };
    xhr.send();
}

// Close Modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Delete Supplier
function deleteSupplier(supplierId) {
    document.getElementById('deleteSupplierId').value = supplierId;
    document.getElementById('deleteSupplierModal').style.display = 'block';
}
