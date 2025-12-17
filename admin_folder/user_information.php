<?php
// filepath: c:\xampp\htdocs\TASTELIBMANAN\admin\user_information.php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");  
    exit;
}
require_once '../db_con.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Information - TasteLibmanan</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"/>
    <style>
      body { font-family: "Inter", sans-serif; }
      /* Custom scrollbar styling for a cleaner look */
      .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
      }
      .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
      }
      .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 4px;
      }
      .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
      }
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
            </li>

            <li class="mb-2 w-full">
              <a href="business_management.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 hover:bg-yellow-100">
                <span class="inline-block w-2 h-8 bg-yellow-500 rounded-full mr-3"></span>
                <i class="ri-store-2-line text-xl"></i>
                <span class="ml-2">Business Management</span>
              </a>
            </li>

            <li class="mb-2 w-full">
              <a href="user_information.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 bg-orange-100 font-bold">
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
            </li>
          </ul>
        </div>

        <div class="md:col-span-3">
          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">  
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">User Account Information</h2>
                <div class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-semibold border border-orange-100">
                    <i class="ri-shield-user-line mr-1"></i> Registered Users
                </div>
            </div>
            
            <div class="overflow-hidden rounded-xl border border-gray-100">
              <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="min-w-full leading-normal relative">
                  <thead class="sticky top-0 z-10 bg-gray-50 border-b border-gray-100">
                    <tr>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User ID</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone Number</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Registered</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 bg-white">
                    <?php
                      $sql_user = "SELECT user_id, name, email, phone_number, date_created FROM accounts WHERE user_type = 'user'";
                      $result_user = $conn->query($sql_user);
                      
                      // PHP Limit Logic removed. All rows are displayed.
                      if ($result_user && $result_user->num_rows > 0) {
                        while ($row = $result_user->fetch_assoc()) {
                          echo "<tr class='hover:bg-gray-50 transition'>";
                          echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['user_id']) . "</td>";
                          echo "<td class='px-5 py-4 text-sm font-medium text-gray-800'>" . htmlspecialchars($row['name']) . "</td>";
                          echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['email']) . "</td>";
                          echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['phone_number']) . "</td>";
                          echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['date_created']) . "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='5' class='text-center py-8 text-gray-500'>No registered users found.</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

          </section>
        </div>
      </div>
    </div>            

    <footer class="bg-white border-t border-gray-200 text-gray-600 py-6 mt-auto">
      <div class="container mx-auto text-center">
        <p class="text-sm">&copy; 2025 <span class="font-['Pacifico'] text-gray-800">TasteLibmanan</span> - BPLO Admin Panel</p>
      </div>
    </footer>
    
    <script>
        const profilingBtn = document.getElementById('profiling-btn');
        const adminMenu = document.getElementById('admin-menu');
        
        profilingBtn.addEventListener('click', (e) => { e.stopPropagation(); adminMenu.classList.toggle('hidden'); });
        document.addEventListener('click', (e) => { if (!profilingBtn.contains(e.target) && !adminMenu.contains(e.target)) adminMenu.classList.add('hidden'); });
        
        function toggleDropdown() { document.getElementById('registration-dropdown').classList.toggle('hidden'); }
        
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