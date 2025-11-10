// js/admin.js - Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded');
    
    // Tab navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tab clicked:', this.dataset.tab);
            
            if (this.classList.contains('logout')) {
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'login_register.php';
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
                const formData = new FormData();
                formData.append('action', 'update_status');
                formData.append('id', id);
                formData.append('status', status);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    location.reload();
                });
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
});

// Delete booking
function deleteBooking(id) {
    if (confirm('Are you sure you want to delete booking #' + id + '?')) {
        alert('Delete functionality would remove booking #' + id);
    }
}

// Search function
function performSearch() {
    const searchTerm = document.getElementById('globalSearch').value;
    const activeTab = document.querySelector('.nav-item.active').dataset.tab;
    alert('Searching for "' + searchTerm + '" in ' + activeTab + ' tab');
}

// Filter bookings
function filterBookings() {
    const filter = document.getElementById('statusFilter').value;
    alert('Filtering bookings by: ' + filter);
}