<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");  
    exit;
}
require_once '../db_con.php';

// Fetch pending registration requests (for badge/logic if needed)
$pending = $conn->query("SELECT COUNT(*) as cnt FROM business_application WHERE status='pending'")->fetch_assoc()['cnt'];
$status = isset($_GET['status']) ? strtolower($_GET['status']) : 'pending';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$barangay_filter = isset($_GET['barangay']) ? $_GET['barangay'] : '';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Business Management - TasteLibmanan</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      body { font-family: "Inter", sans-serif; }
    </style>
  </head>
  <body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div class="container mx-auto px-6 py-3 flex items-center justify-between relative">
        <h1 class="font-['Pacifico'] text-3xl text-black-600">
          Taste<span class="text-red-500">Libmanan</span>
        </h1>
        <div class="relative">
          <button id="profiling-btn" class="text-gray-600 hover:text-blue-600 transition p-2 rounded-full hover:bg-gray-100">
            <i class="ri-admin-line text-2xl"></i>
          </button>
          <div id="admin-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-xl rounded-xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all duration-200">
            <a href="admin_account.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                <i class="ri-user-settings-line mr-2"></i> Account
            </a>
            <div class="border-t border-gray-100 my-1"></div>
            <a href="../index.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                <i class="ri-logout-box-line mr-2"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container mx-auto mt-6 px-4 mb-8 flex-grow">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <div class="md:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-0 flex flex-col items-center min-h-[600px]">
          <h2 class="text-lg font-bold mb-6 mt-6 text-blue-700 tracking-wide">Dashboard Menu</h2>
          <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
          <ul class="w-full">
            <li class="mb-2 w-full">
              <a href="bplo.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 hover:bg-blue-100">
                <span class="inline-block w-2 h-8 bg-blue-500 rounded-full mr-3"></span>
                <i class="ri-dashboard-line text-xl"></i>
                <span class="ml-2">Dashboard</span>
              </a>
            </li>
        
            <li class="relative group w-full">
              <a href="javascript:void(0);" onclick="toggleDropdown()" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 w-full justify-between hover:bg-green-100">
                <div class="flex items-center">
                  <span class="inline-block w-2 h-8 bg-green-500 rounded-full mr-3"></span>
                  <i class="ri-file-text-line text-xl"></i>
                  <span class="ml-2">Requests</span>
                </div>
                <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
              </a>
              <ul id="registration-dropdown" class="hidden pl-14 mt-1 space-y-1">
                <li><a href="business_request.php?type=new" class="block px-4 py-3 text-base text-gray-500 hover:text-black-600 rounded-lg hover:bg-green-100"><i class="ri-file-text-line text-xl mr-4"></i>New</a></li>
                <li><a href="business_request.php?type=renewal" class="block px-4 py-3 text-base text-gray-500 hover:text-black-600 rounded-lg hover:bg-green-100"><i class="ri-refresh-line text-xl mr-4"></i>Renewal</a></li>
              </ul>
        
            <li class="mb-2 w-full">
              <a href="business_management.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 bg-yellow-100 font-bold">
                <span class="inline-block w-2 h-8 bg-yellow-500 rounded-full mr-3"></span>
                <i class="ri-store-2-line text-xl"></i>
                <span class="ml-2">Business Management</span>
              </a>
            </li>
        
            <li class="mb-2 w-full">
              <a href="user_information.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 hover:bg-orange-100">
                <span class="inline-block w-2 h-8 bg-orange-500 rounded-full mr-3"></span>
                <i class="ri-user-line text-xl"></i>
                <span class="ml-2">User Information</span>
              </a>
            </li>
        
            <li class="mb-2 w-full">
              <a href="notification.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 hover:bg-red-100">
                <span class="inline-block w-2 h-8 bg-red-500 rounded-full mr-3"></span>
                <i class="ri-notification-line text-xl"></i>
                <span class="ml-2">Notifications</span>
                <span id="notification-badge" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5" style="display:none;"></span>
              </a>
            </li>
        
            <li class="w-full">
                <a href="chat_with_business_owner.php"
                    id="toggle-chat-btn" 
                    class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 relative
                    <?php echo($current_page == 'chat_with_business_owner.php') ? 'bg-pink-100 font-bold' : 'hover:bg-pink-100'; ?>">

                    <span class="inline-block w-2 h-8 bg-pink-500 rounded-full mr-3"></span>
                    <i class="ri-chat-1-line text-xl"></i>
                    <span class="ml-2">Chat with Business Owner</span>

                    <span id="sidebar-chat-badge" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden">0</span>
                </a>
            </li>
          </ul>
        </div>

        <div class="md:col-span-3 flex flex-col gap-6">
          
          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
               <div>
                  <h2 class="text-xl font-bold text-gray-800">Business Management</h2>
                  <p class="text-sm text-gray-500 mt-1">Manage, activate, or deactivate registered businesses.</p>
               </div>
               
               <div class="relative w-full md:w-1/3">
                  <input type="text" id="searchBusinessInput"
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50"
                    placeholder="Search businesses...">
                  <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
               </div>
            </div>

            <?php if ($barangay_filter): ?>
              <div class="bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
                <span>Showing businesses in <strong><?php echo htmlspecialchars($barangay_filter); ?></strong></span>
                <a href="business_management.php" class="text-sm font-semibold hover:underline">Clear filter</a>
              </div>
            <?php endif; ?>

            <?php if ($category_filter): ?>
              <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
                <span>Showing businesses in category: <strong><?php echo htmlspecialchars($category_filter); ?></strong></span>
                <a href="business_management.php" class="text-sm font-semibold hover:underline">Clear filter</a>
              </div>
            <?php endif; ?>

            <div class="rounded-xl border border-gray-100 bg-white overflow-hidden">
              <div class="overflow-x-auto overflow-y-auto max-h-[650px]">
                <table class="w-full leading-normal relative border-collapse table-auto">
                  <thead class="sticky top-0 z-10 bg-gray-50 shadow-sm">
                    <tr>
                      <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100 whitespace-nowrap w-16">ID</th>
                      <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Business Name</th>
                      <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Owner</th>
                      <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100 w-1/4">Address</th>
                      <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Category</th>
                      <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Status</th>
                      <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="fbOwnerTableBody" class="divide-y divide-gray-100">
                    <?php
                      $sql_fb_owner = "SELECT 
                          fb_owner.fbowner_id AS business_id,
                          fb_owner.fb_name AS business_name,
                          accounts.name AS owner_name,
                          fb_owner.fb_address AS address,
                          fb_owner.fb_type AS business_category,
                          fb_owner.fb_status AS registration_status,
                          accounts.user_id AS user_id,
                          fb_owner.activation AS activation
                          FROM fb_owner
                          INNER JOIN accounts ON fb_owner.user_id = accounts.user_id";

                      $conditions = [];
                      if ($category_filter) {
                          $conditions[] = "fb_owner.fb_type = '" . $conn->real_escape_string($category_filter) . "'";
                      }
                      if ($barangay_filter) {
                        $conditions[] = "TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(fb_owner.fb_address, ',', 2), ',', -1)) = '" . $conn->real_escape_string($barangay_filter) . "'";
                      }
                      if (!empty($conditions)) {
                        $sql_fb_owner .= " WHERE " . implode(" AND ", $conditions);
                      }
                      $sql_fb_owner .= " ORDER BY fb_owner.fbowner_id DESC";
                    
                      $result_fb_owner = $conn->query($sql_fb_owner);
                      if ($result_fb_owner && $result_fb_owner->num_rows > 0) {
                        while ($row = $result_fb_owner->fetch_assoc()) {
                          $get_ba_id = $conn->query("SELECT ba_id FROM business_application WHERE user_id = " . intval($row['user_id']) . " ORDER BY ba_id DESC LIMIT 1");
                          $ba_id = ($get_ba_id && $get_ba_id->num_rows > 0) ? $get_ba_id->fetch_assoc()['ba_id'] : null;

                          $statusColor = 'bg-gray-100 text-gray-600';
                          if(strtolower($row['registration_status']) == 'approved' || strtolower($row['registration_status']) == 'registered') $statusColor = 'bg-green-100 text-green-700';

                          echo "<tr class='hover:bg-gray-50 transition'>";
                          echo "<td class='px-3 py-3 text-sm text-gray-700 font-bold whitespace-nowrap'>" . htmlspecialchars($row['business_id']) . "</td>";
                          echo "<td class='px-3 py-3 text-sm font-medium text-gray-800'>" . html_entity_decode($row['business_name']) . "</td>";
                          echo "<td class='px-3 py-3 text-sm text-gray-600'>" . html_entity_decode($row['owner_name']) . "</td>";
                          echo "<td class='px-3 py-3 text-xs text-gray-500 leading-snug'>" . htmlspecialchars($row['address']) . "</td>";
                          echo "<td class='px-3 py-3 text-sm text-gray-600 whitespace-nowrap'>" . htmlspecialchars($row['business_category']) . "</td>";
                          echo "<td class='px-3 py-3 text-sm text-center whitespace-nowrap'><span class='px-2 py-1 rounded-full text-xs font-semibold " . $statusColor . "'>" . htmlspecialchars($row['registration_status']) . "</span></td>";
                          echo "<td class='px-3 py-3 text-sm whitespace-nowrap'>
                                <div class='flex items-center justify-center gap-2'>
                                  <a href='view_application.php?ba_id=" . htmlspecialchars($ba_id) . "' class='bg-blue-50 text-blue-600 hover:bg-blue-100 px-2 py-1.5 rounded text-xs font-medium transition border border-blue-200'>View</a>";

                                  if ($row['activation'] === 'Active') {
                                    echo "<button class='bg-red-50 text-red-600 hover:bg-red-100 px-2 py-1.5 rounded text-xs font-medium transition border border-red-200 deactivate-btn' data-userid='" . htmlspecialchars($row['user_id']) . "'>Deactivate</button>";
                                  } else {
                                    echo "<button class='bg-green-50 text-green-600 hover:bg-green-100 px-2 py-1.5 rounded text-xs font-medium transition border border-green-200 activate-btn' data-userid='" . htmlspecialchars($row['user_id']) . "'>Activate</button>";
                                  }
                                  echo "<button class='bg-gray-50 text-gray-500 hover:bg-red-600 hover:text-white px-2 py-1.5 rounded text-xs font-medium transition border border-gray-200 delete-btn' data-userid='" . htmlspecialchars($row['user_id']) . "'>Delete</button>";
                          echo "</div></td></tr>";
                        }
                      } else {
                        echo "<tr><td colspan='7' class='text-center py-8 text-gray-500'>No food business owners found.</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <div id="deactivateModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
              <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100 p-6">
                    <div class="text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                            <i class="ri-alert-line text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Deactivate Business</h3>
                        <p class="text-sm text-gray-500 mb-6">Are you sure you want to deactivate this business? They will not appear in the app.</p>
                        <div class="flex gap-3 justify-center">
                            <button id="cancelDeactivate" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition">Cancel</button>
                            <button id="confirmDeactivate" class="bg-red-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-red-500 transition">Yes, Deactivate</button>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div id="activateModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
              <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100 p-6">
                    <div class="text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 mb-4">
                            <i class="ri-check-line text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Activate Business</h3>
                        <p class="text-sm text-gray-500 mb-6">Are you sure you want to activate this business? They will be visible again.</p>
                        <div class="flex gap-3 justify-center">
                            <button id="cancelActivate" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition">Cancel</button>
                            <button id="confirmActivate" class="bg-green-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-green-500 transition">Yes, Activate</button>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>            

    <footer class="bg-white border-t border-gray-200 text-gray-600 py-6 mt-auto">
      <div class="container mx-auto text-center">
        <p class="text-sm">&copy; 2025 <span class="font-['Pacifico'] text-gray-800">TasteLibmanan</span> - BPLO Admin Panel</p>
      </div>
    </footer>

    <script>
      // Menu Logic
      const profilingBtn = document.getElementById('profiling-btn');
      const adminMenu = document.getElementById('admin-menu');
      profilingBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        adminMenu.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (!profilingBtn.contains(e.target) && !adminMenu.contains(e.target)) adminMenu.classList.add('hidden');
      });
      function toggleDropdown() {
        document.getElementById('registration-dropdown').classList.toggle('hidden');
      }

      // Search Logic
      const searchInput = document.getElementById('searchBusinessInput');
      searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#fbOwnerTableBody tr');
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(filter) ? '' : 'none';
        });
      });

      // Deactivate/Activate Logic
      // 1. Deactivate Logic
      document.querySelectorAll('.deactivate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const userId = this.dataset.userid;
          
          Swal.fire({
            title: 'Deactivate Business?',
            text: "They will no longer appear in the map.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Red-600
            cancelButtonColor: '#f3f4f6',  // Gray-100
            cancelButtonText: '<span style="color: #374151">Cancel</span>', // Dark text for light button
            confirmButtonText: 'Yes, Deactivate'
          }).then((result) => {
            if (result.isConfirmed) {
              // Perform AJAX
              fetch('deactivate_fb_owner.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + encodeURIComponent(userId)
              })
              .then(res => res.text())
              .then(resp => {
                 if(resp.trim() === 'success') {
                    Swal.fire({
                      icon: 'success',
                      title: 'Deactivated!',
                      text: 'Business has been deactivated.',
                      showConfirmButton: false,
                      timer: 1500
                    }).then(() => location.reload());
                 } else {
                    Swal.fire('Error', 'Failed to deactivate business.', 'error');
                 }
              });
            }
          });
        });
      });

      // 2. Activate Logic
      document.querySelectorAll('.activate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const userId = this.dataset.userid;

          Swal.fire({
            title: 'Activate Business?',
            text: "They will be visible in the map again.",
            icon: 'question', // Changed to question mark icon
            showCancelButton: true,
            confirmButtonColor: '#16a34a', // Green-600
            cancelButtonColor: '#f3f4f6',
            cancelButtonText: '<span style="color: #374151">Cancel</span>',
            confirmButtonText: 'Yes, Activate'
          }).then((result) => {
            if (result.isConfirmed) {
              // Perform AJAX
              fetch('activate_fb_owner.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + encodeURIComponent(userId)
              })
              .then(res => res.text())
              .then(resp => {
                 if(resp.trim() === 'success') {
                    Swal.fire({
                      icon: 'success',
                      title: 'Activated!',
                      text: 'Business is now active.',
                      showConfirmButton: false,
                      timer: 1500
                    }).then(() => location.reload());
                 } else {
                    Swal.fire('Error', 'Failed to activate business.', 'error');
                 }
              });
            }
          });
        });
      });

      // Delete logic
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const userId = this.dataset.userid;
        
          Swal.fire({
            title: 'Delete Business?',
            text: "This action cannot be undone. All data regarding this business will be permanently removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000000', // Black or Dark Gray for destructive action
            cancelButtonColor: '#f3f4f6',
            cancelButtonText: '<span style="color: #374151">Cancel</span>',
            confirmButtonText: 'Yes, Delete Permanently'
          }).then((result) => {
            if (result.isConfirmed) {
              // Perform AJAX
              fetch('delete_business_owner.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + encodeURIComponent(userId)
              })
              .then(res => res.text())
              .then(resp => {
                 if(resp.trim() === 'success') {
                    Swal.fire({
                      icon: 'success',
                      title: 'Deleted!',
                      text: 'The business has been removed.',
                      showConfirmButton: false,
                      timer: 1500
                    }).then(() => location.reload());
                 } else {
                    Swal.fire('Error', 'Failed to delete business. ' + resp, 'error');
                 }
              });
            }
          });
        });
      });

      // Notification Badge
      function updateNotificationBadge() {
        fetch('get_notification_count.php').then(r => r.text()).then(c => {
            const b = document.getElementById('notification-badge');
            if(parseInt(c)>0) { b.textContent = c; b.style.display = 'inline-block'; } 
            else b.style.display = 'none';
        });
      }
      updateNotificationBadge();
      setInterval(updateNotificationBadge, 30000);

      // Checking for unread messages
        function checkUnreadMessages() {
            fetch('get_unread_counts.php')
            .then(res => res.json())
            .then(data => {
                // For sidebar badge
                const sidebarBadge = document.getElementById('sidebar-chat-badge');
                if (data.total > 0) {
                    sidebarBadge.textContent = data.total;
                    sidebarBadge.classList.remove('hidden');
                } else {
                    sidebarBadge.classList.add('hidden');
                }

                // For highlighting Specific owner if unread
                const allUserBtns = document.querySelectorAll('[id^="user-btn-"]');

                allUserBtns.forEach(btn => {
                    const userId = btn.id.replace('user-btn-', '');

                    // Checking if the user is the Sender
                    if (data.senders[userId]) {
                        // Highlight the chat-box
                        btn.classList.add('font-bold', 'bg-red-50');

                        if (!btn.querySelector('.unread-dot')) {
                            const dot = document.createElement('div');
                            dot.className = "unread-dot w-3 h-3 bg-red-500 rounded-full absolute right-4 top-1/2 transform -translate-y-1/2 border-2 border-white;"
                            btn.style.position = 'relative';
                            btn.appendChild(dot);
                        }
                    } else {
                        // Unhighlight the chat-box
                        btn.classList.remove('font-bold', 'bg-red-50');
                        const dot = btn.querySelector('.unread-dot');
                        if (dot) dot.remove();
                    }
                });
            });
        }

        checkUnreadMessages();
        // Run every 3 seconds
        setInterval(checkUnreadMessages, 3000);
    </script>
  </body>
</html>