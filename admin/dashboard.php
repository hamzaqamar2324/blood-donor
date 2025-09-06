<?php
include('header.php');

// Users ka count nikalna
$sql = "SELECT COUNT(*) AS total FROM registration";
$result = $connection->query($sql);
$totalUsers = 0;

if ($result && $row = $result->fetch_assoc()) {
    $totalUsers = $row['total'];
}
?>
<!-- Dashboard Section -->
        <div id="dashboard" class="content-section active p-6">
            <!-- Real-time Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white p-6 rounded-xl card-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Users</p>
                            <p class="text-3xl font-bold text-gray-800" id="totalUsers"><?= number_format($totalUsers) ?></p>
                            <p class="text-green-500 text-sm">+12% from last month</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-6 rounded-xl card-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Requests</p>
                            <p class="text-3xl font-bold text-gray-800" id="activeRequests">156</p>
                            <p class="text-red-500 text-sm">8 urgent requests</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-medical text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-6 rounded-xl card-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Today's Donations</p>
                            <p class="text-3xl font-bold text-gray-800" id="todayDonations">23</p>
                            <p class="text-green-500 text-sm">+15% from yesterday</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-donate text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white p-6 rounded-xl card-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">System Health</p>
                            <p class="text-3xl font-bold text-green-600">99.9%</p>
                            <p class="text-green-500 text-sm">All systems operational</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heartbeat text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            Real-time Activity Feed
            <!-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl card-shadow">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Live Activity Feed</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full notification-dot"></div>
                                <span class="text-sm text-gray-500">Live</span>
                            </div>
                        </div>
                        <div class="p-6 max-h-96 overflow-y-auto" id="activityFeed"> -->
                            <!-- Activity items will be populated by JavaScript -->
                        <!-- </div>
                    </div>
                </div>
                 -->
                <div>
                    <div class="bg-white rounded-xl card-shadow p-6 mb-6">
                        <h4 class="font-semibold mb-4">Quick Actions</h4>
                        <div class="space-y-3">
                            <button class="w-full text-left p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" onclick="openModal('emergencyRequestModal')">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                Emergency Blood Request
                            </button>
                            <button class="w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" onclick="openModal('addUserModal')">
                                <i class="fas fa-user-plus text-blue-500 mr-3"></i>
                                Add New User
                            </button>
                            <button class="w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors" onclick="openModal('inventoryUpdateModal')">
                                <i class="fas fa-plus text-green-500 mr-3"></i>
                                Update Inventory
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <h4 class="font-semibold mb-4">System Status</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Database</span>
                                <span class="text-green-500 text-sm">●</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">API Services</span>
                                <span class="text-green-500 text-sm">●</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Notifications</span>
                                <span class="text-green-500 text-sm">●</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Chat System</span>
                                <span class="text-green-500 text-sm">●</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Charts -->
            <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl card-shadow">
                    <h3 class="text-lg font-semibold mb-4">Blood Type Distribution</h3>
                    <canvas id="bloodTypeChart" height="300"></canvas>
                </div>
                
                <div class="bg-white p-6 rounded-xl card-shadow">
                    <h3 class="text-lg font-semibold mb-4">Monthly Trends</h3>
                    <canvas id="trendsChart" height="300"></canvas>
                </div>
            </div>
        </div> -->

        <!-- Dashboard end -->

        <?php
include('footer.php');
?>