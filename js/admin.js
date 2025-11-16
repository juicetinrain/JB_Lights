// js/admin.js - Enhanced with inventory CRUD
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded');
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Tab navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.classList.contains('logout')) {
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                }
                return;
            }

            // Update active nav item
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');

            // Show corresponding tab
            const tabName = this.dataset.tab;
            if (tabName) {
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                const targetTab = document.getElementById(tabName + '-tab');
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            }
        });
    });

    // Status change handler
    document.querySelectorAll('.change-status').forEach(select => {
        select.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.value;
            if (!id || !status) return;
            
            if (confirm('Change status to "' + status + '" for booking #' + id + '?')) {
                updateStatus(id, status);
            } else {
                // Reset to original value
                const row = document.querySelector(`tr[data-booking-id="${id}"]`);
                if (row) {
                    const currentStatus = row.querySelector('.status-pill').textContent;
                    this.value = currentStatus;
                }
            }
        });
    });

    // Quick action buttons
    document.querySelectorAll('.action-buttons-vertical .btn').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            if (tabName) {
                // Find and click the corresponding nav item
                const navItem = document.querySelector(`.nav-item[data-tab="${tabName}"]`);
                if (navItem) {
                    navItem.click();
                }
            }
        });
    });

    // View All buttons
    document.querySelectorAll('a[data-tab]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabName = this.dataset.tab;
            const navItem = document.querySelector(`.nav-item[data-tab="${tabName}"]`);
            if (navItem) {
                navItem.click();
            }
        });
    });

    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('#bookings-tab .admin-table tbody tr');
            
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.querySelector('.status-pill').textContent.trim();
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });
        });
    }

    // Search functionality
    initSearch('bookingsSearch', '#bookings-tab .admin-table tbody tr');
    initSearch('usersSearch', '#users-tab .admin-table tbody tr');
    initSearch('inventorySearch', '#inventory-tab .admin-table tbody tr');
    initSearch('contactsSearch', '#contacts-tab .admin-table tbody tr');

    // View details functionality
    initViewDetails();

    // Delete item functionality
    initDeleteItems();

    // Inventory CRUD functionality
    initInventoryCRUD();

    // Return equipment functionality
    initReturnEquipment();
});

// Inventory CRUD functions
function initInventoryCRUD() {
    // Add/Edit Inventory Modal
    const inventoryModal = document.getElementById('inventoryModal');
    if (inventoryModal) {
        inventoryModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const modalTitle = this.querySelector('.modal-title');
            const form = this.querySelector('#inventoryForm');
            
            if (action === 'add') {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add Inventory Item';
                form.reset();
                document.getElementById('inventoryAction').value = 'add_inventory';
                document.getElementById('inventoryId').value = '';
                document.getElementById('itemAvailable').value = document.getElementById('itemQuantity').value || 1;
            } else if (action === 'edit') {
                const itemData = JSON.parse(button.getAttribute('data-item'));
                modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Inventory Item';
                document.getElementById('inventoryAction').value = 'update_inventory';
                document.getElementById('inventoryId').value = itemData.id;
                document.getElementById('itemName').value = itemData.item_name;
                document.getElementById('itemCategory').value = itemData.category;
                document.getElementById('itemBrand').value = itemData.brand || '';
                document.getElementById('itemCondition').value = itemData.condition;
                document.getElementById('itemQuantity').value = itemData.quantity;
                document.getElementById('itemAvailable').value = itemData.available_quantity;
            }
        });

        // Sync available quantity with total quantity for new items
        document.getElementById('itemQuantity').addEventListener('change', function() {
            if (document.getElementById('inventoryAction').value === 'add_inventory') {
                document.getElementById('itemAvailable').value = this.value;
            }
        });
    }

    // Edit inventory buttons
    document.querySelectorAll('.edit-inventory').forEach(button => {
        button.addEventListener('click', function() {
            const itemData = this.getAttribute('data-item');
            const inventoryModal = new bootstrap.Modal(document.getElementById('inventoryModal'));
            const modalTrigger = document.createElement('button');
            modalTrigger.setAttribute('data-bs-toggle', 'modal');
            modalTrigger.setAttribute('data-bs-target', '#inventoryModal');
            modalTrigger.setAttribute('data-action', 'edit');
            modalTrigger.setAttribute('data-item', itemData);
            modalTrigger.style.display = 'none';
            document.body.appendChild(modalTrigger);
            modalTrigger.click();
            document.body.removeChild(modalTrigger);
        });
    });
}

// Return equipment functionality
function initReturnEquipment() {
    const returnModal = document.getElementById('returnModal');
    if (returnModal) {
        returnModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const itemData = JSON.parse(button.getAttribute('data-item'));
            
            document.getElementById('returnItemId').value = itemData.id;
            document.getElementById('returnItemName').value = itemData.item_name;
            document.getElementById('currentlyInUse').value = itemData.quantity - itemData.available_quantity;
            document.getElementById('returnQuantity').setAttribute('max', itemData.quantity - itemData.available_quantity);
            document.getElementById('returnQuantity').value = '';
        });
    }

    // Return equipment buttons
    document.querySelectorAll('.return-equipment').forEach(button => {
        button.addEventListener('click', function() {
            const itemData = this.getAttribute('data-item');
            const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
            const modalTrigger = document.createElement('button');
            modalTrigger.setAttribute('data-bs-toggle', 'modal');
            modalTrigger.setAttribute('data-bs-target', '#returnModal');
            modalTrigger.setAttribute('data-item', itemData);
            modalTrigger.style.display = 'none';
            document.body.appendChild(modalTrigger);
            modalTrigger.click();
            document.body.removeChild(modalTrigger);
        });
    });
}

// Update status function
function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('action', 'update_status');
    formData.append('id', id);
    formData.append('status', status);
    formData.append('ajax', 'true');

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Status updated successfully!', 'success');
            // Update the status pill
            const row = document.querySelector(`tr[data-booking-id="${id}"]`);
            if (row) {
                const statusPill = row.querySelector('.status-pill');
                statusPill.className = 'status-pill status-' + status.toLowerCase();
                statusPill.textContent = status;
            }
        } else {
            showAlert('Error updating status. Please try again.', 'error');
        }
    }).catch(error => {
        console.error('Error:', error);
        showAlert('Error updating status. Please try again.', 'error');
    });
}

// Search function
function initSearch(searchInputId, tableRowsSelector) {
    const searchInput = document.getElementById(searchInputId);
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll(tableRowsSelector);
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

// View details function
function initViewDetails() {
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;
            
            // Show loading state
            const modalBody = document.getElementById('detailsModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading details...</p></div>';
            
            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
            
            // Fetch details based on type
            fetch(`admin_details.php?type=${type}&id=${id}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    document.getElementById('detailsModalLabel').innerHTML = `<i class="bi bi-info-circle me-2"></i>${type.charAt(0).toUpperCase() + type.slice(1)} Details`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details. Please try again.</div>';
                });
        });
    });
}

// Delete item function
function initDeleteItems() {
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;
            const itemName = type.charAt(0).toUpperCase() + type.slice(1);
            
            if (confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
                const formData = new FormData();
                formData.append('action', 'delete_' + type);
                formData.append('id', id);
                formData.append('ajax', 'true');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(`${itemName} deleted successfully!`, 'success');
                        // Remove the row from table
                        const row = document.querySelector(`tr[data-${type}-id="${id}"]`);
                        if (row) {
                            row.remove();
                        }
                    } else {
                        showAlert(`Error deleting ${type}. Please try again.`, 'error');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    showAlert(`Error deleting ${type}. Please try again.`, 'error');
                });
            }
        });
    });
}

// Show alert message
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertId = 'admin-alert-' + Date.now();
    
    const alertDiv = document.createElement('div');
    alertDiv.id = alertId;
    alertDiv.className = `alert ${alertClass} alert-message alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}