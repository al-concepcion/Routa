<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Routa</title>
    
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
                            <i class="bi bi-lock-fill text-success" style="font-size: 48px;"></i>
                        </div>
                        <h1 class="h4 fw-bold mb-2">Forgot Password?</h1>
                        <p class="text-muted mb-4">No worries! Enter your email address and we'll send you a link to reset your password.</p>
                    </div>

                    <form id="forgotPasswordForm" method="post">
                        <div id="alertMessage" class="alert" style="display: none;"></div>
                        
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="your@email.com" required>
                            </div>
                            <small class="text-muted">We'll send a password reset link to this email address.</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-3" id="submitBtn">
                            <i class="bi bi-send-fill me-2"></i>Send Reset Link
                        </button>

                        <p class="text-center mb-0">
                            Remember your password? <a href="login.php" class="text-success text-decoration-none">Login here</a>
                        </p>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        If you don't receive an email within 5 minutes, check your spam folder or try again.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            const alertDiv = document.getElementById('alertMessage');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = emailInput.value.trim();
                
                if (!email) {
                    showAlert('Please enter your email address.', 'danger');
                    return;
                }
                
                // Disable submit button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
                
                // Hide previous alerts
                alertDiv.style.display = 'none';
                
                // Send request
                fetch('php/forgot_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        emailInput.value = '';
                        
                        // Show additional instructions
                        setTimeout(() => {
                            showAlert('Check your email inbox and spam folder for the password reset link. The link will expire in 1 hour.', 'info');
                        }, 3000);
                    } else {
                        showAlert(data.message || 'An error occurred. Please try again.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again later.', 'danger');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Send Reset Link';
                });
            });
            
            function showAlert(message, type) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.style.display = 'block';
                
                // Auto-hide success messages after 5 seconds
                if (type === 'success') {
                    setTimeout(() => {
                        alertDiv.style.display = 'none';
                    }, 5000);
                }
            }
        });
    </script>
</body>
</html>
