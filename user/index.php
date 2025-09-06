<?php
include('header.php');
?>

    <!-- Blood Cells Animation -->
    <div id="bloodCells" class="fixed inset-0 pointer-events-none z-10"></div>

    <!-- Home Page -->
    <div id="homePage" class="page">
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
            <div class="container mx-auto px-6 text-center relative z-20">
                <div class="floating-animation">
                    <h1 class="text-6xl md:text-8xl font-bold mb-6 bg-gradient-to-r from-white to-red-400 bg-clip-text text-transparent">
                        Save Lives
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-gray-300 max-w-3xl mx-auto">
                        Join the world's most premium blood donation platform. Every drop counts, every life matters.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="blood-red px-8 py-4 rounded-full text-lg font-semibold hover-lift">
                            Become a Donor
                        </button>
                        <button class="glass-effect px-8 py-4 rounded-full text-lg font-semibold hover-lift">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-16">Why Choose LifeFlow?</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-effect p-8 rounded-2xl hover-lift">
                        <div class="w-16 h-16 blood-red rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-center">Safe & Secure</h3>
                        <p class="text-gray-300 text-center">Advanced security protocols ensure your data and health information remain protected.</p>
                    </div>
                    
                    <div class="glass-effect p-8 rounded-2xl hover-lift">
                        <div class="w-16 h-16 blood-red rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-center">Real-time Matching</h3>
                        <p class="text-gray-300 text-center">Instant matching with recipients based on blood type and location proximity.</p>
                    </div>
                    
                    <div class="glass-effect p-8 rounded-2xl hover-lift">
                        <div class="w-16 h-16 blood-red rounded-full flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-heart text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-center">Impact Tracking</h3>
                        <p class="text-gray-300 text-center">Track your donation impact and see the lives you've helped save.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 glass-effect">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-red-400 mb-2">50K+</div>
                        <div class="text-gray-300">Lives Saved</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-red-400 mb-2">25K+</div>
                        <div class="text-gray-300">Active Donors</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-red-400 mb-2">100+</div>
                        <div class="text-gray-300">Partner Hospitals</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-red-400 mb-2">24/7</div>
                        <div class="text-gray-300">Support Available</div>
                    </div>
                </div>
            </div>
        </section>
    </div>


 
<script>
      // Blood Cells Animation
        function createBloodCell() {
            const cell = document.createElement('div');
            cell.className = 'blood-cell';
            cell.style.top = Math.random() * window.innerHeight + 'px';
            cell.style.animationDelay = Math.random() * 2 + 's';
            cell.style.animationDuration = (10 + Math.random() * 10) + 's';
            
            document.getElementById('bloodCells').appendChild(cell);
            
            // Remove cell after animation
            setTimeout(() => {
                if (cell.parentNode) {
                    cell.parentNode.removeChild(cell);
                }
            }, 20000);}
             // Create blood cells periodically
        setInterval(createBloodCell, 3000);

        // Initialize with some blood cells
        for (let i = 0; i < 5; i++) {
            setTimeout(createBloodCell, i * 1000);
        }

        // Auto-generate chat messages
        setInterval(() => {
            if (document.getElementById('chatPage').classList.contains('active')) {
                const randomName = userNames[Math.floor(Math.random() * userNames.length)];
                const randomMessage = sampleMessages[Math.floor(Math.random() * sampleMessages.length)];
                addMessage(randomName, randomMessage, false);
            }
        }, 15000 + Math.random() * 10000);

        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

</script>
    

</body>
</html>

 <?php
include('footer.php');
?>
 
