<?php
session_start();
// Getting the owner account ID from session
$user_id = $_SESSION['user_id'];
?>
 
<?php
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          require_once '../db_con.php';

          if (!isset($_SESSION['user_id'])) {
            die(json_encode(['status' => 'error', 'message' => 'Session Expired. Please log in again.']));
          }

          $user_id = $_SESSION['user_id'];
          $fields = [];
          $params = [];
          $types = '';

          // Collecting form data
          if(isset($_POST['business_name']) && $_POST['business_name'] !== '') {
            $fields[] = 'fb_name = ?';
            $params[] = $_POST['business_name'];
            $types .= 's';
          }

          if(isset($_POST['business_type']) && $_POST['business_type'] !== '') {
            $fields[] = 'fb_type = ?';
            $params[] = $_POST['business_type'];
            $types .= 's';
          }

          if(isset($_POST['business_description']) && $_POST['business_description'] !== '') {
            $fields[] = 'fb_description = ?';
            $params[] = $_POST['business_description'];
            $types .= 's';
          }

          if(isset($_POST['phone_number']) && $_POST['phone_number'] !== '') {
            $fields[] = 'fb_phone_number = ?';
            $params[] = $_POST['phone_number'];
            $types .= 's';
          }

          if(isset($_POST['email_address']) && $_POST['email_address'] !== '') {
            $fields[] = 'fb_email_address = ?';
            $params[] = $_POST['email_address'];
            $types .= 's';
          }

          if(isset($_POST['operating_hours']) && $_POST['operating_hours'] !== '') {
            $fields[] = 'fb_operating_hours = ?';
            $params[] = $_POST['operating_hours'];
            $types .= 's';
          }

          if(isset($_POST['business_address']) && $_POST['business_address'] !== '') {
            $fields[] = 'fb_address = ?';
            $params[] = $_POST['business_address'];
            $types .= 's';
          }

         if (!empty($_FILES['business_photo']['name'])) {
            // New photo uploaded
            $business_photo = $_FILES['business_photo']['name'];
            $business_photo_tmp = $_FILES['business_photo']['tmp_name'];
            $business_photo_path = '../uploads/business_photo/' . uniqid() . '_' . basename($business_photo);
            move_uploaded_file($business_photo_tmp, $business_photo_path);
        } else {
            // Only fetch old photo if updating (not for new insert)
            if ($is_update) { // <-- You need to set $is_update = true if updating, false if inserting
                $stmt = $conn->prepare("SELECT fb_photo FROM fb_owner WHERE user_id = ?");
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->bind_result($existing_photo);
                $stmt->fetch();
                $stmt->close();
                $business_photo_path = $existing_photo ?? '';
            } else {
                $business_photo_path = ''; // No photo for new insert if not uploaded
            }
        }

// Always add photo to fields/params/types
$fields[] = 'fb_photo = ?';
$params[] = $business_photo_path;
$types .= 's';

          $gallery_paths = [];
          if (!empty(array_filter($_FILES['business_gallery']['name']))) {
            foreach ($_FILES['business_gallery']['name'] as $key => $business_images) {
              $business_images_tmp = $_FILES['business_gallery']['tmp_name'][$key];
              $business_images_path = '../uploads/business_images/' . uniqid() . '_' . basename($business_images);
              move_uploaded_file($business_images_tmp, $business_images_path);
              $gallery_paths[] = $business_images_path;
            }
            $business_gallery_json = json_encode($gallery_paths);
            $fields[] = "fb_images = ?";
            $params[] = $business_gallery_json;
            $types .= 's';
          }
            if (!isset($business_gallery_json)) {
            $business_gallery_json = json_encode([]); // Always set, even if no upload
            $fields[] = "fb_images = ?";
            $params[] = $business_gallery_json;
            $types .= 's';
        }
          // Checking if it is already existing
          $stmt_checking = $conn->prepare("SELECT 1 FROM fb_owner WHERE user_id = ?");
          $stmt_checking->bind_param('i', $user_id);
          $stmt_checking->execute();
          $result_checking = $stmt_checking->get_result();

          // If there are existing data, update the record
          if ($result_checking->num_rows > 0) {

            // Updating the record one by one
            if (count($fields) > 0) {
              $params[] = $user_id;
              $types .= 'i';
              $sql_update = "UPDATE fb_owner SET " . implode(', ', $fields) . " WHERE user_id = ?";
              $stmt_update = $conn->prepare($sql_update);
              $stmt_update->bind_param($types, ...$params);
              $stmt_update->execute();
              if ($stmt_update->affected_rows > 0) {
              ?>
              <script>
                alert('Business profile saved successfully.');
              </script>
              <?php
              header("Location: " . $_SERVER['PHP_SELF']);
              exit;
              } else {
                echo json_encode(['status'=> 'error', 'message'=> 'No changes made or update failed.']);
              }
              $stmt_update->close();
            } else {
              echo json_encode(['status' => 'error', 'message' => 'No fields to update.']);
            }
          } else {
            if (!isset($gallery_json) || empty($gallery_json)) {
    $gallery_json = json_encode([]);
}
            // Inserting new record if no existing data
            $stmt_insert = $conn->prepare("INSERT INTO fb_owner (user_id, fb_name, fb_type, fb_description, fb_phone_number, fb_email_address, fb_operating_hours, fb_address, fb_photo, fb_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("isssssssss",
              $user_id,
              $_POST['business_name'],
              $_POST['business_type'],
              $_POST['business_description'],
              $_POST['phone_number'],
              $_POST['email_address'],
              $_POST['operating_hours'],
              $_POST['business_address'],
              $photo_path,
              $gallery_json
            );
            $stmt_insert->execute();
            $stmt_insert->close();
                    
                      }
                  
                    }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Business Owner Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#A80000",
            secondary: "#FF6B00",
          },
          borderRadius: {
            button: "8px",
          },
        },
      },
    }
      
    // JavaScript to toggle shop status
    function toggleStatus() {
      const toggle = document.getElementById("status-toggle");
      const statusLabel = document.getElementById("status-label");

      if (toggle.checked) {
        statusLabel.textContent = "Open";
        statusLabel.classList.remove("text-gray-600");
        statusLabel.classList.add("text-green-500");
      } else {
        statusLabel.textContent = "Closed";
        statusLabel.classList.remove("text-green-500");
        statusLabel.classList.add("text-gray-600");
      }
    }
    

    // Mock function to handle notifications
    let hasNotifications = true;
    function toggleNotification() {
      const notifBadge = document.getElementById("notif-badge");
      if (hasNotifications) {
        notifBadge.classList.remove("hidden");
      }
    }

    // Function to add a new item row to the table
    function addNewItem() {
      const tableBody = document.querySelector('tbody');
      const newRow = document.createElement('tr');

      newRow.innerHTML = `
        <td class="p-2">
          <input type="text" class="w-full border rounded px-3 py-2" placeholder="Item Name">
        </td>
        <td class="p-2">
          <input type="text" class="w-full border rounded px-3 py-2" placeholder="Category">
        </td>
        <td class="p-2">
          <input type="number" class="w-full border rounded px-3 py-2" placeholder="Price">
        </td>
        <td class="p-2 flex space-x-2">
          <button class="text-primary hover:underline" onclick="editRow(this)">Edit</button>
          <button class="text-red-500 hover:underline" onclick="deleteRow(this)">Delete</button>
        </td>
      `;

      tableBody.appendChild(newRow);
    }

    // Function to delete a row
    function deleteRow(button) {
      const row = button.closest('tr');
      row.remove();
    }

    // Function to edit a row
    function editRow(button) {
      const row = button.closest('tr');
      const cells = row.querySelectorAll('td');
      
      // Toggle between edit and save state
      if (button.textContent === "Edit") {
        button.textContent = "Save";

        // Make all cells editable
        cells.forEach(cell => {
          if (cell.querySelector('input')) {
            cell.querySelector('input').disabled = false;
          }
        });
      } else {
        button.textContent = "Edit";

        // Disable inputs and save changes
        cells.forEach(cell => {
          if (cell.querySelector('input')) {
            cell.querySelector('input').disabled = true;
          }
        });
      }
    }
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.0/remixicon.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


  
</head>
<body class="bg-gray-50 min-h-screen" onload="toggleNotification()">
<!-- Navbar -->
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
  <h1 class="font-['Pacifico'] text-3xl text-primary">Taste<span class="text-gray-800">Libmanan</span></h1>
  <div class="flex items-center space-x-6">
    <!-- Dashboard -->
    <a href="#" class="text-gray-700 hover:text-primary">
      <i class="ri-dashboard-2-line text-xl"></i>
    </a>
    
    <!-- Profile -->
    <a href="#" class="text-gray-700 hover:text-primary">
      <i class="ri-user-line text-xl"></i>
    </a>
    
    <!-- Notification Icon with Badge -->
    <a href="#" class="relative text-gray-700 hover:text-primary">
      <i class="ri-notification-2-line text-xl"></i>
      <span id="notif-badge" class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full hidden"></span>
    </a>
    
    <!-- Logout -->
    <a href="../index.php" class="text-gray-700 hover:text-primary">
      <i class="ri-logout-box-line text-xl"></i>
    </a>
  </div>
</nav>
 
<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r min-h-screen px-6 py-8 shadow-md hidden md:block">
      <nav class="flex flex-col space-y-4 text-gray-700">
        <a href="#business-profile" class="hover:text-primary flex items-center space-x-2"><i class="ri-building-line"></i> <span>Business Profile</span></a>
        <a href="#menu-management" class="hover:text-primary flex items-center space-x-2"><i class="ri-restaurant-2-line"></i> <span>Menus</span></a>
        <a href="#analytics" class="hover:text-primary flex items-center space-x-2"><i class="ri-bar-chart-line"></i> <span>Analytics</span></a>
        <a href="#status-toggle" class="hover:text-primary flex items-center space-x-2"><i class="ri-toggle-line"></i> <span>Status</span></a>  
        <a href="#customer-feedback" class="hover:text-primary flex items-center space-x-2"><i class="ri-chat-smile-line"></i> <span>Feedback</span></a>
      </nav>
    </aside>
  
  <?php
    require_once '../db_con.php';
    $getting_data_query = "SELECT * FROM `fb_owner` WHERE user_id = $user_id";
    $getResult = $conn->prepare($getting_data_query);
    $getResult->execute();
    $result = $getResult->get_result();
      // Initialize variables to avoid undefined variable warnings
      $business_name = '';
      $business_type = '';
      $business_description = '';
      $phone_number = '';
      $email_address = '';
      $operating_hours = '';
      $business_address = '';

      // Now fetch data from the database
      $getting_data_query = "SELECT * FROM `fb_owner` WHERE user_id = $user_id";
      $getResult = $conn->prepare($getting_data_query);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Display the business profile data
        $business_name = htmlspecialchars($row['fb_name']);
        $business_type = htmlspecialchars($row['fb_type']);
        $business_description = htmlspecialchars($row['fb_description']);
        $phone_number = htmlspecialchars($row['fb_phone_number']);
        $email_address = htmlspecialchars($row['fb_email_address']);
        $operating_hours = htmlspecialchars($row['fb_operating_hours']);
        $business_address = htmlspecialchars($row['fb_address']);
      }
    }
  ?>
    <!-- Business Info -->
    <div class="flex-1 min-h-screen space-y-8 p-6">
      <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Business Profile</h2>
        <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6" id="business-info-form">
          <div>
            <label class="block text-gray-700 mb-1">Business Name</label>
            <input type="text" class="w-full border rounded px-3 py-2" name="business_name" placeholder="Enter Business Name" value="<?php echo $business_name?>" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Business Type</label>
            <select class="w-full border rounded px-3 py-2" name="business_type">
              <option value="Restaurant" <?php if ($business_type == "Restaurant") echo "selected"?>>Restaurant</option>
              <option value="Cafe" <?php if ($business_type == "Cafe") echo "selected" ?>>Cafe</option>
              <option value="Bakery" <?php if ($business_type == "Bakery") echo "selected" ?>>Bakery</option>
              <option value="Fastfood" <?php if ($business_type == "Fastfood") echo "selected" ?>>Fastfood</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-gray-700 mb-1">Business Description</label>
            <textarea class="w-full border rounded px-3 py-2" rows="3" name="business_description" placeholder="Brief description..."><?php echo $business_description ?></textarea>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Phone Number</label>
            <input type="text" class="w-full border rounded px-3 py-2" name="phone_number" placeholder="e.g. 09123456789" value="<?php echo $phone_number?>">
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Email Address</label>
            <input type="email" class="w-full border rounded px-3 py-2" name="email_address" placeholder="e.g. business@email.com" value="<?php echo $email_address?>" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Operating Hours</label>
            <input type="text" class="w-full border rounded px-3 py-2" name="operating_hours" placeholder="e.g. 8:00 AM - 9:00 PM" value="<?php echo $operating_hours?>">
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Business Address</label>
            <input type="text" class="w-full border rounded px-3 py-2" name="business_address" placeholder="e.g. Poblacion, Libmanan" value="<?php echo $business_address?>" required>
          </div>
  
          <!-- Upload Business Photo -->
          <div class="col-span-2">
            <label class="block text-gray-700 mb-1">Upload Business Photo</label>
            <input type="file" class="w-full border rounded px-3 py-2" name="business_photo">
          </div>
          <!-- Upload Business Gallery Images -->
            <div class="col-span-2">
              <label class="block text-gray-700 mb-1">Upload Business Gallery Images</label>
              <input type="file" class="w-full border rounded px-3 py-2" name="business_gallery[]" multiple>
              <p class="text-sm text-gray-500 mt-1">You can select multiple images (e.g., shop, menu, interior).</p>
            </div>

            <div class="mt-6">
                <!-- Save Button -->
                <div x-data="{ showModal: false }" class="flex space-x-2">
                  <button 
                    type="button"
                    class="bg-primary text-white px-6 py-2 rounded-button transform transition-transform duration-200 ease-in-out hover:scale-105"
                    @click="showModal = true">Save Changes
                  </button>

                  <!-- Confirmation Modal -->
                  <div 
                    x-show="showModal" 
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                  >
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md" @click.outside="showModal = false">
                      <h2 class="text-lg font-semibold mb-4">Please Confirm</h2>
                      <p class="mb-6">Are you sure all the information is correct?</p>
                      <div class="flex justify-end space-x-3">
                        <button 
                          type="button"
                          class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400"
                          @click="showModal = false"
                        >
                          Cancel
                        </button>
                        <button
                          type="submit"
                          onclick="validateAndShowModal()"
                          class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        >
                          Save
                        </button>
                      </div>
                    </div>
                  </div>
                  <button 
                    type="reset"
                    class="bg-gray-500 text-white px-6 py-2 rounded-button transform transition-transform duration-200 ease-in-out hover:scale-105 ml-2">
                    Discard
                  </button>  
                </div>
        </form>

        <script>
          function validateAndShowModal() {
            const form = document.getElementById('business-info-form');
            const requiredFields = [
              'business_name',
              'business_type',
              'business_description',
              'phone_number',
              'email_address',
              'operating_hours',
              'business_address',
            ];

            let validating = true;

            requiredFields.forEach(field => {
              const input = form.elements[field];
              if (!input.value.trim()) {
                input.classlist.add('border-red-500');
                validating = false;
              } else {
                input.classList.remove('border-red-500');
              }
            });

            // Email validation
            const emailValidation = form.elements['email_address'];
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/ .test(emailValidation.value)) {
              emailValidation.classList.add('border-red-500');
              validating = false;
            } else {
              emailValidation.classList.remove('border-red-500');
            }

            if (validating) {
              Alpine.store('showModal', true);
            } else {
              alert('Please fill in all required fields correctly.');
            }
          }
          // Realtime validation
          document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('business-info-form');
            form.addEventListener('input', (e) => {
              if (e.target.value.trim()) {
                e.target.classList.remove('border-red-500');
              }
            });
          });
        </script>

      </div>       
    </div>
  
<!-- Shop Status Toggle -->
<div class="col-span-2 flex items-center space-x-4">
  <label class="text-gray-700 mb-1">Shop Status</label>
  <label for="status-toggle" class="flex items-center cursor-pointer">
    <span id="status-label" class="mr-2 <?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? 'text-green-500' : 'text-red-500'; ?>">
      <?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? 'Open' : 'Closed'; ?>
    </span>
    <div class="relative">
      <input type="checkbox" id="status-toggle" class="hidden"
        <?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? 'checked' : ''; ?>
        onchange="updateShopStatusAJAX(this.checked)">
      <div id="toggle-path" class="toggle-path <?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? 'bg-green-500' : 'bg-red-500'; ?> w-14 h-8 rounded-full shadow-inner transition-all"></div>
      <div id="toggle-circle" class="toggle-circle absolute w-6 h-6 bg-white rounded-full shadow <?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? '-right-1' : '-left-1'; ?> -top-1 transition-all hover:scale-105 transform transition-transform duration-200 ease-in-out"></div>
    </div>
    <span id="open-label" class="ml-2 text-green-500">Open</span>
  </label>
  <!-- Add this hidden input -->
  <input type="hidden" name="fb_status" id="fb_status" value="<?php echo (isset($row['fb_status']) && $row['fb_status'] == 'open') ? 'open' : 'closed'; ?>">
</div>
    </div>
  </div>
            <script>
function updateShopStatusAJAX(isOpen) {
  const status = isOpen ? 'open' : 'closed';
  document.getElementById('fb_status').value = status;

  // Update label and toggle colors instantly
  const statusLabel = document.getElementById('status-label');
  const togglePath = document.getElementById('toggle-path');
  const toggleCircle = document.getElementById('toggle-circle');

  if (isOpen) {
    statusLabel.textContent = "Open";
    statusLabel.classList.remove("text-red-500");
    statusLabel.classList.add("text-green-500");
    togglePath.classList.remove("bg-red-500");
    togglePath.classList.add("bg-green-500");
    toggleCircle.classList.remove("-left-1");
    toggleCircle.classList.add("-right-1");
  } else {
    statusLabel.textContent = "Closed";
    statusLabel.classList.remove("text-green-500");
    statusLabel.classList.add("text-red-500");
    togglePath.classList.remove("bg-green-500");
    togglePath.classList.add("bg-red-500");
    toggleCircle.classList.remove("-right-1");
    toggleCircle.classList.add("-left-1");
  }

  // Send AJAX request to update status in DB
  fetch('update_status.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'status=' + status
  })
  .then(response => response.json())
  .then(data => {
    if (!data.success) {
      alert('Failed to update status!');
    }
  });
}
</script>
  <script>
    function toggleStatus() {
      const toggle = document.getElementById('status-toggle');
      const closedLabel = document.getElementById('status-label');
      const openLabel = document.getElementById('open-label');
      const togglePath = document.getElementById('toggle-path');
      const toggleCircle = document.getElementById('toggle-circle');
      fbStatus.value = toggle.checked ? 'open' : 'closed';
      if (toggle.checked) {
        // If the shop is open, update the toggle background and circle position
        togglePath.classList.remove('bg-red-500');
        togglePath.classList.add('bg-green-500');
        
        toggleCircle.classList.remove('-left-1');
        toggleCircle.classList.add('-right-1');
        
        closedLabel.classList.remove('text-red-500');
        closedLabel.classList.add('text-gray-600');
        
        openLabel.classList.remove('text-green-500');
        openLabel.classList.add('text-green-600');
      } else {
        // If the shop is closed, reset the toggle background and circle position
        togglePath.classList.remove('bg-green-500');
        togglePath.classList.add('bg-red-500');
        
        toggleCircle.classList.remove('-right-1');
        toggleCircle.classList.add('-left-1');
        
        closedLabel.classList.remove('text-gray-600');
        closedLabel.classList.add('text-red-500');
        
        openLabel.classList.remove('text-green-600');
        openLabel.classList.add('text-green-500');
      }
    }
  </script>
      
        <!-- Chart Container -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
  <h2 class="text-2xl font-bold text-gray-800 mb-4">Business Performance (January)</h2>
  <canvas id="performanceChart" class="w-full h-96"></canvas>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative">
    <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
    <h3 class="text-xl font-semibold mb-4">Customer Feedback</h3>
    <div id="feedbackList" class="space-y-4 overflow-y-auto max-h-[60vh]"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const feedbackData = {
    'Week 1': [
      {
        name: "Roman Victor Delavega",
        rating: 5,
        comment: "The place is clean and the food is so affordable and delicious.",
        images: ["imgsource/1.jpg", "imgsource/2.jpg"]
      },
      {
        name: "Dale Mar Sandagon",
        rating: 4.9,
        comment: "The restaurant was spotless, and the prices were incredibly reasonable. Everything we ordered was delicious!",
        images: ["imgsource/f1.jpg", "imgsource/f2.jpg"]
      },
      {
        name: "Mike Tyson",
        rating: 5,
        comment: "Clean environment, friendly staff, and the food was amazing for the price. Highly recommend!",
        images: ["imgsource/image1.jpg", "imgsource/image2.jpg"]
      }
      ,
      {
        name: "Mr. Beast",
        rating: 5,
        comment: "I loved how clean the place was. The food was not only delicious but also very affordable. Will definitely return.",
        images: ["imgsource/image1.jpg", "imgsource/image2.jpg"]
      }
      ,
      {
        name: "Jak Roberto",
        rating: 5,
        comment: "Excellent food quality at very affordable prices. The restaurant was also very clean and well-maintained.",
        images: ["imgsource/image1.jpg", "imgsource/image2.jpg"]
      }
      ,
      {
        name: "Gerald Anderson",
        rating: 5,
        comment: "This place is a gem! Clean, affordable, and the food is outstanding. A must-try!",
        images: ["imgsource/image1.jpg", "imgsource/image2.jpg"]
      }
    ],
    'Week 2': [
      {
        name: "Lance Tobias",
        rating: 3,
        comment: "Food was okay, but service was slow.",
        images: []
      }
    ],
    'Week 3': [
      {
        name: "Angel Santos",
        rating: 5,
        comment: "Love the new menu items!",
        images: ["imgsource/image3.jpg"]
      }
    ],
    'Week 4': [
      {
        name: "Kevin L.",
        rating: 4,
        comment: "Consistently good. Keep it up!",
        images: []
      }
    ]
  };

  function createPerformanceChart() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Reviews & Ratings',
          data: [50, 46, 67, 45],
          borderColor: '#FF6B00',
          backgroundColor: 'rgba(255, 107, 0, 0.2)',
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        responsive: true,
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index;
            const week = chart.data.labels[index];
            showFeedbackModal(week);
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  function showFeedbackModal(week) {
    const modal = document.getElementById('feedbackModal');
    const feedbackList = document.getElementById('feedbackList');
    feedbackList.innerHTML = ''; // Clear previous content

    const feedbacks = feedbackData[week] || [];

    feedbacks.forEach(fb => {
      const imagesHTML = fb.images.map(src => `<img src="${src}" class="w-24 h-24 object-cover rounded mr-2">`).join('');
      feedbackList.innerHTML += `
        <div class="border-b pb-4">
          <p class="font-semibold">${fb.name}</p>
          <p class="text-yellow-500">${'★'.repeat(fb.rating)}${'☆'.repeat(5 - fb.rating)}</p>
          <p class="text-gray-700 mb-2">"${fb.comment}"</p>
          <div class="flex flex-wrap gap-2">${imagesHTML}</div>
        </div>
      `;
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('feedbackModal').classList.add('hidden');
  });

  document.addEventListener('DOMContentLoaded', createPerformanceChart);
</script>


<!-- Menu Management -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold text-gray-800">Menu Management</h2>
      <div class="flex space-x-4">
        <button class="bg-secondary text-white px-4 py-2 rounded-button hover:bg-primary" onclick="addNewItem()">+ Add New Item</button>
        <button class="bg-red-500 text-white px-4 py-2 rounded-button hover:bg-red-600" onclick="deleteAllItems()">Delete All</button>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-2">Item Name</th>
            <th class="p-2">Category</th>
            <th class="p-2">Price</th>
            <th class="p-2">Status</th> 
            <th class="p-2">Image</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
        
        </tbody>
        
      </table>
    </div>
  </div>
  
  <script>
    // Function to add a new item row to the table
    function addNewItem() {
      const tableBody = document.querySelector('tbody');
      const newRow = document.createElement('tr');
    
      newRow.innerHTML = `
      <td class="p-2">
        <input type="text" class="w-full border rounded px-3 py-2" placeholder="Item Name" disabled>
      </td>
      <td class="p-2">
        <select class="w-full border rounded px-3 py-2" disabled>
          <option value="Heavy Meals">Heavy Meals</option>
          <option value="Snacks">Snacks</option>
          <option value="Drinks">Drinks</option>
          <option value="Dessert">Dessert</option>
        </select>
      </td>
      <td class="p-2">
        <input type="number" class="w-full border rounded px-3 py-2" placeholder="Price" disabled>
      </td>
      <td class="p-2">
        <select class="w-full border rounded px-3 py-2" disabled>
          <option value="Available">Available</option>
          <option value="Unavailable">Unavailable</option>
        </select>
      </td>
    
      <!-- Image upload field -->
      <td class="p-2">
        <input type="file" class="w-full" accept="image/*" disabled>
      </td>
    
      <td class="p-2 flex space-x-2">
        <button class="text-primary hover:underline" onclick="editRow(this)">Edit</button>
        <button class="text-red-500 hover:underline" onclick="deleteRow(this)">Delete</button>
        <button class="text-green-500 hover:underline hidden" onclick="saveRow(this)">Save</button>
      </td>
    `;
    
      tableBody.appendChild(newRow);
    }
    
    // Function to delete a row
    function deleteRow(button) {
      const row = button.closest('tr');
      row.remove();
    }
    
    // Function to edit a row
    function editRow(button) {
      const row = button.closest('tr');
      const cells = row.querySelectorAll('td');
      const saveButton = row.querySelector('.text-green-500');  // Get the Save button
    
      // Toggle between edit and save state
      if (button.textContent === "Edit") {
        button.textContent = "Cancel";  // Change text of Edit button to Cancel
        saveButton.classList.remove("hidden");  // Show the Save button
    
        // Make all cells editable
        cells.forEach(cell => {
          const input = cell.querySelector('input, select');
          if (input) input.disabled = false;
        });
    
      } else {
        button.textContent = "Edit";  // Reset button text to Edit
        saveButton.classList.add("hidden");  // Hide the Save button
    
        // Disable inputs and save changes
        cells.forEach(cell => {
          const input = cell.querySelector('input, select');
          if (input) input.disabled = true;
        });
      }
    }
    
    // Function to save edited row
    function saveRow(button) {
      const row = button.closest('tr');
      const cells = row.querySelectorAll('td');
      const editButton = row.querySelector('button[text="Edit"]');
      
      // Save changes and disable editing
      cells.forEach(cell => {
        const input = cell.querySelector('input, select');
        if (input) input.disabled = true;
      });
    
      // If an image was uploaded, you can get the image file here
      const imageInput = row.querySelector('input[type="file"]');
      if (imageInput && imageInput.files[0]) {
        // You can handle the file upload here (e.g., preview the image or save it)
        const file = imageInput.files[0];
        console.log('Image file:', file);
      }
  
      button.classList.add("hidden");  // Hide Save button after saving
      editButton.textContent = "Edit";  // Change Edit button text back to Edit
    }
    
    // Function to delete all items in the table
    function deleteAllItems() {
      const tableBody = document.querySelector('tbody');
      tableBody.innerHTML = '';  // Clear all rows from the table body
    }
  </script>
  
<!-- Footer -->
<footer class="bg-primary text-white py-6 mt-10">
  <div class="max-w-7xl mx-auto text-center">
    <p class="text-sm">&copy; 2025 TasteLibmanan. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
