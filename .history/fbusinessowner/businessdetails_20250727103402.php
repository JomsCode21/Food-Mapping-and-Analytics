<?php
require_once '../db_con.php';
// Get business owner user_id from URL (?user_id=)
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch business details from fb_owner
$business = null;
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM fb_owner WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $business = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch owner info from accounts
$owner = null;
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE user_id = ? AND user_type = 'fb_owner'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $owner = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Business Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-md px-6 py-4 flex items-center justify-between no-margin">
        <h1 class="font-['Pacifico'] text-4xl text-primary">Taste<span class="text-gray-800">Libmanan</span></h1>
        <div class="flex items-center space-x-6">
            <div class="hidden md:flex space-x-6">
                <a href="user.html" class="text-gray-800 hover:text-primary">Home</a>
                <a href="categories.html" class="text-gray-800 hover:text-primary">Businesses</a>
                <a href="#" class="text-gray-800 hover:text-primary">About Us</a>
                <a href="landing.html" class="text-gray-800 hover:text-primary">Menus</a>
            </div>
        </div>
    </nav>

    <!-- Back Button -->
    <div class="px-4 py-2 mt-2">
        <button onclick="window.history.back()" class="text-primary hover:text-gray-800 text-xl font-semibold flex items-center">
            <i class="ri-arrow-left-line mr-2"></i> Back
        </button>
    </div>

    <!-- Business Details Section -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <?php if ($business && $owner): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/2">
                        <?php if (!empty($business['fb_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($business['fb_photo']); ?>" alt="Business Photo" class="w-full h-64 object-cover rounded-lg mb-4">
                        <?php else: ?>
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg mb-4 text-gray-400">No Photo</div>
                        <?php endif; ?>
                        <div class="mt-4">
                            <p class="text-2xl font-semibold text-gray-800 mb-2">Photo Gallery</p>
                            <div class="flex overflow-x-auto space-x-4">
                                <?php
                                $gallery = [];
                                if (!empty($business['fb_images'])) {
                                    $gallery = json_decode($business['fb_images'], true);
                                    if (!is_array($gallery)) $gallery = [];
                                }
                                if (count($gallery) > 0) {
                                    foreach ($gallery as $img) {
                                        echo '<img src="'.htmlspecialchars($img).'" alt="Gallery" class="w-32 h-32 object-cover rounded-lg shadow">';
                                    }
                                } else {
                                    echo '<div class="text-gray-400">No Gallery Images</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($business['fb_name']); ?></h2>
                        <p class="text-md text-gray-600 mb-1">Owner: <?php echo htmlspecialchars($owner['name']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Type: <?php echo htmlspecialchars($business['fb_type']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Description: <?php echo htmlspecialchars($business['fb_description']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Phone: <?php echo htmlspecialchars($business['fb_phone_number']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Email: <?php echo htmlspecialchars($business['fb_email_address']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Operating Hours: <?php echo htmlspecialchars($business['fb_operating_hours']); ?></p>
                        <p class="text-md text-gray-600 mb-1">Address: <?php echo htmlspecialchars($business['fb_address']); ?></p>
                        <p class="text-md text-gray-600 mb-1">
                        Status:
                        <span id="business-status">
                            <?php if (!empty($business['fb_status']) && strtolower($business['fb_status']) === 'open'): ?>
                            <span class="text-green-600 font-semibold">Open</span>
                            <?php else: ?>
                            <span class="text-red-600 font-semibold">Closed</span>
                            <?php endif; ?>
                        </span>
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <p class="text-red-600 text-xl font-semibold">Business not found or not registered.</p>
            </div>
        <?php endif; ?>
    </div>
<script>
const userId = <?php echo json_encode($user_id); ?>;
function updateBusinessStatus() {
  fetch('get_status.php?user_id=' + userId)
    .then(response => response.json())
    .then(data => {
      const statusSpan = document.getElementById('business-status');
      if (data.status === 'open') {
        statusSpan.innerHTML = '<span class="text-green-600 font-semibold">Open</span>';
      } else {
        statusSpan.innerHTML = '<span class="text-red-600 font-semibold">Closed</span>';
      }
    });
}
// Poll every 3 seconds
setInterval(updateBusinessStatus, 2000);
</script>
    <!-- Footer -->
   <footer class="bg-primary text-white mt-10 px-4 py-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <h1 class="font-['Pacifico'] text-lg">
            Taste<span class="text-white font-light">Libmanan</span>
          </h1>
          <div class="flex space-x-4 text-sm">
            <a href="#" class="hover:underline">About</a>
            <a href="#" class="hover:underline">Contact</a>
            <a href="#" class="hover:underline">Privacy Policy</a>
          </div>
          <p class="text-xs text-gray-200">&copy; 2025 TasteLibmanan. All rights reserved.</p>
        </div>
      </footer>