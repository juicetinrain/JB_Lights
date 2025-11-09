<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JB Lights & Sound | User Profile</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Profile Page Specific Styles */
        .profile-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--black);
        }

        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .profile-card {
            background: var(--dark-gray);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
        }

        .profile-header {
            background: var(--gradient);
            padding: 3rem 2rem;
            text-align: center;
            color: var(--white);
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
            margin: 0 auto 1rem;
            display: block;
            background: var(--dark);
        }

        .profile-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .profile-header p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .profile-body {
            padding: 2.5rem;
        }

        .info-section {
            margin-bottom: 2.5rem;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--blue);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.25rem;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            border-color: var(--blue);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .info-item label {
            display: block;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item span {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--blue);
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--blue);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .profile-actions {
            padding: 2rem;
            background: var(--dark-gray);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .quick-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-page {
                padding-top: 80px;
            }

            .profile-container {
                padding: 1rem;
            }

            .profile-body {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .profile-actions {
                flex-direction: column;
                text-align: center;
            }

            .quick-actions {
                justify-content: center;
            }

            .action-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .profile-header {
                padding: 2rem 1rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="img/jb_logo.jpg" alt="JB Lights & Sound" class="logo-image">
                <div class="logo-text">
                    <div class="logo-main">JB LIGHTS & SOUND</div>
                    <div class="logo-sub">Rental Services</div>
                </div>
            </a>
            <nav class="main-nav">
                <button class="menu-toggle" onclick="openNav()">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <img src="img/user-profile.jpg" alt="User Profile" class="profile-avatar">
                    <h2>Alex Martinez</h2>
                    <p>Customer | alex.martinez@example.com</p>
                    <div class="action-buttons">
                        <button class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                        <button class="btn btn-outline">
                            <i class="fas fa-cog"></i> Settings
                        </button>
                    </div>
                </div>

                <div class="profile-body">
                    <!-- Personal Information -->
                    <div class="info-section">
                        <h3 class="section-title">Personal Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Full Name</label>
                                <span>John Doe</span>
                            </div>
                            <div class="info-item">
                                <label>Email Address</label>
                                <span>alex.martinez@example.com</span>
                            </div>
                            <div class="info-item">
                                <label>Phone Number</label>
                                <span>09000000000</span>
                            </div>
                            <div class="info-item">
                                <label>Address</label>
                                <span>Dau, Mabalacat City Pampanga</span>
                            </div>
                            <div class="info-item">
                                <label>Member Since</label>
                                <span>March 2025</span>
                            </div>
                            <div class="info-item">
                                <label>Account Status</label>
                                <span style="color: var(--blue);">Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Statistics -->
                    <div class="info-section">
                        <h3 class="section-title">Booking Statistics</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">8</div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">2</div>
                                <div class="stat-label">Upcoming</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">6</div>
                                <div class="stat-label">Completed</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Cancelled</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="info-section">
                        <h3 class="section-title">Recent Activity</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Last Booking</label>
                                <span>Oct 20, 2025 – Wedding Event</span>
                            </div>
                            <div class="info-item">
                                <label>Package Used</label>
                                <span>Premium Sound Package</span>
                            </div>
                            <div class="info-item">
                                <label>Preferred Service</label>
                                <span>Concert Lighting & Stage Sound</span>
                            </div>
                            <div class="info-item">
                                <label>Next Booking</label>
                                <span style="color: var(--blue);">Dec 15, 2025 – Corporate Event</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <div class="quick-actions">
                        <button class="btn btn-secondary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-bell"></i> Notifications
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-history"></i> Booking History
                        </button>
                    </div>
                    <button class="btn btn-outline" style="color: #ef4444; border-color: #ef4444;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <a href="index.php" class="logo">
                        <img src="img/jb_logo.jpg" alt="JB Lights & Sound" class="logo-image">
                        <div class="logo-text">
                            <div class="logo-main">JB LIGHTS & SOUND</div>
                            <div class="logo-sub">Rental Services</div>
                        </div>
                    </a>
                    <p class="footer-desc">Professional lights and sound rental services for all your events. Quality equipment and exceptional service guaranteed.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="packages.php">Packages</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-services">
                    <h4>Our Services</h4>
                    <ul>
                        <li>Sound System Rental</li>
                        <li>Lighting Solutions</li>
                        <li>Stage Setup</li>
                        <li>Event Planning</li>
                        <li>Technical Support</li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact Info</h4>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Dau, Mabalacat City, Pampanga</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+63 900 000 0000</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@jblights.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>24/7 Available</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 JB Lights & Sound Rental. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Side Navigation -->
    <div id="sidenav" class="sidenav">
        <div class="sidenav-header">
            <a href="index.php" class="logo">
                <img src="img/jb_logo.jpg" alt="JB Lights & Sound" class="logo-image">
                <div class="logo-text">
                    <div class="logo-main">JB LIGHTS & SOUND</div>
                    <div class="logo-sub">Rental Services</div>
                </div>
            </a>
            <button class="closebtn" onclick="closeNav()">&times;</button>
        </div>
        
        <div class="sidenav-content">
            <div class="user-profile" onclick="toggleProfileMenu()">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div class="user-name">Alex Martinez</div>
                    <div class="user-email">alex.martinez@example.com</div>
                </div>
            </div>
            
            <nav class="sidenav-menu">
                <a href="index.php" class="menu-item">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="profile.php" class="menu-item active">
                    <i class="fas fa-user"></i> My Profile
                </a>
                <a href="bookings.php" class="menu-item">
                    <i class="fas fa-calendar-alt"></i> My Bookings
                </a>
                <a href="services.php" class="menu-item">
                    <i class="fas fa-music"></i> Services
                </a>
                <a href="gallery.php" class="menu-item">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a href="packages.php" class="menu-item">
                    <i class="fas fa-box"></i> Packages
                </a>
                <a href="contact.php" class="menu-item">
                    <i class="fas fa-phone"></i> Contact
                </a>
            </nav>
            
            <div class="sidenav-footer">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+63 900 000 0000</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@jblights.com</span>
                    </div>
                </div>
                <button class="logout-button" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Navigation functions
        function openNav() {
            document.getElementById("sidenav").style.width = "320px";
        }

        function closeNav() {
            document.getElementById("sidenav").style.width = "0";
        }

        function toggleProfileMenu() {
            // Add profile menu toggle functionality here
            console.log("Profile menu clicked");
        }

        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "login_register.php";
            }
        }

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Font Awesome (add this to your head or use CDN)
        const faScript = document.createElement('script');
        faScript.src = 'https://kit.fontawesome.com/your-fontawesome-kit.js';
        faScript.crossOrigin = 'anonymous';
        document.head.appendChild(faScript);
    </script>
</body>
</html>