// js/contact.js - Clean and Simple Contact Form
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const phoneInput = document.getElementById('phone');
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');

    // Character counter for message
    if (messageInput && charCount) {
        messageInput.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 1000) {
                charCount.style.color = '#ef4444';
                this.style.borderColor = '#ef4444';
            } else {
                charCount.style.color = 'var(--text-secondary)';
                this.style.borderColor = '';
            }
        });
        
        // Initialize count
        charCount.textContent = messageInput.value.length;
    }

    // Phone number validation - only numbers, max 11 digits
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/\D/g, '');
            
            // Limit to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });

        phoneInput.addEventListener('blur', function() {
            if (this.value.length === 11 && !this.value.startsWith('09')) {
                this.setCustomValidity('Phone number must start with 09');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Form submission
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Basic validation
            const requiredFields = contactForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '';
                }
            });

            // Phone validation
            if (phoneInput && phoneInput.value) {
                const phoneRegex = /^09\d{9}$/;
                if (!phoneRegex.test(phoneInput.value)) {
                    isValid = false;
                    phoneInput.style.borderColor = '#ef4444';
                    alert('Please enter a valid 11-digit Philippine mobile number starting with 09');
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            } else {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> SENDING...';
                
                // Allow form to submit normally
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> SEND MESSAGE';
                }, 3000);
            }
        });
    }

    // Clear error styles on input
    const formInputs = document.querySelectorAll('#contactForm input, #contactForm select, #contactForm textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
        });
    });
});