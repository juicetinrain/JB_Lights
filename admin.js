// admin.js - Updated for dark theme
document.addEventListener('DOMContentLoaded', function () {
  // Handle status changes
  document.querySelectorAll('.change-status').forEach(select => {
    select.addEventListener('change', function () {
      const id = this.dataset.id;
      const status = this.value;
      if (!id || !status) return;
      
      if (!confirm('Change status to "' + status + '" for booking #' + id + '?')) {
        this.value = ''; // reset
        return;
      }

      // AJAX POST to admin.php
      const data = new FormData();
      data.append('action', 'update_status');
      data.append('id', id);
      data.append('status', status);

      fetch(window.location.href, {
        method: 'POST',
        body: data
      }).then(r => r.json()).then(resp => {
        if (resp.ok) {
          // Update UI pill
          const row = document.querySelector('select[data-id="' + id + '"]').closest('tr');
          const pill = row.querySelector('.status-pill');
          if (pill) {
            pill.textContent = status;
            pill.className = 'status-pill status-' + status.toLowerCase();
          }
          // Reset select
          document.querySelector('select[data-id="' + id + '"]').value = '';
          
          // Show success message
          showNotification('Status updated successfully!', 'success');
        } else {
          showNotification('Failed to update: ' + (resp.msg || 'Unknown error'), 'error');
        }
      }).catch(err => {
        console.error(err);
        showNotification('Network error occurred', 'error');
      });
    });
  });

  // Add notification function
  function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `admin-notification admin-notification-${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
      </div>
      <button class="notification-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `;
    
    // Add styles if not already added
    if (!document.querySelector('#admin-notification-styles')) {
      const styles = document.createElement('style');
      styles.id = 'admin-notification-styles';
      styles.textContent = `
        .admin-notification {
          position: fixed;
          top: 20px;
          right: 20px;
          background: var(--dark-gray);
          border: 1px solid var(--border);
          border-left: 4px solid var(--blue);
          border-radius: 8px;
          padding: 1rem 1.5rem;
          color: var(--text-primary);
          box-shadow: var(--shadow-lg);
          z-index: 10000;
          max-width: 400px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 1rem;
          animation: slideIn 0.3s ease;
        }
        
        .admin-notification-success {
          border-left-color: #0a8a36;
        }
        
        .admin-notification-error {
          border-left-color: #c92a2a;
        }
        
        .notification-content {
          display: flex;
          align-items: center;
          gap: 0.75rem;
          flex: 1;
        }
        
        .notification-content i {
          font-size: 1.2rem;
        }
        
        .admin-notification-success .notification-content i {
          color: #0a8a36;
        }
        
        .admin-notification-error .notification-content i {
          color: #c92a2a;
        }
        
        .notification-close {
          background: none;
          border: none;
          color: var(--text-secondary);
          cursor: pointer;
          padding: 0.25rem;
          border-radius: 4px;
          transition: all 0.3s ease;
        }
        
        .notification-close:hover {
          background: var(--medium-gray);
          color: var(--text-primary);
        }
        
        @keyframes slideIn {
          from {
            transform: translateX(100%);
            opacity: 0;
          }
          to {
            transform: translateX(0);
            opacity: 1;
          }
        }
      `;
      document.head.appendChild(styles);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove();
      }
    }, 5000);
  }
});