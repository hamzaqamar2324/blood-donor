<?php
// ==========================
// INCLUDE HEADER (session handled inside)
// ==========================
include('header.php'); // header.php me session_start() safe way se ho chuka hoga

// ==========================
// CHECK IF USER IS LOGGED IN
// ==========================
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// ==========================
// DATABASE CONNECTION
// ==========================
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "donors";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ==========================
// AJAX: POPUP UPDATE (unchanged)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['popup_seen'])) {
    $uid = (int)$_SESSION['user_id'];
    $sql = "UPDATE registration SET popup_seen = 1 WHERE user_id='$uid'";
    mysqli_query($conn, $sql);
    echo "updated";
    exit();
}

// ==========================
// AJAX: SAVE MESSAGE (store to DB)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_message'])) {
    header('Content-Type: application/json');
    $sender_id   = (int)$_SESSION['user_id'];
    $receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
    $message     = isset($_POST['message_text']) ? trim($_POST['message_text']) : '';

    if ($sender_id && $receiver_id && $message !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO messages (sender_id, receiver_id, message_text, status) VALUES (?, ?, ?, 'sent')");
        mysqli_stmt_bind_param($stmt, "iis", $sender_id, $receiver_id, $message);
        $ok = mysqli_stmt_execute($stmt);
        if ($ok) {
            $id = mysqli_insert_id($conn);
            $q = mysqli_query($conn, "SELECT created_at FROM messages WHERE message_id=".$id." LIMIT 1");
            $row = mysqli_fetch_assoc($q);
            echo json_encode([
                "success"      => true,
                "message_id"   => $id,
                "created_at"   => $row ? $row['created_at'] : date('Y-m-d H:i:s'),
                "status"       => "sent"
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "DB insert failed"]);
        }
        if (isset($stmt)) mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid payload"]);
    }
    exit();
}

// ==========================
// AJAX: FETCH HISTORY (both sides)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_history'])) {
    header('Content-Type: application/json');
    $user_id    = (int)$_SESSION['user_id'];
    $chat_with  = isset($_POST['chat_with']) ? (int)$_POST['chat_with'] : 0;

    if ($user_id && $chat_with) {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT message_id, sender_id, receiver_id, message_text, status, created_at
             FROM messages
             WHERE (sender_id=? AND receiver_id=?)
                OR (sender_id=? AND receiver_id=?)
             ORDER BY created_at ASC, message_id ASC"
        );
        mysqli_stmt_bind_param($stmt, "iiii", $user_id, $chat_with, $chat_with, $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $messages = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $messages[] = $row;
        }
        echo json_encode(["success" => true, "messages" => $messages]);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid chat_with"]);
    }
    exit();
}

// ==========================
// FETCH REGISTRATION DATA (donors list) + build arrays for filters
// ==========================
$sql = "SELECT * FROM registration";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$donors = [];
$cities = [];
$bloods = [];
while ($row = mysqli_fetch_assoc($result)) {
    $donors[] = $row;
    if (!empty($row['city']))      $cities[] = $row['city'];
    if (!empty($row['blood_type'])) $bloods[] = $row['blood_type'];
}
$cities = array_unique($cities);
sort($cities);
$bloods = array_unique($bloods);
sort($bloods);

// ==========================
// SESSION VARIABLES
// ==========================
$PHP_USER_NAME = htmlspecialchars($_SESSION['user_name']);
$PHP_USER_ID   = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// ==========================
// FETCH USER POPUP STATUS
// ==========================
$showPopup = false;
if ($PHP_USER_ID) {
    $popupCheck = mysqli_query($conn, "SELECT popup_seen FROM registration WHERE user_id='$PHP_USER_ID' LIMIT 1");
    if ($popupCheck && mysqli_num_rows($popupCheck) > 0) {
        $rowPopup = mysqli_fetch_assoc($popupCheck);
        if ((int)$rowPopup['popup_seen'] === 0) {
            $showPopup = true; // sirf naye user ke liye
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Donors (Audio Calls)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    :root{
      --bg1:#0b1220; --bg2:#121a2c; --bg3:#0f1526; --glow:#10b981;
    }
    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      color:white;
      background:
        radial-gradient(1200px 800px at 10% -10%, rgba(16,185,129,0.12), transparent 60%),
        radial-gradient(900px 700px at 110% 10%, rgba(59,130,246,0.12), transparent 60%),
        conic-gradient(from 180deg at 50% 50%, var(--bg1), var(--bg2), var(--bg3), var(--bg1));
      animation: bgShift 18s ease-in-out infinite alternate;
      background-attachment: fixed;
    }
    @keyframes bgShift{
      0%{ filter:hue-rotate(0deg) saturate(1);}
      100%{ filter:hue-rotate(12deg) saturate(1.15);}
    }

    #overlay.blur { filter: blur(6px); pointer-events: none; user-select: none; }
    .popup-enter { animation: popupEnter 0.28s ease-out; }
    .popup-exit { animation: popupExit 0.25s ease-in; }
    @keyframes popupEnter { 0%{opacity:0;transform:translateY(-20px);} 100%{opacity:1;transform:translateY(0);} }
    @keyframes popupExit { 0%{opacity:1;transform:translateY(0);} 100%{opacity:0;transform:translateY(-20px);} }

    .glass-effect {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(12px);
      box-shadow:0 10px 40px rgba(0,0,0,0.35);
      border: 1px solid rgba(255,255,255,0.06);
    }
    .emerald-btn {
      background: linear-gradient(135deg, #10b981, #059669);
      color:white; font-weight:600;
      box-shadow:0 6px 16px rgba(16,185,129,0.28);
    }
    .chat-bubble {
      padding:0.5rem 0.75rem;
      border-radius:12px;
      max-width:70%;
      word-wrap: break-word;
      box-shadow:0 2px 8px rgba(0,0,0,0.25);
      position: relative;
    }
    .msg-sent {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white; align-self: flex-end; animation: slideIn 0.25s ease;
    }
    .msg-recv {
      background: linear-gradient(135deg, #1f2937, #374151);
      color: #d1d5db; align-self: flex-start; animation: slideIn 0.25s ease;
    }
    .msg-time { display:block; font-size:10px; opacity:.8; margin-top:4px; }
    .tick { font-size: 12px; margin-left:6px; opacity:.9; }
    @keyframes slideIn { 0% {opacity:0; transform: translateY(12px);} 100% {opacity:1; transform:translateY(0);} }

    .typing-indicator span { display:inline-block; width:6px; height:6px; margin:0 2px; background:#10b981; border-radius:50%; animation: blink 1s infinite; }
    .typing-indicator span:nth-child(2){animation-delay:0.18s;} .typing-indicator span:nth-child(3){animation-delay:0.36s;}
    @keyframes blink { 0%,80%,100%{opacity:0;} 40%{opacity:1;} }

    .chat-header { display:flex; justify-content:space-between; align-items:center; }
    #chatMessages::-webkit-scrollbar { width:6px; } 
    #chatMessages::-webkit-scrollbar-thumb { background:#10b981; border-radius:3px; }

    .badge-dot { width:10px; height:10px; border-radius:999px; display:inline-block; margin-right:6px; background:#22c55e; box-shadow:0 0 12px rgba(34,197,94,0.7); }

    .field {
      background: rgba(15,23,42,.6);
      border: 1px solid rgba(148,163,184,0.18);
      color: #e5e7eb; border-radius:10px; padding:10px 12px; font-size:14px;
      outline: none;
    }

    #incomingModal { display:none; }
    #callModal { display:none; }
    audio { display:none; }
  </style>
</head>
<body>

<!-- ✅ Notification Popup (TOP, background blur) -->
<?php if ($showPopup): ?>
<div id="notificationPopup" class="fixed top-0 left-0 w-full flex justify-center z-50">
  <div class="bg-white text-gray-800 shadow-2xl rounded-b-2xl w-full max-w-2xl p-6 border border-gray-200 popup-enter">
    <div class="flex items-center mb-4">
      <div class="bg-emerald-500 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-xl mr-3">🔔</div>
      <div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Allow Notifications</h3>
        <p class="text-gray-600 text-sm">Stay updated with alerts and personalized messages. <span class="text-emerald-600 font-semibold">Enable notifications</span> for the best experience.</p>
      </div>
    </div>
    <div class="flex gap-3 mt-3">
      <button id="denyBtn" class="flex-1 px-4 py-2 bg-gray-200 rounded">Not Now</button>
      <button id="allowBtn" class="flex-1 px-4 py-2 bg-emerald-500 text-white rounded">Allow</button>
    </div>
  </div>
</div>
<script>
  window.addEventListener("DOMContentLoaded", function() {
    // ✅ blur lagao jab popup dikhe
    document.getElementById("overlay").classList.add("blur");

    document.getElementById("allowBtn").addEventListener("click", function() {
        fetch("", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "popup_seen=1"
        }).then(() => {
            document.getElementById("notificationPopup").style.display = "none";
            document.getElementById("overlay").classList.remove("blur"); // ✅ blur hatado
        });
    });

    document.getElementById("denyBtn").addEventListener("click", function() {
        window.location.href = "logout.php"; // deny pe logout
    });
  });
</script>
<?php endif; ?>


                                  <!-- INCOMING CALL MODAL    DESIGNED BY: HAMZA QAMAR -->

<div id="incomingModal" class="fixed inset-0 flex items-start justify-center bg-black/70 backdrop-blur-xl z-50">
  <div class="relative w-96 p-7 rounded-3xl bg-white/10 backdrop-blur-2xl shadow-[0_0_40px_rgba(0,0,0,0.6)] border border-white/20 popup-enter mt-20 overflow-hidden">
    
    <!-- Decorative Glow -->
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-500/30 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-44 h-44 bg-red-500/30 rounded-full blur-3xl"></div>

    <!-- Caller Info -->
    <div class="flex items-center gap-4 relative z-10">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-3xl font-bold shadow-xl ring-2 ring-emerald-400/40 animate-pulse">
        📞
      </div>
      <div>
        <div class="font-semibold text-xl text-white tracking-wide">Incoming Call</div>
        <div class="text-sm text-gray-300">from <span id="callerName" class="font-semibold text-emerald-300">Someone</span></div>
      </div>
    </div>

    <!-- Call Type -->
    <div class="mt-5 text-center text-sm text-emerald-200 font-medium tracking-wide animate-pulse">
      🔊 Audio Call Ringing...
    </div>

    <!-- Buttons -->
    <div class="mt-7 flex gap-5 justify-center relative z-10">
      <!-- Reject -->
      <button id="rejectBtn" class="px-6 py-2 rounded-2xl bg-gradient-to-r from-red-600/90 to-red-500/90 
              text-white font-semibold shadow-lg border border-red-400/30 
              hover:scale-105 hover:shadow-[0_0_15px_rgba(255,0,0,0.6)] 
              active:scale-95 transition duration-300 ease-out backdrop-blur-md">
        Reject
      </button>

      <!-- Accept -->
      <button id="acceptBtn" class="px-6 py-2 rounded-2xl bg-gradient-to-r from-emerald-600/90 to-emerald-500/90 
              text-white font-semibold shadow-lg border border-emerald-400/30 
              hover:scale-105 hover:shadow-[0_0_15px_rgba(0,255,150,0.6)] 
              active:scale-95 transition duration-300 ease-out backdrop-blur-md">
        Accept
      </button>
    </div>
  </div>
</div>


<!-- Active Call Modal -->
<div id="callModal" class="fixed inset-0 flex items-center justify-center bg-black/70 backdrop-blur-lg z-50 p-6 opacity-0 scale-95 transition-all duration-500 ease-out popup-enter">
  <div class="bg-white/10 backdrop-blur-2xl rounded-3xl shadow-[0_0_40px_rgba(0,0,0,0.6)] w-full max-w-md p-6 relative flex flex-col items-center text-center overflow-hidden border border-white/20">

    <!-- Decorative Glow -->
    <div class="absolute -top-20 -right-20 w-36 h-36 bg-emerald-500/20 rounded-full blur-3xl animate-ping"></div>
    <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-red-500/20 rounded-full blur-3xl animate-pulse"></div>

    <!-- Header -->
    <div class="flex justify-between items-center w-full mb-4 relative z-10">
      <div class="font-semibold text-lg text-white tracking-wide">
        Call with <span id="peerName" class="text-emerald-400">Unknown</span>
      </div>
      <div class="flex items-center gap-2">
        <button id="muteBtn" class="px-4 py-1 rounded-xl bg-gray-200/30 backdrop-blur-md hover:bg-gray-200/50 text-sm font-medium text-gray-800 shadow-sm transition duration-200">
          Mute
        </button>
        <button id="speakerBtn" class="px-4 py-1 rounded-xl bg-gray-200/30 backdrop-blur-md hover:bg-gray-200/50 text-sm font-medium text-gray-800 shadow-sm transition duration-200">
          Speaker
        </button>
        <button id="endBtn" class="px-4 py-1 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow-lg transition duration-200">
          End
        </button>
      </div>
    </div>

    <!-- Call Info -->
    <div class="flex flex-col items-center mt-6 relative z-10">
      <div class="w-24 h-24 bg-gradient-to-tr from-emerald-500 to-emerald-600 text-white flex items-center justify-center rounded-full text-4xl shadow-2xl ring-2 ring-emerald-400/50 animate-pulse">
        <i class="fas fa-phone"></i>
      </div>
      <p id="callStatus" class="text-gray-300 text-sm mt-3 font-medium animate-pulse">Audio call active...</p>
      <p id="callTimer" class="text-emerald-300 text-sm mt-1 font-semibold tracking-wide">00:00</p>
    </div>
  </div>
</div>

<script>
  // Slide-down + fade-in animation
  const callModal = document.getElementById('callModal');
  setTimeout(() => {
    callModal.classList.remove('opacity-0', 'scale-95');
    callModal.classList.add('opacity-100', 'scale-100');
  }, 50);

  // Call Timer
  let seconds = 0;
  let minutes = 0;
  const timerEl = document.getElementById('callTimer');
  let callInterval = setInterval(() => {
    seconds++;
    if(seconds === 60){ seconds = 0; minutes++; }
    timerEl.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
  }, 1000);

  // End button stops timer
  document.getElementById('endBtn').addEventListener('click', () => {
    clearInterval(callInterval);
    callModal.classList.add('opacity-0', 'scale-95');
    setTimeout(()=>callModal.remove(), 300);
  });
</script>


<!-- Main Dashboard -->
<div id="overlay" class="pt-24 pb-8 min-h-screen">
  <div class="container mx-auto px-6">
    <h1 class="text-4xl font-bold mb-2">Welcome, <?php echo htmlspecialchars($PHP_USER_NAME); ?></h1>
    <p class="text-gray-300 mb-6">Find donors in your area and connect with them instantly</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Donors List -->
      <div class="lg:col-span-2">
        <div class="glass-effect rounded-xl p-6 text-white">
          <h2 class="text-2xl font-bold mb-4">Available Donors</h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <input id="searchName" class="field" placeholder="Search by name...">
            <select id="filterCity" class="field">
              <option value="">All Cities</option>
              <?php foreach ($cities as $c): ?>
                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
              <?php endforeach; ?>
            </select>
            <select id="filterBlood" class="field">
              <option value="">All Blood Groups</option>
              <?php foreach ($bloods as $b): ?>
                <option value="<?php echo htmlspecialchars($b); ?>"><?php echo htmlspecialchars($b); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div id="donorList" class="space-y-4 max-h-96 overflow-y-auto">
            <?php foreach ($donors as $row):
              $name = htmlspecialchars($row['user_name']);
              $city = htmlspecialchars($row['city']);
              $blood = htmlspecialchars($row['blood_type']);
              $uid  = (int)$row['user_id'];
              if ($uid === $PHP_USER_ID) { continue; }
            ?>
              <div class="glass-effect rounded-lg p-4 flex items-center justify-between donor-card"
                   data-name="<?= strtolower($name) ?>"
                   data-city="<?= strtolower($city) ?>"
                   data-blood="<?= strtolower($blood) ?>"
                   data-uid="<?= $uid ?>">
                <div class="flex items-center space-x-4">
                  <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48' viewBox='0 0 48 48'%3E%3Ccircle cx='24' cy='24' r='24' fill='%23059669'/%3E%3Ctext x='24' y='30' text-anchor='middle' fill='white' font-size='18' font-weight='bold'%3E<?= strtoupper(substr($name,0,2)) ?>%3C/text%3E%3C/svg%3E" class="w-12 h-12 rounded-full" alt="avatar">
                  <div>
                    <h3 class="font-semibold"><?= $name ?></h3>
                    <p class="text-gray-300 text-sm"><span class="badge-dot"></span>📍 <?= $city ?> • <?= $blood ?></p>
                    <p class="text-green-400 text-xs">Online</p>
                  </div>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="blood-type px-3 py-1 rounded-full text-sm font-bold"><?= $blood ?></div>
                  <button class="emerald-btn px-4 py-2 rounded-lg text-sm flex items-center space-x-2"
                          onclick="startCall('<?= $name ?>')">
                    <i class="fas fa-phone"></i><span>Call</span>
                  </button>
                  <button class="emerald-btn px-4 py-2 rounded-lg text-sm flex items-center space-x-2"
                          onclick="startChat('<?= $name ?>', <?= $uid ?>)">
                    <i class="fas fa-comment"></i><span>Chat</span>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Chat / Profile Column -->
<div class="lg:col-span-1">
  <div class="glass-effect rounded-xl p-6 h-full flex flex-col text-white">
    <div class="chat-header mb-4">
      <div class="flex items-center space-x-2">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold" id="chatAvatar">A</div>
        <div>
          <h2 id="chatHeader" class="text-xl font-bold">Select a donor to chat</h2>
          <p id="chatStatus" class="text-green-400 text-xs">Last seen: —</p>
        </div>
      </div>
      <div class="flex space-x-2">
        <button title="Call" class="text-gray-300"><i class="fas fa-phone"></i></button>
        <button title="Video Call" class="text-gray-300"><i class="fas fa-video"></i></button>
      </div>
    </div>

    <div id="chatMessages" class="space-y-2 mb-2 flex-1 overflow-y-auto flex flex-col p-2 bg-slate-800 rounded-lg"></div>
    <div id="typingIndicator" class="typing-indicator flex items-center h-6 mb-2 hidden"><span></span><span></span><span></span></div>

    <div class="flex items-center space-x-2 mt-auto">
      <input id="chatInput" placeholder="Type a message..." class="flex-1 bg-slate-700 border border-gray-600 rounded-lg px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none" onkeypress="handleKey(event)">

      <!-- Send file button -->
      <label for="fileInput" class="emerald-btn px-3 py-2 rounded-lg cursor-pointer bg-emerald-500 text-white flex items-center justify-center">
        <i class="fas fa-paperclip"></i>
      </label>
      <input type="file" id="fileInput" class="hidden" onchange="sendFile(event)">

      <!-- Send location button -->
      <button onclick="sendLocation()" class="emerald-btn px-3 py-2 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
        <i class="fas fa-location-arrow"></i>
      </button>

      <button onclick="sendMessage()" class="emerald-btn px-4 py-2 rounded-lg bg-emerald-500 text-white"><i class="fas fa-paper-plane"></i></button>
    </div>
  </div>
</div>

    </div>
  </div>
</div>

<!-- Hidden audio element -->
<audio id="remoteAudio" autoplay></audio>
<script>
  // ========== File Sending ==========
  function sendFile(event) {
    const file = event.target.files[0];
    if (!file) return;

    // WebSocket ya AJAX se file bhejna (backend implement karna)
    console.log("File ready to send:", file.name);
    // example: ws.send(JSON.stringify({type:'file', name:file.name, data: fileData}))
  }

  // ========== Location Sending ==========
  function sendLocation() {
    if (!navigator.geolocation) {
      alert("Geolocation not supported by your browser.");
      return;
    }

    navigator.geolocation.getCurrentPosition((position) => {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;

      // WebSocket ya AJAX se location bhejna
      console.log("Location ready to send:", lat, lon);
      // example: ws.send(JSON.stringify({type:'location', lat, lon}))
      
      // chatMessages me bhi show kar sakte ho
      const msgDiv = document.createElement('div');
      msgDiv.classList.add('bg-slate-700', 'p-2', 'rounded-md', 'text-sm', 'text-green-400');
      msgDiv.innerHTML = `<i class="fas fa-location-arrow"></i> <a href="https://www.google.com/maps?q=${lat},${lon}" target="_blank">My Location</a>`;
      document.getElementById('chatMessages').appendChild(msgDiv);
      msgDiv.scrollIntoView({behavior:'smooth'});
    }, (err) => {
      alert("Unable to fetch location: " + err.message);
    });
  }
</script>



<script>
// ======= Filters (Name/City/Blood) =======
const donorList = document.getElementById('donorList');
const searchName = document.getElementById('searchName');
const filterCity = document.getElementById('filterCity');
const filterBlood = document.getElementById('filterBlood');

function applyFilters(){
  const nameQ = (searchName.value || '').trim().toLowerCase();
  const cityQ = (filterCity.value || '').toLowerCase();
  const bloodQ= (filterBlood.value || '').toLowerCase();

  donorList.querySelectorAll('.donor-card').forEach(card=>{
    const n = card.dataset.name;
    const c = card.dataset.city;
    const b = card.dataset.blood;

    const nameOK = !nameQ || n.includes(nameQ);
    const cityOK = !cityQ || c===cityQ;
    const bloodOK= !bloodQ || b===bloodQ;
    card.style.display = (nameOK && cityOK && bloodOK) ? '' : 'none';
  });
}
searchName.addEventListener('input', applyFilters);
filterCity.addEventListener('change', applyFilters);
filterBlood.addEventListener('change', applyFilters);

// ======= Chat Logic =======
let CURRENT_CHAT_ID = 0;
// let CURRENT_CHAT_NAME = '';
const chatHeader = document.getElementById('chatHeader');
const chatStatus = document.getElementById('chatStatus');
const chatAvatar = document.getElementById('chatAvatar');
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const typingIndicator = document.getElementById('typingIndicator');

function initials(name){ return name ? name.trim().slice(0,1).toUpperCase() : 'U'; }
function formatTime(ts){
  // Expect "YYYY-mm-dd HH:ii:ss"
  const d = new Date(ts.replace(' ', 'T'));
  if (isNaN(d.getTime())) return ts;
  const hh = String(d.getHours()).padStart(2,'0');
  const mm = String(d.getMinutes()).padStart(2,'0');
  return `${hh}:${mm}`;
}
function scrollToBottom(){ chatMessages.scrollTop = chatMessages.scrollHeight; }

function renderMessage(m, selfId){
  const isSelf = m.sender_id == selfId;
  const wrap = document.createElement('div');
  wrap.className = `chat-bubble ${isSelf ? 'msg-sent' : 'msg-recv'}`;
  const text = document.createElement('div');
  text.textContent = m.message_text;
  const meta = document.createElement('span');
  meta.className = 'msg-time';
  let tick = '';
  if (isSelf) {
    // status based tick (basic)
    tick = (m.status === 'sent' || !m.status) ? '✓' : (m.status === 'delivered' ? '✓✓' : '✓✓');
  }
  meta.textContent = `${formatTime(m.created_at || '')}${isSelf ? ' ' : ''}`;
  if (isSelf){
    const t = document.createElement('span');
    t.className = 'tick';
    t.textContent = tick;
    meta.appendChild(t);
  }
  wrap.appendChild(text);
  wrap.appendChild(meta);
  chatMessages.appendChild(wrap);
}

function startChat(name, userId){
  CURRENT_CHAT_ID = userId;
  CURRENT_CHAT_NAME = name;
  chatHeader.textContent = `Chat with ${name}`;
  chatAvatar.textContent = initials(name);
  chatStatus.textContent = `Last seen: just now`;
  chatMessages.innerHTML = '';
  typingIndicator.classList.add('hidden');

  fetch('', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body: new URLSearchParams({ fetch_history:1, chat_with: userId })
  })
  .then(r=>r.json())
  .then(data=>{
    if (data && data.success){
      const selfId = <?php echo (int)$PHP_USER_ID; ?>;
      data.messages.forEach(m=>renderMessage(m, selfId));
      scrollToBottom();
    }
  })
  .catch(console.error);
}

function handleKey(e){
  if (e.key === 'Enter') sendMessage();
}

function sendMessage(){
  const msg = (chatInput.value || '').trim();
  if (!msg || !CURRENT_CHAT_ID) return;
  typingIndicator.classList.add('hidden');

  const payload = new URLSearchParams({
    save_message:1,
    receiver_id: CURRENT_CHAT_ID,
    message_text: msg
  });

  fetch('', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: payload })
    .then(r=>r.json())
    .then(data=>{
      if (data && data.success){
        // Append immediately
        const selfId = <?php echo (int)$PHP_USER_ID; ?>;
        renderMessage({
          sender_id: selfId,
          receiver_id: CURRENT_CHAT_ID,
          message_text: msg,
          status: data.status || 'sent',
          created_at: data.created_at
        }, selfId);
        chatInput.value = '';
        scrollToBottom();
      }
    })
    .catch(console.error);
}

// Fake typing indicator toggle when focusing input
chatInput.addEventListener('input', ()=>{
  if (!CURRENT_CHAT_ID) return;
  if (chatInput.value.length>0) typingIndicator.classList.remove('hidden');
  else typingIndicator.classList.add('hidden');
});

// ======= Call UI (placeholder) =======
function startCall(name){
  document.getElementById('peerName').textContent = name;
  document.getElementById('callModal').style.display = 'flex';
}
document.getElementById('endBtn').addEventListener('click', ()=> {
  document.getElementById('callModal').style.display = 'none';
});
document.getElementById('muteBtn').addEventListener('click', ()=> {
  // basic toggle UI only
  alert('Mute toggled (placeholder)');
});

// Optional: simulate "Last seen" update every 30s (UI only)
setInterval(()=>{
  if (CURRENT_CHAT_ID){
    const t = new Date();
    chatStatus.textContent = `Last seen: ${String(t.getHours()).padStart(2,'0')}:${String(t.getMinutes()).padStart(2,'0')}`;
  }
}, 30000);
</script>

<script>
// ==========================
// CLIENT CONSTANTS
// ==========================
const LOGGED_IN_USER_ID = <?php echo (int)$PHP_USER_ID; ?>;

let CURRENT_CHAT_WITH = null;
let CURRENT_CHAT_NAME = null;

// Safely escape HTML (avoid XSS in chat)
function esc(str){
  return String(str)
    .replace(/&/g,'&amp;')
    .replace(/</g,'&lt;')
    .replace(/>/g,'&gt;');
}

// UI helpers
function setChatHeader(name){
  const avatar = document.getElementById('chatAvatar');
  const header = document.getElementById('chatHeader');
  avatar.textContent = (name||'?').slice(0,1).toUpperCase();
  header.textContent = name ? `Chat with ${name}` : 'Select a donor to chat';
}

// Start chat with a user (load history)
function startChat(name, userId){
  CURRENT_CHAT_WITH = userId;
  CURRENT_CHAT_NAME = name;
  setChatHeader(name);
  document.getElementById('chatMessages').innerHTML = '';

  // Fetch history via POST (same page)
  const form = new URLSearchParams();
  form.append('fetch_history', '1');
  form.append('chat_with', String(userId));

  fetch('', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: form.toString()
  })
  .then(r => r.json())
  .then(data => {
    if(data.success){
      renderMessages(data.messages || []);
      scrollToBottom();
    }
  })
  .catch(console.error);
}

// Render messages array
function renderMessages(list){
  const wrap = document.getElementById('chatMessages');
  wrap.innerHTML = '';
  (list||[]).forEach(m => {
    const isMine = Number(m.sender_id) === LOGGED_IN_USER_ID;
    const bubble = document.createElement('div');
    bubble.className = `chat-bubble ${isMine ? 'msg-sent' : 'msg-recv'}`;
    const time = m.created_at ? ` <small style="font-size:10px;color:#cbd5e1">${esc(m.created_at)}</small>` : '';
    bubble.innerHTML = `${esc(m.message_text)}${time}`;
    wrap.appendChild(bubble);
  });
}

// Send message (UI + save to DB + optional WebSocket)
function sendMessage(){
  const input = document.getElementById('chatInput');
  const text = input.value.trim();
  if(!text || !CURRENT_CHAT_WITH){ return; }

  // Immediately show in UI
  appendMyMessage(text, new Date());

  // 1) Optional: Send to WebSocket if your ws client exists
  try {
    if (window.ws && ws.readyState === 1) {
      ws.send(JSON.stringify({
        type: 'chat',
        sender_id: LOGGED_IN_USER_ID,
        receiver_id: CURRENT_CHAT_WITH,
        message: text
      }));
    }
  } catch(e){ /* ignore if ws not set */ }

  // 2) Save to DB via POST
  const form = new URLSearchParams();
  form.append('save_message','1');
  form.append('receiver_id', String(CURRENT_CHAT_WITH));
  form.append('message_text', text);

  fetch('', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: form.toString()
  })
  .then(r => r.json())
  .then(data => {
    // If needed, you can update status/timestamp using response
    // but UI already shows it.
  })
  .catch(console.error);

  input.value = '';
}

// Append my message in UI
function appendMyMessage(text, timeObj){
  const wrap = document.getElementById('chatMessages');
  const bubble = document.createElement('div');
  bubble.className = 'chat-bubble msg-sent';
  const ts = timeObj instanceof Date ? timeObj.toISOString().slice(0,19).replace('T',' ') : '';
  bubble.innerHTML = `${esc(text)} ${ts ? '<small style="font-size:10px;color:#cbd5e1">'+esc(ts)+'</small>' : ''}`;
  wrap.appendChild(bubble);
  scrollToBottom();
}

// Append received message in UI (for WebSocket onmessage)
function appendIncomingMessage(text, created_at){
  const wrap = document.getElementById('chatMessages');
  const bubble = document.createElement('div');
  bubble.className = 'chat-bubble msg-recv';
  const ts = created_at ? created_at : new Date().toISOString().slice(0,19).replace('T',' ');
  bubble.innerHTML = `${esc(text)} <small style="font-size:10px;color:#cbd5e1">${esc(ts)}</small>`;
  wrap.appendChild(bubble);
  scrollToBottom();
}

function handleKey(e){
  if(e.key === 'Enter'){ sendMessage(); }
}

function scrollToBottom(){
  const wrap = document.getElementById('chatMessages');
  wrap.scrollTop = wrap.scrollHeight;
}

/* ==========================
   OPTIONAL: WebSocket hookup
   (agar already configured hai)
   Bas yahan apna ws URL laga de,
   warna ignore rahega.
========================== */
// Example:
// const ws = new WebSocket('wss://your-websocket-server');
// ws.onmessage = (ev) => {
//   try {
//     const data = JSON.parse(ev.data);
//     if (data.type === 'chat') {
//        // Sirf tab show karein jab current chat same user se hai
//        if (CURRENT_CHAT_WITH && Number(data.sender_id) === Number(CURRENT_CHAT_WITH)) {
//           appendIncomingMessage(data.message_text || data.message, data.created_at);
//        }
//     }
//   } catch(e){}
// };

</script>

<script>
/* =========================
   Small helpers & popup
   ========================= */
const popup = document.getElementById('notificationPopup');
const allowBtn = document.getElementById('allowBtn');
const denyBtn = document.getElementById('denyBtn');
if (allowBtn) allowBtn.addEventListener('click', () => { document.cookie = "notification_permission=allow"; popup.classList.add('popup-exit'); setTimeout(()=>popup.style.display='none',300); });
if (denyBtn) denyBtn.addEventListener('click', () => { alert("Please allow notifications to access dashboard features."); });

/* =========================
   Chat logic (simple UI)
   ========================= */
let currentUser = '';
function startChat(user){
  currentUser = user;
  document.getElementById('chatMessages').innerHTML = '';
  document.getElementById('chatHeader').innerText = '' + user;
  document.getElementById('chatStatus').innerText = '🟢 Online';
  document.getElementById('chatAvatar').innerText = user.substring(0,1).toUpperCase();
  addMessageToChat(user, 'Hi! I am available for donation.', 'recv');
}
function sendMessage(){
  const input = document.getElementById('chatInput');
  const msg = input.value.trim();
  if(msg==='' || currentUser==='') return;
  addMessageToChat('You', msg, 'sent');
  wsSend({ type: 'chat', from: "<?php echo $PHP_USER_NAME; ?>", to: currentUser, message: msg });
  input.value='';
}
function handleKey(e){ if(e.key==='Enter') sendMessage(); }
function addMessageToChat(sender, msg, type='recv'){
  const msgBox = document.getElementById('chatMessages');
  const el = document.createElement('div');
  el.className = `chat-bubble ${type==='sent'?'msg-sent':'msg-recv'} self-${type==='sent'?'end':'start'}`;
  el.innerHTML = `<strong>${sender}</strong><p>${msg}</p><p class="text-xs text-gray-400 mt-1">${new Date().toLocaleTimeString()}</p>`;
  msgBox.appendChild(el);
  msgBox.scrollTop = msgBox.scrollHeight;
  if(type==='recv'){ showTypingIndicator(); }
}
function showTypingIndicator(){ const indicator = document.getElementById('typingIndicator'); if(!indicator) return; indicator.classList.remove('hidden'); setTimeout(()=>indicator.classList.add('hidden'),1200); }

/* =========================
   WebSocket signalling
   ========================= */
const socketUrl = "ws://localhost:8080";
let socket = null;
function startSocket() {
  socket = new WebSocket(socketUrl);

  socket.onopen = () => {
    console.log("WS open");
    // register with username property so server can map ws -> username
    socket.send(JSON.stringify({ username: "<?php echo $PHP_USER_NAME; ?>" }));
  };

  socket.onmessage = (ev) => {
    let data;
    try { data = JSON.parse(ev.data); } catch(e){ console.warn("Bad ws JSON", ev.data); return; }
    handleSocketMessage(data);
  };

  socket.onclose = () => { console.log("WS closed - reconnect in 2s"); setTimeout(startSocket,2000); };
  socket.onerror = (e) => { console.warn("WS error", e); };
}
startSocket();

function wsSend(obj){
  if (!socket || socket.readyState !== 1) return console.warn("ws not ready");
  try { socket.send(JSON.stringify(obj)); } catch(e){ console.warn("ws send failed", e); }
}

/* =========================
   WebRTC / Call flow (audio-only)
   ========================= */
let pc = null;
let localStream = null;
let inCallWith = null;
let pendingCaller = null;
let isCaller = false;
let muted = false;
let callActive = false;
let ringingTimeout = null;

const iceConfig = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };
const $ = id => document.getElementById(id);

async function handleSocketMessage(data) {
  // chat
  if (data.type === 'chat' && data.to === "<?php echo $PHP_USER_NAME; ?>") {
    addMessageToChat(data.from, data.message, 'recv');
    return;
  }

  // call signalling
  switch (data.type) {
    case 'call:request':
      // incoming request
      if (callActive || inCallWith) {
        // auto reject when busy
        wsSend({ type:'call:reject', to: data.from, from: "<?php echo $PHP_USER_NAME; ?>" });
        break;
      }
      pendingCaller = { name: data.from };
      $('callerName').innerText = pendingCaller.name;
      $('incomingModal').style.display = 'flex';
      break;

    case 'call:reject':
      if (isCaller && inCallWith === data.from) {
        alert('Call rejected by user.');
        cleanupCall();
      }
      break;

    case 'call:accept':
      if (isCaller && inCallWith === data.from) {
        // show caller UI and create offer
        $('peerName').textContent = inCallWith;
        $('callModal').style.display = 'block';
        await setupAsCaller();
      }
      break;

    case 'rtc:offer':
      if (!pc) await setupAsAnswerer();
      try {
        await pc.setRemoteDescription(new RTCSessionDescription(data.offer));
        const answer = await pc.createAnswer();
        await pc.setLocalDescription(answer);
        wsSend({ type:'rtc:answer', to: data.from, from: "<?php echo $PHP_USER_NAME; ?>", answer });
      } catch (err) {
        console.error("handle offer failed", err);
        cleanupCall();
      }
      break;

    case 'rtc:answer':
      if (pc && isCaller) {
        try { await pc.setRemoteDescription(new RTCSessionDescription(data.answer)); }
        catch(e){ console.warn("setRemoteDescription failed", e); cleanupCall(); }
      }
      break;

    case 'rtc:candidate':
      if (pc && data.candidate) {
        try { await pc.addIceCandidate(new RTCIceCandidate(data.candidate)); }
        catch(e){ console.warn('ICE add failed', e); }
      }
      break;

    case 'call:end':
      cleanupCall();
      break;

    default:
      break;
  }
}

/* get microphone (audio-only) */
async function getMedia() {
  if (localStream) return localStream;
  try {
    localStream = await navigator.mediaDevices.getUserMedia({
      audio: { echoCancellation:true, noiseSuppression:true },
      video: false
    });
    return localStream;
  } catch (err) {
    alert("Please allow microphone access.");
    throw err;
  }
}

function newPeerConnection() {
  pc = new RTCPeerConnection(iceConfig);

  pc.onicecandidate = (e) => {
    if (e.candidate && inCallWith) {
      wsSend({ type:'rtc:candidate', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>", candidate: e.candidate });
    }
  };

  pc.ontrack = (e) => {
    const ra = $('remoteAudio');
    if (ra) {
      ra.srcObject = e.streams[0];
      ra.play().catch(()=>{ /* some browsers require user gesture to start audio */ });
    }
  };

  pc.onconnectionstatechange = () => {
    if (!pc) return;
    console.log("PC state:", pc.connectionState);
    if (pc.connectionState === 'connected') {
      callActive = true;
      if (ringingTimeout) { clearTimeout(ringingTimeout); ringingTimeout = null; }
    }
    if (["failed","disconnected","closed"].includes(pc.connectionState)) {
      cleanupCall();
    }
  };

  return pc;
}

/* Call control functions */
function startCall(targetName) {
  if (!targetName) return alert('Invalid user.');
  if (inCallWith || callActive) return alert('Already in a call.');

  inCallWith = targetName;
  isCaller = true;

  // notify callee
  wsSend({ type:'call:request', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>" });

  // show caller UI
  $('peerName').textContent = inCallWith;
  $('callModal').style.display = 'block';

  if (ringingTimeout) clearTimeout(ringingTimeout);
  ringingTimeout = setTimeout(() => {
    alert('No response. Call ended.');
    wsSend({ type:'call:end', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>" });
    cleanupCall();
  }, 30000);
}

document.getElementById('acceptBtn').addEventListener('click', async () => {
  if (!pendingCaller) return;
  inCallWith = pendingCaller.name;
  isCaller = false;
  pendingCaller = null;
  $('incomingModal').style.display = 'none';

  // tell caller we accepted
  wsSend({ type:'call:accept', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>" });

  // show call UI and prepare to answer when offer arrives
  $('peerName').textContent = inCallWith;
  $('callModal').style.display = 'block';
  await setupAsAnswerer();
});

document.getElementById('rejectBtn').addEventListener('click', () => {
  if (!pendingCaller) return;
  wsSend({ type:'call:reject', to: pendingCaller.name, from: "<?php echo $PHP_USER_NAME; ?>" });
  pendingCaller = null;
  $('incomingModal').style.display = 'none';
});

document.getElementById('endBtn').addEventListener('click', () => {
  if (inCallWith) wsSend({ type:'call:end', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>" });
  cleanupCall();
});

document.getElementById('muteBtn').addEventListener('click', () => {
  if (!localStream) return;
  muted = !muted;
  localStream.getAudioTracks().forEach(t => t.enabled = !muted);
  document.getElementById('muteBtn').innerText = muted ? 'Unmute' : 'Mute';
});

async function setupAsCaller(){
  try {
    await getMedia();
    newPeerConnection();
    localStream.getTracks().forEach(track => pc.addTrack(track, localStream));

    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);

    wsSend({ type:'rtc:offer', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>", offer });
  } catch (err) {
    console.error('Caller setup failed', err);
    cleanupCall();
  }
}

async function setupAsAnswerer(){
  try {
    await getMedia();
    if (!pc) newPeerConnection();
    localStream.getTracks().forEach(track => pc.addTrack(track, localStream));
    // remote offer will be handled in ws handler which will send answer back
  } catch (err) {
    console.error('Answerer setup failed', err);
    cleanupCall();
  }
}

function cleanupCall(){
  console.log("cleanupCall()");
  try { if (pc) pc.close(); } catch(e){}
  pc = null;
  try { if (localStream) localStream.getTracks().forEach(t=>t.stop()); } catch(e){}
  localStream = null;

  inCallWith = null;
  pendingCaller = null;
  isCaller = false;
  muted = false;
  callActive = false;

  if (ringingTimeout) { clearTimeout(ringingTimeout); ringingTimeout = null; }

  const callM = $('callModal'); if (callM) callM.style.display = 'none';
  const inc = $('incomingModal'); if (inc) inc.style.display = 'none';
  const ra = $('remoteAudio'); if (ra) ra.srcObject = null;
  const pn = $('peerName'); if (pn) pn.textContent = 'Unknown';
  const muteBtn = $('muteBtn'); if (muteBtn) muteBtn.innerText = 'Mute';
}

/* before unload notify remote */
window.addEventListener('beforeunload', () => {
  if (inCallWith) {
    wsSend({ type:'call:end', to: inCallWith, from: "<?php echo $PHP_USER_NAME; ?>" });
  }
});


</script>

<?php include('footer.php'); ?>
</body>
</html>
