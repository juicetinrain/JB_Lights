// js/profile.js - Enhanced with admin panel features and cancellation requests
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile JS loaded');
    
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
                
                // Save active tab to session
                saveActiveTab(tabName);
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
    const bookingsSearch = document.getElementById('bookingsSearch');
    if (bookingsSearch) {
        bookingsSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#bookings-tab .admin-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // View booking details
    document.querySelectorAll('.view-booking-details').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            
            // Show loading state
            const modalBody = document.getElementById('bookingDetailsModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading details...</p></div>';
            
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
            modal.show();
            
            // Fetch booking details
            fetch(`profile_booking_details.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details. Please try again.</div>';
                });
        });
    });

    // Initialize cancellation requests
    initCancellationRequests();

    // Password confirmation validation
    document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            showAlert('New passwords do not match. Please confirm your new password.', 'error');
            document.getElementById('confirm_password').focus();
        }
    });

    // Phone number validation
    document.getElementById('phone')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });

    // Form validation for cancellation
    document.getElementById('cancellationForm')?.addEventListener('submit', function(e) {
        const reason = document.getElementById('cancellation_reason').value.trim();
        if (reason.length < 10) {
            e.preventDefault();
            showAlert('Please provide a detailed reason for cancellation (at least 10 characters).', 'error');
        }
    });
});

// Enhanced Cancellation Request Handling
function initCancellationRequests() {
    // Request cancellation buttons - Use event delegation for dynamic content
    document.addEventListener('click', function(e) {
        if (e.target.closest('.request-cancellation')) {
            const button = e.target.closest('.request-cancellation');
            const id = button.dataset.id;
            const eventType = button.dataset.eventType || 'Event';
            const eventDate = button.dataset.eventDate || '';
            
            document.getElementById('cancellationReservationId').value = id;
            
            // Update modal title with booking info
            const modalTitle = document.getElementById('cancellationModalLabel');
            modalTitle.innerHTML = `
                <i class="bi bi-x-circle me-2"></i>
                Request Cancellation - #${id}
                <small class="d-block text-muted fs-6 mt-1">${eventType} on ${eventDate}</small>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('cancellationModal'));
            modal.show();
            
            // Clear previous reason
            document.getElementById('cancellation_reason').value = '';
            
            // Focus on reason field
            setTimeout(() => {
                document.getElementById('cancellation_reason').focus();
            }, 500);
        }
    });

    // Enhanced form validation for cancellation
    const cancellationForm = document.getElementById('cancellationForm');
    if (cancellationForm) {
        cancellationForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('cancellation_reason').value.trim();
            const minLength = 10;
            
            if (reason.length < minLength) {
                e.preventDefault();
                showAlert(`Please provide a detailed reason for cancellation (at least ${minLength} characters).`, 'error');
                document.getElementById('cancellation_reason').focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Submitting...';
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds if still on page (fallback)
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 5000);
        });
    }

    // Real-time character count for cancellation reason
    const cancellationReason = document.getElementById('cancellation_reason');
    if (cancellationReason) {
        // Create character counter
        const counter = document.createElement('div');
        counter.className = 'form-text text-end';
        counter.id = 'cancellationReasonCounter';
        cancellationReason.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = cancellationReason.value.length;
            const minLength = 10;
            counter.textContent = `${length} characters (minimum ${minLength})`;
            counter.className = `form-text text-end ${length >= minLength ? 'text-success' : 'text-warning'}`;
        }
        
        cancellationReason.addEventListener('input', updateCounter);
        updateCounter(); // Initial update
    }
}

// Save active tab to session
function saveActiveTab(tabName) {
    const formData = new FormData();
    formData.append('action', 'save_active_tab');
    formData.append('tab', tabName);
    formData.append('type', 'profile');
    
    fetch('save_active_tab.php', {
        method: 'POST',
        body: formData
    }).catch(error => console.error('Error saving tab:', error));
}

// Show alert message
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertId = 'profile-alert-' + Date.now();
    
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

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});