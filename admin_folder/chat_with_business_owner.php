<?php

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
    <title>Libmanan Food - BPLO Admin Site</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"/>
    <style>
      body { font-family: "Inter", sans-serif; }
      /* Custom Scrollbar for Chat */
      .scrollbar-hide::-webkit-scrollbar {
          display: none;
      }
      .custom-scrollbar::-webkit-scrollbar {
          width: 6px;
      }
      .custom-scrollbar::-webkit-scrollbar-track {
          background: #f1f1f1; 
      }
      .custom-scrollbar::-webkit-scrollbar-thumb {
          background: #cbd5e1; 
          border-radius: 10px;
      }
      .custom-scrollbar::-webkit-scrollbar-thumb:hover {
          background: #94a3b8; 
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
          <div id="admin-menu"
               class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-xl rounded-xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all duration-200">
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
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-full">
        
        <div class="md:col-span-1 bg-white rounded-md shadow-md p-0 flex flex-col items-center min-h-[600px]">
          <h2 class="text-lg font-bold mb-6 mt-6 text-blue-700 tracking-wide">Dashboard Menu</h2>
          <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
          <ul class="w-full">
            <li class="mb-2 w-full">
              <a href="bplo.php"
                id="admin-dashboard"
                class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3
                <?php echo ($current_page == 'bplo.php') ? 'bg-blue-100 font-bold' : 'hover:bg-blue-100'; ?>">
                <span class="inline-block w-2 h-8 bg-blue-500 rounded-full mr-3"></span>
                <i class="ri-dashboard-line text-xl"></i>
                <span class="ml-2">Dashboard</span>
              </a>
            </li>
        
            <li class="relative group">
              <a href="javascript:void(0);" onclick="toggleDropdown()" class="flex items-center px-5 py-4 rounded-xl transition text-base font-medium w-full justify-between <?php echo ($current_page == 'business_request.php') ? 'bg-green-100 font-bold' : 'hover:bg-green-100'; ?>">
            
                <div class="flex items-center">
                  <span class="inline-block w-2.5 h-10 bg-green-500 rounded-full mr-4"></span>
                  <i class="ri-file-text-line text-xl mr-4"></i>
                  <span>Requests</span>
                </div>
                <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
              </a>
            
              <ul id="registration-dropdown" class="hidden pl-14 mt-1 space-y-1">
                <li><a href="business_request.php?type=new" class="block px-4 py-3 text-base text-gray-500 hover:text-black-600 rounded-lg hover:bg-green-100"><i class="ri-file-text-line text-xl mr-4"></i>New</a></li>
                <li><a href="business_request.php?type=renewal" class="block px-4 py-3 text-base text-gray-500 hover:text-black-600 rounded-lg hover:bg-green-100"><i class="ri-refresh-line text-xl mr-4"></i>Renewal</a></li>
              </ul>
            </li>
        
            <li class="mb-2 w-full">
              <a href="business_management.php"
                id="admin-business-management"
                class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3
                <?php echo ($current_page == 'business_management.php') ? 'bg-yellow-100 font-bold' : 'hover:bg-yellow-100'; ?>">
                <span class="inline-block w-2 h-8 bg-yellow-500 rounded-full mr-3"></span>
                <i class="ri-store-2-line text-xl"></i>
                <span class="ml-2">Business Management</span>
              </a>
            </li>
        
            <li class="mb-2 w-full">
              <a href="user_information.php"
                id="admin-user-information"
                class="flex items-center px-6 py-3 rounded-lg transition font-medium text-gray-700 gap-3
                <?php echo ($current_page == 'user_information.php') ? 'bg-orange-100 font-bold' : 'hover:bg-orange-100'; ?>">
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

        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 h-[650px] flex overflow-hidden">
                
                <div class="w-1/3 border-r border-gray-200 flex flex-col bg-white">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="font-bold text-gray-700">Conversations</h2>
                        <p class="text-xs text-gray-500 mt-1">Select a business owner</p>
                    </div>
                    
                    <div class="overflow-y-auto flex-grow custom-scrollbar p-2">
                        <ul id="owners-list" class="space-y-1">
                            <?php
                                $owners = $conn->query("SELECT user_id, fb_name FROM fb_owner ORDER BY fb_name ASC");
                                if ($owners && $owners->num_rows > 0):
                                  while ($row = $owners->fetch_assoc()):
                                    // Generate initials
                                    $initials = strtoupper(substr($row['fb_name'], 0, 1));
                            ?>
                            <li>
                                <button
                                  id="user-btn-<?= $row['user_id'] ?>"
                                  onclick="openChat(<?= (int)$row['user_id'] ?>, <?= htmlspecialchars(json_encode($row['fb_name']), ENT_QUOTES) ?>)"
                                  class="w-full text-left px-3 py-3 rounded-xl hover:bg-blue-50 transition flex items-center group focus:outline-none"
                                >
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm mr-3 group-hover:bg-blue-200 transition">
                                        <?= $initials ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate"><?= html_entity_decode($row['fb_name']) ?></p>
                                        <p class="text-xs text-gray-500 truncate">Click to send message</p>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-gray-300 opacity-0 group-hover:opacity-100 transition"></i>
                                </button>
                            </li>
                            <?php endwhile; else: ?>
                            <li class="text-center py-8">
                                <div class="text-gray-300 mb-2"><i class="ri-user-unfollow-line text-3xl"></i></div>
                                <span class="text-gray-500 text-sm">No business owners found.</span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="w-2/3 flex flex-col bg-gray-50 relative">
                    
                    <div id="no-chat" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-gray-50 z-10">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="ri-chat-smile-3-line text-4xl text-gray-300"></i>
                        </div>
                        <p class="text-lg font-medium text-gray-500">Select a conversation</p>
                        <p class="text-sm">Choose a business owner from the list to start chatting.</p>
                    </div>

                    <div id="chat-window" class="hidden flex flex-col h-full z-20">
                        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center font-bold text-lg mr-3">
                                <i class="ri-user-3-line"></i>
                            </div>
                            <div>
                                <h4 id="chat-with" class="font-bold text-gray-800 text-base"></h4>
                                <span class="text-xs text-green-500 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Active</span>
                            </div>
                        </div>

                        <div id="chat-box" class="flex-grow p-6 overflow-y-auto custom-scrollbar space-y-4">
                            </div>

                        <div class="bg-white p-4 border-t border-gray-200">
                            <div class="flex items-center bg-gray-100 rounded-full px-4 py-2 border border-gray-200 focus-within:ring-2 focus-within:ring-blue-100 focus-within:border-blue-400 transition">
                                <input type="text" id="message-input" 
                                    class="flex-grow bg-transparent border-none outline-none text-gray-700 placeholder-gray-400 py-2" 
                                    placeholder="Type your message..." autocomplete="off" />
                                <button id="send-btn" class="ml-2 bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md transition transform active:scale-95">
                                    <i class="ri-send-plane-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
      </div>
    </div>

    <script>
        // --- UI Interactions ---
        const profilingBtn = document.getElementById('profiling-btn');
        const adminMenu = document.getElementById('admin-menu');

        profilingBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            adminMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!profilingBtn.contains(event.target) && !adminMenu.contains(event.target)) {
                adminMenu.classList.add('hidden');
            }
        });

        function toggleDropdown() {
            const dropdown = document.getElementById('registration-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // --- Notifications ---
        function updateNotificationBadge() {
        fetch('get_notification_count.php').then(r => r.text()).then(c => {
            const b = document.getElementById('notification-badge');
            if(parseInt(c)>0) { b.textContent = c; b.style.display = 'inline-block'; } 
            else b.style.display = 'none';
        });
      }
      updateNotificationBadge();
      setInterval(updateNotificationBadge, 30000);

        // --- Chat Functionality ---
        let receiverId = null;
        const chatBox = document.getElementById("chat-box");
        const messageInput = document.getElementById("message-input");
        const sendBtn = document.getElementById("send-btn");
        const noChatDiv = document.getElementById("no-chat");
        const chatWindow = document.getElementById("chat-window");

        // 1. Enter Key to Send
        messageInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Stop newline
                sendBtn.click();
            }
        });

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

        // Function to Mark as read
        const originalOpenChat = openChat;

        function openChat(id, name) {
            receiverId = id;

            // UI Toggles
            noChatDiv.classList.add("hidden");
            chatWindow.classList.remove("hidden");
            document.getElementById("chat-with").textContent = name;

            // Sidebar styling (keep your existing logic here)
            document.querySelectorAll('[id^="user-btn-"]').forEach(btn => {
                btn.classList.remove('bg-blue-100', 'ring-1', 'ring-blue-300');
            });
            const activeBtn = document.getElementById(`user-btn-${id}`);
            if(activeBtn) {
                activeBtn.classList.add('bg-blue-100', 'ring-1', 'ring-blue-300');

                // NEW: Immediately remove "Unread" styles when clicked
                activeBtn.classList.remove('font-bold', 'bg-red-50');
                const dot = activeBtn.querySelector('.unread-dot');
                if (dot) dot.remove();
            }
        
            // NEW: Tell backend to mark these messages as read
            fetch('mark_messages_read.php', {
                method: 'POST',
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `sender_id=${id}`
            }).then(() => {
                // Refresh counts immediately after marking as read
                checkUnreadMessages();
            });
        
            // Focus input and load messages
            messageInput.focus();
            loadMessages(true);
        }

        checkUnreadMessages();
        // Run every 3 seconds
        setInterval(checkUnreadMessages, 3000);

        sendBtn.addEventListener("click", () => {
            const message = messageInput.value.trim();
            if (!message || !receiverId) return;

            // Optimistic UI: Clear input immediately
            messageInput.value = "";
            messageInput.focus();

            fetch("send_message.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `receiver_id=${receiverId}&message=${encodeURIComponent(message)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadMessages(true);
                } else {
                    alert("Failed to send message.");
                }
            })
            .catch(err => console.error("Send error:", err));
        });

        function loadMessages(shouldScroll = false) {
            if (!receiverId) return;

            fetch(`get_messages.php?receiver_id=${receiverId}`)
                .then(res => res.json())
                .then(messages => {
                    const currentUserId = <?= $_SESSION['user_id']; ?>;
                    
                    // Check if content actually changed to avoid flickering/unnecessary DOM updates?
                    // For simplicity, we rebuild, but in production, diffing is better.
                    chatBox.innerHTML = ""; 

                    messages.forEach(msg => {
                        const isMine = msg.sender_id == currentUserId;
                        
                        const wrapperDiv = document.createElement("div");
                        wrapperDiv.className = `flex w-full ${isMine ? 'justify-end' : 'justify-start'}`;
                        
                        const bubbleDiv = document.createElement("div");
                        // Modern Bubble Styling
                        bubbleDiv.className = isMine 
                            ? "bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-tr-none max-w-[75%] shadow-sm text-sm" 
                            : "bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-tl-none max-w-[75%] shadow-sm text-sm";
                        
                        bubbleDiv.innerHTML = `
                            <div class="leading-relaxed">${msg.message}</div>
                            <div class="text-[10px] ${isMine ? 'text-blue-100' : 'text-gray-400'} mt-1 text-right opacity-80">
                                ${formatTime(msg.timestamp)}
                            </div>
                        `;
                        
                        wrapperDiv.appendChild(bubbleDiv);
                        chatBox.appendChild(wrapperDiv);
                    });

                    if (shouldScroll) {
                        scrollToBottom();
                    }
                })
                .catch(err => console.error("Load error:", err));
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();

            // Check if date is valid
            if (isNaN(date.getTime())) {
                return timestamp;
            }

            // Check if the message date matches today's date
            const isToday = date.getDate() === now.getDate() &&
                            date.getMonth() === now.getMonth() &&
                            date.getFullYear() === now.getFullYear();

            if (isToday) {
                // If today, return ONLY the time (e.g., "2:30 PM")
                return date.toLocaleTimeString([], { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
            } else {
                // If not today, return Date AND Time (e.g., "Nov 30, 2:30 PM")
                return date.toLocaleDateString([], {
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }

        // Real-time polling
        setInterval(() => {
            if (receiverId) {
                // Check if user is scrolled to bottom before forcing scroll
                const isScrolledToBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 100;
                loadMessages(isScrolledToBottom); 
            }
        }, 3000);

    </script>
  </body>
</html>