// home.js - Dark Mode JavaScript - SIMPLIFIED
document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.querySelector('.main-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Menu toggle for side navigation
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            openNav();
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = document.querySelector('.main-header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close side nav if open
                    closeNav();
                }
            }
        });
    });

    // Animation on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.service-card, .package-card, .gallery-item');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Initialize elements for animation
    const animatedElements = document.querySelectorAll('.service-card, .package-card, .gallery-item');
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });

    if (animatedElements.length > 0) {
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll();
    }

    // Particle animation
    const particles = document.querySelectorAll('.particle');
    particles.forEach(particle => {
        particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
    });
});

// Side navigation functions
function openNav() {
    const sidenav = document.getElementById("mySidenav");
    if (sidenav) {
        sidenav.style.width = "320px";
        document.body.style.overflow = "hidden";
    }
}

function closeNav() {
    const sidenav = document.getElementById("mySidenav");
    if (sidenav) {
        sidenav.style.width = "0";
        document.body.style.overflow = "auto";
    }
}

// Close sidenav when clicking outside
document.addEventListener('click', function(event) {
    const sidenav = document.getElementById('mySidenav');
    if (sidenav && sidenav.style.width !== '0px' && sidenav.style.width !== '0') {
        if (!sidenav.contains(event.target)) {
            closeNav();
        }
    }
});

// Close sidenav on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeNav();
    }
});

// Contact Form Validation - Add to home.js
function validateContactForm() {
    const firstName = document.getElementById('first-name');
    const lastName = document.getElementById('last-name');
    const phone = document.getElementById('phone');
    const email = document.getElementById('email');
    const subject = document.getElementById('subject');
    const message = document.getElementById('message');
    
    let isValid = true;
    
    // Reset previous errors
    document.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('error');
    });
    
    // Validate First Name
    if (!firstName.value.trim()) {
        showFieldError(firstName, 'First name is required');
        isValid = false;
    }
    
    // Validate Last Name
    if (!lastName.value.trim()) {
        showFieldError(lastName, 'Last name is required');
        isValid = false;
    }
    
    // Validate Phone
    if (!phone.value.trim()) {
        showFieldError(phone, 'Phone number is required');
        isValid = false;
    } else if (!validatePhone(phone.value)) {
        showFieldError(phone, 'Please enter a valid Philippine mobile number');
        isValid = false;
    }
    
    // Validate Email
    if (!email.value.trim()) {
        showFieldError(email, 'Email address is required');
        isValid = false;
    } else if (!validateEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email address');
        isValid = false;
    }
    
    // Validate Subject
    if (!subject.value) {
        showFieldError(subject, 'Please select a subject');
        isValid = false;
    }
    
    // Validate Message
    if (!message.value.trim()) {
        showFieldError(message, 'Message is required');
        isValid = false;
    } else if (message.value.trim().length < 10) {
        showFieldError(message, 'Message should be at least 10 characters long');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(field, message) {
    const formGroup = field.closest('.form-group');
    formGroup.classList.add('error');
    
    // Remove existing error message
    const existingError = formGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.style.color = '#ef4444';
    errorElement.style.fontSize = '0.875rem';
    errorElement.style.marginTop = '0.5rem';
    errorElement.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
    
    formGroup.appendChild(errorElement);
    
    // Focus on the field
    field.focus();
}

// Add this CSS for error states in your contact page styles
const contactErrorStyles = `
    .form-group.error input,
    .form-group.error select,
    .form-group.error textarea {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
`;

// Inject the error styles
const styleSheet = document.createElement('style');
styleSheet.textContent = contactErrorStyles;
document.head.appendChild(styleSheet);