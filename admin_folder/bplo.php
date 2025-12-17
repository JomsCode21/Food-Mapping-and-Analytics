<?php

  session_start();

  if (!isset($_SESSION['user_id'])) {
    $redirect1 = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../index.php?redirect={$redirect1}");
    exit();
}
  require_once '../db_con.php';
  
  $period = isset($_GET['period']) ? $_GET['period'] : 'all'; 

  // Initialize date filter conditions
  $fb_date_filter = ""; // For fb_owner table
  $review_date_filter = ""; // For reviews table

  switch ($period) {
      case 'monthly':
          // Current Month
          $fb_date_filter = " AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
          $review_date_filter = " AND MONTH(r.created_at) = MONTH(CURRENT_DATE()) AND YEAR(r.created_at) = YEAR(CURRENT_DATE())";
          break;
      case 'quarterly':
          // Current Quarter
          $fb_date_filter = " AND QUARTER(created_at) = QUARTER(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
          $review_date_filter = " AND QUARTER(r.created_at) = QUARTER(CURRENT_DATE()) AND YEAR(r.created_at) = YEAR(CURRENT_DATE())";
          break;
      case 'semi_annually':
          // Last 6 Months
          $fb_date_filter = " AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
          $review_date_filter = " AND r.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
          break;
      case 'annually':
          // Current Year
          $fb_date_filter = " AND YEAR(created_at) = YEAR(CURRENT_DATE())";
          $review_date_filter = " AND YEAR(r.created_at) = YEAR(CURRENT_DATE())";
          break;
      case 'all':
      default:
          $fb_date_filter = "";
          $review_date_filter = "";
          break;
  }

  // GETTING THE COUNTS OF EVERY BUSINESS CATEGORY (Applied Filter)
  $restaurant_count = $conn->query("SELECT COUNT(*) AS count FROM fb_owner WHERE fb_type = 'Restaurant' $fb_date_filter")->fetch_assoc()['count'];
  $bakery_count = $conn->query("SELECT COUNT(*) AS count FROM fb_owner WHERE fb_type = 'Bakery' $fb_date_filter")->fetch_assoc()['count'];
  $cafe_count = $conn->query("SELECT COUNT(*) AS count FROM fb_owner WHERE fb_type = 'Cafe' $fb_date_filter")->fetch_assoc()['count'];
  $fastfood_count = $conn->query("SELECT COUNT(*) AS count FROM fb_owner WHERE fb_type = 'Fastfood' $fb_date_filter")->fetch_assoc()['count'];


  // data for pie chart
  $category_counts = [
    'Restaurant' => $restaurant_count,
    'Cafe' => $cafe_count,
    'Fastfood' => $fastfood_count,
    'Bakery' => $bakery_count
  ];

  // For most visited category of Businesses
  $categories_business_labels = [];
  $categories_business_data = [];

  // Applied $review_date_filter here
  $categories_business_query = "SELECT f.fb_type,
                          COUNT(r.id) AS total_reviews
                        FROM fb_owner f
                        LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
                        WHERE f.activation = 'Active' $review_date_filter
                        GROUP BY f.fb_type
                        ORDER BY total_reviews DESC";
  $category_visit_result = $conn->query($categories_business_query);

  while ($row = $category_visit_result->fetch_assoc()) {
    $categories_business_labels[] = $row['fb_type'];
    $categories_business_data[] = $row['total_reviews'];
  }

  // QUERY for top most visited Businesses
  $top_business_labels = [];
  $top_business_data = [];
  $top_business_id = [];

  // Applied $review_date_filter here
  $top_business_query = "SELECT f.fbowner_id, f.fb_name,
                        COUNT(r.id) AS total_reviews
                        FROM fb_owner f
                        LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
                        WHERE f.activation = 'Active' $review_date_filter
                        GROUP BY f.fb_name
                        ORDER BY total_reviews DESC
                        LIMIT 10";
  $top_business_result = $conn->query($top_business_query);

  while ($row = $top_business_result->fetch_assoc()) {
    $top_business_id[] = $row['fbowner_id'];
    $top_business_labels[] = $row['fb_name'];
    $top_business_data[] = $row['total_reviews'];
  }

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      
      <div class="flex justify-between items-center mb-6">
          <div>
             <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
             <p class="text-sm text-gray-500">Welcome back! Here's what's happening.</p>
          </div>
          <div class="flex items-center bg-white p-2 rounded-lg shadow-sm border border-gray-200">
             <i class="ri-calendar-event-line text-gray-500 mr-2 ml-2"></i>
             <select id="period-filter" class="bg-transparent text-sm font-medium text-gray-700 focus:outline-none cursor-pointer pr-2">
                 <option value="all" <?php echo ($period == 'all') ? 'selected' : ''; ?>>All Time</option>
                 <option value="monthly" <?php echo ($period == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                 <option value="quarterly" <?php echo ($period == 'quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                 <option value="semi_annually" <?php echo ($period == 'semi_annually') ? 'selected' : ''; ?>>Semi-Annually</option>
                 <option value="annually" <?php echo ($period == 'annually') ? 'selected' : ''; ?>>Annually (Yearly)</option>
             </select>
          </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
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

        <div class="md:col-span-3 space-y-6">
          
          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="ri-pie-chart-2-line mr-2 text-blue-500"></i>
                    Business Registration Overview
                </h2>
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Analytics</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                <h3 class="text-base font-semibold mb-6 text-gray-700">Businesses per Barangay</h3>
                <div class="w-full h-full">
                    <canvas id="barChart"></canvas>
                </div>
              </div>
              
              <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                <h3 class="text-base font-semibold mb-6 text-gray-700">Registered Category Distribution</h3>
                <div class="w-full h-full flex justify-center">
                    <canvas id="pieChart"></canvas>
                </div>
              </div>
            </div>
          </section>

          <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="ri-line-chart-line mr-2 text-green-500"></i>
                    Engagement & Visits
                </h2>
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Metrics</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                <h3 class="text-base font-semibold mb-6 text-gray-700">Top 10 Most Visited Businesses</h3>
                <div class="w-full h-[350px]"> 
                    <canvas id="topBusinessChart"></canvas>
                </div>
              </div>

              <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                <h3 class="text-base font-semibold mb-6 text-gray-700">Total Visits per Category</h3>
                <div class="w-full h-full flex justify-center">
                    <canvas id="categoryPopularityChart"></canvas>
                </div>
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

    <div id="TotalVisitcategoryModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity"></div>
      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

          <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">

              <div class="sm:flex sm:items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div class="flex items-center">
                    <div class="mx-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 sm:mx-0">
                      <i class="ri-bar-chart-horizontal-line text-blue-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                      <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">
                        Category Analysis: <span id="modal-category-name" class="text-blue-600"></span>
                      </h3>
                    </div>
                </div>
                <button type="button" onclick="closeTotalVisitCategoryModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-2xl"></i>
                </button>
              </div>

              <div class="mt-2 w-full h-[350px] relative flex justify-center items-center bg-gray-50 rounded-xl p-2">
                 <div id="modal-loader" class="absolute inset-0 flex items-center justify-center bg-white/80 z-10 hidden rounded-xl">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                 </div>

                 <canvas id="modalBarChart"></canvas>
              </div>

            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="button" onclick="closeTotalVisitCategoryModal()" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // --- Menu Scripts ---
      const profilingBtn = document.getElementById('profiling-btn');
      const adminMenu = document.getElementById('admin-menu');
      const periodFilter = document.getElementById('period-filter'); // Filter Dropdown

      // RELOAD PAGE ON FILTER CHANGE
      periodFilter.addEventListener('change', function() {
          const selectedPeriod = this.value;
          // Reload page with the new period parameter
          window.location.search = '?period=' + selectedPeriod;
      });

      // CHANGE: Default to 'all' if no parameter
      const urlParams = new URLSearchParams(window.location.search);
      const currentPeriod = urlParams.get('period') || 'all';

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

      function updateNotificationBadge() {
        fetch('get_notification_count.php')
          .then(response => response.text())
          .then(count => {
            const badge = document.getElementById('notification-badge');
            if (parseInt(count) > 0) {
              badge.textContent = count;
              badge.style.display = 'inline-block';
            } else {
              badge.style.display = 'none';
            }
          });
      }
      updateNotificationBadge();
      setInterval(updateNotificationBadge, 30000);

      // --- Chart Scripts ---
      document.addEventListener("DOMContentLoaded", function() {
        
        // Bar chart (Businesses per Barangay) - Pass the Period!
        fetch('fetch_business_barangay.php?period=' + currentPeriod)
        .then(response => response.json())
        .then(data => {
          const ctx = document.getElementById("barChart").getContext("2d");
          const barChart = new Chart(ctx, {
            type: "bar",
            data: {
              labels: data.barangays,
              datasets: [
                {
                  label: "Businesses",
                  data: data.total,
                  backgroundColor: "#3b82f6", 
                  hoverBackgroundColor: "#2563eb", 
                  borderRadius: 6,
                  barThickness: 20,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: {
                  ticks: { maxRotation: 45, minRotation: 0, font: {size: 11} },
                  grid: { display: false }
                },
                y: {
                  beginAtZero: true,
                  grid: { color: '#f3f4f6' }
                },
              },
              plugins: {
                legend: { display: false },
                tooltip: {
                  backgroundColor: 'rgba(0,0,0,0.8)',
                  padding: 12,
                  cornerRadius: 8,
                }
              },
              onClick: function (evt, elements) {
                if (elements.length > 0) {
                  const clickedIndex = elements[0].index;
                  const barangay = this.data.labels[clickedIndex];
                  window.location.href = "business_management.php?barangay=" + encodeURIComponent(barangay);
                }
              },
              onHover: (event, chartElement) => {
                event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
              }
            },
          });
        })
        .catch(error => console.error('Error fetching data:', error));

        // Pie chart (Category Distribution)
        const pieChart = new Chart(document.getElementById("pieChart"), {
          type: "doughnut", 
          data: {
            labels: ["Restaurant", "Cafe", "Fastfood", "Bakery"],
            datasets: [
              {
                data: [
                  <?php echo isset($category_counts['Restaurant']) ? $category_counts['Restaurant'] : 0; ?>,
                  <?php echo isset($category_counts['Cafe']) ? $category_counts['Cafe'] : 0; ?>,
                  <?php echo isset($category_counts['Fastfood']) ? $category_counts['Fastfood'] : 0; ?>,
                  <?php echo isset($category_counts['Bakery']) ? $category_counts['Bakery'] : 0; ?>
                ],
                backgroundColor: ["#34d399", "#4caf50", "#ef4444", "#fbbf24"],
                borderWidth: 0,
                hoverOffset: 10
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            onClick: function(evt, elements) {
              if (elements.length > 0) {
                const chart = elements[0];
                const category = this.data.labels[chart.index];
                window.location.href = "business_management.php?category=" + encodeURIComponent(category);
              }
            },
            onHover: (event, chartElement) => {
                event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            }
          }
        });

        // Top 10 Most Visited
        const top_business_labels = <?php echo json_encode(array_map(function($str) { return html_entity_decode($str, ENT_QUOTES); }, $top_business_labels)); ?>;
        const top_business_data = <?php echo json_encode($top_business_data); ?>;
        const top_business_id = <?php echo json_encode($top_business_id); ?>;
        
        new Chart(document.getElementById("topBusinessChart"), {
          type: "bar",
          data: {
            labels: top_business_labels,
            datasets: [{
              label: "Visits",
              data: top_business_data,
              backgroundColor: "rgba(239, 68, 68, 0.8)",
              hoverBackgroundColor: "rgba(220, 38, 38, 1)",
              borderRadius: 4,
              barPercentage: 0.6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
              x: { grid: { color: '#f3f4f6' } },
              y: { grid: { display: false } }
            },
            plugins: {
              legend: { display: false }
            },
            onClick: function(evt, elements) {
              if (elements.length > 0) {
                const index = elements[0].index;
                const selectedId = top_business_id[index];
                window.location.href = "../users/businessdetails.php?fbowner_id=" + selectedId + "&view=public";
              }
            },
            onHover: (event, chartElement) => {
              event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            }
          }
        });
      
        // Visits per Category Chart
        const categories_business_labels = <?php echo json_encode($categories_business_labels); ?>;
        const categories_business_data = <?php echo json_encode($categories_business_data); ?>;
      
        new Chart(document.getElementById("categoryPopularityChart"), {
          type: "polarArea", 
          data: {
            labels: categories_business_labels,
            datasets: [{
              label: "Visits",
              data: categories_business_data,
              backgroundColor: [
                "rgba(255, 99, 132, 0.7)",
                "rgba(54, 162, 235, 0.7)",
                "rgba(255, 206, 86, 0.7)",
                "rgba(75, 192, 192, 0.7)"
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    pointLabels: {
                        display: true,
                        centerPointLabels: true,
                        font: { size: 10 }
                    }
                }
            },
            plugins: {
              legend: { position: 'right', labels: { usePointStyle: true } }
            },
            onHover: (event, chartElement) => {
              event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
            onClick: function(evt, elements) {
              if (elements.length > 0) {
                const elementIndex = elements[0].index;
                const categoryType = this.data.labels[elementIndex];
                openCategoryModal(categoryType);
              }
            }
          }
        });
      });

      // MODAL function
      function openCategoryModal(category) {
        const modal = document.getElementById('TotalVisitcategoryModal');
        const loader = document.getElementById('modal-loader');
        
        // Grab current period from URL or default
        const urlParams = new URLSearchParams(window.location.search);
        const currentPeriod = urlParams.get('period') || 'all';

        document.getElementById('modal-category-name').textContent = category;
        modal.classList.remove('hidden');
        loader.classList.remove('hidden');

        // Fetching data - Pass period here too
        const formData = new FormData();
        formData.append('category', category);
        formData.append('period', currentPeriod); // Send period to PHP

        fetch('fetch_totalvisitpercategory.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          renderModalChart(data.labels, data.data);
          loader.classList.add('hidden');
        })
        .catch(error => {
          console.error('Error: ', error);
          loader.classList.add('hidden');
        });
      }

      let modalChartInstance = null;

      function renderModalChart(labels, dataCounts) {
        const ctx = document.getElementById('modalBarChart').getContext('2d');

        if (modalChartInstance) {
          modalChartInstance.destroy();
        }

        modalChartInstance = new Chart(ctx, {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{
              label: 'Visits',
              data: dataCounts,
              backgroundColor: 'rgba(59, 130, 246, 0.8)',
              borderRadius: 4,
              barPercentage: 0.6
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' }
              },
              y: {
                grid: { display: false }
              }
            },
            plugins: {
              legend: {display:false}
            }
          }
        });
      }

      function closeTotalVisitCategoryModal() {
        document.getElementById('TotalVisitcategoryModal').classList.add('hidden');
      }

      window.onclick = function(event) {
        const modal = document.getElementById('TotalVisitcategoryModal');
        if (event.target === modal.querySelector('.fixed.inset-0')) {
          closeTotalVisitCategoryModal();
        }
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
                const allUserBtns = document.querySelectorAll('[id^="user-btn-"]');
                allUserBtns.forEach(btn => {
                    const userId = btn.id.replace('user-btn-', '');
                    if (data.senders[userId]) {
                        btn.classList.add('font-bold', 'bg-red-50');
                        if (!btn.querySelector('.unread-dot')) {
                            const dot = document.createElement('div');
                            dot.className = "unread-dot w-3 h-3 bg-red-500 rounded-full absolute right-4 top-1/2 transform -translate-y-1/2 border-2 border-white;"
                            btn.style.position = 'relative';
                            btn.appendChild(dot);
                        }
                    } else {
                        btn.classList.remove('font-bold', 'bg-red-50');
                        const dot = btn.querySelector('.unread-dot');
                        if (dot) dot.remove();
                    }
                });
            });
        }

        checkUnreadMessages();
        setInterval(checkUnreadMessages, 3000);
    </script>
  </body>
</html>