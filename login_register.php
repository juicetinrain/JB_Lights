<?php
// login_register.php
require_once 'db/db_connect.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check if user exists with the provided email and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'] ?? 'user'; // Default to 'user' if not set
        
        // Redirect based on user type
        if ($_SESSION['user_type'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $login_error = "Invalid email or password!";
    }
    $stmt->close();
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if ($check->num_rows > 0) {
        $register_error = "Email already exists!";
    } else {
        // Insert new user as regular user
        $user_type = 'user';
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $user_type);
        
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = $user_type;
            
            header('Location: index.php');
            exit();
        } else {
            $register_error = "Registration failed. Please try again.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/login_register.css">
</head>
<body class="dark-mode login-register-page">
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="logo-image">
                <div class="logo-text">
                    <span class="logo-main">JB LIGHTS & SOUND</span>
                    <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                </div>
            </a>
            <nav class="main-nav">
                <a href="reservation.php" class="btn btn-primary me-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar-check"></i> BOOK NOW
                </a>
                <button class="menu-toggle">
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
    <main class="login-register-main">
        <div class="background"></div>
        
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-wrapper">
                        <div class="logo-section">
                            <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="login-logo">
                        </div>

                        <div class="tabs">
                            <button class="tab active" onclick="switchTab('login')">Login</button>
                            <button class="tab" onclick="switchTab('register')">Register</button>
                        </div>

                        <div class="form-container">
                            <!-- Login Form -->
                            <form id="loginForm" class="form" method="POST">
                                <input type="hidden" name="login" value="1">
                                <?php if (isset($login_error)): ?>
                                    <div class="error-message show"><?php echo $login_error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="loginEmail">Email</label>
                                    <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
                                </div>

                                <div class="form-group">
                                    <label for="loginPassword">Password</label>
                                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                                </div>

                                <div class="form-options">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="showPassword" onchange="togglePassword('login')">
                                        <label for="showPassword">Show password</label>
                                    </div>
                                    <a href="#" class="forgot-password">Forgot password?</a>
                                </div>

                                <button type="submit" class="submit-btn">Login</button>
                            </form>

                            <!-- Register Form -->
                            <form id="registerForm" class="form hidden" method="POST">
                                <input type="hidden" name="register" value="1">
                                <?php if (isset($register_error)): ?>
                                    <div class="error-message show"><?php echo $register_error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="registerName">Name</label>
                                    <input type="text" id="registerName" name="name" placeholder="Enter your full name" required>
                                </div>

                                <div class="form-group">
                                    <label for="registerEmail">Email</label>
                                    <input type="email" id="registerEmail" name="email" placeholder="Enter your email" required>
                                </div>

                                <div class="form-group">
                                    <label for="registerPassword">Password</label>
                                    <input type="password" id="registerPassword" name="password" placeholder="Create a password" required>
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password</label>
                                    <input type="password" id="confirmPassword" placeholder="Confirm your password" required>
                                </div>

                                <div class="form-options">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="showRegisterPassword" onchange="togglePassword('register')">
                                        <label for="showRegisterPassword">Show password</label>
                                    </div>
                                </div>

                                <button type="submit" class="submit-btn">Register</button>
                            </form>
                        </div>
                    </div>
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
                        <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="logo-image">
                        <div class="logo-text">
                            <span class="logo-main">JB LIGHTS & SOUND</span>
                            <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                        </div>
                    </a>
                    <p class="footer-desc">Your premier partner for professional event production services in Pampanga and surrounding areas.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-messenger"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="tel:+639656396053" class="social-link"><i class="bi bi-telephone"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>QUICK LINKS</h4>
                    <ul>
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="about_us.php">ABOUT US</a></li>
                        <li><a href="index.php#services">SERVICES</a></li>
                        <li><a href="index.php#packages">PACKAGES</a></li>
                        <li><a href="ContactUs.php">CONTACT</a></li>
                    </ul>
                </div>
                
                <div class="footer-services">
                    <h4>OUR SERVICES</h4>
                    <ul>
                        <li>SOUND SYSTEMS</li>
                        <li>LIGHTING EQUIPMENT</li>
                        <li>STAGE & TRUSSES</li>
                        <li>LED VIDEO WALLS</li>
                        <li>EVENT PRODUCTION</li>
                        <li>TECHNICAL SUPPORT</li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>CONTACT INFO</h4>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>235, PUROK 2, BICAL, MABALACAT CITY, PAMPANGA</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <span>09656396053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope"></i>
                        <span>JBLIGHTSANDSOUNDRENTAL@GMAIL.COM</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>24/7 EMERGENCY SUPPORT</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 JB LIGHTS & SOUND. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

    <!-- Side Navigation -->
    <?php include 'side_nav.php'; ?>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const tabs = document.querySelectorAll('.tab');

            tabs.forEach(t => t.classList.remove('active'));

            if (tab === 'login') {
                tabs[0].classList.add('active');
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
            } else {
                tabs[1].classList.add('active');
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
            }
        }

        function togglePassword(form) {
            if (form === 'login') {
                const passwordInput = document.getElementById('loginPassword');
                const checkbox = document.getElementById('showPassword');
                passwordInput.type = checkbox.checked ? 'text' : 'password';
            } else {
                const passwordInput = document.getElementById('registerPassword');
                const confirmInput = document.getElementById('confirmPassword');
                const checkbox = document.getElementById('showRegisterPassword');
                const type = checkbox.checked ? 'text' : 'password';
                passwordInput.type = type;
                confirmInput.type = type;
            }
        }

        // Password confirmation validation
        document.getElementById('confirmPassword')?.addEventListener('input', function() {
            const password = document.getElementById('registerPassword').value;
            const confirm = this.value;
            
            if (confirm && password !== confirm) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });

        // Real-time email validation
        document.getElementById('registerEmail')?.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/common.js"></script>
</body>
</html>