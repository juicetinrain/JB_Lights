<?php
// side_nav.php - Standardized Side Navigation
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
            </div>
        </div>
        <?php else: ?>
        <div class="user-profile">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <div class="user-name">WELCOME TO JB LIGHTS</div>
                <div class="user-email">EVENT SERVICES</div>
            </div>
        </div>
        <?php endif; ?>

        <nav class="sidenav-menu">
            <a href="index.php" class="menu-item">
                <i class="bi bi-house"></i> HOME
            </a>
            <a href="index.php#services" class="menu-item">
                <i class="bi bi-speaker"></i> SERVICES
            </a>
            <a href="index.php#packages" class="menu-item">
                <i class="bi bi-star"></i> PACKAGES
            </a>
            <a href="reservation.php" class="menu-item">
                <i class="bi bi-calendar-check"></i> BOOK NOW
            </a>
            <a href="about_us.php" class="menu-item">
                <i class="bi bi-info-circle"></i> ABOUT US
            </a>
            <a href="ContactUs.php" class="menu-item">
                <i class="bi bi-telephone"></i> CONTACT
            </a>
            <?php if (isLoggedIn()): ?>
            <a href="profile.php" class="menu-item">
                <i class="bi bi-person"></i> MY PROFILE
            </a>
            <?php endif; ?>
        </nav>
        
        <div class="sidenav-footer">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <span>0965-639-6053</span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-clock"></i>
                    <span>24/7 SUPPORT</span>
                </div>
            </div>
            <?php if (isLoggedIn()): ?>
            <button class="logout-button" onclick="location.href='logout.php'">
                <i class="bi bi-box-arrow-right"></i> LOGOUT
            </button>
            <?php else: ?>
            <button class="logout-button" onclick="location.href='login_register.php'">
                <i class="bi bi-person"></i> LOGIN / REGISTER
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>