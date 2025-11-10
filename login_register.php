<?php
// login_register.php
require_once 'db/db_connect.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        // Redirect based on user type
        if ($user['user_type'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $login_error = "Invalid email or password!";
    }
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - JB Lights & Sound</title>
    <link rel="stylesheet" href="css/pages/login_register.css">
</head>
<body>
    
    <div class="background"></div>
    
    <div class="container">
        <div class="form-wrapper">
            <div class="logo-section">
                <img src="https://i.imgur.com/wOkfD9T.jpeg" class="jbl">
            </div>

            <div class="tabs">
                <button class="tab active" onclick="switchTab('login')">LOGIN</button>
                <button class="tab" onclick="switchTab('register')">REGISTER</button>
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
</body>
</html>