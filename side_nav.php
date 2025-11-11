<?php
// side_nav.php - Updated with admin panel link
require_once 'db/db_connect.php';
$current_user = isLoggedIn() ? getCurrentUser() : null;
?>
<!-- Side Navigation -->
<div id="mySidenav" class="sidenav">
    <div class="sidenav-header">
        <a href="index.php" class="logo">
            <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="logo-image">
            <div class="logo-text">
                <div class="logo-main">JB LIGHTS & SOUND</div>
                <div class="logo-sub">RENTAL SERVICES</div>
            </div>
        </a>
        <button class="closebtn" onclick="closeNav()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    <div class="sidenav-content">
        <!-- Dynamic User Info -->
        <?php if (isLoggedIn() && $current_user): ?>
        <div class="user-profile">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($current_user['name']); ?></div>
                <div class="user-email"><?php echo htmlspecialchars($current_user['email']); ?></div>
                <?php if (isAdmin()): ?>
                <div class="user-badge">Administrator</div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="user-profile">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <div class="user-name">Welcome to JB Lights</div>
                <div class="user-email">Event Services</div>
            </div>
        </div>
        <?php endif; ?>

        <nav class="sidenav-menu">
            <a href="index.php" class="menu-item">
                <i class="bi bi-house"></i> Home
            </a>
            <a href="index.php#services" class="menu-item">
                <i class="bi bi-speaker"></i> Services
            </a>
            <a href="index.php#packages" class="menu-item">
                <i class="bi bi-star"></i> Packages
            </a>
            <a href="reservation.php" class="menu-item">
                <i class="bi bi-calendar-check"></i> Book Now
            </a>
            <a href="about_us.php" class="menu-item">
                <i class="bi bi-info-circle"></i> About Us
            </a>
            <a href="ContactUs.php" class="menu-item">
                <i class="bi bi-telephone"></i> Contact
            </a>
            
            <?php if (isLoggedIn()): ?>
                <!-- Admin Panel Link (only for admins) -->
                <?php if (isAdmin()): ?>
                <a href="admin.php" class="menu-item admin-link">
                    <i class="bi bi-speedometer2"></i> Admin Panel
                </a>
                <?php endif; ?>
                
                <!-- User Profile Link -->
                <a href="profile.php" class="menu-item">
                    <i class="bi bi-person"></i> My Profile
                </a>
            <?php endif; ?>
        </nav>
        
        <div class="sidenav-footer">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <span>09656396053</span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-clock"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
            <?php if (isLoggedIn()): ?>
            <button class="logout-button" onclick="location.href='logout.php'">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
            <?php else: ?>
            <button class="logout-button" onclick="location.href='login_register.php'">
                <i class="bi bi-person"></i> Login / Register
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>