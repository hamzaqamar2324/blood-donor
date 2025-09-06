<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Profile - BloodConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .blood-red {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
}

.pulse-red {
    animation: pulseRed 2s infinite;
}

@keyframes pulseRed {
    0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
}
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .premium-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .blood-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        }
        
        .premium-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 32px 64px -12px rgba(0, 0, 0, 0.35);
        }
        
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
            background: #e5e7eb;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .toggle-switch.active {
            background: linear-gradient(135deg, #dc2626, #991b1b);
        }
        
        .toggle-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .toggle-switch.active .toggle-slider {
            transform: translateX(30px);
        }
        
        .tab-button {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border-radius: 2px;
        }
        
        .profile-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            padding: 4px;
        }
        
        .profile-image {
            border-radius: 50%;
            background: white;
            padding: 4px;
        }
        
        .donation-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-completed {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .badge-upcoming {
            background: rgba(251, 191, 36, 0.1);
            color: #d97706;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .premium-input {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .premium-input:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-red-50 to-slate-100">
    <!-- Premium Header -->
    <div class="premium-gradient text-white py-8 premium-shadow">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between">
                <!-- Left Side: Back Arrow and Title -->
                <div class="flex items-center space-x-4">
                    
                    <!-- Back Arrow -->
                    <a href="dashboard.php" class="text-white text-xl hover:text-red-200 transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>

                    <!-- Drop Icon -->
                    <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center animate-ping-slow">
                        <i class="fas fa-tint text-white"></i>
                    </div>

                    <!-- Logo and Title -->
                    
                    <h1 class="text-3xl font-bold">BloodConnect Premium</h1>
                </div>

                <!-- Right Side: Membership Info -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm opacity-90">Premium Member</p>
                        <p class="font-semibold">Since 2022</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
