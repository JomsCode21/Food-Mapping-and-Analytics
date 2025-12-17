<?php
session_start();
require_once '../db_con.php'; // Make sure this sets $conn as MySQLi
require_once '../status_logic.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $redirect1 = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../index.php?redirect={$redirect1}");
    exit();
}

$user_id = $_SESSION['user_id'];
$receiver_id = 27; // BPLO Admin user_id

$current_status = checkAndSyncBusinessStatus($conn, $user_id);

// Now fetch the rest of the row as usual
$stmt = $conn->prepare("SELECT * FROM fb_owner WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Update the row array with the fresh status
if ($row) {
    $row['fb_status'] = $current_status;
} else {
    $row = ['fb_status' => 'closed'];
}

$fb_id = $row['fbowner_id'] ?? null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'message' => 'Session Expired. Please log in again.']));
    }

    $user_id = $_SESSION['user_id'];
    $fields = [];
    $params = [];
    $types = '';

    // Collecting form data (Preserved logic)
    if (isset($_POST['business_name'])) { $fields[] = 'fb_name = ?'; $params[] = $_POST['business_name']; $types .= 's'; }
    if (isset($_POST['business_type'])) { $fields[] = 'fb_type = ?'; $params[] = $_POST['business_type']; $types .= 's'; }
    if (isset($_POST['fb_status'])) { $fields[] = 'fb_status = ?'; $params[] = $_POST['fb_status']; $types .= 's'; }
    if (isset($_POST['business_description'])) { $fields[] = 'fb_description = ?'; $params[] = $_POST['business_description']; $types .= 's'; }
    if (isset($_POST['phone_number'])) { $fields[] = 'fb_phone_number = ?'; $params[] = $_POST['phone_number']; $types .= 's'; }
    if (isset($_POST['email_address'])) { $fields[] = 'fb_email_address = ?'; $params[] = $_POST['email_address']; $types .= 's'; }
    if (isset($_POST['operating_hours'])) { $fields[] = 'fb_operating_hours = ?'; $params[] = $_POST['operating_hours']; $types .= 's'; }
    if (isset($_POST['fb_latitude'])) { $fields[] = 'fb_latitude = ?'; $params[] = $_POST['fb_latitude']; $types .= 's'; }
    if (isset($_POST['fb_longitude'])) { $fields[] = 'fb_longitude = ?'; $params[] = $_POST['fb_longitude']; $types .= 's'; }
    if (isset($_POST['business_address'])) { $fields[] = 'fb_address = ?'; $params[] = $_POST['business_address']; $types .= 's'; }

    // Handle Business Photo Upload
    if (!empty($_FILES['business_photo']['name'])) {
        $business_photo = $_FILES['business_photo']['name'];
        $business_photo_tmp = $_FILES['business_photo']['tmp_name'];
        $business_folder = isset($_POST['business_name']) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_POST['business_name']) : 'default';
        $upload_dir = '../uploads/business_photo/' . $business_folder . '/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        $business_photo_path = $upload_dir . uniqid() . '_' . basename($business_photo);
        move_uploaded_file($business_photo_tmp, $business_photo_path);
    } else {
        $business_photo_path = isset($_POST['existing_business_photo']) ? $_POST['existing_business_photo'] : '';
    }
    if (!empty($_FILES['business_photo']['name'])) { $fields[] = 'fb_photo = ?'; $params[] = $business_photo_path; $types .= 's'; }

    // Handle Cover Photo Upload
    if (!empty($_FILES['fb_cover']['name'])) {
        $cover_photo = $_FILES['fb_cover']['name'];
        $cover_photo_tmp = $_FILES['fb_cover']['tmp_name'];
        $business_folder = isset($_POST['business_name']) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_POST['business_name']) : 'default';
        $upload_dir = '../uploads/business_cover/' . $business_folder . '/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        $cover_photo_path = $upload_dir . uniqid() . '_' . basename($cover_photo);
        move_uploaded_file($cover_photo_tmp, $cover_photo_path);
    } else {
        $cover_photo_path = isset($_POST['existing_fb_cover']) ? $_POST['existing_fb_cover'] : '';
    }
    if (!empty($_FILES['fb_cover']['name'])) { $fields[] = 'fb_cover = ?'; $params[] = $cover_photo_path; $types .= 's'; }

    // Handle Gallery Uploads
    $gallery_paths = [];
    if (isset($_FILES['business_gallery']['name']) && is_array($_FILES['business_gallery']['name']) && !empty(array_filter($_FILES['business_gallery']['name']))) {
        $business_folder = isset($_POST['business_name']) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_POST['business_name']) : 'default';
        $upload_dir = '../uploads/business_gallery/' . $business_folder . '/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        foreach ($_FILES['business_gallery']['name'] as $key => $business_images) {
            $business_images_tmp = $_FILES['business_gallery']['tmp_name'][$key];
            $business_images_filename = uniqid() . '_' . basename($business_images);
            $business_images_path = $upload_dir . $business_images_filename;
            if (move_uploaded_file($business_images_tmp, $business_images_path)) {
                $relative_path = 'uploads/business_gallery/' . $business_folder . '/' . $business_images_filename;
                $gallery_paths[] = $relative_path;
            }
        }
    }
    // Merge Gallery
    $stmt_latest = $conn->prepare("SELECT fb_gallery FROM fb_owner WHERE user_id = ?");
    $stmt_latest->bind_param("i", $user_id);
    $stmt_latest->execute();
    $result_latest = $stmt_latest->get_result();
    $row_latest = $result_latest->fetch_assoc();
    $existing_gallery = [];
    if ($row_latest && !empty($row_latest['fb_gallery'])) {
        $existing_gallery = json_decode($row_latest['fb_gallery'], true);
        if (!is_array($existing_gallery)) $existing_gallery = [];
    }
    $all_gallery = array_merge($existing_gallery, $gallery_paths);
    if (!empty($all_gallery)) { $fields[] = 'fb_gallery = ?'; $params[] = json_encode($all_gallery); $types .= 's'; }

    // Handle Menu Image Uploads
    $menu_image_paths = [];
    if (!empty($_FILES['menu_images']['name']) && is_array($_FILES['menu_images']['name'])) {
        $raw_name = isset($_POST['business_name']) ? $_POST['business_name'] : '';

        if (empty($raw_name) && isset($row['fb_name'])) {
            $raw_name = $row['fb_name'];
        }
        
        $business_folder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $raw_name);
        
        if (empty($business_folder)) {
            $business_folder = 'default';
        }

        $upload_dir = '../uploads/menu_images/' . $business_folder . '/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        
        foreach ($_FILES['menu_images']['name'] as $key => $img_name) {
            // Check for upload errors
            if ($_FILES['menu_images']['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $tmp_name = $_FILES['menu_images']['tmp_name'][$key];
            $filename = uniqid() . '_' . basename($img_name);
            $path = $upload_dir . $filename;
            
            if (move_uploaded_file($tmp_name, $path)) {
                // Save correct relative path
                $menu_image_paths[] = ['path' => 'uploads/menu_images/' . $business_folder . '/' . $filename, 'hidden' => 0];
            }
        }
    }
    // Stack new menu images
    $existing_menu_images = [];
    if (isset($row['menu_images']) && !empty($row['menu_images'])) {
        $existing_menu_images = json_decode($row['menu_images'], true);
        if (!is_array($existing_menu_images)) $existing_menu_images = [];
        foreach ($existing_menu_images as &$img) { if (is_string($img)) $img = ['path' => $img, 'hidden' => 0]; }
    }
    $all_menu_images = array_merge($existing_menu_images, $menu_image_paths);
    if (!empty($all_menu_images)) { $fields[] = 'menu_images = ?'; $params[] = json_encode($all_menu_images); $types .= 's'; }

    // Check for existing record
    $stmt_checking = $conn->prepare("SELECT 1 FROM fb_owner WHERE user_id = ?");
    $stmt_checking->bind_param('i', $user_id);
    $stmt_checking->execute();
    $result_checking = $stmt_checking->get_result();

    if ($result_checking->num_rows > 0) {
        if (count($fields) > 0) {
            $params[] = $user_id;
            $types .= 'i';
            $sql_update = "UPDATE fb_owner SET " . implode(', ', $fields) . " WHERE user_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param($types, ...$params);
            
            if ($stmt_update->execute()) {
                $_SESSION['swal_success'] = 'Details saved successfully.'; 
                header("Location: " . $_SERVER['REQUEST_URI']); 
                exit();
            }
            $stmt_update->close();
        }
    } else {
        // Insert New Record logic (preserved)
        $defaults = [
            'name' => $_POST['business_name'] ?? 'My Business',
            'type' => $_POST['business_type'] ?? 'Restaurant',
            'desc' => $_POST['business_description'] ?? '',
            'phone' => $_POST['phone_number'] ?? '',
            'email' => $_POST['email_address'] ?? '',
            'hours' => $_POST['operating_hours'] ?? '',
            'addr' => $_POST['business_address'] ?? '',
            'lat' => $_POST['fb_latitude'] ?? '0',
            'long' => $_POST['fb_longitude'] ?? '0'
        ];
        $json_gallery_str = isset($all_gallery) ? json_encode($all_gallery) : '[]';
        $json_menu_images_str = isset($all_menu_images) ? json_encode($all_menu_images) : '[]';
        
        $stmt_insert = $conn->prepare("INSERT INTO fb_owner (user_id, fb_name, fb_type, fb_description, fb_phone_number, fb_email_address, fb_operating_hours, fb_address, fb_photo, fb_cover, fb_latitude, fb_longitude, fb_gallery, menu_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt_insert->bind_param("isssssssssssss", 
            $user_id, 
            $defaults['name'], 
            $defaults['type'], 
            $defaults['desc'], 
            $defaults['phone'], 
            $defaults['email'], 
            $defaults['hours'], 
            $defaults['addr'], 
            $business_photo_path, 
            $cover_photo_path, 
            $defaults['lat'], 
            $defaults['long'],
            $json_gallery,     
            $json_menu_images 
        );
        
        if($stmt_insert->execute()) {
             echo "<script>alert('Business profile created and images saved.'); window.location.href = window.location.href;</script>";
             exit;
        }
        $stmt_insert->close();
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TasteLibmanan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.0/remixicon.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#A80000",
                        secondary: "#FF6B00",
                        dark: "#1A1A1A",
                        light: "#F9FAFB"
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Pacifico', 'cursive'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        /* Hide scrollbar for gallery but allow scroll */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen pb-20">

    <nav class="glass-nav fixed w-full top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <h1 class="font-display text-2xl text-primary">
                        Taste<span class="text-gray-800">Libmanan</span>
                    </h1>
                    <span class="hidden sm:inline-block px-2 py-0.5 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">Owner</span>
                </div>
                
                <div class="hidden sm:flex items-center space-x-6">
                    <button id="publicProfileLink" class="text-gray-500 hover:text-primary transition flex items-center gap-2 text-sm font-medium">
                        <i class="ri-eye-line text-lg"></i> View Public Page
                    </button>
                    
                    <button id="settingsLink" class="text-gray-500 hover:text-primary transition">
                        <i class="ri-settings-3-line text-xl"></i>
                    </button>

                    <div class="relative">
                        <button class="text-gray-500 hover:text-primary transition relative">
                            <i class="ri-notification-2-line text-xl"></i>
                            <span id="notif-badge" class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full hidden"></span>
                        </button>
                    </div>

                    <a href="../logout.php" class="bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <i class="ri-logout-box-r-line"></i> Logout
                    </a>
                </div>

                <button id="menuToggle" class="sm:hidden text-gray-600 focus:outline-none p-2">
                    <i class="ri-menu-4-line text-2xl"></i>
                </button>
            </div>
        </div>

        <div id="topIcons" class="hidden sm:hidden bg-white border-t border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 py-3 space-y-3">
                <a href="#" id="publicProfileLinkMobile" class="block text-gray-600 hover:text-primary py-2"><i class="ri-user-line mr-2"></i> View Profile</a>
                <a href="#" id="settingsLinkMobile" class="block text-gray-600 hover:text-primary py-2"><i class="ri-settings-3-line mr-2"></i> Settings</a>
                <a href="../logout.php" class="block text-red-600 py-2"><i class="ri-logout-box-line mr-2"></i> Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="h-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <?php
        // Data Fetching Logic preserved
        $business_name = ''; $business_type = ''; $business_description = ''; 
        $phone_number = ''; $email_address = ''; $operating_hours = ''; 
        $business_address = ''; $latitude = ''; $longitude = ''; $status = 'closed';

        $stmt_get = $conn->prepare("SELECT * FROM `fb_owner` WHERE user_id = ?");
        $stmt_get->bind_param("i", $user_id);
        $stmt_get->execute();
        $result = $stmt_get->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $business_name = htmlspecialchars($row['fb_name']);
                $business_type = htmlspecialchars($row['fb_type']);
                $business_description = htmlspecialchars($row['fb_description']);
                $phone_number = htmlspecialchars($row['fb_phone_number']);
                $email_address = htmlspecialchars($row['fb_email_address']);
                $operating_hours = htmlspecialchars($row['fb_operating_hours']);
                $business_address = htmlspecialchars($row['fb_address']);
                $business_photo = htmlspecialchars($row['fb_photo']);
                $fb_cover = htmlspecialchars($row['fb_cover']);
                $latitude = htmlspecialchars($row['fb_latitude']);
                $longitude = htmlspecialchars($row['fb_longitude']);
                $status = htmlspecialchars($row['fb_status']);
            }
        }
        $stmt_get->close();
        ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-2xl p-6 card-shadow flex items-center justify-between border border-gray-100">
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Store Status</h2>
                    <p class="text-xs text-gray-400 mt-1">Control visibility to customers</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="status-label" class="text-lg font-bold <?php echo ($status === 'open') ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo ($status === 'open') ? 'Open' : 'Closed'; ?>
                    </span>
                    <label for="status-toggle" class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="status-toggle" class="sr-only peer" <?php echo ($status === 'open') ? 'checked' : ''; ?> onchange="updateShopStatusAJAX(this.checked)">
                        <div id="toggle-path" class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                    <input type="hidden" name="fb_status" id="fb_status" value="<?php echo $status; ?>">
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 card-shadow flex items-center justify-between border border-gray-100">
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Store QR Code</h2>
                    <p class="text-xs text-gray-400 mt-1">Let customers find you</p>
                </div>
                <button type="button" id="showQrBtn" class="bg-gray-100 text-gray-700 hover:bg-primary hover:text-white p-3 rounded-xl transition shadow-sm">
                    <i class="ri-qr-code-line text-2xl"></i>
                </button>
            </div>

            <div class="bg-white rounded-2xl p-6 card-shadow flex items-center justify-between border border-gray-100">
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Menu Management</h2>
                    <p class="text-xs text-gray-400 mt-1">Update your offerings</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="openManageMenus()" class="bg-secondary text-white p-3 rounded-xl hover:bg-orange-600 transition shadow-sm" title="Manage Items">
                        <i class="ri-list-settings-line text-xl"></i>
                    </button>
                    <button id="addMenuManualBtn" class="bg-primary text-white p-3 rounded-xl hover:bg-red-700 transition shadow-sm" title="Add New">
                        <i class="ri-add-line text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="ri-bar-chart-grouped-line text-primary"></i> Review Performance
                        </h3>
                        
                        <div class="flex gap-2">
                            <select id="year-select" onchange="updateChartDate()" class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                                <?php 
                                    // Set defaults based on URL or current date
                                    $selected_month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
                                    $selected_year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
                                    
                                    $current_year_real = date('Y');
                                    $start_year = 2025; 
                                    $end_year = $current_year_real + 1;
                        
                                    for ($y = $start_year; $y <= $end_year; $y++) {
                                        $sel = ($selected_year == $y) ? 'selected' : '';
                                        echo "<option value='$y' $sel>$y</option>";
                                    }
                                ?>
                            </select>
                                
                            <select id="month-select" onchange="updateChartDate()" class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                                <?php 
                                    // Loop 1 to 12 for all months
                                    for ($m = 1; $m <= 12; $m++) {
                                        // Create date object to get Month Name
                                        $monthName = date('F', mktime(0, 0, 0, $m, 10)); 
                                        $sel = ($selected_month == $m) ? 'selected' : '';
                                        echo "<option value='$m' $sel>$monthName</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <form method="post" enctype="multipart/form-data" id="business-info-form" class="bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="ri-store-3-line text-secondary"></i> Business Profile
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update your public establishment details.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                                <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary p-2.5 bg-gray-50" name="business_name" value="<?php echo $business_name ?>" required>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary p-2.5 bg-gray-50" name="business_type">
                                    <option value="Restaurant" <?php if ($business_type == "Restaurant") echo "selected" ?>>Restaurant</option>
                                    <option value="Cafe" <?php if ($business_type == "Cafe") echo "selected" ?>>Cafe</option>
                                    <option value="Bakery" <?php if ($business_type == "Bakery") echo "selected" ?>>Bakery</option>
                                    <option value="Fastfood" <?php if ($business_type == "Fastfood") echo "selected" ?>>Fastfood</option>
                                </select>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary p-2.5 bg-gray-50" rows="3" name="business_description"><?php echo $business_description ?></textarea>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="ri-contacts-line"></i> Contact & Operations</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Phone Number</label>
                                    <input 
                                        type="tel" 
                                        maxlength="11"
                                        minlength="11"
                                        pattern="\d{11}"
                                        title="Please enter exactly 11 digits (e.g., 09123456789)"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                        placeholder="e.g. 09123456789"
                                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" 
                                        name="phone_number" 
                                        value="<?php echo $phone_number ?>"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Email Address</label>
                                    <input 
                                        type="email" 
                                        pattern="[^ @]*@[^ @]*"
                                        title="Please enter a valid email address"
                                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" 
                                        name="email_address" 
                                        value="<?php echo $email_address ?>" 
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Operating Hours</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" name="operating_hours" value="<?php echo $operating_hours ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Address Text</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" name="business_address" value="<?php echo $business_address ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-end mb-3">
                                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2"><i class="ri-map-pin-line"></i> Location Mapping</h4>
                                <button type="button" id="locateBtn" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition font-medium flex items-center gap-1">
                                    <i class="ri-crosshair-2-line"></i> Locate Me
                                </button>
                            </div>
                            <div class="w-full h-64 rounded-xl overflow-hidden shadow-inner border border-gray-200 mb-4">
                                <div id="map" class="w-full h-full"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-400">Latitude</label>
                                    <input type="text" id="latitude" class="w-full bg-gray-100 border-none rounded text-xs text-gray-600" name="fb_latitude" value="<?php echo $latitude ?>" readonly>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400">Longitude</label>
                                    <input type="text" id="longitude" class="w-full bg-gray-100 border-none rounded text-xs text-gray-600" name="fb_longitude" value="<?php echo $longitude ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <button type="reset" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Discard</button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium bg-primary text-white hover:bg-red-800 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Save Changes</button>
                    </div>
                </div>

            <div class="space-y-6">
                
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100 h-fit">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="ri-image-line text-secondary"></i> Visuals
                    </h3>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Logo / Profile</label>
                        <div class="flex items-start gap-4">
                            <div class="relative">
                                <?php if (!empty($business_photo)): ?>
                                    <img id="preview-logo" src="<?php echo '../' . ltrim($business_photo, './'); ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm transition-opacity duration-200">
                                <?php else: ?>
                                    <div id="preview-logo-placeholder" class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-dashed border-gray-300"><i class="ri-store-line text-2xl"></i></div>
                                    <img id="preview-logo" src="" class="hidden w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm transition-opacity duration-200">
                                <?php endif; ?>
                                
                                <div id="loader-logo" class="hidden absolute inset-0 bg-white/50 flex items-center justify-center">
                                    <i class="ri-loader-4-line animate-spin text-primary"></i>
                                </div>
                            </div>
                                
                            <div class="flex-1">
                                <input type="file" id="input-logo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-red-800 cursor-pointer"/>
                                <p class="text-[10px] text-gray-400 mt-1">Rec: 500x500px JPG/PNG. Auto-saves on selection.</p>
                            </div>
                        </div>
                    </div>
                                
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Cover Photo</label>
                        <div class="relative w-full h-32 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 group">
                            <?php if (!empty($fb_cover)): ?>
                                <img id="preview-cover" src="<?php echo '../' . ltrim($fb_cover, './'); ?>" class="w-full h-full object-cover transition-opacity duration-200">
                            <?php else: ?>
                                <div id="preview-cover-placeholder" class="w-full h-full flex items-center justify-center text-gray-400"><span class="text-xs">No Cover</span></div>
                                <img id="preview-cover" src="" class="hidden w-full h-full object-cover transition-opacity duration-200">
                            <?php endif; ?>
                            
                            <div id="loader-cover" class="hidden absolute inset-0 bg-black/20 z-20 flex items-center justify-center">
                                <i class="ri-loader-4-line animate-spin text-white text-2xl"></i>
                            </div>
                            
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer z-10">
                                <input type="file" id="input-cover" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                <span class="text-white text-xs font-bold"><i class="ri-upload-cloud-line mr-1"></i> Change Cover</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase">Gallery</label>
                            <button type="button" id="viewGalleryBtn" class="text-xs text-primary font-medium hover:underline">Manage</button>
                        </div>
                        <input type="file" name="business_gallery[]" form="business-info-form" multiple class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 mb-2"/>
                        
                        <div class="grid grid-cols-4 gap-2">
                            <?php
                            if (!empty($row['fb_gallery'])) {
                                $gallery_images = json_decode($row['fb_gallery'], true);
                                if (is_array($gallery_images)) {
                                    $count = 0;
                                    foreach ($gallery_images as $img) {
                                        if($count < 4) {
                                            echo '<div class="aspect-square rounded overflow-hidden border border-gray-200">';
                                            echo '<img src="../' . htmlspecialchars($img) . '" class="w-full h-full object-cover">';
                                            echo '</div>';
                                            $count++;
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100 text-center">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Menu Images</h3>
                    <p class="text-xs text-gray-400 mb-3">Upload visual menus (flyers/scans)</p>
                    <button type="button" id="addMenuImageBtn" class="w-full py-2 border border-dashed border-primary text-primary rounded-lg text-sm hover:bg-red-50 transition">
                        <i class="ri-file-list-3-line"></i> Manage Menu Images
                    </button>
                </div>

            </div>
            
            </form> </div>

    </div>

    <button onclick="toggleChatWindow()" class="fixed bottom-6 right-6 h-14 w-14 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full shadow-lg hover:shadow-blue-500/30 hover:scale-105 transition flex items-center justify-center z-50">
        <i class="ri-message-3-line text-2xl"></i>
                            
        <span id="chat-notification-badge" class="absolute -top-1 -left-1 flex h-5 w-5 hidden">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-5 w-5 bg-red-600 border-2 border-white text-[10px] items-center justify-center text-white font-bold" id="badge-count">!</span>
        </span>
    </button>
                            
    <div id="floating-chat" 
         style="right: 24px; left: auto;"
         class="fixed bottom-24 right-6 left-auto w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden z-50 flex flex-col overflow-hidden transition-all duration-300 origin-bottom-right transform scale-95">
                            
        <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center"><i class="ri-admin-line"></i></div>
                <div>
                    <h3 class="font-bold text-sm">BPLO Support</h3>
                    <p class="text-[10px] text-blue-100 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> Online</p>
                </div>
            </div>
            <button onclick="toggleChatWindow()" class="hover:bg-white/10 rounded p-1"><i class="ri-close-line"></i></button>
        </div>
                            
        <div id="owner-chat-box" class="h-80 overflow-y-auto p-4 bg-gray-50 flex flex-col gap-3"></div>
                            
        <form id="chat-form" onsubmit="event.preventDefault(); sendMessage();" class="p-3 bg-white border-t flex gap-2">
            <input type="text" id="owner-msg-input" class="flex-grow bg-gray-100 border-none rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Type message..." autocomplete="off" />
            <button type="submit" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition"><i class="ri-send-plane-fill"></i></button>
        </form>
    </div>

    <style>
        .modal-bg { background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
    </style>

    <div id="qrModal" class="fixed inset-0 modal-bg flex items-center justify-center z-50 hidden transition-opacity">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full text-center relative animate-[fadeIn_0.3s_ease-out]">
            <button id="closeQrModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl"><i class="ri-close-circle-fill"></i></button>
            <h3 class="text-xl font-bold mb-1 text-gray-800">Scan to Visit</h3>
            <p class="text-sm text-gray-500 mb-6">Share this QR code with your customers</p>
            <div class="bg-white p-2 rounded-xl border border-dashed border-gray-300 inline-block mb-4">
                <img id="qrImage" src="" alt="QR Code" class="w-48 h-48 object-contain">
            </div>
            <a id="qrUrl" href="#" target="_blank" class="block text-xs text-blue-500 hover:underline break-all">Link</a>
            <button class="mt-6 w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium" onclick="window.print()">Print QR</button>
        </div>
    </div>

    <div id="publicProfileModal" class="hidden fixed inset-0 modal-bg flex justify-center items-center z-50">
        <div class="bg-white w-full h-full md:w-[90%] md:h-[90%] md:rounded-2xl shadow-2xl relative overflow-hidden flex flex-col">
            <div class="bg-gray-100 px-4 py-2 flex justify-between items-center border-b">
                <span class="text-sm font-semibold text-gray-500">Public View Preview</span>
                <button id="closePublicProfile" class="text-gray-500 hover:text-black text-2xl">&times;</button>
            </div>
            <iframe id="publicProfileFrame" src="" class="w-full flex-1 border-0"></iframe>
        </div>
    </div>

    <div id="accountModal" class="fixed inset-0 modal-bg hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-96 p-6 relative">
            <h2 class="text-xl font-bold mb-4">Account Settings</h2>
            <form id="accountForm" method="POST" action="../users/update_account.php">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo isset($_SESSION['fb_email_address']) ? htmlspecialchars($_SESSION['fb_email_address']) : ''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" data-close-modal class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">Save Changes</button>
                </div>
            </form>
            <button data-close-modal class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
        </div>
    </div>

    <div id="galleryModal" class="hidden fixed inset-0 modal-bg flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-11/12 md:w-3/4 lg:w-1/2 relative p-6 max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold">Gallery Management</h2>
                <button type="button" id="closeGalleryModal" class="text-gray-400 hover:text-gray-600 text-2xl"><i class="ri-close-line"></i></button>
            </div>
            <div id="galleryImages" class="flex-1 overflow-y-auto p-2 grid grid-cols-3 sm:grid-cols-4 gap-4 content-start">
                <p class="text-gray-400 col-span-full text-center py-10">Loading images...</p>
            </div>
            <div class="mt-4 pt-4 border-t flex justify-between items-center bg-gray-50 -m-6 p-6 rounded-b-2xl">
                <span class="text-xs text-gray-500">Manage your business photos</span>
                <button type="button" id="deleteAllGalleryBtn" class="text-red-600 text-sm font-medium hover:bg-red-50 px-3 py-1.5 rounded-lg border border-transparent hover:border-red-100 transition">Delete All</button>
            </div>
        </div>
    </div>
    
    <div id="deleteAllModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl p-6 shadow-xl max-w-xs w-full text-center">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ri-delete-bin-line text-2xl"></i></div>
            <h3 class="font-bold text-gray-800">Delete all photos?</h3>
            <p class="text-sm text-gray-500 mb-6">This action cannot be undone.</p>
            <div class="flex gap-2 justify-center">
                <button id="cancelDeleteAllBtn" class="px-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-700">Cancel</button>
                <button id="confirmDeleteAllBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">Yes, Delete</button>
            </div>
        </div>
    </div>

    <div id="menuModal" class="fixed inset-0 hidden modal-bg flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-11/12 max-w-4xl h-[90vh] flex flex-col relative overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="ri-restaurant-2-line text-secondary"></i> Add Menu Items</h2>
                <button onclick="closeMenuModal()" class="text-gray-400 hover:text-gray-700 text-2xl"><i class="ri-close-line"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div id="menuItemsContainer" class="space-y-4">
                    <div class="menu-item flex flex-col sm:flex-row gap-6 bg-white p-5 rounded-xl shadow-sm border border-gray-100 relative group">
                        <div class="w-full sm:w-32 flex-shrink-0">
                            <label class="cursor-pointer block">
                                <input type="file" class="menuImage hidden" accept="image/*">
                                <div class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center border-2 border-dashed border-gray-200 hover:border-secondary transition relative">
                                    <img class="previewImage hidden absolute inset-0 w-full h-full object-cover" alt="Preview">
                                    <div class="uploadText text-center p-2">
                                        <i class="ri-image-add-line text-2xl text-gray-400"></i>
                                        <span class="block text-[10px] text-gray-500 mt-1">Upload</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Item Name</label>
                                <input type="text" class="menuName w-full border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-secondary focus:border-secondary" placeholder="e.g. Adobo">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Category</label>
                                <select class="menuCategory w-full border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-secondary focus:border-secondary">
                                    <option value="">Select...</option>
                                    <option value="Meals">Meals</option>
                                    <option value="Snacks">Snacks</option>
                                    <option value="Desserts">Desserts</option>
                                    <option value="Drinks">Drinks</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Price (₱)</label>
                                <input type="number" class="menuPrice w-full border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-secondary focus:border-secondary" placeholder="0.00">
                            </div>
                        </div>
                        <button class="removeItem absolute top-3 right-3 text-red-400 hover:text-red-600 hidden group-hover:block"><i class="ri-delete-bin-line text-lg"></i></button>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <button id="addAnotherItem" class="flex items-center gap-2 text-secondary font-medium hover:bg-orange-50 px-4 py-2 rounded-lg transition">
                        <i class="ri-add-circle-line text-xl"></i> Add Another Item
                    </button>
                </div>
            </div>

            <div class="p-4 border-t bg-white flex justify-end gap-3">
                <button onclick="closeMenuModal()" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 text-sm font-medium">Cancel</button>
                <button id="saveMenuBtn" type="button" class="px-5 py-2.5 rounded-lg bg-secondary text-white hover:bg-orange-600 shadow text-sm font-medium">Save Items</button>
            </div>
        </div>
    </div>

    <div id="menuImagesModal" class="fixed inset-0 modal-bg flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-lg relative">
            <button id="closeMenuImagesModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="ri-close-line text-xl"></i></button>
            <h3 class="text-lg font-bold mb-4">Menu Pages/Flyers</h3>
            <div id="menuImagesList" class="flex flex-wrap gap-4 mb-6 max-h-60 overflow-y-auto p-2 bg-gray-50 rounded-lg border border-gray-100">
            </div>
                            
            <form method="post" enctype="multipart/form-data" class="border-t pt-4">
                <input type="hidden" name="business_name" value="<?php echo htmlspecialchars($business_name); ?>">
                            
                <label class="block mb-2 text-sm font-medium text-gray-700">Upload New Page</label>
                <input type="file" name="menu_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-4">
                <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg hover:bg-red-800 transition">Upload</button>
            </form>
        </div>
    </div>

    <div id="manageMenusModal" class="fixed inset-0 modal-bg hidden z-50 flex items-center justify-center p-2 sm:p-4">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800">Edit Menu Items</h2>
                <button onclick="closeManageMenus()" class="text-gray-400 hover:text-red-500 text-2xl"><i class="ri-close-line"></i></button>
            </div>
            <div id="menusList" class="overflow-y-auto p-6 space-y-3 bg-gray-50 flex-1">
                <p class="text-gray-500 text-center py-10">Loading...</p>
            </div>
        </div>
    </div>

    <div id="reviewsModal" class="fixed inset-0 hidden modal-bg flex items-center justify-center z-50">
        <div id="modalBox" class="bg-white p-6 rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto shadow-2xl relative">
            <button onclick="closeReviewsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="ri-close-line text-2xl"></i></button>
            <h2 id="modalTitle" class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Reviews</h2>
            <div id="reviews-container" class="space-y-4"></div>
        </div>
    </div>

    <!-- FOR VIEWING PICTURE AND VIDEO -->
    <div id="mediaPreviewModal" class="fixed inset-0 z-[100] hidden bg-black/90 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeMediaModal()">
        <button class="absolute top-5 right-5 text-white text-4xl hover:text-red-500 transition">&times;</button>
                            
        <div class="max-w-4xl max-h-full w-full flex items-center justify-center" onclick="event.stopPropagation()">
            <img id="previewImage" src="" class="hidden max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
            <video id="previewVideo" controls class="hidden max-w-full max-h-[90vh] rounded-lg shadow-2xl">
                <source id="previewVideoSource" src="" type="video/mp4">
            </video>
        </div>
    </div>


    <script>
        // For Auto_Uploading business photo and cover_photo
        document.addEventListener('DOMContentLoaded', () => {

            const handleAutoUpload = (inputId, type, imgId, loaderId, placeholderId = null) => {
                const input = document.getElementById(inputId);

                if (!input) {
                    console.error("Input element not found:", inputId);
                    return;
                }
            
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('type', type);
                    
                        // Showing Loader
                        const loader = document.getElementById(loaderId);
                        const image = document.getElementById(imgId);
                        if(loader) {
                            loader.classList.remove('hidden');
                            loader.classList.add('opacity-50');
                        }
                    
                        fetch('auto_uploading_photo_cover.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text()) 
                        .then(text => {
                            // Log the raw response to Console for debugging
                            console.log("Raw Server Response:", text); 
                        
                            try {
                                // Manually parse the JSON
                                const data = JSON.parse(text);
                            
                                if (data.success) {
                                    image.src = data.new_src + '?t=' + new Date().getTime();
                                    image.classList.remove('hidden');
                                    if (placeholderId) {
                                        const placeholder = document.getElementById(placeholderId);
                                        if(placeholder) placeholder.classList.add('hidden');
                                    }
                                
                                    // SweetAlert Toast Notification
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message
                                    });
                                } else {
                                    Swal.fire('Error: ', data.message || 'Upload failed', 'error');
                                }
                            } catch (e) {
                                // This block runs if PHP returns text mixed with JSON
                                console.error("JSON Parsing Error:", e);
                                Swal.fire('Server Error', 'The server returned invalid data. Check console for details.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Fetch/Network Error: ', error);
                            Swal.fire('Error: ', 'An unexpected connection error occurred', 'error');
                        })
                        .finally(() => {
                            if(loader) loader.classList.add('hidden');
                            if(image) image.classList.remove('opacity-50');
                            input.value = '';
                        });
                    }
                });
            };

            // Listeners
            handleAutoUpload('input-logo', 'logo', 'preview-logo', 'loader-logo', 'preview-logo-placeholder');
            handleAutoUpload('input-cover', 'cover', 'preview-cover', 'loader-cover', 'preview-cover-placeholder');
        });

        // Notification of "Save Changes"
        <?php if (isset($_SESSION['swal_success'])): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '<?php echo $_SESSION['swal_success']; ?>',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                });
            <?php unset($_SESSION['swal_success']); // Clear message so it doesn't show again on refresh ?>
        <?php endif; ?>

        // --- Navigation Mobile Toggle ---
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('menuToggle');
            const topIcons = document.getElementById('topIcons');
            const settingsLink = document.getElementById('settingsLink');
            const settingsLinkMobile = document.getElementById('settingsLinkMobile');
            const accountModal = document.getElementById('accountModal');
            const closeModals = document.querySelectorAll('[data-close-modal]');

            toggleBtn.addEventListener('click', () => { topIcons.classList.toggle('hidden'); });

            // Account Modal Logic
            const openSettings = (e) => { e.preventDefault(); accountModal.classList.remove('hidden'); }
            if(settingsLink) settingsLink.addEventListener('click', openSettings);
            if(settingsLinkMobile) settingsLinkMobile.addEventListener('click', openSettings);
            
            closeModals.forEach(btn => btn.addEventListener('click', () => accountModal.classList.add('hidden')));
        });

        // --- Status Logic (Synced with Toggle) ---
        function updateShopStatusAJAX(isOpen) {
            const status = isOpen ? 'open' : 'closed';
            document.getElementById('fb_status').value = status;
            
            // Visual Update
            const label = document.getElementById('status-label');
            const path = document.getElementById('toggle-path');
            
            label.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            if(status === 'open') {
                label.classList.remove('text-red-600'); label.classList.add('text-green-600');
                path.classList.add('peer-checked:bg-green-500');
            } else {
                label.classList.remove('text-green-600'); label.classList.add('text-red-600');
            }

            // AJAX
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'status=' + encodeURIComponent(status)
            }).then(r => r.json()).catch(e => console.error(e));
        }

        function checkRealTimeStatus() {
            fetch('get_status.php?user_id=<?php echo $_SESSION['user_id']; ?>')
            .then(r => r.json())
            .then(data => {
                if(data.status) {
                    const currentToggle = document.getElementById('status-toggle').checked;
                    const remoteOpen = data.status === 'open';
                    if(currentToggle !== remoteOpen) {
                        document.getElementById('status-toggle').checked = remoteOpen;
                        updateShopStatusAJAX(remoteOpen); // Update UI text
                    }
                }
            });
        }
        setInterval(checkRealTimeStatus, 10000);

        // --- Google Maps Logic ---
        function initBusinessMap() {
            var existingLat = document.getElementById('latitude').value;
            var existingLng = document.getElementById('longitude').value;
            var defaultLat = 13.6860; var defaultLng = 123.0560;
            var myLat = existingLat ? parseFloat(existingLat) : defaultLat;
            var myLng = existingLng ? parseFloat(existingLng) : defaultLng;
            var myLatlng = { lat: myLat, lng: myLng };

            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: false,
                styles: [{ featureType: "poi", stylers: [{ visibility: "off" }] }]
            });

            var marker = new google.maps.Marker({ position: myLatlng, map: map, draggable: true, title: "Drag to location" });

            function updateInputs(latLng) {
                document.getElementById('latitude').value = latLng.lat().toFixed(6);
                document.getElementById('longitude').value = latLng.lng().toFixed(6);
            }

            marker.addListener("dragend", function(event) { updateInputs(event.latLng); map.panTo(event.latLng); });
            map.addListener("click", function(event) { marker.setPosition(event.latLng); updateInputs(event.latLng); map.panTo(event.latLng); });

            // Geolocation
            var locateBtn = document.getElementById('locateBtn');
            if (locateBtn) {
                locateBtn.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        locateBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Locating...';
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
                            marker.setPosition(userPos); map.setCenter(userPos); map.setZoom(17);
                            updateInputs(new google.maps.LatLng(userPos.lat, userPos.lng));
                            locateBtn.innerHTML = '<i class="ri-crosshair-2-line"></i> Locate Me';
                        }, function() { alert("Location access denied or unavailable."); locateBtn.innerHTML = '<i class="ri-crosshair-2-line"></i> Locate Me'; });
                    }
                });
            }
        }

        function openMediaModal(src, type) {
            const modal = document.getElementById('mediaPreviewModal');
            const img = document.getElementById('previewImage');
            const vid = document.getElementById('previewVideo');
            const vidSrc = document.getElementById('previewVideoSource');
                
            // Reset
            img.classList.add('hidden');
            vid.classList.add('hidden');
                
            if (type === 'image') {
                img.src = src;
                img.classList.remove('hidden');
            } else if (type === 'video') {
                vidSrc.src = src;
                vid.load();
                vid.classList.remove('hidden');
            }
        
            modal.classList.remove('hidden');
        }
        
        function closeMediaModal() {
            const modal = document.getElementById('mediaPreviewModal');
            const vid = document.getElementById('previewVideo');
            
            modal.classList.add('hidden');
            vid.pause(); // Stop video when closing
            vid.currentTime = 0;
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeMediaModal();
            }
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1A2Mej_RdKT_Lq-y0kYIcNW93yY-RrBY&callback=initBusinessMap"></script>

    <?php
    $fbowner_id = $fb_id;
    $sql = "
        SELECT 
            WEEK(effective_date, 1) - WEEK(DATE_SUB(effective_date, INTERVAL DAYOFMONTH(effective_date)-1 DAY), 1) + 1 AS week_num, 
            COUNT(*) AS review_count 
        FROM (
            SELECT 
                fbowner_id,
                CASE 
                    WHEN updated_at IS NOT NULL AND updated_at != '0000-00-00 00:00:00' AND updated_at != created_at THEN updated_at 
                    ELSE created_at 
                END as effective_date
            FROM reviews
        ) as r
        WHERE fbowner_id = ? 
        AND MONTH(effective_date) = ? 
        AND YEAR(effective_date) = ? 
        GROUP BY week_num 
        ORDER BY week_num ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $fbowner_id, $selected_month, $selected_year);
    $stmt->execute(); $res = $stmt->get_result();
    $weeklyData = [0, 0, 0, 0, 0];
    while ($row = $res->fetch_assoc()) { $idx = $row['week_num'] - 1; if ($idx >= 0 && $idx < 5) $weeklyData[$idx] = (int)$row['review_count']; }
    $stmt->close();
    ?>
    <script>
        function updateChartDate() {
            const m = document.getElementById('month-select').value;
            const y = document.getElementById('year-select').value;
                
            // Reload page with both parameters
            window.location.href = `?month=${m}&year=${y}`;
        }
        
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const weeklyData = <?php echo json_encode($weeklyData); ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
                datasets: [{
                    label: 'Reviews',
                    data: weeklyData,
                    backgroundColor: '#A80000',
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                },
                onClick: (e, elements) => {
                    if (elements.length > 0) {
                        const week = elements[0].index + 1;
                        openReviewsModal(week);
                    }
                }
            }
        });

        function openReviewsModal(week) {
            const m = <?php echo $selected_month; ?>; const y = <?php echo $selected_year; ?>; const id = <?php echo $fbowner_id; ?>;
            document.getElementById("modalTitle").innerText = `Reviews: Week ${week}`;
            document.getElementById('reviewsModal').classList.remove('hidden');
            fetch(`fetch_reviews.php?week=${week}&month=${m}&year=${y}&fbowner_id=${id}`)
                .then(r => r.text()).then(h => document.getElementById('reviews-container').innerHTML = h);
        }
        function closeReviewsModal() { document.getElementById('reviewsModal').classList.add('hidden'); }
    </script>

    <script>
        // Chat
        // Chat
    const adminId = 27; 
    const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
    let chatInterval = null;
    let notificationInterval = null; // New interval for checking unread
    
    const chatWidget = document.getElementById('floating-chat');
    const chatBox = document.getElementById('owner-chat-box');
    const msgInput = document.getElementById('owner-msg-input');
    const notificationBadge = document.getElementById('chat-notification-badge');

    // 1. Function to check for unread messages (runs in background)
    function checkUnreadMessages() {
        // Don't check if chat window is currently OPEN
        if (!chatWidget.classList.contains('hidden')) return;

        fetch(`check_unread_messages.php?sender_id=${adminId}`)
            .then(r => r.json())
            .then(data => {
                if (data.count > 0) {
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            })
            .catch(err => console.error("Error checking unread:", err));
    }

    // 2. Start checking immediately when page loads
    document.addEventListener('DOMContentLoaded', () => {
        checkUnreadMessages();
        notificationInterval = setInterval(checkUnreadMessages, 3000); // Check every 3 seconds
    });

    // 3. Modified Toggle Function
    function toggleChatWindow() {
        chatWidget.classList.toggle('hidden');
        
        if(!chatWidget.classList.contains('hidden')) {
            // Chat is OPENED
            loadMessages(); 
            scrollToBottom();
            
            // Mark as read immediately
            markMessagesAsRead();
            
            // Hide badge visually
            notificationBadge.classList.add('hidden');

            if(!chatInterval) chatInterval = setInterval(loadMessages, 3000);
        } else { 
            // Chat is CLOSED
            if(chatInterval) clearInterval(chatInterval); 
            chatInterval = null; 
            
            // Resume checking for notification badge
            checkUnreadMessages();
        }
    }

    // Mark messages as read in Database
    function markMessagesAsRead() {
        const fd = new FormData();
        fd.append('sender_id', adminId);
        fetch('mark_messages_read.php', { method: 'POST', body: fd });
    }

    function formatTimeMessage(timestamp) {
        if (timestamp === 'Sending') return timestamp;
        const date = new Date(timestamp);
        const now = new Date();

        // Checking if date is valid
        if (isNaN(date.getTime())) return timestamp;

        // Check if the message date matches today's date
        const isToday = date.getDate() === now.getDate() &&
                        date.getMonth() === now.getMonth() &&
                        date.getFullYear() === now.getFullYear();
        const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };

        // Checking the date
        if (isToday) {
            return date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        } else {
            return date.toLocaleTimeString([], {
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }

    function createMessageBubble(text, isMe, timestamp) {
        const formattedTime = formatTimeMessage(timestamp);

        const div = document.createElement("div");
        div.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'}`;
        div.innerHTML = `
            <div class="px-4 py-2 rounded-2xl text-sm max-w-[85%] ${isMe ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-none shadow-sm'}">
                ${text}
            </div>
            <span class="text-[10px] text-gray-400 mt-1 px-1">${formattedTime}</span>
        `;
        return div;
    }

    function loadMessages() {
        if(chatWidget.classList.contains('hidden')) return;
        
        fetch(`get_messages.php?receiver_id=${adminId}`).then(r=>r.json()).then(msgs => {
            const atBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 50;
            chatBox.innerHTML = "";
            if(msgs.length === 0) chatBox.innerHTML = '<p class="text-center text-gray-300 text-xs mt-10">No messages yet.</p>';
            
            msgs.forEach(m => chatBox.appendChild(createMessageBubble(m.message, m.sender_id == currentUserId, m.timestamp)));
            
            if(atBottom) scrollToBottom();
            
            // Ensure we keep marking as read if new messages arrive while window is open
            markMessagesAsRead();
        });
    }

    function sendMessage() {
        const text = msgInput.value.trim(); if(!text) return;
        chatBox.appendChild(createMessageBubble(text, true, 'Sending...')); scrollToBottom();
        msgInput.value = "";
        const fd = new FormData(); fd.append('receiver_id', adminId); fd.append('message', text);
        fetch('send_message.php', { method: 'POST', body: fd }).then(r=>r.json());
    }
    
    function scrollToBottom() { chatBox.scrollTop = chatBox.scrollHeight; }

        // Gallery Modals
        const galModal = document.getElementById('galleryModal');
        const galDiv = document.getElementById('galleryImages');
        
        document.getElementById('viewGalleryBtn').onclick = () => {
            galModal.classList.remove('hidden');
            fetch('get_gallery_images.php').then(r=>r.json()).then(imgs => {
                galDiv.innerHTML = '';
                if(imgs.length === 0) galDiv.innerHTML = '<p class="text-gray-400 col-span-full text-center">No images found.</p>';
                imgs.forEach(img => {
                    const d = document.createElement('div');
                    d.className = 'relative aspect-square rounded-lg overflow-hidden group border';
                    d.innerHTML = `<img src="../${img}" class="w-full h-full object-cover"><button class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition gallery-delete-btn" data-img="${img}">&times;</button>`;
                    galDiv.appendChild(d);
                });
            });
        };
        document.getElementById('closeGalleryModal').onclick = () => galModal.classList.add('hidden');
        
        // Delegation for delete
        galDiv.addEventListener('click', e => {
            if(e.target.classList.contains('gallery-delete-btn')) {
                const img = e.target.dataset.img;
                const xhr = new XMLHttpRequest(); xhr.open('POST', 'delete_gallery_image.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = () => { if(xhr.status === 200) e.target.parentElement.remove(); };
                xhr.send('img=' + encodeURIComponent(img));
            }
        });

        // Delete All
        document.getElementById('deleteAllGalleryBtn').onclick = () => document.getElementById('deleteAllModal').classList.remove('hidden');
        document.getElementById('cancelDeleteAllBtn').onclick = () => document.getElementById('deleteAllModal').classList.add('hidden');
        document.getElementById('confirmDeleteAllBtn').onclick = () => {
            const xhr = new XMLHttpRequest(); xhr.open('POST', 'delete_gallery_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = () => { if(xhr.status === 200) { galDiv.innerHTML=''; document.getElementById('deleteAllModal').classList.add('hidden'); }};
            xhr.send('delete_all=1');
        };

        // QR Code
        document.getElementById('showQrBtn').onclick = () => {
            const url = "<?php echo 'https://tastelibmanan.systemproj.com/users/businessdetails.php?fbowner_id=' . $fb_id . '&tab=Ratings&showmodal=1'; ?>";
            document.getElementById('qrImage').src = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(url);
            document.getElementById('qrUrl').href = url; document.getElementById('qrUrl').textContent = url;
            document.getElementById('qrModal').classList.remove('hidden');
        };
        document.getElementById('closeQrModal').onclick = () => document.getElementById('qrModal').classList.add('hidden');
        
        // Public Profile Iframe
        const pubModal = document.getElementById('publicProfileModal');
        document.getElementById('publicProfileLink').onclick = (e) => {
            e.preventDefault(); 
            document.getElementById('publicProfileFrame').src = "../users/businessdetails.php?view=public&user_id=<?php echo $_SESSION['user_id']; ?>";
            pubModal.classList.remove('hidden');
        };
        document.getElementById('publicProfileLinkMobile').onclick = document.getElementById('publicProfileLink').onclick;
        document.getElementById('closePublicProfile').onclick = () => pubModal.classList.add('hidden');

    </script>

    <script>
        // Manual Add Menu
        const menuModal = document.getElementById('menuModal');
        document.getElementById('addMenuManualBtn').onclick = () => menuModal.classList.remove('hidden');
        window.closeMenuModal = () => menuModal.classList.add("hidden");
        
        const c = document.getElementById("menuItemsContainer");
        document.getElementById("addAnotherItem").onclick = () => {
            const n = c.firstElementChild.cloneNode(true);
            n.querySelectorAll('input').forEach(i => i.value='');
            n.querySelector('select').selectedIndex=0;
            n.querySelector('.previewImage').classList.add('hidden');
            n.querySelector('.uploadText').classList.remove('hidden');
            n.querySelector('.removeItem').classList.remove('hidden');
            c.appendChild(n);
        }
        c.addEventListener("click", e => { if(e.target.closest(".removeItem")) e.target.closest(".menu-item").remove(); });
        c.addEventListener("change", e => {
            if(e.target.classList.contains("menuImage")) {
                const f = e.target.files[0]; const p = e.target.closest(".menu-item").querySelector(".previewImage");
                if(f) { const r = new FileReader(); r.onload=ev=>{ p.src=ev.target.result; p.classList.remove("hidden"); e.target.closest(".menu-item").querySelector(".uploadText").classList.add("hidden");}; r.readAsDataURL(f); }
            }
        });

        document.getElementById("saveMenuBtn").onclick = () => {
            const fd = new FormData();
            let hasItem = false;
            document.querySelectorAll(".menu-item").forEach(i => {
                const n = i.querySelector(".menuName").value; const cat = i.querySelector(".menuCategory").value; const p = i.querySelector(".menuPrice").value; const f = i.querySelector(".menuImage").files[0];
                if(n && cat && p) {
                    hasItem = true;
                    fd.append("name[]", n); fd.append("category[]", cat); fd.append("price[]", p); if(f) fd.append("image[]", f);
                }
            });
            if(!hasItem) return Swal.fire("Error", "Please fill in at least one item.", "error");
            
            fetch("save_menu.php", { method: "POST", body: fd }).then(r=>r.json()).then(d => {
                if(d.success) Swal.fire({ icon: "success", title: "Saved!", timer: 1500, showConfirmButton: false }).then(() => location.reload());
                else Swal.fire("Error", d.error, "error");
            });
        };
        
        function openManageMenus() {
            document.getElementById('manageMenusModal').classList.remove('hidden');

            fetch('load_menus.php?t=' + Date.now())
            .then(r => r.json())
            .then(d => {
                const con = document.getElementById('menusList');
                con.innerHTML = '';

                if (d.length === 0) {
                    con.innerHTML = '<p class="text-gray-500 text-center">No menus found.</p>';
                } else {
                    d.forEach(m => {
                        let statusVal = (m.is_available === null || m.is_available === undefined) ? 1 : parseInt(m.is_available);
                        const isAvailable = (statusVal === 1);

                        const statusBtnText = isAvailable ? 'Set Unavailable' : 'Set Available';
                        const statusBtnColor = isAvailable 
                            ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' 
                            : 'bg-green-100 text-green-700 hover:bg-green-200';

                        const rowBg = isAvailable ? 'bg-white' : 'bg-gray-100';
                        const contentStyle = isAvailable ? '' : 'opacity-50 grayscale';
                    
                        con.innerHTML += `
                        <div class="flex gap-4 p-3 rounded-lg border items-center transition-all duration-300 ${rowBg}">

                            <img src="../${m.menu_image || 'uploads/default.png'}" class="w-16 h-16 rounded object-cover bg-gray-100 ${contentStyle}">

                            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2 ${contentStyle}">
                                <input id="name_${m.menu_id}" value="${m.menu_name}" class="border rounded px-2 py-1 text-sm bg-transparent">
                                <select id="cat_${m.menu_id}" class="border rounded px-2 py-1 text-sm bg-transparent">
                                    <option value="${m.category_id}" selected>${m.category_name || 'Category'}</option>
                                </select>
                                <input id="price_${m.menu_id}" value="${m.menu_price}" type="number" class="border rounded px-2 py-1 text-sm bg-transparent">
                            </div>

                            <div class="flex flex-col gap-1.5 w-32 shrink-0">
                                <button onclick="saveMenuChanges(${m.menu_id})" class="text-xs bg-blue-50 text-blue-600 px-2 py-1.5 rounded hover:bg-blue-100 font-medium w-full text-center">
                                    Save Changes
                                </button>

                                <button onclick="toggleMenuStatus(${m.menu_id}, ${isAvailable ? 0 : 1})" class="text-xs ${statusBtnColor} px-2 py-1.5 rounded transition font-medium w-full text-center">
                                    ${statusBtnText}
                                </button>
                    
                                <button onclick="deleteMenu(${m.menu_id})" class="text-xs bg-red-100 text-red-700 px-2 py-1.5 rounded hover:bg-red-200 font-medium w-full text-center flex justify-center items-center gap-1">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </button>
                            </div>
                        </div>`;
                    });
                }
            })
            .catch(e => {
                console.error("Error loading menus:", e);
                document.getElementById('menusList').innerHTML = '<p class="text-red-500 text-center">Error loading data.</p>';
            });
        }

        function deleteMenu(id) {
            Swal.fire({
                title: 'Delete this item?',
                text: "You won't be able to recover this menu item!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_menu.php', { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'menu_id=' + id 
                    })
                    .then(r => r.json())
                    .then(d => {
                        if(d.success || d.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            openManageMenus(); // Refresh list immediately
                        } else {
                            Swal.fire('Error', 'Failed to delete item.', 'error');
                        }
                    })
                    .catch(e => Swal.fire('Error', 'Network error occurred.', 'error'));
                }
            });
        }

        function toggleMenuStatus(id, newStatus) {
            const fd = new FormData();
            fd.append('menu_id', id);
            fd.append('is_available', newStatus);
                
            fetch('update_menu_status.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if(d.success) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end', 
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                
                    // Determine Message
                    const msg = (newStatus == 1) 
                        ? 'Menu item is now Available' 
                        : 'Menu item marked Unavailable';
                
                    //Fire the Toast
                    Toast.fire({
                        icon: 'success',
                        title: msg
                    });
                
                    //Reload the list immediately to update colors/buttons
                    openManageMenus(); 
                } else {
                    Swal.fire('Error', d.error || 'Could not update status.', 'error');
                }
            })
            .catch(e => {
                console.error("Error:", e);
                Swal.fire('Error', 'Connection error. Check console.', 'error');
            });
        }

        function closeManageMenus() { document.getElementById('manageMenusModal').classList.add('hidden'); }
        
        function saveMenuChanges(id) {
            const fd = new FormData();
            fd.append('menu_id', id); 
            fd.append('menu_name', document.getElementById('name_'+id).value);
            fd.append('menu_price', document.getElementById('price_'+id).value); 
            fd.append('category_id', document.getElementById('cat_'+id).value);

            fetch('update_menu.php', { method:'POST', body:fd })
                .then(r=>r.json())
                .then(d=>{ 
                    if(d.success) {
                        // SweetAlert Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Menu item saved successfully.',
                            timer: 1500, // Closes automatically after 1.5 seconds
                            showConfirmButton: false,
                            confirmButtonColor: '#A80000'
                        });
                    } else {
                        // SweetAlert Error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to update item.',
                            confirmButtonColor: '#A80000'
                        });
                    }
                });
        }

        function deleteMenu(id) {
            // SweetAlert Confirmation instead of standard confirm()
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this item!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A80000', // Matches your Primary Red
                cancelButtonColor: '#6b7280',  // Gray for cancel
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_menu.php', { method:'POST', body: new URLSearchParams('menu_id='+id) })
                    .then(r=>r.json())
                    .then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        openManageMenus(); // Refresh the list
                    });
                }
            });
        }

        // Menu Images (Flyers)
        const miModal = document.getElementById('menuImagesModal');

        document.getElementById('addMenuImageBtn').onclick = () => {
            miModal.classList.remove('hidden');
        
            // Fetch the file we just created
            fetch('get_menu_images.php')
                .then(r => r.json())
                .then(imgs => {
                    const list = document.getElementById('menuImagesList');
                    list.innerHTML = '';
                
                    // Check if we have images
                    if (!imgs || imgs.length === 0) {
                        list.innerHTML = '<p class="text-gray-500 text-xs w-full text-center py-4">No menu images found.</p>';
                        return;
                    }
                
                    // Loop through the database results
                    imgs.forEach(img => {
                        let imagePath = '';

                        // If it's an object (which it is based on your data)
                        if (typeof img === 'object' && img.path) {
                            imagePath = img.path;
                        } 
                        // Fallback if data is just a string
                        else if (typeof img === 'string') {
                            imagePath = img;
                        }
                    
                        if (imagePath) {
                            const finalSrc = '../' + imagePath;
                        
                            list.innerHTML += `
                            <div class="relative w-24 h-32 border rounded overflow-hidden group bg-gray-100">
                                <img src="${finalSrc}" 
                                     class="w-full h-full object-cover" 
                                     onerror="this.onerror=null; this.src='https://placehold.co/100x150?text=Error';">

                                <button class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 menu-del-btn" 
                                        data-img="${imagePath}">&times;</button>
                            </div>`;
                        }
                    });
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('menuImagesList').innerHTML = '<p class="text-red-500 text-xs">Error loading images. Check console.</p>';
                });
        };

        document.getElementById('closeMenuImagesModal').onclick = () => miModal.classList.add('hidden');

        // Delete Logic
        document.getElementById('menuImagesList').addEventListener('click', e => {
            if (e.target.classList.contains('menu-del-btn')) {
                if (!confirm('Delete this image?')) return;

                const imgPath = e.target.dataset.img;

                fetch('delete_menu_image.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'img=' + encodeURIComponent(imgPath)
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        // Refresh list
                        document.getElementById('addMenuImageBtn').click();
                    } else {
                        alert('Failed to delete.');
                    }
                });
            }
        });
    </script>
</body>
</html>