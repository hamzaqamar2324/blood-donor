<?php
include('header.php');
include('config.php'); // database connection

// ======= DELETE LOGIC =======
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "DELETE FROM registration WHERE user_id = $id";
    if ($connection->query($sql) === TRUE) {
        $msg = "User Deleted Successfully";
    } else {
        $msg = "Error deleting user: " . $connection->error;
    }
}

// ======= FETCH USERS (A → Z by name) =======
$sql = "SELECT * FROM registration ORDER BY user_name ASC";
$result = $connection->query($sql);

if(isset($_POST['add_user'])){
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $contact = mysqli_real_escape_string($connection, $_POST['contact']);
    $blood_type = mysqli_real_escape_string($connection, $_POST['blood_type']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    $query = "INSERT INTO registration (user_name, user_email, contact, blood_type, password) 
              VALUES ('$username', '$email', '$contact', '$blood_type', '$password')";

    if(mysqli_query($connection, $query)){
        echo "<script>alert('User added successfully');</script>";
    } else {
        echo "<script>alert('Error: ".mysqli_error($connection)."');</script>";
    }
}


?>

<!-- Tailwind CDN (optional) + Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
/* Card shadow */
.card-shadow { box-shadow: 0 8px 24px rgba(0,0,0,.08); }

/* Blink entire row */
@keyframes rowBlink {
  0%,100% { background-color: transparent; }
  25%,75% { background-color: #fff7b0; } /* warm light */
  50%     { background-color: #ffef99; }
}
.blink-row { animation: rowBlink .8s ease-in-out 3; }

/* mark style */
.name-mark mark { background:#fff1a6; padding:0 .2rem; border-radius:.2rem; }

/* simple style for alpha buttons (no Tailwind @apply) */
.alpha-btn {
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  font-size: 13px;
  background: #fff;
  cursor: pointer;
  transition: all .15s ease;
}
.alpha-btn:hover { background:#f3e8ff; }
.alpha-btn.active { background:#7c3aed; color:#fff; border-color:#7c3aed; }

/* Print-friendly */
@media print {
  #am-toolbar, #am-filters, #alphaBar { display:none !important; }
  body { background:#fff; }
}
</style>

<main class="max-w-7xl mx-auto px-6 py-8">

  <!-- Message -->
  <?php if (!empty($msg)): ?>
    <div class="mb-4 px-4 py-3 rounded-lg" style="background:#d1fae5;color:#065f46;">
      <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  
<!-- Toolbar -->
<div id="am-toolbar" class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4 p-5 rounded-xl card-shadow" style="background:linear-gradient(90deg,#0f172a 0%, #111827 100%); color:#fff;">
    <div>
        <h3 class="text-2xl font-extrabold flex items-center gap-3" style="background:linear-gradient(90deg,#fbbf24,#fb923c); -webkit-background-clip:text; background-clip:text; color:transparent;">
            <i class="fas fa-crown text-yellow-400"></i> Advanced User Management
        </h3>
        <p class="text-sm mt-1 opacity-80">Manage users — grouped A → Z</p>
    </div>

    <div class="flex gap-2 flex-wrap">
        <button id="exportCsvBtn" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
            <i class="fas fa-file-csv mr-2"></i>Export CSV
        </button>
        <button id="printBtn" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors">
            <i class="fas fa-print mr-2"></i>Print / PDF
        </button>
        <!-- <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors" onclick="openModal('bulkImportModal')">
            <i class="fas fa-upload mr-2"></i>Bulk Import
        </button> -->
        <!-- <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors" onclick="openModal('exportUsersModal')">
            <i class="fas fa-download mr-2"></i>Export Data
        </button> -->
        <button class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity" onclick="openModal('addUserModal')">
            <i class="fas fa-plus mr-2"></i>Add User
        </button>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
        <h2 class="text-xl font-bold mb-4">Add New User</h2>
        <form action="" method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="w-full border rounded px-3 py-2" required>
            <input type="email" name="email" placeholder="Email" class="w-full border rounded px-3 py-2" required>
            <input type="text" name="contact" placeholder="Contact" class="w-full border rounded px-3 py-2" required>
            <input type="text" name="blood_type" placeholder="Blood Type" class="w-full border rounded px-3 py-2" required>
            <!-- Password Field -->
            <input type="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('addUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" name="add_user" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Add</button>
            </div>
        </form>
    </div>
</div>


<!-- JS for modal -->
<script>
function openModal(id){
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id){
    document.getElementById(id).classList.add('hidden');
}
</script>

<!-- Advanced Filters -->
<div id="am-filters" class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200 p-6 mb-6">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div>
      <label class="block text-sm font-semibold text-gray-800 mb-2">Search Users</label>
      <input id="searchInput" type="text" placeholder="Name, email, phone..."
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-800 mb-2">Role Filter</label>
      <select id="roleFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
        <option value="">All Roles</option>
        <option>Admin</option><option>Manager</option><option>User</option><option>Guest</option><option>Donor</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-800 mb-2">Blood Type</label>
      <select id="bloodFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
        <option value="">All Types</option>
        <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
        <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-800 mb-2">Status</label>
      <select id="statusFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
        <option value="">All Status</option>
        <option>Active</option><option>Inactive</option><option>Suspended</option><option>Blocked</option>
      </select>
    </div>
  </div>

  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mt-6 gap-4">
    <!-- Search + Reset -->
    <div class="flex space-x-3">
      <button id="searchBtn"
              class="px-5 py-2 rounded-lg font-medium text-white shadow-md bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 hover:shadow-lg transition-all duration-200">
        <i class="fas fa-search mr-2"></i>Search
      </button>
      <button id="resetBtn"
              class="px-5 py-2 rounded-lg font-medium text-white shadow-md bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 hover:shadow-lg transition-all duration-200">
        <i class="fas fa-undo mr-2"></i>Reset
      </button>
    </div>

    <!-- A–Z Bar -->
    <div id="alphaBar" class="flex flex-wrap gap-2 items-center">
      <span class="text-sm font-medium text-gray-600 mr-2">Quick A–Z:</span>
  <?php
  $letters = range('A','Z');
  foreach ($letters as $L) {
    echo "<button type='button' 
            class='alpha-btn px-3 py-1.5 rounded-lg 
                   bg-gradient-to-r from-purple-500 to-pink-500 
                   text-gray-900 font-bold shadow 
                   hover:shadow-lg hover:scale-105 transition-all duration-200 
                   tracking-wide'
            style='text-shadow:0 1px 2px rgba(255,255,255,0.7);'
            data-letter='$L'>$L</button>";
  }
  echo "<button type='button' 
          class='alpha-btn px-3 py-1.5 rounded-lg 
                 bg-gradient-to-r from-amber-400 to-orange-500 
                 text-gray-900 font-bold shadow 
                 hover:shadow-lg hover:scale-105 transition-all duration-200 
                 tracking-wide'
          style='text-shadow:0 1px 2px rgba(255,255,255,0.7);'
          data-letter='#'>#</button>";
  echo "<button type='button' 
          class='alpha-btn px-3 py-1.5 rounded-lg 
                 bg-gradient-to-r from-emerald-400 to-green-600 
                 text-gray-900 font-bold shadow 
                 hover:shadow-lg hover:scale-105 transition-all duration-200 
                 tracking-wide'
          style='text-shadow:0 1px 2px rgba(255,255,255,0.7);'
          data-letter=''>All</button>";
?>

    </div>
  </div>
</div>


  <!-- Users Table (Alphabet-wise groups) -->
  <div class="bg-white rounded-xl card-shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
      <h4 class="font-semibold">User Database</h4>
      <div class="text-sm text-gray-500">Ordered A → Z by Name</div>
    </div>

    <div class="overflow-x-auto p-2">
      <?php
      if ($result && $result->num_rows > 0) {
          $currentLetter = '';
          while($row = $result->fetch_assoc()) {
              $uid    = htmlspecialchars($row['user_id'] ?? '');
              $uname  = trim($row['user_name'] ?? '');
              $unameE = htmlspecialchars($uname);
              $uemail = htmlspecialchars($row['user_email'] ?? '');
              $ucont  = htmlspecialchars($row['contact'] ?? '');
              $ubld   = htmlspecialchars($row['blood_type'] ?? '');
              $ucity  = htmlspecialchars($row['city'] ?? '');
              $ustat  = htmlspecialchars($row['status'] ?? '');   // optional
              $urole  = htmlspecialchars($row['role'] ?? '');     // optional

              $firstLetter = strtoupper(substr($uname,0,1));
              if (!ctype_alpha($firstLetter)) $firstLetter = '#';

              if ($firstLetter !== $currentLetter) {
                  if ($currentLetter !== '') {
                      echo "</tbody></table></div>";
                  }
                  $currentLetter = $firstLetter;

                  echo "<div class='mb-6 border border-gray-200 rounded-lg overflow-hidden group-block' data-group-letter='{$currentLetter}'>";
                  echo "  <div class='bg-gray-100 px-4 py-2 font-bold text-lg text-gray-700'> {$currentLetter} </div>";
                  echo "  <table class='min-w-full'>";
                  echo "    <thead class='bg-gray-50'>
                            <tr>
                              <th class='px-6 py-3'>ID</th>
                              <th class='px-6 py-3'>Name</th>
                              <th class='px-6 py-3'>Email</th>
                              <th class='px-6 py-3'>Contact</th>
                              <th class='px-6 py-3'>Blood Type</th>
                              <th class='px-6 py-3'>City</th>
                              <th class='px-6 py-3'>Status</th>
                              <th class='px-6 py-3'>Role</th>
                              <th class='px-6 py-3'>Actions</th>
                            </tr>
                          </thead>
                          <tbody class='bg-white divide-y divide-gray-200'>";
              }

              echo "<tr class='user-row hover:bg-gray-50 transition'
                        data-name=\"{$unameE}\"
                        data-email=\"{$uemail}\"
                        data-contact=\"{$ucont}\"
                        data-blood=\"{$ubld}\"
                        data-city=\"{$ucity}\"
                        data-status=\"{$ustat}\"
                        data-role=\"{$urole}\"
                        data-first-letter=\"{$firstLetter}\">
                      <td class='px-6 py-4'>{$uid}</td>
                      <td class='px-6 py-4 name-cell name-mark' data-original='{$unameE}'>{$unameE}</td>
                      <td class='px-6 py-4'>{$uemail}</td>
                      <td class='px-6 py-4'>{$ucont}</td>
                      <td class='px-6 py-4'>{$ubld}</td>
                      <td class='px-6 py-4'>{$ucity}</td>
                      <td class='px-6 py-4'>".($ustat ?: "<span class='text-gray-400'>—</span>")."</td>
                      <td class='px-6 py-4'>".($urole ?: "<span class='text-gray-400'>—</span>")."</td>
                      <td class='px-6 py-4 font-medium'>
                        <a href='view_user.php?id={$uid}' class='text-blue-600 hover:text-blue-900'>View</a>
                        <a href='user_management.php?delete_id={$uid}' onclick=\"return confirm('Are you sure?')\" class='text-red-600 hover:text-red-900 ml-3'>Delete</a>
                      </td>
                    </tr>";
          }
          // close last group
          echo "</tbody></table></div>";
      } else {
          echo "<div class='px-6 py-4 text-center text-gray-500'>No users found</div>";
      }
      ?>
    </div>
  </div>
</main>

<script>
// Helpers
function debounce(fn, delay=200){ let t; return function(){ clearTimeout(t); t=setTimeout(()=>fn.apply(this,arguments), delay); }; }
function highlightName(cell, q){
  const original = cell.getAttribute('data-original') || cell.textContent;
  if(!q){ cell.innerHTML = original; return; }
  const safe = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const re = new RegExp('(' + safe + ')', 'ig');
  cell.innerHTML = original.replace(re, '<mark>$1</mark>');
}
function scrollToFirstVisible(){ const rows = document.querySelectorAll('.user-row'); for(const r of rows){ if(r.style.display !== 'none'){ r.scrollIntoView({behavior:'smooth', block:'center'}); break; } } }
function setAlphaActive(letter){
  document.querySelectorAll('#alphaBar .alpha-btn').forEach(b=>{
    if((b.dataset.letter||'')===letter) b.classList.add('active'); else b.classList.remove('active');
  });
}

// Elements
const searchInput  = document.getElementById('searchInput');
const roleFilter   = document.getElementById('roleFilter');
const bloodFilter  = document.getElementById('bloodFilter');
const statusFilter = document.getElementById('statusFilter');
const alphaBar     = document.getElementById('alphaBar');
const searchBtn    = document.getElementById('searchBtn');
const resetBtn     = document.getElementById('resetBtn');

let currentLetter = ''; // '' means All

function runFilter(){
  const q = (searchInput && searchInput.value || '').trim().toLowerCase();
  const rf= (roleFilter && roleFilter.value || '').trim().toLowerCase();
  const bf= (bloodFilter && bloodFilter.value || '').trim().toLowerCase();
  const sf= (statusFilter && statusFilter.value || '').trim().toLowerCase();
  const alf = (currentLetter || '').toUpperCase();

  const rows = document.querySelectorAll('.user-row');
  let anyMatch=false;

  rows.forEach(row=>{
    const nameCell = row.querySelector('.name-cell');
    const name = (row.dataset.name || '').toLowerCase();
    const email = (row.dataset.email || '').toLowerCase();
    const phone = (row.dataset.contact || '').toLowerCase();
    const blood = (row.dataset.blood || '').toLowerCase();
    const city  = (row.dataset.city || '').toLowerCase();
    const status = (row.dataset.status || '').toLowerCase();
    const role = (row.dataset.role || '').toLowerCase();
    const firstL = (row.dataset.firstLetter || '').toUpperCase();

    const composite = name + ' ' + email + ' ' + phone + ' ' + city + ' ' + blood;
    const matchesSearch = q ? composite.indexOf(q) !== -1 : true;
    const matchesRole = rf ? role === rf : true;
    const matchesBlood= bf ? blood === bf.toLowerCase() : true;
    const matchesStatus= sf ? status === sf : true;
    const matchesAlpha = alf ? firstL === alf : true;

    if(matchesSearch && matchesRole && matchesBlood && matchesStatus && matchesAlpha){
      row.style.display = '';
      highlightName(nameCell, q);
      if(q){
        // add blink then remove
        row.classList.add('blink-row');
        setTimeout(()=>{ row.classList.remove('blink-row'); }, 2500);
      } else {
        row.classList.remove('blink-row');
      }
      anyMatch = true;
    } else {
      row.style.display = 'none';
      highlightName(nameCell, '');
      row.classList.remove('blink-row');
    }
  });

  // hide empty groups
  document.querySelectorAll('.group-block').forEach(g=>{
    const visible = Array.from(g.querySelectorAll('tbody tr')).some(r => r.style.display !== 'none');
    g.style.display = visible ? '' : 'none';
  });

  if(q && anyMatch) scrollToFirstVisible();
}

// events
if(searchInput) searchInput.addEventListener('input', debounce(runFilter,120));
if(roleFilter) roleFilter.addEventListener('change', runFilter);
if(bloodFilter) bloodFilter.addEventListener('change', runFilter);
if(statusFilter) statusFilter.addEventListener('change', runFilter);
if(searchBtn) searchBtn.addEventListener('click', runFilter);

// reset
if(resetBtn) resetBtn.addEventListener('click', ()=>{
  if(searchInput) searchInput.value = '';
  if(roleFilter) roleFilter.value = '';
  if(bloodFilter) bloodFilter.value = '';
  if(statusFilter) statusFilter.value = '';
  currentLetter = '';
  setAlphaActive('');
  runFilter();
});

// alphabet quick filter
if(alphaBar) alphaBar.addEventListener('click', (e)=>{
  const btn = e.target.closest('button[data-letter]');
  if(!btn) return;
  currentLetter = btn.dataset.letter || '';
  setAlphaActive(currentLetter);
  runFilter();
});

// Export CSV
document.getElementById('exportCsvBtn')?.addEventListener('click', ()=>{
  const visibleRows = Array.from(document.querySelectorAll('.user-row')).filter(r=>r.style.display !== 'none');
  if(!visibleRows.length){ alert('No rows to export.'); return; }

  const headers = ['ID','Name','Email','Contact','Blood Type','City','Status','Role'];
  const lines = [headers.join(',')];

  visibleRows.forEach(r=>{
    const tds = r.querySelectorAll('td');
    const id = (tds[0]?.textContent || '').trim();
    const nameCell = r.querySelector('.name-cell');
    const name = (nameCell?.getAttribute('data-original') || nameCell?.textContent || '').trim();
    const email = (tds[2]?.textContent || '').trim();
    const cont = (tds[3]?.textContent || '').trim();
    const blood = (tds[4]?.textContent || '').trim();
    const city = (tds[5]?.textContent || '').trim();
    const stat = (tds[6]?.textContent || '').trim();
    const role = (tds[7]?.textContent || '').trim();
    const esc = v => `"${(v||'').replace(/"/g,'""')}"`;
    lines.push([id,name,email,cont,blood,city,stat,role].map(esc).join(','));
  });

  const blob = new Blob([lines.join('\n')], {type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href = url; a.download = 'users_export.csv';
  document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
});

// Print / PDF
document.getElementById('printBtn')?.addEventListener('click', ()=>window.print());

// initial run
runFilter();
</script>
<?php include('footer.php'); ?>
