<?php
// ==========================
// CONFIGURATION FILE
// ==========================
include("config.php");

// ==========================
// SESSION HANDLING (safe)
// ==========================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeFlow - Premium Blood Donation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body.locked { overflow: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); }
        .glass-effect { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .emerald-btn { background: linear-gradient(45deg, #059669, #047857); }
        .pulse-emerald { animation: pulse-emerald 2s infinite; }
        @keyframes pulse-emerald { 0%,100%{ box-shadow:0 0 0 0 rgba(5,150,105,0.7);} 50%{ box-shadow:0 0 0 10px rgba(5,150,105,0);} }
        .donor-card { transition: all 0.3s ease; } .donor-card:hover { transform: translateY(-5px); box-shadow:0 20px 40px rgba(0,0,0,0.3);}
        .chat-bubble { animation: slideIn 0.3s ease; } @keyframes slideIn { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
        .blood-type { background: linear-gradient(45deg, #dc2626, #b91c1c); }
        .status-online { background: #10b981; animation: pulse 2s infinite; }
        .status-offline { background: #6b7280; }
        .nav-link { position: relative; } .nav-link::after { content:''; position:absolute; width:0; height:2px; bottom:-5px; left:0; background:#059669; transition:width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #000000 0%, #1a0000 50%, #330000 100%); }
        .blood-red { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); }
        .glass-effect { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .floating-animation { animation: float 6s ease-in-out infinite; } @keyframes float {0%,100%{transform:translateY(0);}50%{transform:translateY(-20px);} }
        .pulse-red { animation: pulseRed 2s infinite; } @keyframes pulseRed {0%,100%{ box-shadow:0 0 0 0 rgba(220,38,38,0.7);}70%{ box-shadow:0 0 0 10px rgba(220,38,38,0);} }
        .blood-cell { position:absolute; width:8px; height:8px; background:#dc2626; border-radius:50%; animation:bloodFlow 15s linear infinite; }
        @keyframes bloodFlow {0%{transform:translateX(-100px) translateY(0); opacity:0;}10%,90%{opacity:1;}100%{transform:translateX(calc(100vw + 100px)) translateY(-50px); opacity:0;} }
        .hover-lift { transition: all 0.3s ease; } .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(220,38,38,0.3); }
        .chat-message { animation: slideIn 0.3s ease-out; } @keyframes slideIn {from{transform:translateX(-20px);opacity:0;}to{transform:translateX(0);opacity:1;}}
        .typing-indicator { animation: typing 1.5s infinite; } @keyframes typing {0%,60%,100%{opacity:0.3;}30%{opacity:1;} }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 blood-red rounded-full flex items-center justify-center pulse-red">
                        <i class="fas fa-tint text-white"></i>
                    </div>
                    <span class="text-2xl font-bold">LifeFlow</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    
                    
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <!-- Agar login hua hai -->
                        <a href="profile_page.php" class="nav-link hover:text-red-400 transition-colors">Profile</a>
                        <a href="dashboard.php" class="nav-link hover:text-red-400 transition-colors">live Chat</a>
                        <form action="logout.php" method="post" class="inline">
                            <button type="submit" class="blood-red px-6 py-2 rounded-full hover:opacity-90 transition-opacity">Logout</button>
                        </form>
                    <?php else: ?>
                        <!-- Agar login nahi hai -->
                         <a href="index.php" class="nav-link hover:text-red-400 transition-colors">Home</a>
                        <a href="register.php" class="nav-link hover:text-red-400 transition-colors">Register</a>
                        <a href="login.php" class="nav-link hover:text-red-400 transition-colors">Login</a>
                        <a href="aboutus.php" class="nav-link hover:text-red-400 transition-colors">About us</a>
                    <?php endif; ?>
                </div>
                
                <button class="md:hidden text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden mt-4 space-y-4 hidden">
                <a href="index.php" class="block hover:text-red-400 transition-colors">Home</a>
                
                <?php if (isset($_SESSION['user_name'])): ?>
                    <a href="profile_page.php" class="block hover:text-red-400 transition-colors">Profile</a>
                    <form action="logout.php" method="post" class="inline">
                        <button type="submit" class="blood-red px-6 py-2 rounded-full hover:opacity-90 transition-opacity">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="register.php" class="block hover:text-red-400 transition-colors">Register</a>
                    <a href="login.php" class="block hover:text-red-400 transition-colors">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
