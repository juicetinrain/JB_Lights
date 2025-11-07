// home.js - Common JavaScript for JB Lights & Sound
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenu = document.querySelector('.menu-toggle');
    const navList = document.querySelector('.nav-list');
    
    if (mobileMenu && navList) {
        mobileMenu.addEventListener('click', function() {
            const isVisible = navList.style.display === 'flex';
            navList.style.display = isVisible ? 'none' : 'flex';
            if (!isVisible) {
                navList.style.flexDirection = 'column';
                navList.style.position = 'absolute';
                navList.style.top = '100%';
                navList.style.right = '0';
                navList.style.background = 'rgba(255, 255, 255, 0.95)';
                navList.style.backdropFilter = 'blur(10px)';
                navList.style.padding = '1rem';
                navList.style.borderRadius = '0 0 0 12px';
                navList.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
                navList.style.zIndex = '1000';
            }
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    // Calculate offset for fixed header
                    const headerHeight = document.querySelector('.main-header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (navList && window.innerWidth <= 768) {
                        navList.style.display = 'none';
                    }
                }
            }
        });
    });

    // Navbar background on scroll
    const header = document.querySelector('.main-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            }
        });
    }

    // Animation on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.service-card, .package-card, .testimonial-card, .featured-event, .faq-item');
        
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
    const animatedElements = document.querySelectorAll('.service-card, .package-card, .testimonial-card, .featured-event, .faq-item');
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check

    // Statistics counter animation
    const statNumbers = document.querySelectorAll('.stat-number');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const target = parseInt(statNumber.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        clearInterval(timer);
                        current = target;
                    }
                    statNumber.textContent = Math.floor(current) + (statNumber.textContent.includes('%') ? '%' : '+');
                }, 50);
                observer.unobserve(statNumber);
            }
        });
    }, observerOptions);

    statNumbers.forEach(stat => {
        observer.observe(stat);
    });

    // Add loading animation for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (navList && navList.style.display === 'flex' && 
            !event.target.closest('.main-nav') && 
            !event.target.closest('.nav-list')) {
            navList.style.display = 'none';
        }
    });

    // Package card hover effects for homepage
    const packageCards = document.querySelectorAll('.packages-section .package-card');
    packageCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('featured')) {
                this.style.borderColor = 'var(--primary)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('featured')) {
                this.style.borderColor = 'var(--border)';
            }
        });
    });

    // Set active menu item based on current page
    const currentPage = window.location.pathname.split('/').pop();
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && (href === currentPage || href.includes(currentPage))) {
            item.classList.add('active');
        }
        
        // Handle anchor links for index.php sections
        if (currentPage === 'index.php' || currentPage === '') {
            if (href && href.includes('index.php#')) {
                item.classList.add('active');
            } else if (href === 'index.php') {
                item.classList.add('active');
            }
        }
    });

    // Set active nav link based on current page
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (href === currentPage || href.includes(currentPage))) {
            link.classList.add('active');
        }
        
        if (currentPage === 'index.php' || currentPage === '') {
            if (href && href.includes('index.php#')) {
                link.classList.add('active');
            } else if (href === 'index.php') {
                link.classList.add('active');
            }
        }
    });
});

// Side navigation functions
function openNav() {
    document.getElementById("mySidenav").style.width = "320px";
    document.body.style.overflow = "hidden";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.body.style.overflow = "auto";
}

// Close sidenav when clicking outside
document.addEventListener('click', function(event) {
    const sidenav = document.getElementById('mySidenav');
    const menuToggle = document.querySelector('.menu-toggle');
    if (sidenav && menuToggle) {
        if (!sidenav.contains(event.target) && !menuToggle.contains(event.target)) {
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

// Utility function for form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^(09|\+639)\d{9}$/;
    return re.test(phone.replace(/\D/g, ''));
}

// Contact form validation
document.querySelector('.contact-form')?.addEventListener('submit', function(e) {
    const phone = document.getElementById('phone')?.value;
    if (phone) {
        const phoneRegex = /^(09|\+639)\d{9}$/;
        const cleanPhone = phone.replace(/\D/g, '');
        
        if (!phoneRegex.test('09' + cleanPhone.slice(-9))) {
            e.preventDefault();
            alert('Please enter a valid Philippine mobile number (09XXXXXXXXX)');
            return false;
        }
    }
    
    // Add loading state
    const submitBtn = this.querySelector('.btn-submit');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
        submitBtn.disabled = true;
    }
});