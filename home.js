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