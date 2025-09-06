<?php
include("header.php");
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn'])) {
    // Escape inputs to prevent SQL injection
    $name      = mysqli_real_escape_string($connection, $_POST['name']);
    $email     = mysqli_real_escape_string($connection, $_POST['email']);
    $contact   = mysqli_real_escape_string($connection, $_POST['contact']);
    $bloodtype = mysqli_real_escape_string($connection, $_POST['blood_type']);
    $city      = mysqli_real_escape_string($connection, $_POST['city']);
    $pwd       = mysqli_real_escape_string($connection, $_POST['pass']);

    // Validate fields (optional, but good practice)
    if (empty($name) || empty($email) || empty($contact) || empty($bloodtype) || empty($city) || empty($pwd)) {
        echo "<script>alert('Please fill in all fields!');</script>";
    } else {
        // Insert query
        $query = "INSERT INTO registration (user_name, user_email, contact, blood_type, city, password)
                  VALUES ('$name', '$email', '$contact', '$bloodtype', '$city', '$pwd')";

        $run = mysqli_query($connection, $query);

        if ($run) {
            echo "<script>
                alert('User has been Registered!');
                window.location.href = 'login.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Registration failed: " . mysqli_error($connection) . "');</script>";
        }
    }
}
?>

<!-- HTML REGISTRATION FORM -->
<div id="registerPage" class="page">
    <section class="min-h-screen flex items-center justify-center pt-20 pb-10">
        <div class="container mx-auto px-6 max-w-md">
            <div class="glass-effect p-8 rounded-2xl">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 blood-red rounded-full flex items-center justify-center mx-auto mb-4 pulse-red">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold">Join LifeFlow</h2>
                    <p class="text-gray-300 mt-2">Become a life-saving hero today</p>
                </div>

                <form class="space-y-6" method="post">
                    <div>
                        <label class="block text-sm font-medium mb-2">Full Name</label>
                        <input type="text" required name="name" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Email Address</label>
                        <input type="email" required name="email" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Phone Number</label>
                        <input type="tel" required name="contact" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Blood Type</label>
                        <select required name="blood_type" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                            <option value="">Select Blood Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">City</label>
                        <select required name="city" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                            <option value="">Select your city</option>
                            <option value="Karachi">Karachi</option>
                            <option value="Lahore">Lahore</option>
                            <option value="Islamabad">Islamabad</option>
                            <option value="Rawalpindi">Rawalpindi</option>
                            <option value="Sialkot">Sialkot</option>
                            <option value="Hyderabad">Hyderabad</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <input type="text" required name="pass" class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors">
                    </div>

                    <button type="submit" name="btn" class="w-full blood-red py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include("footer.php"); ?>
