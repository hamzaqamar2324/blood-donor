<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - Life Flow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;600&display=swap');

    :root{
      --red-1:#ff1a1a;
      --red-2:#cc0000;
      --red-3:#a30000;
    }

    body { background:#050505; font-family:'Inter',sans-serif; color:#fff; overflow-x:hidden; }

    /* Hero Gradient */
    .hero-gradient {
      background:
        radial-gradient(circle at 20% 30%, rgba(153,0,0,.25), transparent 70%),
        radial-gradient(circle at 80% 70%, rgba(204,0,0,.30), transparent 60%),
        #000;
    }

    /* Animated Blood Gradient */
    .blood-red{
      background:linear-gradient(45deg, var(--red-2), var(--red-1), var(--red-2));
      background-size:200% 200%;
      -webkit-background-clip:text;background-clip:text;
      animation:bloodFlow 4s ease-in-out infinite;
    }
    @keyframes bloodFlow{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}

    /* DNA Glow Background */
    .dna-bg{position:absolute; inset:0; overflow:hidden; z-index:-1; opacity:.25}
    .dna-line{
      position:absolute; width:200%; height:2px;
      background:linear-gradient(90deg, rgba(255,80,80,.7), rgba(180,0,0,.3), rgba(255,50,50,.6));
      animation:dnaMove 6s linear infinite;
    }
    .dna-line:nth-child(1){top:20%;animation-delay:0s}
    .dna-line:nth-child(2){top:40%;animation-delay:2s}
    .dna-line:nth-child(3){top:60%;animation-delay:4s}
    .dna-line:nth-child(4){top:80%;animation-delay:1s}
    @keyframes dnaMove{from{transform:translateX(-50%)}to{transform:translateX(0%)}}

    /* Section heading reveal */
    .blood-heading{opacity:0; transform:translateY(40px); transition:all 1.2s ease}
    .blood-heading.animate{opacity:1; transform:translateY(0); text-shadow:0 0 10px rgba(204,0,0,.6)}

    .hover-scale{transition:all .4s ease}
    .hover-scale:hover{transform:scale(1.05) translateY(-6px)}
    .glass-effect{background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); backdrop-filter:blur(12px)}
    .neon-glow{box-shadow:0 0 20px rgba(255,0,0,.5), 0 0 40px rgba(255,0,0,.2)}

    /* ===== Hero blood morph canvas ===== */
    #bloodMorphWrap{position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none}
    #bloodMorph{width:100%; height:100%}
    #brandHeading{opacity:0; transition:opacity .8s ease}
    #brandHeading.show{opacity:1}
  </style>
</head>
<body class="overflow-x-hidden">

  <!-- ================= HERO ================= -->
  <section class="hero-gradient min-h-screen flex items-center relative overflow-hidden">
    <div class="dna-bg">
      <div class="dna-line"></div>
      <div class="dna-line"></div>
      <div class="dna-line"></div>
      <div class="dna-line"></div>
    </div>

    <div id="bloodMorphWrap" aria-hidden="true">
      <canvas id="bloodMorph"></canvas>
    </div>

    <div class="container mx-auto px-6 relative z-10">
      <div class="max-w-4xl">
        <h1 id="brandHeading" class="text-7xl md:text-8xl font-bold mb-6" style="font-family:'Playfair Display',serif;">
          LIFE <span class="blood-red text-transparent">FLOW</span>
        </h1>
        <p class="text-2xl mb-8 text-gray-300 max-w-xl leading-relaxed">
          Where Every Drop Counts, Every Life Matters
        </p>
        <p class="text-lg text-gray-400 mb-10 max-w-2xl leading-relaxed">
          Pakistan's most advanced blood donor network connecting heroes with those who need them most. Real-time matching, instant connections, life-saving results.
        </p>
        <button class="blood-red px-12 py-4 text-lg font-semibold rounded-xl neon-glow hover-scale"
                style="-webkit-text-fill-color:transparent;">
          Discover Our Mission
        </button>
      </div>
    </div>
  </section>

  <!-- ================= WHO WE ARE ================= -->
  <section class="py-20 bg-gradient-to-b from-black to-gray-900">
    <div class="container mx-auto px-6 text-center max-w-4xl">
      <h2 class="text-5xl font-bold mb-8 blood-heading">Who <span class="text-red-500">We Are</span></h2>
      <p class="text-lg text-gray-300 leading-relaxed">
        Life Flow is a pioneering initiative started by a group of healthcare professionals and tech innovators united by a single vision:
        to make blood accessible anytime, anywhere. We are a community-driven network that leverages technology to save lives,
        ensuring no patient is left waiting when every second matters.
      </p>
    </div>
  </section>

  <!-- ================= MISSION ================= -->
  <section class="py-20 bg-gradient-to-b from-black to-gray-900">
    <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
      <div>
        <h2 class="text-5xl font-bold mb-8 blood-heading">The <span class="text-red-500">Mission</span></h2>
        <div class="space-y-6 text-lg text-gray-300 leading-relaxed">
          <p>Life Flow isn't just a platform—it's a revolution in emergency healthcare. We've engineered the most sophisticated blood donor matching system in Pakistan, connecting verified donors with critical patients in under 60 seconds.</p>
          <p>Our AI-powered algorithm considers blood type compatibility, geographic proximity, donor availability, and medical urgency to ensure the fastest possible response when every second counts.</p>
          <p>We're building a network of modern-day heroes—ordinary people doing extraordinary things through the simple act of blood donation.</p>
        </div>
      </div>
      <div class="glass-effect p-8 rounded-3xl hover-scale">
        <div class="w-32 h-32 mx-auto blood-red rounded-full flex items-center justify-center neon-glow"
             style="-webkit-text-fill-color:transparent;">
          <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C12 2 8 6 8 10C8 13.314 10.686 16 14 16H10C13.314 16 16 13.314 16 10C16 6 12 2 12 2Z"/>
            <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z"/>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-red-400 mt-6 text-center">Instant Matching</h3>
        <p class="text-gray-300 text-center mt-3">Advanced algorithms connecting donors and recipients in real-time</p>
      </div>
    </div>
  </section>

  <!-- ================= VISION ================= -->
  <section class="py-20 bg-gradient-to-b from-gray-900 to-gray-800 text-center">
    <div class="container mx-auto px-6 max-w-4xl">
      <h2 class="text-5xl font-bold mb-8 blood-heading">Our <span class="text-red-500">Vision</span></h2>
      <p class="text-lg text-gray-300 leading-relaxed">
        To create a Pakistan where no life is lost due to the unavailability of blood. Our vision is a nation where every citizen is empowered to save lives,
        and technology ensures that blood donation is fast, safe, and accessible to all.
      </p>
    </div>
  </section>

  <!-- ================= VALUES ================= -->
  <section class="py-20 bg-gradient-to-b from-gray-800 to-black">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-5xl font-bold mb-12 blood-heading">Our <span class="text-red-500">Values</span></h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <h3 class="text-2xl font-bold text-red-400 mb-4">Compassion</h3>
          <p class="text-gray-300">We believe in humanity first and aim to ease suffering through life-saving connections.</p>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <h3 class="text-2xl font-bold text-red-400 mb-4">Innovation</h3>
          <p class="text-gray-300">Using cutting-edge technology to bridge the gap between donors and patients.</p>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <h3 class="text-2xl font-bold text-red-400 mb-4">Trust</h3>
          <p class="text-gray-300">Building a transparent and reliable system that people can count on in times of need.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= TEAM ================= -->
<section class="py-20 bg-gradient-to-b from-black to-gray-900">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-5xl font-extrabold mb-16 blood-heading tracking-wide">
      Meet Our <span class="text-red-500 drop-shadow-lg">Team</span>
    </h2>

    <div class="grid md:grid-cols-3 gap-12">

      <!-- Card 1 -->
      <div class="glass-effect p-10 rounded-3xl hover:-translate-y-3 hover:shadow-2xl duration-500">
        <div class="relative w-32 h-32 mx-auto mb-6">
          <img id="previewImage1" src="https://via.placeholder.com/150"
               class="w-32 h-32 rounded-full object-cover border-4 border-red-500 shadow-lg">
          <label for="fileUpload1"
                 class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-red-700 transition">
            📷
          </label>
          <input type="file" id="fileUpload1" accept="image/*" class="hidden" onchange="previewFile(event, 'previewImage1')">
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Qurat ul Ain Haider</h3>
        <p class="text-gray-400">Founder & CEO</p>
      </div>

      <!-- Card 2 -->
      <div class="glass-effect p-10 rounded-3xl hover:-translate-y-3 hover:shadow-2xl duration-500">
        <div class="relative w-32 h-32 mx-auto mb-6">
          <img id="previewImage2" src="https://via.placeholder.com/150"
               class="w-32 h-32 rounded-full object-cover border-4 border-red-500 shadow-lg">
          <label for="fileUpload2"
                 class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-red-700 transition">
            📷
          </label>
          <input type="file" id="fileUpload2" accept="image/*" class="hidden" onchange="previewFile(event, 'previewImage2')">
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Umm e Habiba Iqbal</h3>
        <p class="text-gray-400">Co-Founder & CTO</p>
      </div>

      <!-- Card 3 -->
      <div class="glass-effect p-10 rounded-3xl hover:-translate-y-3 hover:shadow-2xl duration-500">
        <div class="relative w-32 h-32 mx-auto mb-6">
          <img id="previewImage3" src="https://via.placeholder.com/150"
               class="w-32 h-32 rounded-full object-cover border-4 border-red-500 shadow-lg">
          <label for="fileUpload3"
                 class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-red-700 transition">
            📷
          </label>
          <input type="file" id="fileUpload3" accept="image/*" class="hidden" onchange="previewFile(event, 'previewImage3')">
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Hamza Qamar</h3>
        <p class="text-gray-400">Head of Operations</p>
      </div>

    </div>
  </div>
</section>

  <!-- ================= TESTIMONIALS ================= -->
  <section class="py-24 bg-gradient-to-b from-gray-900 to-gray-800">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-5xl font-bold mb-12 blood-heading">What People <span class="text-red-500">Say</span></h2>
      <div class="grid md:grid-cols-2 gap-12">
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <p class="text-lg text-gray-300 italic">"Life Flow helped me find a donor for my father in less than 5 minutes.
            I can’t express my gratitude enough. Truly life-saving!"</p>
          <h4 class="mt-6 text-red-400 font-semibold">– Ayesha, Karachi</h4>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <p class="text-lg text-gray-300 italic">"As a regular donor, I feel proud to be part of this network.
            The process is simple and I know I’m making a difference."</p>
          <h4 class="mt-6 text-red-400 font-semibold">– Hamza, Lahore</h4>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= STATS ================= -->
  <section class="py-24 bg-gradient-to-b from-gray-900 to-gray-800">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-6xl mb-12 blood-heading">Impact & Results</h2>
      <div class="grid md-grid-cols-4 gap-8 md:grid-cols-4">
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <div class="text-5xl font-light mb-2">2,847</div>
          <p class="text-gray-300">Verified Donors</p>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <div class="text-5xl font-light mb-2">1,523</div>
          <p class="text-gray-300">Lives Saved</p>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <div class="text-5xl font-light mb-2">38s</div>
          <p class="text-gray-300">Avg. Response</p>
        </div>
        <div class="glass-effect p-8 rounded-3xl hover-scale">
          <div class="text-5xl font-light mb-2">99.7%</div>
          <p class="text-gray-300">Match Accuracy</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="py-24 bg-gradient-to-t from-red-900/30 to-black text-center">
    <h2 class="text-6xl mb-6 blood-heading">Join Our Mission</h2>
    <p class="text-xl text-gray-400 mb-12 max-w-2xl mx-auto">
      Whether you need blood or want to save lives, Life Flow connects you with a community that cares. Every connection matters, every donation counts.
    </p>
    <div class="flex flex-col sm:flex-row gap-6 justify-center">
      <button class="blood-red px-10 py-4 rounded-full neon-glow hover-scale" style="-webkit-text-fill-color:transparent;">Find Donors</button>
      <button class="px-10 py-4 rounded-full border border-gray-400 hover:border-white hover:text-white hover-scale">Become a Donor</button>
    </div>
  </section>

  <!-- ================= CONTACT ================= -->
  <section class="py-20 bg-black text-center">
    <div class="container mx-auto px-6 max-w-3xl">
      <h2 class="text-5xl font-bold mb-8 blood-heading">Get In <span class="text-red-500">Touch</span></h2>
      <p class="text-lg text-gray-300 mb-10">Have questions or want to collaborate? Reach out to us anytime.</p>
      <div class="glass-effect p-8 rounded-3xl">
        <p class="text-gray-400">📍 Karachi, Pakistan</p>
        <p class="text-gray-400 mt-2">📧 support@lifeflow.org</p>
        <p class="text-gray-400 mt-2">📞 +92 300 1234567</p>
      </div>
    </div>
  </section>
  
<script>
  // Save image to localStorage
  function previewFile(event, imageId, storageKey) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
      const imgSrc = e.target.result;
      document.getElementById(imageId).src = imgSrc;
      localStorage.setItem(storageKey, imgSrc); // save in localStorage
    };
    reader.readAsDataURL(file);
  }

  // Load saved images on page reload
  window.onload = function() {
    const savedImg1 = localStorage.getItem('img1');
    const savedImg2 = localStorage.getItem('img2');
    const savedImg3 = localStorage.getItem('img3');

    if (savedImg1) document.getElementById('previewImage1').src = savedImg1;
    if (savedImg2) document.getElementById('previewImage2').src = savedImg2;
    if (savedImg3) document.getElementById('previewImage3').src = savedImg3;
  };
</script>

    <script>
    // ==== Blood Morph Animation ====
    const wrap = document.getElementById('bloodMorphWrap');
    const canvas = document.getElementById('bloodMorph');
    const ctx = canvas.getContext('2d');
    const brandHeading = document.getElementById('brandHeading');

    function sizeCanvas(){
      const hero = document.querySelector('.hero-gradient');
      const rect = hero.getBoundingClientRect();
      canvas.width = rect.width;
      canvas.height = rect.height;
    }
    sizeCanvas();
    window.addEventListener('resize', sizeCanvas);

    let t = 0;
    function animateBlood(){
      ctx.clearRect(0,0,canvas.width,canvas.height);

      ctx.beginPath();
      const cx = canvas.width/2, cy = canvas.height/2;
      const baseR = Math.min(canvas.width,canvas.height)/3.2;
      const points = 80;
      for(let i=0;i<=points;i++){
        const angle = (i/points)*Math.PI*2;
        const off = Math.sin(angle*3 + t)*15 + Math.cos(angle*2 + t*0.7)*10;
        const r = baseR + off;
        const x = cx + r*Math.cos(angle);
        const y = cy + r*Math.sin(angle);
        i===0 ? ctx.moveTo(x,y) : ctx.lineTo(x,y);
      }
      ctx.closePath();

      // Gradient red effect
      const grad = ctx.createRadialGradient(cx,cy,baseR*0.2,cx,cy,baseR);
      grad.addColorStop(0,"rgba(255,50,50,0.9)");
      grad.addColorStop(1,"rgba(120,0,0,0.4)");
      ctx.fillStyle = grad;
      ctx.fill();

      t+=0.02;
      requestAnimationFrame(animateBlood);
    }
    animateBlood();

    // Show heading after 1s
    setTimeout(()=> brandHeading.classList.add("show"), 1000);

    // ===== Scroll animation for headings =====
    const observer = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.classList.add("animate");
        }
      });
    },{threshold:0.3});

    document.querySelectorAll(".blood-heading").forEach(el=>{
      observer.observe(el);
    });
  </script>
  <?php include('footer.php'); ?>
  
</body>
</html>
