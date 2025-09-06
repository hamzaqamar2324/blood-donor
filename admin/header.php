<?php
include('config.php');
?>
     <!-- header----- start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeFlow Advanced Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .status-online { background: linear-gradient(45deg, #10b981, #34d399); }
        .status-offline { background: linear-gradient(45deg, #6b7280, #9ca3af); }
        .status-typing { background: linear-gradient(45deg, #3b82f6, #60a5fa); }
        .priority-high { border-left: 4px solid #ef4444; }
        .priority-medium { border-left: 4px solid #f59e0b; }
        .priority-low { border-left: 4px solid #10b981; }
        .chat-bubble { max-height: 300px; overflow-y: auto; }
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .sidebar-item:hover { background: rgba(255,255,255,0.1); }
        .active-item { background: rgba(255,255,255,0.2); border-right: 4px solid #fff; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal.show { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 12px; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .status-active { background: #10b981; color: white; }
        .status-inactive { background: #ef4444; color: white; }
        .status-blocked { background: #f59e0b; color: white; }
        .role-admin { background: #8b5cf6; color: white; }
        .role-donor { background: #06b6d4; color: white; }
        .role-recipient { background: #f97316; color: white; }
        .content-section { display: none; }
        .content-section.active { display: block; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .chat-container { height: 400px; overflow-y: auto; }
        .message-bubble { max-width: 70%; }
        .message-sent { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .message-received { background: #f3f4f6; }
        .notification-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .priority-high { border-left: 4px solid #ef4444; }
        .priority-medium { border-left: 4px solid #f59e0b; }
        .priority-low { border-left: 4px solid #10b981; }
        .blood-type-card { transition: all 0.3s ease; }
        .blood-type-card:hover { transform: scale(1.05); }
        .sidebar { transition: transform 0.3s ease; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-50 bg-white p-2 rounded-lg shadow-lg">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed inset-y-0 left-0 w-64 gradient-bg text-white z-40">
        <div class="flex items-center justify-center h-16 border-b border-white/20">
            <i class="fas fa-heartbeat text-2xl mr-3"></i>
            <h1 class="text-xl font-bold">LifeFlow Admin</h1>
        </div>
        
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="dashboard.php" class="sidebar-item active-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('dashboard')">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="user_management.php" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('users')">
                    <i class="fas fa-users mr-3"></i>
                    User Management
                    <span class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full">2,847</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('blood-requests')">
                    <i class="fas fa-hand-holding-medical mr-3"></i>
                    Blood Requests
                    <span class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full notification-dot">8</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('inventory')">
                    <i class="fas fa-warehouse mr-3"></i>
                    Blood Inventory
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('donations')">
                    <i class="fas fa-donate mr-3"></i>
                    Donations
                    <span class="ml-auto bg-green-500 text-xs px-2 py-1 rounded-full">156</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('hospitals')">
                    <i class="fas fa-hospital mr-3"></i>
                    Hospitals
                    <span class="ml-auto bg-blue-500 text-xs px-2 py-1 rounded-full">89</span>
                </a>
                <a href="chat.php" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('chat')">
                    <i class="fas fa-comments mr-3"></i>
                    Chat Management
                    <span class="ml-auto bg-yellow-500 text-xs px-2 py-1 rounded-full notification-dot">12</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('notifications')">
                    <i class="fas fa-bell mr-3"></i>
                    Notifications
                    <span class="ml-auto bg-purple-500 text-xs px-2 py-1 rounded-full">24</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('analytics')">
                    <i class="fas fa-chart-line mr-3"></i>
                    Advanced Analytics
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('reports')">
                    <i class="fas fa-file-alt mr-3"></i>
                    Reports
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('security')">
                    <i class="fas fa-shield-alt mr-3"></i>
                    Security & Logs
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-colors" onclick="showSection('settings')">
                    <i class="fas fa-cog mr-3"></i>
                    System Settings
                </a>
            </div>
        </nav>
        
      <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md">
       Logout
       </a>
    </div>

    <!-- Main Content -->
    <div class="main-content ml-0 md:ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <h2 class="text-2xl font-semibold text-gray-800" id="page-title">Dashboard</h2>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <!-- Quick Actions -->
                    <button class="p-2 text-gray-500 hover:text-gray-700 relative" onclick="openModal('quickActionsModal')">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 text-gray-500 hover:text-gray-700" onclick="openModal('notificationsModal')">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center notification-dot">3</span>
                        </button>
                    </div>
                    
                    <!-- Profile -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100" onclick="toggleDropdown('profileDropdown')">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                            <i class="fas fa-chevron-down text-sm text-gray-500"></i>
                        </button>
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account Security</a>
                            <hr class="my-1">
                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

     <!-- header-----end---->

   
 <script>
        // Chat Modal Functions
        function openChatModal() {
            document.getElementById('chatModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeChatModal() {
            document.getElementById('chatModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // New Chat Modal Functions
        function openNewChatModal() {
            document.getElementById('newChatModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeNewChatModal() {
            document.getElementById('newChatModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Announcement Modal Functions
        function openAnnouncementModal() {
            document.getElementById('announcementModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Auto-Reply Modal Functions
        function openAutoReplyModal() {
            document.getElementById('autoReplyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAutoReplyModal() {
            document.getElementById('autoReplyModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Block User Modal Functions
        function openBlockUserModal() {
            document.getElementById('blockUserModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBlockUserModal() {
            document.getElementById('blockUserModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Add click event to all "View Chat" buttons
        document.addEventListener('DOMContentLoaded', function() {
            const viewChatButtons = document.querySelectorAll('button');
            viewChatButtons.forEach(button => {
                if (button.textContent.includes('View Chat')) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        openChatModal();
                    });
                }
            });
        });

        // Close modals when clicking outside
        document.getElementById('chatModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeChatModal();
            }
        });

        document.getElementById('newChatModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewChatModal();
            }
        });

        document.getElementById('announcementModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAnnouncementModal();
            }
        });

        document.getElementById('autoReplyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAutoReplyModal();
            }
        });

        document.getElementById('blockUserModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBlockUserModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open modal
                closeChatModal();
                closeNewChatModal();
                closeAnnouncementModal();
                closeAutoReplyModal();
                closeBlockUserModal();
            }
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96f0650a5264a75a',t:'MTc1NTE3MzkwNS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
</body>

</html>

