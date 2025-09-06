<?php
include('header.php');

// Fetch all users from registration table
$activeChatsQuery = "SELECT user_id, user_name, contact FROM registration";
$activeChatsResult = mysqli_query($connection, $activeChatsQuery);

// Database connection
$conn = new mysqli("localhost", "root", "", "donors");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Online users count
$onlineUsers = $connection->query("SELECT COUNT(*) as total FROM user_activity WHERE status = 'online'")->fetch_assoc()['total'];

// Active chats count
$activeChats = $connection->query("SELECT COUNT(*) as total FROM user_activity WHERE is_chatting = 1")->fetch_assoc()['total'];

// Blocked users count
$blockedUsers = $connection->query("SELECT COUNT(*) as total FROM user_activity WHERE status = 'blocked'")->fetch_assoc()['total'];

$connection->close();
?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Real-Time Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Online Users -->
    <div class="bg-white rounded-xl card-shadow p-6 transition transform hover:scale-105 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Online Users</p>
                <p class="text-3xl font-bold text-green-600"><?= $onlineUsers ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-circle text-green-600 text-xl animate-pulse"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Chats -->
    <div class="bg-white rounded-xl card-shadow p-6 transition transform hover:scale-105 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Chats</p>
                <p class="text-3xl font-bold text-blue-600"><?= $activeChats ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-comment-dots text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Blocked Users -->
    <div class="bg-white rounded-xl card-shadow p-6 transition transform hover:scale-105 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Blocked</p>
                <p class="text-3xl font-bold text-red-600"><?= $blockedUsers ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-ban text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

        <!-- Chat Management Controls -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search chats, users..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64">
                    </div>
                    
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Status</option>
                        <option>Online</option>
                        <option>Typing</option>
                        <option>Away</option>
                        <option>In Queue</option>
                        <option>Blocked</option>
                    </select>
                    
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Priority</option>
                        <option>High Priority</option>
                        <option>Medium Priority</option>
                        <option>Low Priority</option>
                    </select>
                </div>
                
                <div class="flex space-x-3">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-broadcast-tower mr-2"></i>Broadcast
                    </button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Live Chat Sessions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Active Chats List -->
            
<!-- Active Chats List -->
<div class="lg:col-span-2">
    <div class="bg-white rounded-xl card-shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Active Chat Sessions</h2>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></div>
                <span class="text-sm text-gray-500">Live Updates</span>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            <?php while($user = mysqli_fetch_assoc($activeChatsResult)) { 
                $initials = strtoupper(substr($user['user_name'], 0, 2));
            ?>
            <div class="p-6 priority-low hover:bg-gray-50 cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <div class="relative">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-teal-400 flex items-center justify-center text-white font-semibold">
                                <?= $initials ?>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-sm font-semibold text-gray-900"><?= $user['user_name'] ?></h3>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Low Priority</span>
                                <span class="status-online text-white px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-circle mr-1 text-xs"></i>Online
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">General inquiry about services</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span><i class="fas fa-clock mr-1"></i>Started just now</span>
                                <span><i class="fas fa-comment mr-1"></i>0 messages</span>
                                <span><i class="fas fa-user-clock mr-1"></i>Response time: 0 sec</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                            <i class="fas fa-eye mr-1"></i>View Chat
                        </button>
                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                            <i class="fas fa-check mr-1"></i>Resolve
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
            <!-- Chat Management Panel -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <button onclick="openNewChatModal()" class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                            <span class="text-gray-900">Start New Chat</span>
                        </button>
                        <button onclick="openAnnouncementModal()" class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-broadcast-tower text-blue-600 mr-3"></i>
                            <span class="text-gray-900">Send Announcement</span>
                        </button>
                        <button onclick="openAutoReplyModal()" class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-robot text-purple-600 mr-3"></i>
                            <span class="text-gray-900">Auto-Reply Settings</span>
                        </button>
                        <button onclick="openBlockUserModal()" class="w-full text-left px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                            <i class="fas fa-ban text-orange-600 mr-3"></i>
                            <span class="text-gray-900">Block/Unblock Users</span>
                        </button>
                    </div>
                </div>

                <!-- Live Statistics -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chart-pulse text-green-500 mr-2"></i>Live Statistics
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Messages/Hour</span>
                            <span class="text-lg font-semibold text-blue-600">156</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Avg Response Time</span>
                            <span class="text-lg font-semibold text-green-600">1.2 min</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Resolution Rate</span>
                            <span class="text-lg font-semibold text-purple-600">87%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Customer Satisfaction</span>
                            <span class="text-lg font-semibold text-yellow-600">4.8/5</span>
                        </div>
                    </div>
                </div>


    <!-- Start New Chat Modal -->
    <div id="newChatModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Start New Chat</h3>
                    <button onclick="closeNewChatModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User Email/Phone</label>
                            <input type="text" placeholder="Enter email or phone number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                            <select class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option>Low Priority</option>
                                <option>Medium Priority</option>
                                <option>High Priority</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Initial Message</label>
                            <textarea placeholder="Type your opening message..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" rows="3"></textarea>
                        </div>
                        <div class="flex space-x-3 pt-4">
                            <button type="button" onclick="closeNewChatModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Start Chat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Announcement Modal -->
    <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Send Announcement</h3>
                    <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Send To</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="sendTo" value="all" class="mr-2" checked>
                                    <span>All Active Users (89 users)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="sendTo" value="online" class="mr-2">
                                    <span>Online Users Only (34 users)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="sendTo" value="specific" class="mr-2">
                                    <span>Specific Users</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message Type</label>
                            <select class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option>General Information</option>
                                <option>System Maintenance</option>
                                <option>Important Update</option>
                                <option>Emergency Alert</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Announcement Message</label>
                            <textarea placeholder="Type your announcement here..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" rows="4"></textarea>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Preview:</h4>
                            <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="fas fa-broadcast-tower text-blue-600"></i>
                                    <span class="font-medium text-blue-900">System Announcement</span>
                                </div>
                                <p class="text-gray-700 text-sm">Your message will appear here...</p>
                            </div>
                        </div>
                        <div class="flex space-x-3 pt-4">
                            <button type="button" onclick="closeAnnouncementModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-broadcast-tower mr-2"></i>Send Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-Reply Settings Modal -->
    <div id="autoReplyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Auto-Reply Settings</h3>
                    <button onclick="closeAutoReplyModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="space-y-6">
                        <!-- General Settings -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">General Settings</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Enable Auto-Reply</span>
                                    <div class="w-12 h-6 bg-green-500 rounded-full relative cursor-pointer">
                                        <div class="w-5 h-5 bg-white rounded-full absolute right-0.5 top-0.5"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Reply Delay (seconds)</span>
                                    <input type="number" value="2" class="w-20 p-2 border border-gray-300 rounded text-center">
                                </div>
                            </div>
                        </div>

                        <!-- Welcome Message -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Welcome Message</h4>
                            <textarea placeholder="Enter welcome message for new users..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" rows="3">Hello! Welcome to our support chat. How can I help you today?</textarea>
                        </div>

                        <!-- Quick Responses -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Quick Responses</h4>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <input type="text" placeholder="Trigger keyword" value="hello" class="flex-1 p-2 border border-gray-300 rounded">
                                    <input type="text" placeholder="Auto response" value="Hi there! How can I assist you?" class="flex-2 p-2 border border-gray-300 rounded">
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="text" placeholder="Trigger keyword" value="price" class="flex-1 p-2 border border-gray-300 rounded">
                                    <input type="text" placeholder="Auto response" value="Let me get you our pricing information..." class="flex-2 p-2 border border-gray-300 rounded">
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <button class="w-full p-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-blue-500 hover:text-blue-500">
                                    <i class="fas fa-plus mr-2"></i>Add New Response
                                </button>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Business Hours Auto-Reply</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Enable After Hours Message</span>
                                    <div class="w-12 h-6 bg-green-500 rounded-full relative cursor-pointer">
                                        <div class="w-5 h-5 bg-white rounded-full absolute right-0.5 top-0.5"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                                        <input type="time" value="09:00" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">End Time</label>
                                        <input type="time" value="18:00" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                </div>
                                <textarea placeholder="After hours message..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" rows="2">Thank you for contacting us! Our support team is currently offline. We'll respond to your message during business hours (9 AM - 6 PM).</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3 p-6 border-t border-gray-200">
                    <button type="button" onclick="closeAutoReplyModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Block/Unblock Users Modal -->
    <div id="blockUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Block/Unblock Users</h3>
                    <button onclick="closeBlockUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <!-- Search and Add User -->
                    <div class="mb-6">
                        <div class="flex space-x-4">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" placeholder="Search users by email, phone, or name..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-ban mr-2"></i>Block User
                            </button>
                        </div>
                    </div>

                    <!-- Blocked Users List -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-4">Currently Blocked Users (3)</h4>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-red-400 to-pink-400 flex items-center justify-center text-white font-semibold">
                                        SP
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900">Spam User</h5>
                                        <p class="text-sm text-gray-600">spam.user@email.com</p>
                                        <p class="text-xs text-red-600">Blocked: 2 days ago - Reason: Inappropriate behavior</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-unlock mr-1"></i>Unblock
                                    </button>
                                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-eye mr-1"></i>View History
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-400 to-red-400 flex items-center justify-center text-white font-semibold">
                                        AB
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900">Abusive User</h5>
                                        <p class="text-sm text-gray-600">+91-9876543210</p>
                                        <p class="text-xs text-red-600">Blocked: 1 week ago - Reason: Abusive language</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-unlock mr-1"></i>Unblock
                                    </button>
                                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-eye mr-1"></i>View History
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-gray-400 to-gray-600 flex items-center justify-center text-white font-semibold">
                                        FR
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900">Fraud Account</h5>
                                        <p class="text-sm text-gray-600">fraud.account@fake.com</p>
                                        <p class="text-xs text-red-600">Blocked: 3 weeks ago - Reason: Fraudulent activity</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-unlock mr-1"></i>Unblock
                                    </button>
                                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                                        <i class="fas fa-eye mr-1"></i>View History
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Block Reasons -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-4">Common Block Reasons</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                                <span class="text-sm">Inappropriate Behavior</span>
                            </button>
                            <button class="p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-comment-slash text-red-500 mr-2"></i>
                                <span class="text-sm">Abusive Language</span>
                            </button>
                            <button class="p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-envelope text-purple-500 mr-2"></i>
                                <span class="text-sm">Spam Messages</span>
                            </button>
                            <button class="p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                                <span class="text-sm">Fraudulent Activity</span>
                            </button>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Block Statistics</h4>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-red-600">3</p>
                                <p class="text-sm text-gray-600">Currently Blocked</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-green-600">12</p>
                                <p class="text-sm text-gray-600">Unblocked This Month</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-blue-600">0.3%</p>
                                <p class="text-sm text-gray-600">Block Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3 p-6 border-t border-gray-200">
                    <button type="button" onclick="closeBlockUserModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                        Close
                    </button>
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat View Modal -->
    <div id="chatModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[80vh] flex flex-col">
                <!-- Chat Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-red-400 to-pink-400 flex items-center justify-center text-white font-semibold">
                                AK
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Amit Kumar</h3>
                            <div class="flex items-center space-x-2">
                                <span class="status-online text-white px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-circle mr-1 text-xs"></i>Online
                                </span>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">High Priority</span>
                                <span class="text-xs text-gray-500">amit.kumar@email.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-2 rounded-lg text-sm">
                            <i class="fas fa-user mr-1"></i>Profile
                        </button>
                        <button class="bg-orange-100 hover:bg-orange-200 text-orange-600 px-3 py-2 rounded-lg text-sm">
                            <i class="fas fa-pause mr-1"></i>Hold
                        </button>
                        <button class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-2 rounded-lg text-sm">
                            <i class="fas fa-times mr-1"></i>End Chat
                        </button>
                        <button onclick="closeChatModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                    <!-- System Message -->
                    <div class="flex justify-center">
                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-xs">
                            Chat started at 2:30 PM - Priority: High
                        </div>
                    </div>

                    <!-- User Message -->
                    <div class="flex items-start space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-red-400 to-pink-400 flex items-center justify-center text-white text-sm font-semibold">
                            AK
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-gray-900">Hello, I'm having trouble with my payment. The transaction failed but money was deducted from my account.</p>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500">2:30 PM</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-xs text-gray-500">Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Reply -->
                    <div class="flex items-start space-x-3 flex-row-reverse">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                            AD
                        </div>
                        <div class="flex-1">
                            <div class="bg-blue-600 text-white rounded-lg p-4 shadow-sm">
                                <p>Hi Amit! I understand your concern. Let me check your transaction details. Can you please provide your transaction ID?</p>
                            </div>
                            <div class="flex items-center space-x-2 mt-1 justify-end">
                                <span class="text-xs text-gray-500">Read</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-xs text-gray-500">2:31 PM</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Message -->
                    <div class="flex items-start space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-red-400 to-pink-400 flex items-center justify-center text-white text-sm font-semibold">
                            AK
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-gray-900">Sure, here is my transaction ID: TXN123456789</p>
                                <p class="text-gray-900 mt-2">Amount: ₹2,500</p>
                                <p class="text-gray-900">Time: 2:15 PM today</p>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500">2:32 PM</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-xs text-gray-500">Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Reply -->
                    <div class="flex items-start space-x-3 flex-row-reverse">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                            AD
                        </div>
                        <div class="flex-1">
                            <div class="bg-blue-600 text-white rounded-lg p-4 shadow-sm">
                                <p>Thank you for the details. I can see the transaction in our system. The payment was processed but there was a technical glitch. I'm initiating a refund for you right now.</p>
                            </div>
                            <div class="flex items-center space-x-2 mt-1 justify-end">
                                <span class="text-xs text-gray-500">Read</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-xs text-gray-500">2:33 PM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Typing Indicator -->
                    <div class="flex items-start space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-red-400 to-pink-400 flex items-center justify-center text-white text-sm font-semibold">
                            AK
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    <span class="text-gray-500 text-sm ml-2">Amit is typing...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input Area -->
                <div class="border-t border-gray-200 p-6">
                    <div class="flex items-center space-x-4">
                        <!-- Quick Replies -->
                        <div class="flex space-x-2">
                            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-clock mr-1"></i>Please wait
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check mr-1"></i>Issue resolved
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-phone mr-1"></i>Call you back
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-end space-x-4 mt-4">
                        <div class="flex-1">
                            <div class="relative">
                                <textarea 
                                    placeholder="Type your message here..." 
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    rows="3"
                                ></textarea>
                                <div class="absolute bottom-3 right-3 flex items-center space-x-2">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-smile"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg font-medium transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Send
                        </button>
                    </div>
                    
                    <!-- Chat Actions -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="fas fa-clock mr-1"></i>Response time: 45 seconds</span>
                            <span><i class="fas fa-comment mr-1"></i>12 messages</span>
                            <span><i class="fas fa-user-clock mr-1"></i>Chat duration: 5 minutes</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="bg-green-100 hover:bg-green-200 text-green-600 px-3 py-2 rounded-lg text-sm">
                                <i class="fas fa-check mr-1"></i>Mark Resolved
                            </button>
                            <button class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 px-3 py-2 rounded-lg text-sm">
                                <i class="fas fa-forward mr-1"></i>Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>