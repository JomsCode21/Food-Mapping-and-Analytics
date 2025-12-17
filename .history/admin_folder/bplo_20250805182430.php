<?php
// filepath: c:\xampp\htdocs\TASTELIBMANAN\admin\bplo.php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}
     require_once '../db_con.php';
        // Fetch pending registration requests
        $pending = $conn->query("SELECT COUNT(*) as cnt FROM business_applications WHERE status='pending'")->fetch_assoc()['cnt'];
        $approved = $conn->query("SELECT COUNT(*) as cnt FROM business_applications WHERE status='approved'")->fetch_assoc()['cnt'];
        $rejected = $conn->query("SELECT COUNT(*) as cnt FROM business_applications WHERE status='rejected'")->fetch_assoc()['cnt'];
        $status = isset($_GET['status']) ? $_GET['status'] : 'pending';

      $sql = "SELECT user_id, business_name, owner_name, business_type, application_date FROM business_applications WHERE status='$status' ORDER BY application_date DESC";
      $result = $conn->query($sql);
        
?>
<script>
  // Make the session user_id available to JS
  const sessionUserId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
</script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Libmanan Food - BPLO Admin Site</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
      body {
        font-family: "Inter", sans-serif;
      }

      .indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-top: 30px solid #34d399; /* Green */
        border-left: 30px solid transparent;
        z-index: 10;
        display: none;
      }

      .hidden {
        display: none;
      }
    </style>
  </head>
  <body class="bg-gray-100 text-gray-800">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md">
      <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="#" class="text-xl font-semibold text-primary">
          TasteLibmanan - BPLO Admin
        </a>
        <div>
          <button 
  class="text-sm bg-red-500 text-white px-4 py-2 rounded-md"
  onclick="window.location.href='../index.php';"
>
  Logout
</button>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Sidebar -->
        <div class="md:col-span-1 bg-white rounded-md shadow-md p-4">
          <h2 class="text-lg font-semibold mb-2">Dashboard Menu</h2>
          <ul>
            <li class="mb-2">
              <a href="#" class="hover:bg-gray-200 hover:text-primary flex items-center" onclick="updateMenuItem(this)">
                <i class="ri-dashboard-line mr-2"></i>
                <span id="dashboard-text">Dashboard</span> </a>
            </li>
            <li class="mb-2">
              <a href="#" class="hover:bg-gray-200 hover:text-primary flex items-center" onclick="updateMenuItem(this)">
                <i class="ri-file-text-line mr-2"></i>
                <span id="registration-text">Registration Requests</span> </a>
            </li>
            <li class="mb-2">
              <a href="#" class="hover:bg-gray-200 hover:text-primary flex items-center" onclick="updateMenuItem(this)">
                <i class="ri-store-2-line mr-2"></i>
                <span id="business-text">Business Management</span> </a>
            </li>
            <li>
              <a href="#" class="hover:bg-gray-200 hover:text-primary flex items-center" onclick="updateMenuItem(this)">
                <i class="ri-notification-line mr-2"></i>
                <span id="notification-text">Notifications</span> </a>
            </li>
          </ul>
        </div>

    <script>
      let activeMenuItem = null;

      function updateMenuItem(item) {
        // Remove bold from previously active item
        if (activeMenuItem) {
          activeMenuItem.querySelector('span').classList.remove('font-bold');
        }

        // Add bold to the clicked item
        item.querySelector('span').classList.add('font-bold');

        activeMenuItem = item;
      }
    </script>

        <!-- Main Content Area -->
        <div class="md:col-span-3">
          <!-- KPIs -->
         <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
           <div id="card-pending" class="status-card bg-white rounded-md shadow-md p-4 transition-transform hover:scale-105 cursor-pointer relative <?php if($status=='pending') echo 'ring ring-green-500'; ?>" onclick="window.location.href='bplo.php?status=pending'">
              <div id="indicator-pending" class="indicator absolute top-0 right-0 w-4 h-4 bg-green-400 rounded-bl-md <?php if($status=='pending') echo ''; else echo 'hidden'; ?>"></div>
              <h3 class="text-md font-semibold mb-2">Pending Registrations</h3>
              <p class="text-3xl font-bold text-primary"><?php echo $pending; ?></p>
            </div>
            <div id="card-approved" class="status-card bg-white rounded-md shadow-md p-4 transition-transform hover:scale-105 cursor-pointer relative <?php if($status=='approved') echo 'ring ring-green-500'; ?>" onclick="window.location.href='bplo.php?status=approved'">
              <div id="indicator-approved" class="indicator absolute top-0 right-0 w-4 h-4 bg-green-400 rounded-bl-md <?php if($status=='approved') echo ''; else echo 'hidden'; ?>"></div>
              <h3 class="text-md font-semibold mb-2">Approved Applications</h3>
              <p class="text-3xl font-bold text-green-500"><?php echo $approved; ?></p>
            </div>
            <div id="card-rejected" class="status-card bg-white rounded-md shadow-md p-4 transition-transform hover:scale-105 cursor-pointer relative <?php if($status=='rejected') echo 'ring ring-green-500'; ?>" onclick="window.location.href='bplo.php?status=rejected'">
              <div id="indicator-rejected" class="indicator absolute top-0 right-0 w-4 h-4 bg-green-400 rounded-bl-md <?php if($status=='rejected') echo ''; else echo 'hidden'; ?>"></div>
              <h3 class="text-md font-semibold mb-2">Rejected Applications</h3>
              <p class="text-3xl font-bold text-red-500"><?php echo $rejected; ?></p>
            </div>
          </section>
                    

<!-- Registration Requests Table -->
<section class="bg-white rounded-md shadow-md p-4 mb-6" id="registrationTable">  
  <h2 class="text-lg font-semibold mb-4" id="tableTitle">
    <?php
    if ($status == 'pending') {
      echo "Pending Registration Requests";
    } elseif ($status == 'approved') {
      echo "Approved Applications";
    }  elseif ($status == 'rejected') {
    echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
            <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
            <button class='bg-red-500 text-white px-3 py-1 rounded-md remove-rejected-btn' data-business='" . htmlspecialchars($row['business_name']) . "'>Remove</button>
          </td>";
} else {
      echo "Registration Requests";
    }
  ?>
  </h2>
  <div class="overflow-x-auto">
    <table class="min-w-full leading-normal">
      <thead>
        <tr>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Business Name
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Owner Name
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Category
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Date Submitted
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Actions
          </th>
        </tr>
      </thead>
      
    <tbody id="tableBody">
      <?php
      
        $sql_fb_owner = "SELECT 
            fb_owner.fbowner_id AS business_id,
            fb_owner.fb_name AS business_name,
            accounts.name AS owner_name,
            fb_owner.fb_address AS address,
            fb_owner.fb_phone_number AS contact_number,
            fb_owner.fb_type AS business_category,
            fb_owner.fb_status AS registration_status,
            fb_owner.user_id,
            accounts.email,
            accounts.phone_number
        FROM fb_owner
        INNER JOIN accounts ON fb_owner.user_id = accounts.user_id
        ORDER BY fb_owner.fbowner_id DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['business_name']) . "</td>";
          echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['owner_name']) . "</td>";
          echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['business_type']) . "</td>";
          echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['application_date']) . "</td>";

          if ($status == 'approved') {
             if ($status == 'approved') {
    $userType = '';
    $uid = intval($row['user_id']);
    $userTypeResult = $conn->query("SELECT user_type FROM accounts WHERE user_id = $uid");
    if ($userTypeResult && $userTypeResult->num_rows > 0) {
        $userType = $userTypeResult->fetch_assoc()['user_type'];
    }
    echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>";
    if ($userType === 'fb_owner') {
        echo "<button class='bg-green-600 text-white px-3 py-1 rounded-md' disabled>Confirmed</button>";
    } else {
        echo "<button class='bg-green-600 text-white px-3 py-1 rounded-md confirm-btn' data-userid='" . htmlspecialchars($row['user_id']) . "'>Confirm</button>";
    }
    echo "</td>";
}
          } elseif ($status == 'pending') {
              echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
                      <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
                      <button class='bg-green-500 text-white px-3 py-1 rounded-md approve-btn' data-business='" . htmlspecialchars($row['business_name']) . "'>Approve</button>
                      <button class='bg-red-500 text-white px-3 py-1 rounded-md reject-btn' data-business='" . htmlspecialchars($row['business_name']) . "'>Reject</button>
                    </td>";
          } else {
              echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
                      <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
                    </td>";
          }
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center py-4'>No registration requests found.</td></tr>";
      }
     
      ?>
      </tbody>

      <tbody id="rejectedTableBody">
      <!-- Rejected rows go here -->
      </tbody>

    </table>
    <!-- Approve Confirmation Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
    <h2 class="text-lg font-semibold mb-4 text-center text-green-600">Approve Application</h2>
    <p class="mb-6 text-center">Are you sure you want to approve this application?</p>
    <div class="flex justify-center gap-4">
      <button id="cancelApprove" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
      <button id="confirmApprove" class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
    </div>
  </div>
</div>
      <!-- Reject Confirmation Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
    <h2 class="text-lg font-semibold mb-4 text-center text-red-600">Reject Application</h2>
    <p class="mb-6 text-center">Are you sure you want to reject this application?</p>
    <div class="flex justify-center gap-4">
      <button id="cancelReject" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
      <button id="confirmReject" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
    </div>
  </div>
</div>
  </div>
      <!-- Confirm FB Owner Modal -->
<div id="confirmFbOwnerModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
    <h2 class="text-lg font-semibold mb-4 text-center text-green-600">Confirm Food Business Owner</h2>
    <p class="mb-6 text-center">Are you sure you want to confirm this owner as a Food Business Owner?</p>
    <div class="flex justify-center gap-4">
      <button id="cancelConfirmFbOwner" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</button>
      <button id="confirmFbOwnerBtn" class="bg-green-500 text-white px-4 py-2 rounded">Confirm</button>
    </div>
  </div>
</div>
  <!-- View All Button -->
  <div class="flex justify-center mt-4">
    <button class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition-transform transform hover:scale-105">
      View All
    </button>
  </div>
</section>
          

          <!-- Data Analytics -->
          <section class="bg-white rounded-md shadow-md p-4">
            <h2 class="text-lg font-semibold mb-4">Business Trends Analytics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Bar Chart -->
              <div class="bg-white rounded-md p-4 shadow-sm">
                <canvas id="barChart" class="w-full h-50"></canvas>
              </div>

              <!-- Pie Chart -->
              <div class="bg-white rounded-md p-4 shadow-sm">
                <canvas id="pieChart" class="w-full h-48"></canvas>
              </div>
            </div>
            
          </section>
<br>
       <!-- Food Business Owner Information Table -->
          <section class="bg-white rounded-md shadow-md p-4 mb-6">
  <h2 class="text-lg font-semibold mb-4">Food Business Owner Information</h2>
  <div class="overflow-x-auto">
    <table class="min-w-full leading-normal">
      <thead>
       
        <tr>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Business ID
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Business Name
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Owner Name
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Address
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Contact Number
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Business Category
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Registration Status
          </th>
          <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Actions
          </th>
        </tr>
      </thead>

       <tbody id="fbOwnerTableBody">

        <?php
        // Food Business Owner Information Table

   $sql_fb_owner = "SELECT 
    fb_owner.fbowner_id AS business_id,
    fb_owner.fb_name AS business_name,
    accounts.name AS owner_name,
    fb_owner.fb_address AS address,
    fb_owner.fb_phone_number AS contact_number,
    fb_owner.fb_type AS business_category,
    fb_owner.fb_status AS registration_status
FROM fb_owner
INNER JOIN accounts ON fb_owner.user_id = accounts.user_id
ORDER BY fb_owner.fbowner_id DESC";
$result_fb_owner = $conn->query($sql_fb_owner);

if ($result_fb_owner && $result_fb_owner->num_rows > 0) {
    while ($row = $result_fb_owner->fetch_assoc()) {
    echo "<tr>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['business_id']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['business_name']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['owner_name']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['address']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['contact_number']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['business_category']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['registration_status']) . "</td>";
               echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
                       <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>

                       <button class='bg-red-500 text-white px-3 py-1 rounded-md'>Deactivate</button>
                     </td>";
               echo "</tr>";
  }
} else {
  echo "<tr><td colspan='8' class='text-center py-4'>No food business owners found.</td></tr>";
}
        ?>
      </tbody>
    </table>
  </div>

</section>

          <!-- User table -->
          
          <section class="bg-white rounded-md shadow-md p-4 mb-6">  
            <h2 class="text-lg font-semibold mb-4">User Account Information</h2>
            <div class="overflow-x-auto">
              <table class="min-w-full leading-normal">
                <thead>
                  
                <tr>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User ID
                  </th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Name
                  </th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Email
                  </th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Phone Number
                  </th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Date Registered
                  </th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
                    <tbody id="userTableBody">
                    <?php
                    // User Account Information Table
                    $sql_user = "SELECT user_id, name, email, phone_number, date_created FROM accounts WHERE user_type = 'user'";
                    $result_user = $conn->query($sql_user);

                    if ($result_user && $result_user->num_rows > 0) {
                      while ($row = $result_user->fetch_assoc()) {
                        echo "<tr>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['user_id']) . "</td>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['email']) . "</td>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['phone_number']) . "</td>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>" . htmlspecialchars($row['date_created']) . "</td>";
                                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
                                                        <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
                                                        <button class='bg-green-500 text-white px-3 py-1 rounded-md'>Edit</button>
                                                        <button class='bg-red-500 text-white px-3 py-1 rounded-md'>Deactivate</button>
                                                      </td>";
                                                echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center py-4'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </section>
  
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
      <div class="container mx-auto text-center">
        <p>&copy; 2025 TasteLibmanan - BPLO Admin</p>
      </div>
    </footer>

    <script>
      // Function to show the selected table and indicator
  function showIndicator(type) { 
  const table = document.getElementById("registrationTable");
  const tableBody = document.getElementById("tableBody");
  const tableTitle = table.querySelector("h2");

  // Update section title based on type
  const titles = {
    pending: "Pending Registration Requests",
    approved: "Approved Applications",
    rejected: "Rejected Applications",
  };
  tableTitle.textContent = titles[type] || "Registration Requests";

  // Show table
  table.style.display = "block";

  // Hide all indicators
  document.querySelectorAll(".indicator").forEach((el) => {
    el.style.display = "none";
  });

  // Remove highlight from all cards
  document.querySelectorAll(".status-card").forEach((card) => {
    card.classList.remove("ring", "ring-green-500");
  });

  // Highlight selected card and show its indicator
  const selected = document.getElementById(`card-${type}`);
  const indicator = document.getElementById(`indicator-${type}`);
  if (selected) {
    indicator.style.display = "block"; // Show indicator
    selected.classList.add("ring", "ring-green-500");
  }

  // Bar chart initialization
  const barChart = new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: [
        "Aling Nena's",
        "Lechon Juan",
        "Cafe Sa Puno",
      "Tita's Kitchen",
      "Juan's Lechon",
      "Luna Cafe",
      "Panda Express",
      "Sarap Bites",
      "Bistro Miki",
      "Hapag Kubo",
    ],
    datasets: [
      {
        label: "Number of Visits",
        data: [150, 200, 175, 120, 250, 180, 210, 160, 140, 190], // Visits for each food business
        backgroundColor: "#34d399",
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      x: {
        ticks: {
          maxRotation: 45, // Rotate labels for better visibility
          minRotation: 0,
        },
      },
      y: {
        beginAtZero: true,
      },
    },
  },
});


      // Pie chart initialization
      const pieChart = new Chart(document.getElementById("pieChart"), {
        type: "pie",
        data: {
          labels: ["Restaurant", "Cafe", "Fastfood", "Bakery"],
          datasets: [
            {
              data: [
            <?php echo $category_counts['Restaurant']; ?>,
            <?php echo $category_counts['Cafe']; ?>,
            <?php echo $category_counts['Fastfood']; ?>,
            <?php echo $category_counts['Bakery']; ?>
          ],
              backgroundColor: ["#34d399", "#4caf50", "#f44336" , "#FF0000"],
            },
          ],
        },
      });
    }
      document.addEventListener("DOMContentLoaded", function() {
  showIndicator('<?php echo $status; ?>');
}); 
      <?php
$category_counts = [
    'Restaurant' => 0,
    'Cafe' => 0,
    'Fastfood' => 0,
    'Bakery' => 0
];

$sql = "SELECT fb_type, COUNT(*) as total FROM fb_owner GROUP BY fb_type";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $type = $row['fb_type'];
        if (isset($category_counts[$type])) {
            $category_counts[$type] = (int)$row['total'];
        }
    }
}
?>
    </script>

    <script>
      
let businessToReject = null;
let rowToDelete = null;
let businessToApprove = null;
let rowToApprove = null;
let ownerToConfirm = null;
let confirmBtnRef = null;

// reject button functionality
document.querySelectorAll('.remove-rejected-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const businessName = this.getAttribute('data-business');
    const row = this.closest('tr');
    fetch('permanent_delete_application.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ business_name: businessName })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        row.remove();
      } else {
        alert('Failed to remove application.');
      }
    });
  });
});
document.querySelectorAll('.reject-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    businessToReject = this.getAttribute('data-business');
    rowToDelete = this.closest('tr');
    document.getElementById('rejectModal').classList.remove('hidden');
  });
});

document.getElementById('cancelReject').onclick = function() {
  document.getElementById('rejectModal').classList.add('hidden');
  businessToReject = null;
  rowToDelete = null;
};

document.getElementById('confirmReject').onclick = function() {
  if (!businessToReject) return;
  fetch('delete_application.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ business_name: businessToReject })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if (rowToDelete) {
        // Remove from pending table
        rowToDelete.remove();

        // Update indicators
        const pendingCountElem = document.querySelector('#card-pending .text-primary');
        const rejectedCountElem = document.querySelector('#card-rejected .text-red-500');
        if (pendingCountElem) {
          let count = parseInt(pendingCountElem.textContent, 10) || 1;
          pendingCountElem.textContent = Math.max(count - 1, 0);
        }
        if (rejectedCountElem) {
          let count = parseInt(rejectedCountElem.textContent, 10) || 0;
          rejectedCountElem.textContent = count + 1;
        }

        // Add to rejected table
        const rejectedTableBody = document.querySelector('tbody#rejectedTableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${rowToDelete.children[0].textContent}</td>
          <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${rowToDelete.children[1].textContent}</td>
          <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${rowToDelete.children[2].textContent}</td>
          <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${rowToDelete.children[3].textContent}</td>
          <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
            <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
            <button class='bg-red-500 text-white px-3 py-1 rounded-md remove-rejected-btn' data-business="${businessToReject}">Remove</button>
          </td>
        `;
        rejectedTableBody.appendChild(newRow);

        // Attach remove event to the new button
        newRow.querySelector('.remove-rejected-btn').onclick = function() {
          const businessName = this.getAttribute('data-business');
          fetch('permanent_delete_application.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ business_name: businessName })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              newRow.remove();
              // Update rejected indicator
              if (rejectedCountElem) {
                let count = parseInt(rejectedCountElem.textContent, 10) || 1;
                rejectedCountElem.textContent = Math.max(count - 1, 0);
              }
            } else {
              alert('Failed to remove application.');
            }
          });
        };
      }
     
    } else {
      alert('Failed to reject application.');
    }
    document.getElementById('rejectModal').classList.add('hidden');
    businessToReject = null;
    rowToDelete = null;
  })
  .catch(() => {
    alert('Network error.');
    document.getElementById('rejectModal').classList.add('hidden');
    businessToReject = null;
    rowToDelete = null;
  });
};


   // Approve button functionality
document.querySelectorAll('.approve-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    businessToApprove = this.getAttribute('data-business');
    rowToApprove = this.closest('tr');
    document.getElementById('approveModal').classList.remove('hidden');
  });
});

document.getElementById('cancelApprove').onclick = function() {
  document.getElementById('approveModal').classList.add('hidden');
  businessToApprove = null;
  rowToApprove = null;
};

document.getElementById('confirmApprove').onclick = function() {
  if (!businessToApprove) return;
  fetch('approve_application.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ business_name: businessToApprove })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Remove from pending table
      if (rowToApprove) {
        // Clone the row for transfer
        const approvedTableSection = document.querySelector('section#registrationTable');
        const approvedTableBody = document.querySelectorAll('tbody#tableBody')[1]; // 2nd tableBody is for approved
        const clonedRow = rowToApprove.cloneNode(true);

        // Remove Approve/Reject buttons from the cloned row
        const actionsCell = clonedRow.querySelector('td:last-child');
        if (actionsCell) {
          actionsCell.innerHTML = "<button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>";
        }

        // Append to approved table if on the page
        if (approvedTableBody) {
          approvedTableBody.appendChild(clonedRow);
        }

        // Remove from pending table
        rowToApprove.remove();
      }

      // Update approved count indicator
      const approvedCountElem = document.querySelector('#card-approved .text-green-500');
      if (approvedCountElem) {
        let count = parseInt(approvedCountElem.textContent, 10) || 0;
        approvedCountElem.textContent = count + 1;
      }

      // Update pending count indicator
      const pendingCountElem = document.querySelector('#card-pending .text-primary');
      if (pendingCountElem) {
        let count = parseInt(pendingCountElem.textContent, 10) || 1;
        pendingCountElem.textContent = count - 1;
      }
    } else {
      alert('Failed to approve application.');
    }
    document.getElementById('approveModal').classList.add('hidden');
    businessToApprove = null;
    rowToApprove = null;
  })
  .catch(() => {
    alert('Network error.');
    document.getElementById('approveModal').classList.add('hidden');
    businessToApprove = null;
    rowToApprove = null;
  });
};

// Confirm button functionality for approved applications
document.querySelectorAll('.confirm-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    ownerToConfirm = this.getAttribute('data-userid');
    confirmBtnRef = this;
    document.getElementById('confirmFbOwnerModal').classList.remove('hidden');
  });
});

document.getElementById('cancelConfirmFbOwner').onclick = function() {
  document.getElementById('confirmFbOwnerModal').classList.add('hidden');
  ownerToConfirm = null;
  confirmBtnRef = null;
};

document.getElementById('confirmFbOwnerBtn').onclick = function() {
  if (!ownerToConfirm) return;
  fetch('confirm_fb_owner.php', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({ owner_id: ownerToConfirm })
})
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if (confirmBtnRef) {
        confirmBtnRef.disabled = true;
        confirmBtnRef.textContent = 'Confirmed';
      }
      
    } 
    document.getElementById('confirmFbOwnerModal').classList.add('hidden');
    ownerToConfirm = null;
    confirmBtnRef = null;
  })
  .catch(() => {
    alert('Network error.');
    document.getElementById('confirmFbOwnerModal').classList.add('hidden');
    ownerToConfirm = null;
    confirmBtnRef = null;
  });
};
   
   window.addEventListener('message', function(event) {
  if (event.data && event.data.type === 'newApplication') {
    const app = event.data.application;
    // Add new row to Pending table
    const pendingTableBody = document.querySelector('tbody#tableBody');
    if (pendingTableBody) {
      const newRow = document.createElement('tr');
      newRow.innerHTML = `
        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${app.business_name}</td>
        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${app.owner_name}</td>
        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${app.business_type}</td>
        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>${app.application_date}</td>
        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
          <button class='bg-blue-500 text-white px-3 py-1 rounded-md'>View</button>
          <button class='bg-green-500 text-white px-3 py-1 rounded-md approve-btn'>Approve</button>
          <button class='bg-red-500 text-white px-3 py-1 rounded-md reject-btn'>Reject</button>
        </td>
      `;
      pendingTableBody.prepend(newRow);
    }
    // Update indicator
    const pendingCountElem = document.querySelector('#card-pending .text-primary');
    if (pendingCountElem) {
      let count = parseInt(pendingCountElem.textContent, 10) || 0;
      pendingCountElem.textContent = count + 1;
    }
  }
});
</script>

  </body>
</html>