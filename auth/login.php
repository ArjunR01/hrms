<?php
// Start session at the very top
require_once __DIR__ . '/../core/session.php';
Session::init();

// Check if already logged in - redirect to appropriate dashboard
if (Session::isLoggedIn()) {
    $role = Session::getUserRole();
    $dashboard_map = [
        'super_admin' => '../dashboards/super_admin_dashboard.php',
        'admin' => '../dashboards/admin_dashboard.php',
        'hr' => '../dashboards/hr_dashboard.php',
        'manager' => '../dashboards/manager_dashboard.php',
        'employee' => '../dashboards/employee_dashboard.php'
    ];
    $redirect = $dashboard_map[$role] ?? '../dashboards/employee_dashboard.php';
    header("Location: $redirect");
    exit();
}

// Get error or success messages from URL
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SSMS HRMS | Human Resource Management System</title>
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-dark: #1a202c;
            --text-gray: #4a5568;
            --text-light: #718096;
            --border-color: #e2e8f0;
            --bg-light: #f7fafc;
            --white: #ffffff;
            --success-color: #48bb78;
            --error-color: #f56565;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--primary-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            position: relative;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Animated Background Elements */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
            z-index: 0;
        }
        
        body::before {
            width: 600px;
            height: 600px;
            top: -300px;
            right: -200px;
            animation: float1 20s ease-in-out infinite;
        }
        
        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            left: -100px;
            animation: float2 15s ease-in-out infinite;
        }
        
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
        }
        
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(50px, 50px) rotate(180deg); }
        }
        
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            height: 100vh;
            margin: 0 auto;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: var(--white);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 90vh;
            max-height: 750px;
            width: 100%;
            position: relative;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Left Side - Branding */
        .login-brand {
            background: var(--primary-gradient);
            padding: 3rem 2.5rem;
            color: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        
        .login-brand::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -150px;
            left: -150px;
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }
        
        .brand-content {
            position: relative;
            z-index: 1;
        }
        
        .brand-logo {
            width: 75px;
            height: 75px;
            background: var(--white);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .brand-logo i {
            font-size: 38px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }
        
        .brand-subtitle {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.95;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }
        
        .brand-company {
            font-size: 0.95rem;
            opacity: 0.85;
            font-weight: 500;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .brand-features {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateX(8px);
        }
        
        .feature-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .feature-text {
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.4;
        }
        
        /* Right Side - Login Form */
        .login-form-section {
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
            height: 100%;
            overflow: hidden;
        }
        
        .form-header {
            margin-bottom: 1.5rem;
        }
        
        .form-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.375rem;
            letter-spacing: -0.02em;
        }
        
        .form-description {
            color: var(--text-light);
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .alert {
            padding: 0.75rem 0.875rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            font-weight: 500;
            animation: slideDown 0.4s ease-out;
            line-height: 1.3;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .alert-error {
            background: #fff5f5;
            color: #c53030;
            border: 1.5px solid #fc8181;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #2f855a;
            border: 1.5px solid #9ae6b4;
        }
        
        .form-group {
            margin-bottom: 1.125rem;
        }
        
        .form-label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            letter-spacing: -0.01em;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.125rem;
            pointer-events: none;
            z-index: 1;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: inherit;
            font-weight: 500;
            color: var(--text-dark);
            background: var(--bg-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-input::placeholder {
            color: #cbd5e0;
            font-weight: 400;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.25rem;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .toggle-password:hover {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }
        
        .password-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .submit-button {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.01em;
        }
        
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
        }
        
        .submit-button:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.125rem 0 1rem 0;
            color: var(--text-light);
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }
        
        .divider span {
            padding: 0 0.875rem;
        }
        
        .demo-credentials {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 0;
        }
        
        .demo-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            font-size: 0.825rem;
            font-weight: 700;
        }
        
        .demo-header i {
            color: var(--primary-color);
            font-size: 0.95rem;
        }
        
        .demo-list {
            display: grid;
            gap: 0.5rem;
        }
        
        .demo-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background: var(--white);
            border-radius: 7px;
            font-size: 0.8rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }
        
        .demo-row:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }
        
        .demo-label {
            color: var(--text-gray);
            font-weight: 600;
        }
        
        .demo-value {
            color: var(--text-dark);
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            font-weight: 600;
            background: #edf2f7;
            padding: 0.3rem 0.625rem;
            border-radius: 5px;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 550px;
                margin: 0 auto;
                height: auto;
                max-height: none;
            }
            
            .login-brand {
                padding: 2.5rem 2rem;
                min-height: auto;
            }
            
            .brand-features {
                display: none;
            }
            
            .brand-title {
                font-size: 2.25rem;
            }
            
            .login-form-section {
                padding: 2.5rem 2rem;
                overflow-y: auto;
            }
        }
        
        @media (max-width: 640px) {
            body {
                padding: 0;
            }
            
            .login-wrapper {
                padding: 0.75rem;
            }
            
            .login-container {
                height: 100vh;
                max-height: none;
                border-radius: 0;
            }
            
            .login-brand {
                padding: 2.5rem 1.75rem;
            }
            
            .brand-logo {
                width: 70px;
                height: 70px;
                margin-bottom: 1.5rem;
            }
            
            .brand-logo i {
                font-size: 36px;
            }
            
            .brand-title {
                font-size: 2rem;
            }
            
            .brand-subtitle {
                font-size: 1.05rem;
            }
            
            .login-form-section {
                padding: 2.5rem 1.75rem;
            }
            
            .form-title {
                font-size: 1.875rem;
            }
            
            .form-input {
                padding: 0.875rem 1rem 0.875rem 2.75rem;
            }
            
            .submit-button {
                padding: 1rem 1.5rem;
            }
            
            .demo-credentials {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .demo-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .demo-value {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Left Side - Branding -->
            <div class="login-brand">
                <div class="brand-content">
                    <div class="brand-logo">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h1 class="brand-title">SSMS HRMS</h1>
                    <p class="brand-subtitle">Complete Human Resource Management Solution</p>
                    <p class="brand-company"><i class="fas fa-building"></i> Srinivasa Sales & Service Pvt. Ltd.</p>
                </div>
                
                <div class="brand-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="feature-text">Bank-level Security & Encryption</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="feature-text">Real-time Attendance Tracking</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-text">Advanced Analytics & Reports</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="login-form-section">
                <div class="form-header">
                    <h2 class="form-title">Welcome Back!</h2>
                    <p class="form-description">Enter your credentials to access your dashboard</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="login_action.php" autocomplete="on">
                    <div class="form-group">
                        <label for="username" class="form-label">Email Address or Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input 
                                type="text" 
                                id="username"
                                name="username" 
                                class="form-input" 
                                placeholder="Enter your email or username" 
                                required 
                                autocomplete="username"
                                value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="password-label-row">
                            <label for="password" class="form-label">Password</label>
                            <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
                        </div>
                        <div class="password-wrapper">
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    id="password"
                                    name="password" 
                                    class="form-input" 
                                    placeholder="Enter your password" 
                                    required 
                                    autocomplete="current-password"
                                >
                            </div>
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-button">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In to Dashboard</span>
                    </button>
                </form>
                
                <div class="divider">
                    <span>Demo Accounts</span>
                </div>
                
                <div class="demo-credentials">
                    <div class="demo-header">
                        <i class="fas fa-info-circle"></i>
                        <span>Test Credentials Available</span>
                    </div>
                    <div class="demo-list">
                        <div class="demo-row">
                            <span class="demo-label">Employee Account:</span>
                            <span class="demo-value">employee@ssspl.com</span>
                        </div>
                        <div class="demo-row">
                            <span class="demo-label">Manager Account:</span>
                            <span class="demo-value">manager@ssspl.com</span>
                        </div>
                        <div class="demo-row">
                            <span class="demo-label">All Passwords:</span>
                            <span class="demo-value">demo@123</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Auto-hide alerts after 8 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-15px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 8000);
        
        // Add focus effect on inputs
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
