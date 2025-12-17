<?php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");  
    exit;
}
require_once '../db_con.php';

// Mark all as read logic
if (isset($_POST['mark_all_read'])) {
    $conn->query("UPDATE notification SET is_read = 1 WHERE user_id = 0 OR user_id IS NULL"); 
    header("Location: notification.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notifications - TasteLibmanan</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
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
            </li>
        
            <li class="mb-2 w-full">
              <a href="business_management.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 hover:bg-yellow-100">
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
              <a href="notification.php" class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3 bg-red-100 font-bold">
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
          
          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-6">
               <div>
                  <h2 class="text-xl font-bold text-gray-800">Notifications</h2>
                  <p class="text-sm text-gray-500 mt-1">Updates on registrations, renewals.</p>
               </div>
               <form method="POST" action="">
                   <button type="submit" name="mark_all_read" class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                       <i class="ri-check-double-line mr-1"></i> Mark all as read
                   </button>
               </form>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white overflow-hidden">
              <div class="overflow-x-auto overflow-y-auto max-h-[650px]">
                <table class="min-w-full leading-normal relative border-collapse">
                  <thead class="sticky top-0 z-10 bg-gray-50 shadow-sm">
                    <tr>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Type</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">From</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Message</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Date</th>
                      <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                  <?php
                    $result = $conn->query("SELECT n.*, a.name FROM notification n LEFT JOIN accounts a ON n.user_id = a.user_id ORDER BY n.created_at DESC");
                    
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        
                        // Icon Logic
                        $iconClass = 'ri-notification-line';
                        $iconColor = 'text-gray-500';
                        $rowType = htmlspecialchars($row['type']);
                        
                        if ($row['type'] === 'Registration') {
                          $iconClass = 'ri-file-add-line';
                          $iconColor = 'text-green-600';
                        } elseif ($row['type'] === 'Renewal') {
                          $iconClass = 'ri-refresh-line';
                          $iconColor = 'text-blue-600';
                        } elseif ($row['type'] === 'Email_Reply') {
                          $iconClass = 'ri-mail-star-line';
                          $iconColor = 'text-purple-600';
                        }

                        // Read/Unread Styling
                        $isRead = $row['is_read'];
                        $rowBg = $isRead ? 'bg-white' : 'bg-red-50/40'; // Subtle red tint for unread
                        $fontClass = $isRead ? 'font-normal' : 'font-semibold text-gray-900';
                        $textClass = $isRead ? 'text-gray-600' : 'text-gray-800';

                        // Link Logic
                        $link = "#";
                        if (($row['type'] === 'Registration' || $row['type'] === 'Renewal') && $row['ref_id']) {
                          $link = "view_application.php?ba_id=" . urlencode($row['ref_id']);
                        } elseif ($row['type'] === 'Email_Reply' && $row['ref_id']) {
                          $link = "view_email.php?reply_id=" . urlencode($row['ref_id']);
                        }

                        $timeAgo = date('M d, Y h:i A', strtotime($row['created_at']));

                        echo "<tr class='hover:bg-gray-50 transition {$rowBg}'>";
                        
                        // Type Column
                        echo "<td class='px-5 py-4 whitespace-nowrap'>
                                <div class='flex items-center gap-2'>
                                    <i class='{$iconClass} {$iconColor} text-lg'></i>
                                    <span class='text-sm font-medium {$textClass}'>{$rowType}</span>
                                </div>
                              </td>";
                        
                        // From Column
                        echo "<td class='px-5 py-4 text-sm {$fontClass} whitespace-nowrap'>" . 
                                html_entity_decode($row['name'] ?? 'System / Guest') . 
                             "</td>";
                        
                        // Message Column (Truncated)
                        echo "<td class='px-5 py-4 text-sm {$textClass} max-w-xs'>
                                <p class='truncate' title='" . htmlspecialchars($row['message']) . "'>" . 
                                    htmlspecialchars($row['message']) . 
                                "</p>
                              </td>";

                        // Date Column
                        echo "<td class='px-5 py-4 text-sm text-gray-500 whitespace-nowrap'>" . 
                                $timeAgo . 
                             "</td>";

                        // Actions Column
                        echo "<td class='px-5 py-4 text-sm'>
                                <a href='{$link}' onclick='handleNotificationClick(event, {$row['notification_id']}, \"{$link}\")' 
                                   class='bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-medium transition inline-flex items-center gap-1'>
                                   <i class='ri-eye-line'></i> View
                                </a>
                              </td>";
                        
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='5' class='text-center py-10 text-gray-500'>
                                <div class='flex flex-col items-center justify-center'>
                                    <i class='ri-notification-off-line text-4xl mb-2 opacity-50'></i>
                                    <p>No notifications found.</p>
                                </div>
                            </td></tr>";
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

      // Notification Badge Logic
      function updateNotificationBadge() {
        fetch('get_notification_count.php').then(r => r.text()).then(c => {
            const b = document.getElementById('notification-badge');
            if(parseInt(c)>0) { b.textContent = c; b.style.display = 'inline-block'; } 
            else b.style.display = 'none';
        });
      }
      updateNotificationBadge();
      setInterval(updateNotificationBadge, 30000);

      // Handle Click & Mark Read
      function handleNotificationClick(event, notificationId, url) {
        event.preventDefault();
        if (url === '#' || url === '') return;
        
        fetch('mark_notification_read.php?id=' + notificationId, { method: 'POST' })
        .then(() => { window.location.href = url; })
        .catch(err => { console.error(err); window.location.href = url; });
      }

      // Checking for unread messages
        function checkUnreadMessages() {
            fetch('get_unread_counts.php')
            .then(res => res.json())
            .then(data => {
                const sidebarBadge = document.getElementById('sidebar-chat-badge');
                if (data.total > 0) {
                    sidebarBadge.textContent = data.total;
                    sidebarBadge.classList.remove('hidden');
                } else {
                    sidebarBadge.classList.add('hidden');
                }
            });
        }
        checkUnreadMessages();
        setInterval(checkUnreadMessages, 3000);
    </script>
  </body>
</html>