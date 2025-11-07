<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JB Lights & Sound Rental</title>
    <link rel="stylesheet" href="login_register.css">
</head>
<body>
    <div class="background"></div>
    <div class="stage-decorations">
        <div class="flowers"></div>
    </div>

    <div class="container">
        <div class="form-wrapper">
            <div class="logo-section">
                <img src="img/jb_logo.jpg" class="jbl">
            </div>

            <div class="tabs">
                <button class="tab active" onclick="switchTab('login')">LOGIN</button>
                <button class="tab" onclick="switchTab('register')">REGISTER</button>
            </div>

            <div class="form-container">
                <!-- Login Form -->
                <form id="loginForm" class="form" onsubmit="handleLogin(event)">
                    <div class="form-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" placeholder="Enter your email" required>
                        <div class="error-message" id="loginEmailError">Please enter a valid email</div>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" placeholder="Enter your password" required>
                        <div class="error-message" id="loginPasswordError">Password is required</div>
                    </div>

                    <div class="form-options">
                        <div class="checkbox-group">
                            <input type="checkbox" id="showPassword" onchange="togglePassword('login')">
                            <label for="showPassword">Show password</label>
                        </div>
                        <a href="#" class="forgot-password" onclick="forgotPassword(event)">Forgot password?</a>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" class="form hidden" onsubmit="handleRegister(event)">
                    <div class="form-group">
                        <label for="registerName">Name</label>
                        <input type="text" id="registerName" placeholder="Enter your full name" required>
                        <div class="error-message" id="registerNameError">Name is required</div>
                    </div>

                    <div class="form-group">
                        <label for="registerEmail">Email</label>
                        <input type="email" id="registerEmail" placeholder="Enter your email" required>
                        <div class="error-message" id="registerEmailError">Please enter a valid email</div>
                    </div>

                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <input type="password" id="registerPassword" placeholder="Create a password" required>
                        <div class="error-message" id="registerPasswordError">Password must be at least 6 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" placeholder="Confirm your password" required>
                        <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
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
                clearErrors();
            } else {
                tabs[1].classList.add('active');
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
                clearErrors();
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

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.classList.add('error');
            error.textContent = message;
            error.classList.add('show');
        }

        function clearError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.classList.remove('error');
            error.classList.remove('show');
        }

        function clearErrors() {
            const inputs = document.querySelectorAll('input');
            const errors = document.querySelectorAll('.error-message');
            inputs.forEach(input => input.classList.remove('error'));
            errors.forEach(error => error.classList.remove('show'));
        }

        function handleLogin(event) {
            event.preventDefault();
            clearErrors();

            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            let isValid = true;

            if (!validateEmail(email)) {
                showError('loginEmail', 'loginEmailError', 'Please enter a valid email');
                isValid = false;
            }

            if (password.length < 1) {
                showError('loginPassword', 'loginPasswordError', 'Password is required');
                isValid = false;
            }

            if (isValid) {
                alert(`Login successful!\nEmail: ${email}`);
                // Here you would typically send the data to your server
                console.log('Login data:', { email, password });
            }
        }

        function handleRegister(event) {
            event.preventDefault();
            clearErrors();

            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            let isValid = true;

            if (name.trim().length < 2) {
                showError('registerName', 'registerNameError', 'Name must be at least 2 characters');
                isValid = false;
            }

            if (!validateEmail(email)) {
                showError('registerEmail', 'registerEmailError', 'Please enter a valid email');
                isValid = false;
            }

            if (password.length < 6) {
                showError('registerPassword', 'registerPasswordError', 'Password must be at least 6 characters');
                isValid = false;
            }

            if (password !== confirmPassword) {
                showError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
                isValid = false;
            }

            if (isValid) {
                alert(`Registration successful!\nName: ${name}\nEmail: ${email}`);
                // Here you would typically send the data to your server
                console.log('Registration data:', { name, email, password });
            }
        }

        function forgotPassword(event) {
            event.preventDefault();
            alert('Password reset link will be sent to your email address.');
            // Here you would typically implement password reset functionality
        }

        function socialLogin(provider) {
            alert(`${provider.charAt(0).toUpperCase() + provider.slice(1)} login clicked!\nThis would connect to ${provider} OAuth.`);
            // Here you would typically implement OAuth integration
            console.log(`Social login with: ${provider}`);
        }

        // Add real-time validation
        document.getElementById('loginEmail')?.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                showError('loginEmail', 'loginEmailError', 'Please enter a valid email');
            } else {
                clearError('loginEmail', 'loginEmailError');
            }
        });

        document.getElementById('registerEmail')?.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                showError('registerEmail', 'registerEmailError', 'Please enter a valid email');
            } else {
                clearError('registerEmail', 'registerEmailError');
            }
        });

        document.getElementById('confirmPassword')?.addEventListener('input', function() {
            const password = document.getElementById('registerPassword').value;
            if (this.value && this.value !== password) {
                showError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
            } else {
                clearError('confirmPassword', 'confirmPasswordError');
            }
        });
    </script>
</body>
</html>