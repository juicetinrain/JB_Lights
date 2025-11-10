// js/contact.js - Contact Form Validation and Submission
class ContactForm {
    constructor() {
        this.form = document.getElementById('contactForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.charCount = document.getElementById('charCount');
        this.messageInput = document.getElementById('message');
        this.init();
    }

    init() {
        this.initEventListeners();
        this.initCharacterCount();
        console.log('Contact form initialized');
    }

    initEventListeners() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
            
            // Real-time validation
            const inputs = this.form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearError(input));
            });
        }
    }

    initCharacterCount() {
        if (this.messageInput && this.charCount) {
            this.messageInput.addEventListener('input', () => {
                const count = this.messageInput.value.length;
                this.charCount.textContent = count;
                
                if (count > 1000) {
                    this.charCount.style.color = '#ef4444';
                } else {
                    this.charCount.style.color = 'var(--text-secondary)';
                }
            });
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        
        if (this.validateForm()) {
            this.setLoadingState(true);
            this.form.submit();
        }
    }

    validateForm() {
        let isValid = true;
        const fields = [
            'first-name', 'last-name', 'phone', 'email', 'subject', 'message'
        ];

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        this.clearError(field);
        
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        switch(field.type) {
            case 'text':
                if (field.id === 'first-name' || field.id === 'last-name') {
                    if (!value) {
                        errorMessage = 'THIS FIELD IS REQUIRED';
                        isValid = false;
                    } else if (value.length < 2) {
                        errorMessage = 'PLEASE ENTER AT LEAST 2 CHARACTERS';
                        isValid = false;
                    }
                }
                break;
                
            case 'tel':
                if (!value) {
                    errorMessage = 'PHONE NUMBER IS REQUIRED';
                    isValid = false;
                } else if (!this.validatePhone(value)) {
                    errorMessage = 'PLEASE ENTER A VALID PHILIPPINE MOBILE NUMBER (09XXXXXXXXX)';
                    isValid = false;
                }
                break;
                
            case 'email':
                if (!value) {
                    errorMessage = 'EMAIL ADDRESS IS REQUIRED';
                    isValid = false;
                } else if (!this.validateEmail(value)) {
                    errorMessage = 'PLEASE ENTER A VALID EMAIL ADDRESS';
                    isValid = false;
                }
                break;
                
            case 'select-one':
                if (!value) {
                    errorMessage = 'PLEASE SELECT A SUBJECT';
                    isValid = false;
                }
                break;
                
            case 'textarea':
                if (!value) {
                    errorMessage = 'MESSAGE IS REQUIRED';
                    isValid = false;
                } else if (value.length < 10) {
                    errorMessage = 'MESSAGE SHOULD BE AT LEAST 10 CHARACTERS LONG';
                    isValid = false;
                } else if (value.length > 1000) {
                    errorMessage = 'MESSAGE SHOULD NOT EXCEED 1000 CHARACTERS';
                    isValid = false;
                }
                break;
        }

        if (!isValid) {
            this.showError(field, errorMessage);
        }

        return isValid;
    }

    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    validatePhone(phone) {
        const cleanPhone = phone.replace(/\D/g, '');
        const phoneRegex = /^(09)\d{9}$/;
        return phoneRegex.test(cleanPhone);
    }

    showError(field, message) {
        const formGroup = field.closest('.form-group');
        formGroup.classList.add('error');
        
        const errorElement = formGroup.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = message;
        }
        
        field.focus();
    }

    clearError(field) {
        const formGroup = field.closest('.form-group');
        formGroup.classList.remove('error');
    }

    setLoadingState(loading) {
        if (this.submitBtn) {
            if (loading) {
                this.submitBtn.disabled = true;
                this.submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SENDING...';
            } else {
                this.submitBtn.disabled = false;
                this.submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> SEND MESSAGE';
            }
        }
    }
}

// Initialize contact form when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new ContactForm();
});