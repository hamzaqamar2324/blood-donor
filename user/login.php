<?php
include('header.php');
include("config.php");

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $pwd = $_POST['password'];

    $query = "SELECT * FROM registration WHERE user_email = '$email' AND password = '$pwd'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['user_id'] = $row['user_id'];         // ✅ Add this
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_name'] = $row['user_name'];

        echo "<script>
            alert('Login successful!');
            window.location.href = 'dashboard.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Incorrect email or password');</script>";
    }
}
?>


  
  <!-- Login Page -->
    <div id="loginPage" class="page">
        <section class="min-h-screen flex items-center justify-center pt-20 pb-10">
            <div class="container mx-auto px-6 max-w-md">
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 blood-red rounded-full flex items-center justify-center mx-auto mb-4 pulse-red">
                            <i class="fas fa-sign-in-alt text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold">Welcome Back</h2>
                        <p class="text-gray-300 mt-2">Sign in to continue saving lives</p>
                    </div>
                    
                    <form  class="space-y-6" method="post">
                        <div>
                            <label class="block text-sm font-medium mb-2">Email Address</label>
                            <input type="email" required class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors" name="email">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Password</label>
                            <input type="text" required class="w-full px-4 py-3 bg-black/50 border border-gray-600 rounded-lg focus:border-red-400 focus:outline-none transition-colors" name="password">
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2">
                                <span class="text-sm text-gray-300">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-red-400 hover:underline">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="w-full blood-red py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity" name="login">
                            Sign In
                        </button>
                    </form>
                    
                    <p class="text-center mt-6 text-gray-300">
                        Don't have an account? 
                        <a href="#" class="text-red-400 hover:underline">Register now</a>
                    </p>
                </div>
            </div>
        </section>
    </div>

     <?php
include('footer.php');
?>
 
