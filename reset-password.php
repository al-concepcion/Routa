<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Routa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/pages/login.css">
    <link rel="shortcut icon" href="assets/images/Logo.png" type="image/x-icon">
</head>
<body>
    <div class="container"> 
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-12 col-md-6 col-lg-5">
                <a href="login.php" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Back to Login
                </a>

                <div class="card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="mb-4">
                            <i class="bi bi-key-fill text-success" style="font-size: 48px;"></i>
                        </div>
                        <h1 class="h4 fw-bold mb-2">Reset Your Password</h1>
                        <p class="text-muted mb-4">Enter your new password below.</p>
                    </div>

                    <form id="resetPasswordForm" method="post">
                        <div id="alertMessage" class="alert" style="display: none;"></div>
                        
                        <input type="hidden" name="token" id="token" value="">
                        
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="password-input-group">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Enter new password" required minlength="8">
                                </div>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Must be at least 8 characters long.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="password-input-group">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border-start-0" placeholder="Confirm new password" required minlength="8">
                                </div>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="card bg-light border-0">
                                <div class="card-body py-3">
                                    <small class="text-muted">
                                        <strong>Password Requirements:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>At least 8 characters</li>
                                            <li>Mix of uppercase and lowercase letters (recommended)</li>
                                            <li>Include numbers and special characters (recommended)</li>
                                        </ul>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-3" id="submitBtn">
                            <i class="bi bi-check-circle-fill me-2"></i>Reset Password
                        </button>

                        <p class="text-center mb-0">
                            Remember your password? <a href="login.php" class="text-success text-decoration-none">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleBtn = passwordInput.closest('.password-input-group').querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.remove('bi-eye');
                toggleBtn.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('bi-eye-slash');
                toggleBtn.classList.add('bi-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const alertDiv = document.getElementById('alertMessage');
            const submitBtn = document.getElementById('submitBtn');
            const tokenInput = document.getElementById('token');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            // Get token from URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            
            if (!token) {
                showAlert('Invalid or missing reset token. Please request a new password reset link.', 'danger');
                submitBtn.disabled = true;
                return;
            }
            
            tokenInput.value = token;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                // Validate passwords
                if (password.length < 8) {
                    showAlert('Password must be at least 8 characters long.', 'danger');
                    return;
                }
                
                if (password !== confirmPassword) {
                    showAlert('Passwords do not match. Please try again.', 'danger');
                    return;
                }
                
                // Disable submit button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Resetting...';
                
                // Hide previous alerts
                alertDiv.style.display = 'none';
                
                // Send request
                fetch('php/reset_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        token: token,
                        password: password,
                        confirm_password: confirmPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        
                        // Clear form
                        passwordInput.value = '';
                        confirmPasswordInput.value = '';
                        
                        // Redirect to login after 3 seconds
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 3000);
                    } else {
                        showAlert(data.message || 'An error occurred. Please try again.', 'danger');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Reset Password';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again later.', 'danger');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Reset Password';
                });
            });
            
            function showAlert(message, type) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.style.display = 'block';
                
                // Scroll to alert
                alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    </script>
</body>
</html>
