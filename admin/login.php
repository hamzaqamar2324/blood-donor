<?php
// login.php
session_start();

// ====== CONFIG (EDIT THESE) ======
define('ADMIN_EMAIL', 'admin@gmail.com');      // Fixed admin login ID
define('ADMIN_PASS',  'admin123');             // Fixed admin password
define('NOTIFY_TO',   'yourgmail@gmail.com');  // <<< Replace with YOUR Gmail to receive alerts
date_default_timezone_set('Asia/Karachi');
// ==================================

$login_error = '';
$just_logged_in = false;

// Simple CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function send_login_email($who, $when, $ip, $ua) {
    // Basic mail headers
    $subject = "Admin Panel Login Alert";
    $body  = "Assalam-o-Alaikum 👋\n\n";
    $body .= "Aap ke admin panel par abhi login hua hai.\n\n";
    $body .= "Email: {$who}\n";
    $body .= "Time:  {$when}\n";
    $body .= "IP:    {$ip}\n";
    $body .= "Agent: {$ua}\n\n";
    $body .= "— Automated Security Alert";

    $headers  = "From: no-reply@localhost\r\n";
    $headers .= "Reply-To: no-reply@localhost\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Try to send email (may require SMTP/sendmail setup on local)
    @mail(NOTIFY_TO, $subject, $body, $headers);

    // Always also log to a file as a backup
    $logLine = "[".$when."] {$who} | IP: {$ip} | UA: {$ua}\n";
    @file_put_contents(__DIR__ . "/login_log.txt", $logLine, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $email = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass  = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $csrf  = $_POST['csrf'] ?? '';

    if (!hash_equals($_SESSION['csrf'], $csrf)) {
        $login_error = 'Invalid request. Please refresh and try again.';
    } else {
        if (strcasecmp($email, ADMIN_EMAIL) === 0 && $pass === ADMIN_PASS) {
            // Valid login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = ADMIN_EMAIL;

            // Send notification
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $when = date('Y-m-d H:i:s');
            send_login_email($email, $when, $ip, $ua);

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $login_error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        
        .login-container {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #667eea 50%, #764ba2 75%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1), 
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                        0 0 50px rgba(255, 255, 255, 0.1);
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }
        .slide-in { animation: slideIn 0.6s ease-out 0.2s both; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-30px);} to { opacity: 1; transform: translateX(0);} }
        
        .floating-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
        .particle { position: absolute; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: float 20s infinite linear; }
        .particle:nth-child(1) { width: 4px; height: 4px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 6px; height: 6px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 3px; height: 3px; left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 5px; height: 5px; left: 40%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 4px; height: 4px; left: 50%; animation-delay: 8s; }
        .particle:nth-child(6) { width: 7px; height: 7px; left: 60%; animation-delay: 10s; }
        .particle:nth-child(7) { width: 3px; height: 3px; left: 70%; animation-delay: 12s; }
        .particle:nth-child(8) { width: 5px; height: 5px; left: 80%; animation-delay: 14s; }
        .particle:nth-child(9) { width: 4px; height: 4px; left: 90%; animation-delay: 16s; }
        .particle:nth-child(10) { width: 6px; height: 6px; left: 15%; animation-delay: 18s; }
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        
        .luxury-glow { position: relative; }
        .luxury-glow::before {
            content: '';
            position: absolute; top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffeaa7, #dda0dd);
            background-size: 400% 400%;
            border-radius: inherit; z-index: -1;
            animation: gradientRotate 3s ease infinite; opacity: 0.7;
        }
        @keyframes gradientRotate {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>
    <div class="login-container flex items-center justify-center p-4">
        <!-- Floating Particles Background -->
        <div class="floating-particles">
            <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
            <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
        </div>
        
        <div class="glass-effect luxury-glow rounded-2xl p-8 w-full max-w-md fade-in" style="position: relative; z-index: 10;">
            <!-- Logo/Header Section -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-white/30 to-white/10 rounded-full flex items-center justify-center mx-auto mb-4 shadow-2xl border border-white/20 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 animate-pulse"></div>
                    <svg class="w-10 h-10 text-white relative z-10 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2 drop-shadow-lg bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Admin Panel</h1>
                <p class="text-white text-opacity-90 text-sm font-medium">Enter your credentials to access the luxury admin panel</p>
            </div>

            <!-- Demo Notice -->
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6 text-sm">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Demo:</strong> This is a sample login page</span>
                </div>
            </div>

            <!-- Error -->
            <?php if (!empty($login_error)): ?>
                <div class="mb-4 rounded-lg border border-red-400 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form class="space-y-6" method="POST" action="">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                
                <div class="slide-in">
                    <label for="username" class="block text-sm font-medium text-white mb-2">
                        Username or Email
                    </label>
                    <input 
                        type="email" 
                        id="username" 
                        name="username"
                        required
                        class="input-focus w-full px-5 py-4 bg-white bg-opacity-15 border-2 border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-60 focus:border-opacity-60 transition-all duration-500 shadow-lg backdrop-blur-sm"
                        placeholder="admin@gmail.com"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    >
                </div>

                <div class="slide-in">
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            required
                            class="input-focus w-full px-5 py-4 bg-white bg-opacity-15 border-2 border-white border-opacity-30 rounded-xl text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-60 focus:border-opacity-60 transition-all duration-500 shadow-lg backdrop-blur-sm"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white text-opacity-70 hover:text-opacity-100 transition-all duration-200"
                        >
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between slide-in">
                    <label class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 text-white bg-white bg-opacity-20 border-white border-opacity-30 rounded focus:ring-white focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-white text-opacity-80">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-white text-opacity-80 hover:text-opacity-100 transition-all duration-200">
                        Forgot password?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="login-btn w-full bg-gradient-to-r from-white/25 to-white/15 hover:from-white/35 hover:to-white/25 text-white font-bold py-4 px-6 rounded-xl transition-all duration-500 slide-in shadow-2xl border border-white/30 hover:border-white/50 backdrop-blur-sm relative overflow-hidden group"
                >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 group-hover:animate-pulse"></div>
                    <span class="flex items-center justify-center relative z-10">
                        <svg class="w-5 h-5 mr-3 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-lg font-bold tracking-wide">LOGIN</span>
                    </span>
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center mt-8 slide-in">
                <p class="text-white text-opacity-60 text-xs">
                    © 2024 Admin Panel. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Small focus animation (kept from your original)
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (this.parentElement) this.parentElement.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    if (this.parentElement) this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>
