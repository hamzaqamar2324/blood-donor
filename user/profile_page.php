<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Bio + Contact update form
if (isset($_POST['save_profile'])) {
    $fullName = mysqli_real_escape_string($connection, $_POST['fullName']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $bloodGroup = mysqli_real_escape_string($connection, $_POST['bloodGroup']);
    $city = mysqli_real_escape_string($connection, $_POST['city']);
    $bio = mysqli_real_escape_string($connection, $_POST['bio']);

    $updateQuery = "UPDATE registration SET user_name='$fullName', contact='$phone', blood_type='$bloodGroup', city='$city', bio='$bio' WHERE user_id='$userId'";

    if (mysqli_query($connection, $updateQuery)) {
        echo "<script>alert('Profile updated successfully'); window.location.href='profile_page.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

// Personal info update form
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($connection, $_POST['user_name']);
    $email = mysqli_real_escape_string($connection, $_POST['user_email']);
    $contact = mysqli_real_escape_string($connection, $_POST['contact']);
    $blood = mysqli_real_escape_string($connection, $_POST['blood_type']);
    $city = mysqli_real_escape_string($connection, $_POST['city']);

    $updateQuery = "UPDATE registration SET 
        user_name = '$name', 
        user_email = '$email',
        contact = '$contact', 
        blood_type = '$blood',
        city = '$city' 
        WHERE user_id = '$userId'";

    if (mysqli_query($connection, $updateQuery)) {
        echo "<script>
            alert('Profile updated successfully');
            window.location.href='profile_page.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

// User data fetch
$query = "SELECT * FROM registration WHERE user_id='$userId'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);
$username = $user['user_name'];

include('profile_header.php');

$userId = $_SESSION['user_id'];

// Profile update
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($connection, $_POST['user_name'] ?? '');
    $email = mysqli_real_escape_string($connection, $_POST['user_email'] ?? '');
    $contact = mysqli_real_escape_string($connection, $_POST['contact'] ?? '');
    $blood = mysqli_real_escape_string($connection, $_POST['blood_type'] ?? '');
    $city = mysqli_real_escape_string($connection, $_POST['city'] ?? '');
    $bio = mysqli_real_escape_string($connection, $_POST['bio'] ?? '');

    $updateQuery = "UPDATE registration SET 
        user_name = '$name', 
        user_email = '$email',
        contact = '$contact', 
        blood_type = '$blood',
        city = '$city',
        bio = '$bio'
        WHERE user_id = '$userId'";

    if (mysqli_query($connection, $updateQuery)) {
        echo "<script>alert('Profile updated successfully'); window.location.href='profile_page.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

// Fetch user data
$query = "SELECT * FROM registration WHERE user_id='$userId'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Get current password from DB
    $query = "SELECT password FROM registration WHERE user_id = '$userId'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dbPassword = $row['password'];

        // Plain text password check
        if ($currentPassword == $dbPassword) {
            if ($newPassword === $confirmPassword) {
                $updateQuery = "UPDATE registration SET password = '$newPassword' WHERE user_id = '$userId'";
                if (mysqli_query($connection, $updateQuery)) {
                    echo "<p class='text-green-600 mt-4'>Password changed successfully!</p>";
                } else {
                    echo "<p class='text-red-600 mt-4'>Something went wrong while updating!</p>";
                }
            } else {
                echo "<p class='text-red-600 mt-4'>New passwords do not match!</p>";
            }
        } else {
            echo "<p class='text-red-600 mt-4'>Current password is incorrect!</p>";
        }
    } else {
        echo "<p class='text-red-600 mt-4'>User not found!</p>";
    }
}


?>



 <div class="max-w-6xl mx-auto px-6 py-8">
        <!-- Profile Header Card -->
        <div class="glass-effect rounded-3xl p-8 mb-8 hover-lift premium-shadow">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <!-- Profile Image -->
                <div class="profile-image-container w-32 h-32">
                    <div class="profile-image w-full h-full">
                        <img id="profileImg" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='35' r='20' fill='%23dc2626'/%3E%3Cpath d='M20 80 Q20 60 50 60 Q80 60 80 80 L80 90 L20 90 Z' fill='%23dc2626'/%3E%3C/svg%3E" alt="Profile" class="w-full h-full object-cover rounded-full">
                    </div>
                    <input type="file" id="imageUpload" class="hidden" accept="image/*">
                    <button onclick="document.getElementById('imageUpload').click()" class="absolute bottom-2 right-2 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                        <i class="fas fa-camera text-xs"></i>
                    </button>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo ($username);?></h2> 
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mb-4">
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                             <i class="fas fa-tint mr-2"></i><?= htmlspecialchars($user['blood_type']) ?>
                        </span>

                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Verified Donor
                        </span>
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            <i class="fas fa-award mr-2"></i>15 Donations
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 max-w-md">Passionate about saving lives through blood donation. Regular donor for over 3 years, committed to helping those in need.</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-gray-500">
                        <span><i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($user['city']) ?></span>
                        <span><i class="fas fa-envelope mr-1"></i><?= htmlspecialchars($user['user_email']) ?> ✅</span>
                        <span><i class="fas fa-phone mr-1"></i><?= htmlspecialchars($user['contact']) ?></span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-white bg-opacity-60 rounded-2xl p-4">
                        <div class="text-2xl font-bold text-red-600">15</div>
                        <div class="text-sm text-gray-600">Total Donations</div>
                    </div>
                    <div class="bg-white bg-opacity-60 rounded-2xl p-4">
                        <div class="text-2xl font-bold text-green-600">45</div>
                        <div class="text-sm text-gray-600">Lives Saved</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="glass-effect rounded-2xl p-2 mb-8">
            <div class="flex flex-wrap gap-2">
                <button class="tab-button active flex-1 md:flex-none px-6 py-3 text-red-600 font-medium rounded-xl bg-white bg-opacity-60" onclick="switchTab('profile')">
                    <i class="fas fa-user mr-2"></i>Profile
                </button>
                <button class="tab-button flex-1 md:flex-none px-6 py-3 text-gray-600 font-medium rounded-xl hover:bg-white hover:bg-opacity-40 transition-all" onclick="switchTab('security')">
                    <i class="fas fa-shield-alt mr-2"></i>Security
                </button>
                <button class="tab-button flex-1 md:flex-none px-6 py-3 text-gray-600 font-medium rounded-xl hover:bg-white hover:bg-opacity-40 transition-all" onclick="switchTab('history')">
                    <i class="fas fa-history mr-2"></i>History
                </button>
                <button class="tab-button flex-1 md:flex-none px-6 py-3 text-gray-600 font-medium rounded-xl hover:bg-white hover:bg-opacity-40 transition-all" onclick="switchTab('preferences')">
                    <i class="fas fa-cog mr-2"></i>Preferences
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div id="profileTab" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Personal Information</h3>
                        <button id="editBtn" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors" onclick="toggleEdit()">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                    
                    <form action="profile_page.php" method="post">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="fullName" name="user_name" value="<?= $user['user_name'] ?>" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="user_email" value="<?= $user['user_email'] ?>" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" >
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="contact" id="phone" value="<?= $user['contact'] ?>" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                                <select id="bloodGroup" name="blood_type" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none">
                                  <option value="O+" <?php if ($user['blood_type'] == 'O+') echo 'selected'; ?>>O+</option>
                                            <option value="O-" <?php if ($user['blood_type'] == 'O-') echo 'selected'; ?>>O-</option>
                                            <option value="A+" <?php if ($user['blood_type'] == 'A+') echo 'selected'; ?>>A+</option>
                                            <option value="A-" <?php if ($user['blood_type'] == 'A-') echo 'selected'; ?>>A-</option>
                                            <option value="B+" <?php if ($user['blood_type'] == 'B+') echo 'selected'; ?>>B+</option>
                                            <option value="B-" <?php if ($user['blood_type'] == 'B-') echo 'selected'; ?>>B-</option>
                                            <option value="AB+" <?php if ($user['blood_type'] == 'AB+') echo 'selected'; ?>>AB+</option>
                                            <option value="AB-" <?php if ($user['blood_type'] == 'AB-') echo 'selected'; ?>>AB-</option>
                                </select>
                            </div>
                        </div>
            
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                           <select  id="city" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" name="city" >
                                    <option value="" <?php if ($user['city'] == '') echo 'selected'; ?>></option>
                                    <option value="Karachi" <?php if ($user['city'] == 'Karachi') echo 'selected'; ?>>Karachi</option>
                                    <option value="Lahore" <?php if ($user['city'] == 'Lahore') echo 'selected'; ?>>Lahore</option>
                                    <option value="Islamabad" <?php if ($user['city'] == 'Islamabad') echo 'selected'; ?>>Islamabad</option>
                                    <option value="Rawalpindi" <?php if ($user['city'] == 'Rawalpindi') echo 'selected'; ?>>Rawalpindi</option>
                                    <option value="Sialkot" <?php if ($user['city'] == 'Sialkot') echo 'selected'; ?>>Sialkot</option>
                                    <option value="Hydarabad" <?php if ($user['city'] == 'Hydarabad') echo 'selected'; ?>>Hydarabad</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio (150 characters max)</label>
                            <textarea id="bio" name="bio" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none resize-none" rows="3" maxlength="150" >Passionate about saving lives through blood donation. Regular donor for over 3 years, committed to helping those in need.</textarea>
                            <div class="text-right text-sm text-gray-500 mt-1">
                                <span id="bioCount">123</span>/150
                            </div>
                        </div>
                    </div>
                    
                    <div id="saveSection" class="hidden mt-6 flex gap-4">
                        <button  type="submit" name="update_profile" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors" >
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                        <button class="px-6 py-3 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors" onclick="cancelEdit()">
                            Cancel
                        </button>
                    </div>
                    </form>
                </div>

                <!-- Privacy & Visibility -->
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Privacy & Visibility</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Public Profile</h4>
                                <p class="text-sm text-gray-600">Allow others to see your profile in donor listings</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Contact Information</h4>
                                <p class="text-sm text-gray-600">Show phone number to verified users</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Donation History</h4>
                                <p class="text-sm text-gray-600">Display donation count publicly</p>
                            </div>
                            <div class="toggle-switch" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="securityTab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Password Change -->
                 <form action="" method="post">
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <h3 class="text-xl font-bold text-gray-800 mb-6" >Change Password</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"  >Current Password</label>
                            <input  type="password" name="current_password" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input  type="password" name="new_password" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none" required>
                        </div>
                        
                        <button class="w-full px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors" type="submit" name="change_password">
                            <i class="fas fa-key mr-2"></i>Update Password
                        </button>
                        </form>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Account Actions</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-200">
                            <h4 class="font-medium text-blue-800 mb-2">Two-Factor Authentication</h4>
                            <p class="text-sm text-blue-600 mb-3">Add an extra layer of security to your account</p>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                Enable 2FA
                            </button>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-200">
                            <h4 class="font-medium text-yellow-800 mb-2">Download Data</h4>
                            <p class="text-sm text-yellow-600 mb-3">Get a copy of all your account data</p>
                            <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                Request Data
                            </button>
                        </div>
                        
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-200">
                            <h4 class="font-medium text-red-800 mb-2">Deactivate Account</h4>
                            <p class="text-sm text-red-600 mb-3">Temporarily disable your account</p>
                            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm" onclick="confirmDeactivation()">
                                Deactivate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="historyTab" class="tab-content hidden">
            <div class="glass-effect rounded-3xl p-8 hover-lift">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Donation History</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-2 font-medium text-gray-700">Date</th>
                                <th class="text-left py-4 px-2 font-medium text-gray-700">Location</th>
                                <th class="text-left py-4 px-2 font-medium text-gray-700">Type</th>
                                <th class="text-left py-4 px-2 font-medium text-gray-700">Status</th>
                                <th class="text-left py-4 px-2 font-medium text-gray-700">Impact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-white hover:bg-opacity-40 transition-colors">
                                <td class="py-4 px-2">Dec 15, 2023</td>
                                <td class="py-4 px-2">NYC Blood Center</td>
                                <td class="py-4 px-2">Whole Blood</td>
                                <td class="py-4 px-2">
                                    <span class="donation-badge badge-completed">
                                        <i class="fas fa-check-circle mr-1"></i>Completed
                                    </span>
                                </td>
                                <td class="py-4 px-2 text-green-600 font-medium">3 lives saved</td>
                            </tr>
                            <tr class="hover:bg-white hover:bg-opacity-40 transition-colors">
                                <td class="py-4 px-2">Oct 20, 2023</td>
                                <td class="py-4 px-2">Mount Sinai Hospital</td>
                                <td class="py-4 px-2">Platelets</td>
                                <td class="py-4 px-2">
                                    <span class="donation-badge badge-completed">
                                        <i class="fas fa-check-circle mr-1"></i>Completed
                                    </span>
                                </td>
                                <td class="py-4 px-2 text-green-600 font-medium">2 lives saved</td>
                            </tr>
                            <tr class="hover:bg-white hover:bg-opacity-40 transition-colors">
                                <td class="py-4 px-2">Jan 10, 2024</td>
                                <td class="py-4 px-2">Red Cross Center</td>
                                <td class="py-4 px-2">Whole Blood</td>
                                <td class="py-4 px-2">
                                    <span class="donation-badge badge-upcoming">
                                        <i class="fas fa-clock mr-1"></i>Scheduled
                                    </span>
                                </td>
                                <td class="py-4 px-2 text-gray-500">Pending</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="preferencesTab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Notification Preferences -->
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Notification Preferences</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Email Notifications</h4>
                                <p class="text-sm text-gray-600">Receive updates via email</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">SMS Alerts</h4>
                                <p class="text-sm text-gray-600">Get text messages for urgent requests</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Browser Notifications</h4>
                                <p class="text-sm text-gray-600">Show desktop notifications</p>
                            </div>
                            <div class="toggle-switch" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Donation Reminders</h4>
                                <p class="text-sm text-gray-600">Remind me when I'm eligible to donate</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matching Preferences -->
                <div class="glass-effect rounded-3xl p-8 hover-lift">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Matching Preferences</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Travel Distance</label>
                            <select class="premium-input w-full px-4 py-3 rounded-xl border-0 outline-none">
                                <option value="5">Within 5 miles</option>
                                <option value="10">Within 10 miles</option>
                                <option value="25" selected>Within 25 miles</option>
                                <option value="50">Within 50 miles</option>
                                <option value="100">Within 100 miles</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Donation Times</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center p-3 bg-white bg-opacity-40 rounded-xl cursor-pointer hover:bg-opacity-60 transition-colors">
                                    <input type="checkbox" class="mr-3 text-red-600" checked>
                                    <span class="text-sm">Morning</span>
                                </label>
                                <label class="flex items-center p-3 bg-white bg-opacity-40 rounded-xl cursor-pointer hover:bg-opacity-60 transition-colors">
                                    <input type="checkbox" class="mr-3 text-red-600" checked>
                                    <span class="text-sm">Afternoon</span>
                                </label>
                                <label class="flex items-center p-3 bg-white bg-opacity-40 rounded-xl cursor-pointer hover:bg-opacity-60 transition-colors">
                                    <input type="checkbox" class="mr-3 text-red-600">
                                    <span class="text-sm">Evening</span>
                                </label>
                                <label class="flex items-center p-3 bg-white bg-opacity-40 rounded-xl cursor-pointer hover:bg-opacity-60 transition-colors">
                                    <input type="checkbox" class="mr-3 text-red-600">
                                    <span class="text-sm">Weekend</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-white bg-opacity-40 rounded-2xl">
                            <div>
                                <h4 class="font-medium text-gray-800">Emergency Donations</h4>
                                <p class="text-sm text-gray-600">Available for urgent requests</p>
                            </div>
                            <div class="toggle-switch active" onclick="toggleSwitch(this)">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div id="successMessage" class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300">
        <i class="fas fa-check-circle mr-2"></i>
        <span>Profile updated successfully!</span>
    </div>

    <script>
        let isEditing = false;
        let originalValues = {};

        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'text-red-600', 'bg-white', 'bg-opacity-60');
                btn.classList.add('text-gray-600');
            });
            
            // Show selected tab
            document.getElementById(tabName + 'Tab').classList.remove('hidden');
            
            // Add active class to clicked button
            event.target.classList.add('active', 'text-red-600', 'bg-white', 'bg-opacity-60');
            event.target.classList.remove('text-gray-600');
        }

        // Toggle switch functionality
        function toggleSwitch(element) {
            element.classList.toggle('active');
        }

        // Profile editing
        function toggleEdit() {
            const editBtn = document.getElementById('editBtn');
            const saveSection = document.getElementById('saveSection');
            const inputs = ['fullName', 'email', 'phone', 'bloodGroup', 'city', 'bio'];
            
            if (!isEditing) {
                // Store original values
                inputs.forEach(id => {
                    const element = document.getElementById(id);
                    originalValues[id] = element.value;
                    element.removeAttribute('readonly');
                    element.removeAttribute('disabled');
                });
                
                editBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Cancel';
                editBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
                saveSection.classList.remove('hidden');
                isEditing = true;
            } else {
                cancelEdit();
            }
        }

        function cancelEdit() {
            const editBtn = document.getElementById('editBtn');
            const saveSection = document.getElementById('saveSection');
            const inputs = ['fullName', 'email', 'phone', 'bloodGroup', 'city', 'bio'];
            
            // Restore original values
            inputs.forEach(id => {
                const element = document.getElementById(id);
                element.value = originalValues[id];
                element.setAttribute('readonly', '');
                if (element.tagName === 'SELECT') {
                    element.setAttribute('disabled', '');
                }
            });
            
            editBtn.innerHTML = '<i class="fas fa-edit mr-2"></i>Edit';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            saveSection.classList.add('hidden');
            isEditing = false;
        }

        function saveProfile() {
            const inputs = ['fullName', 'email', 'phone', 'bloodGroup', 'city', 'bio'];
            
            // Make inputs readonly again
            inputs.forEach(id => {
                const element = document.getElementById(id);
                element.setAttribute('readonly', '');
                if (element.tagName === 'SELECT') {
                    element.setAttribute('disabled', '');
                }
            });
            
            const editBtn = document.getElementById('editBtn');
            const saveSection = document.getElementById('saveSection');
            
            editBtn.innerHTML = '<i class="fas fa-edit mr-2"></i>Edit';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            saveSection.classList.add('hidden');
            isEditing = false;
            
            // Show success message
            showSuccessMessage();
        }

        function showSuccessMessage() {
            const message = document.getElementById('successMessage');
            message.classList.remove('translate-x-full');
            setTimeout(() => {
                message.classList.add('translate-x-full');
            }, 3000);
        }

        function confirmDeactivation() {
            if (confirm('Are you sure you want to deactivate your account? This action can be reversed by contacting support.')) {
                alert('Account deactivation request submitted. You will receive a confirmation email shortly.');
            }
        }

        // Image upload preview
        document.getElementById('imageUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImg').src = e.target.result;
                    showSuccessMessage();
                };
                reader.readAsDataURL(file);
            }
        });

        // Bio character counter
        document.getElementById('bio').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('bioCount').textContent = count;
        });

        // Initialize bio counter
        document.addEventListener('DOMContentLoaded', function() {
            const bio = document.getElementById('bio');
            document.getElementById('bioCount').textContent = bio.value.length;
        });
    </script>
    
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9693c3d913dc5b2e',t:'MTc1NDIwMjYxMy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>