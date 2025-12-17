<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}
require_once '../db_con.php';

// Fetch pending registration requests logic
$type = isset($_GET['type']) ? strtolower($_GET['type']) : 'new';
$status = isset($_GET['status']) ? strtolower($_GET['status']) : 'pending';

// KPIs
$pending = $conn->query("SELECT COUNT(*) as cnt FROM business_application WHERE status='pending' AND application_type = '$type'")->fetch_assoc()['cnt'];
$approved = $conn->query("SELECT COUNT(*) as cnt FROM business_application WHERE status='approved' AND application_type = '$type'")->fetch_assoc()['cnt'];
$rejected = $conn->query("SELECT COUNT(*) as cnt FROM business_application WHERE status='rejected' AND application_type = '$type'")->fetch_assoc()['cnt'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Business Requests - TasteLibmanan</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      body { font-family: "Inter", sans-serif; }
      .custom-scroll::-webkit-scrollbar { width: 6px; }
      .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 8px; }
      .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
      .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
      
      /* Indicator for cards */
      .indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-top: 30px solid; 
        border-left: 30px solid transparent;
        z-index: 10;
        border-top-right-radius: 1rem; /* Matches rounded-2xl roughly */
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
          <button id="profiling-btn" class="text-gray-600 hover:text-green-600 transition p-2 rounded-full hover:bg-gray-100">
            <i class="ri-admin-line text-2xl"></i>
          </button>
          <div id="admin-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-xl rounded-xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all duration-200">
            <a href="admin_account.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600">
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
              <a href="javascript:void(0);" onclick="toggleDropdown()" class="flex items-center px-6 py-3 rounded-lg transition font-medium gap-3 w-full justify-between bg-green-100 text-gray-800 font-bold">
                <div class="flex items-center">
                  <span class="inline-block w-2 h-8 bg-green-500 rounded-full mr-3"></span>
                  <i class="ri-file-text-line text-xl"></i>
                  <span class="ml-2">Requests</span>
                </div>
                <i class="ri-arrow-down-s-line text-gray-600 text-xl"></i>
              </a>
              <ul id="registration-dropdown" class="pl-14 mt-1 space-y-1">
                <li>
                    <a href="business_request.php?type=new" class="block px-4 py-3 text-base rounded-lg transition <?php echo ($type == 'new') ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-500 hover:text-green-600 hover:bg-green-50'; ?>">
                        <i class="ri-file-text-line text-xl mr-4"></i>New
                    </a>
                </li>
                <li>
                    <a href="business_request.php?type=renewal" class="block px-4 py-3 text-base rounded-lg transition <?php echo ($type == 'renewal') ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-500 hover:text-green-600 hover:bg-green-50'; ?>">
                        <i class="ri-refresh-line text-xl mr-4"></i>Renewal
                    </a>
                </li>
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
          
          <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div onclick="window.location.href='business_request.php?status=pending&type=<?php echo $type; ?>'"
                 class="relative bg-white p-6 rounded-2xl shadow-sm border transition-all cursor-pointer hover:shadow-md <?php echo ($status=='pending') ? 'border-yellow-400 ring-1 ring-yellow-400' : 'border-gray-100 hover:border-yellow-200'; ?>">
                 
                 <?php if($status=='pending'): ?>
                    <div class="indicator" style="border-top-color: #fbbf24;"></div>
                 <?php endif; ?>

                 <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i class="ri-time-line text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800"><?php echo $pending; ?></span>
                 </div>
                 <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pending Requests</h3>
            </div>

            <div onclick="window.location.href='business_request.php?status=approved&type=<?php echo $type; ?>'"
                 class="relative bg-white p-6 rounded-2xl shadow-sm border transition-all cursor-pointer hover:shadow-md <?php echo ($status=='approved') ? 'border-green-400 ring-1 ring-green-400' : 'border-gray-100 hover:border-green-200'; ?>">
                 
                 <?php if($status=='approved'): ?>
                    <div class="indicator" style="border-top-color: #4ade80;"></div>
                 <?php endif; ?>

                 <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="ri-check-double-line text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800"><?php echo $approved; ?></span>
                 </div>
                 <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Approved</h3>
            </div>

            <div onclick="window.location.href='business_request.php?status=rejected&type=<?php echo $type; ?>'"
                 class="relative bg-white p-6 rounded-2xl shadow-sm border transition-all cursor-pointer hover:shadow-md <?php echo ($status=='rejected') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-100 hover:border-red-200'; ?>">
                 
                 <?php if($status=='rejected'): ?>
                    <div class="indicator" style="border-top-color: #f87171;"></div>
                 <?php endif; ?>

                 <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <i class="ri-close-circle-line text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800"><?php echo $rejected; ?></span>
                 </div>
                 <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Rejected</h3>
            </div>
          </section>

          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-grow flex flex-col">
            <div class="flex items-center justify-between mb-6">
               <div>
                  <h2 class="text-xl font-bold text-gray-800">
                    <?php
                        if ($status == 'pending') echo "Pending Applications";
                        elseif ($status == 'approved') echo "Approved Applications";
                        elseif ($status == 'rejected') echo "Rejected Applications";
                        else echo "Registration Requests";
                    ?>
                  </h2>
                  <p class="text-sm text-gray-500 mt-1">Manage <?php echo htmlspecialchars($type); ?> business requests.</p>
               </div>
               
               <button onclick="document.getElementById('viewAllModal').classList.remove('hidden')" class="text-sm text-green-600 hover:text-green-800 font-medium hover:bg-green-50 px-4 py-2 rounded-lg transition border border-transparent hover:border-green-100">
                   View All Records
               </button>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
              <table class="min-w-full leading-normal">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Business Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner Name</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <?php
                    // SQL Builder
                    $sql = "SELECT ba.ba_id, business_name, user_id, o.owner_name, bd.business_type, application_date
                            FROM business_application AS ba
                            JOIN owner_details AS o ON ba.ba_id = o.ba_id
                            JOIN business_details AS bd ON ba.ba_id = bd.ba_id
                            WHERE status = '$status' AND application_type = '$type'
                            ORDER BY application_date DESC";
                    
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        $count = 0;
                        while ($row = $result->fetch_assoc()) {
                            if ($count >= 5) break; // Limit 5 for the dashboard view
                            $count++;
                            
                            echo "<tr class='hover:bg-gray-50 transition'>";
                            echo "<td class='px-5 py-4 text-sm font-medium text-gray-900'>" . htmlspecialchars($row['business_name']) . "</td>";
                            echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['owner_name']) . "</td>";
                            echo "<td class='px-5 py-4 text-sm text-gray-600'><span class='px-2 py-1 bg-gray-100 rounded text-xs'>" . htmlspecialchars($row['business_type']) . "</span></td>";
                            echo "<td class='px-5 py-4 text-sm text-gray-500'>" . date('M d, Y', strtotime($row['application_date'])) . "</td>";
                            
                            echo "<td class='px-5 py-4 text-sm text-center'>";
                            echo "<div class='flex justify-center gap-2'>";
                            
                            // View Button (Always available)
                            echo "<a href='view_application.php?ba_id=" . htmlspecialchars($row['ba_id']) . "' class='text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-2 rounded-lg transition' title='View'><i class='ri-eye-line text-lg'></i></a>";

                            if ($status == 'pending') {
                                echo "<button class='approve-btn text-green-500 hover:text-green-700 hover:bg-green-50 p-2 rounded-lg transition' data-baid='" . htmlspecialchars($row['ba_id']) . "' title='Approve'><i class='ri-check-line text-lg'></i></button>";
                                echo "<button class='reject-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition' data-baid='" . htmlspecialchars($row['ba_id']) . "' title='Reject'><i class='ri-close-line text-lg'></i></button>";
                            } elseif ($status == 'approved') {
                                $uid = intval($row['user_id']);
                                $checkUser = $conn->query("SELECT user_type FROM accounts WHERE user_id = $uid")->fetch_assoc();
                                if ($checkUser && $checkUser['user_type'] === 'fb_owner') {
                                    echo "<span class='text-green-600 text-xs font-bold border border-green-200 bg-green-50 px-2 py-1 rounded'>Confirmed</span>";
                                } else {
                                    echo "<button class='confirm-btn text-purple-500 hover:text-purple-700 hover:bg-purple-50 p-2 rounded-lg transition' data-userid='" . htmlspecialchars($row['user_id']) . "' data-baid='" . htmlspecialchars($row['ba_id']) . "' title='Confirm Owner'><i class='ri-user-star-line text-lg'></i></button>";
                                }
                            } elseif ($status == 'rejected') {
                                echo "<button class='remove-rejected-btn text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition' data-baid='" . htmlspecialchars($row['ba_id']) . "' title='Remove'><i class='ri-delete-bin-line text-lg'></i></button>";
                            }

                            echo "</div></td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='px-5 py-10 text-center text-gray-400'>
                                <i class='ri-folder-open-line text-4xl mb-2 block'></i>
                                No records found in this category.
                              </td></tr>";
                    }
                  ?>
                </tbody>
              </table>
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

    <div id="viewAllModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60] hidden backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-4xl h-[80vh] flex flex-col transform transition-all scale-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">All <?php echo ucfirst($status); ?> Applications</h2>
                <button onclick="document.getElementById('viewAllModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-2xl"></i></button>
            </div>
            <div class="overflow-y-auto custom-scroll flex-grow pr-2">
                <table class="min-w-full leading-normal">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Business</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                            $all_result = $conn->query($sql); // Re-run query for all
                            if($all_result && $all_result->num_rows > 0){
                                while($row = $all_result->fetch_assoc()){
                                    // Simplified row for modal
                                    echo "<tr>";
                                    echo "<td class='px-5 py-4 text-sm font-medium'>" . htmlspecialchars($row['business_name']) . "</td>";
                                    echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['owner_name']) . "</td>";
                                    echo "<td class='px-5 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['business_type']) . "</td>";
                                    echo "<td class='px-5 py-4 text-center'><a href='view_application.php?ba_id=" . htmlspecialchars($row['ba_id']) . "' target='_blank' class='text-blue-600 hover:underline text-sm'>View Details</a></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60] hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-check-line text-3xl"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Approve Application?</h2>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to approve this application?</p>
            <div class="flex justify-center gap-3">
                <button id="cancelApprove" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium">Cancel</button>
                <button id="confirmApprove" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 font-medium shadow-md shadow-green-200">Yes, Approve</button>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60] hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-close-line text-3xl"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Reject Application?</h2>
            <p class="text-sm text-gray-500 mb-6">This action will move the application to the rejected list.</p>
            <div class="flex justify-center gap-3">
                <button id="cancelReject" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium">Cancel</button>
                <button id="confirmReject" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 font-medium shadow-md shadow-red-200">Yes, Reject</button>
            </div>
        </div>
    </div>

    <div id="confirmFbOwnerModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60] hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
            <div class="w-16 h-16 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-user-star-line text-3xl"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Confirm Owner Role?</h2>
            <p class="text-sm text-gray-500 mb-6">This will upgrade the user to "Food Business Owner".</p>
            <div class="flex justify-center gap-3">
                <button id="cancelConfirmFbOwner" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium">Cancel</button>
                <button id="confirmFbOwnerBtn" class="px-4 py-2 rounded-lg bg-purple-500 text-white hover:bg-purple-600 font-medium shadow-md shadow-purple-200">Confirm</button>
            </div>
        </div>
    </div>
    
    <div id="remove-rejected-Modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60] hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
            <h2 class="text-lg font-bold text-red-600 mb-2">Delete Record</h2>
            <p class="text-sm text-gray-500 mb-6">Are you sure? This cannot be undone.</p>
            <div class="flex justify-center gap-3">
                <button id="cancelRemove" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Cancel</button>
                <button id="confirmRemove" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>


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

      // --- Action Handlers (AJAX) ---
      let selectedId = null;
      let selectedRow = null;
      let selectedUserId = null;
      let selectedBtn = null;

      // Approve Logic
      document.querySelectorAll('.approve-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            selectedId = this.dataset.baid;
            selectedRow = this.closest('tr');
            document.getElementById('approveModal').classList.remove('hidden');
          });
      });
      document.getElementById('cancelApprove').onclick = () => document.getElementById('approveModal').classList.add('hidden');
      
      document.getElementById('confirmApprove').onclick = () => {
          fetch('approve_application.php', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'ba_id=' + selectedId })
          .then(r => r.text()).then(res => {
              document.getElementById('approveModal').classList.add('hidden'); // Close modal first
              
              if(res.trim() === 'success') { 
                  Swal.fire({
                      icon: 'success',
                      title: 'Approved!',
                      text: 'Application has been successfully approved.',
                      showConfirmButton: false,
                      timer: 1500,
                      confirmButtonColor: '#22c55e' // Green
                  }).then(() => {
                      location.reload(); 
                  });
              } else {
                  Swal.fire('Error', res, 'error');
              }
          });
      };

      // Reject Logic
      document.querySelectorAll('.reject-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            selectedId = this.dataset.baid;
            selectedRow = this.closest('tr');
            document.getElementById('rejectModal').classList.remove('hidden');
          });
      });
      document.getElementById('cancelReject').onclick = () => document.getElementById('rejectModal').classList.add('hidden');
      
      document.getElementById('confirmReject').onclick = () => {
          fetch('reject_application.php', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'ba_id=' + selectedId })
          .then(r => r.text()).then(res => {
              document.getElementById('rejectModal').classList.add('hidden'); // Close modal first

              if(res.trim() === 'success') { 
                  Swal.fire({
                      icon: 'success', // Using success icon even for reject as the action was successful
                      title: 'Rejected',
                      text: 'Application has been moved to rejected list.',
                      showConfirmButton: false,
                      timer: 1500,
                      confirmButtonColor: '#ef4444' // Red
                  }).then(() => {
                      location.reload();
                  });
              } else {
                  Swal.fire('Error', res, 'error');
              }
          });
      };

      // Confirm Owner Logic
      document.querySelectorAll('.confirm-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            selectedId = this.dataset.baid;
            selectedUserId = this.dataset.userid;
            selectedBtn = this;
            document.getElementById('confirmFbOwnerModal').classList.remove('hidden');
          });
      });
      document.getElementById('cancelConfirmFbOwner').onclick = () => document.getElementById('confirmFbOwnerModal').classList.add('hidden');
      
      document.getElementById('confirmFbOwnerBtn').onclick = () => {
          fetch('confirm_fb_owner.php', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'user_id=' + selectedUserId + '&ba_id=' + selectedId })
          .then(r => r.text()).then(res => {
              document.getElementById('confirmFbOwnerModal').classList.add('hidden');

              if(res.trim() === 'success') {
                  // Visual update without reload
                  selectedBtn.outerHTML = "<span class='text-green-600 text-xs font-bold border border-green-200 bg-green-50 px-2 py-1 rounded'>Confirmed</span>";
                  
                  Swal.fire({
                      icon: 'success',
                      title: 'Owner Confirmed',
                      text: 'User upgraded to Food Business Owner.',
                      timer: 2000,
                      showConfirmButton: false
                  });
              } else {
                  Swal.fire('Error', 'Failed to confirm owner.', 'error');
              }
          });
      };

      // Remove Rejected Logic
      document.querySelectorAll('.remove-rejected-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            selectedId = this.dataset.baid;
            selectedRow = this.closest('tr');
            document.getElementById('remove-rejected-Modal').classList.remove('hidden');
          });
      });
      document.getElementById('cancelRemove').onclick = () => document.getElementById('remove-rejected-Modal').classList.add('hidden');
      
      document.getElementById('confirmRemove').onclick = () => {
         fetch('remove_rejected_application.php', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'ba_id=' + selectedId })
         .then(r => r.text()).then(res => {
             document.getElementById('remove-rejected-Modal').classList.add('hidden');

             if(res.trim() === 'success') {
                 Swal.fire({
                      icon: 'success',
                      title: 'Deleted',
                      text: 'Record removed successfully.',
                      showConfirmButton: false,
                      timer: 1500
                 }).then(() => {
                     location.reload();
                 });
             } else {
                 Swal.fire('Error', res, 'error');
             }
         });
      };

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