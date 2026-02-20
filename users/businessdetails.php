<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../db_con.php'; 
require_once '../status_logic.php';

$maps_api_key = env_value('GOOGLE_MAPS_API_KEY', '');

// Check if public view is enabled
$isPublicView = isset($_GET['view']) && $_GET['view'] === 'public';

// Enforce login if not public
if (!$isPublicView && !isset($_SESSION['user_id'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../index.php?redirect=$redirect");
    exit();
}

// Initialize
$business = null;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$fbowner_id = isset($_GET['fbowner_id']) ? intval($_GET['fbowner_id']) : 0;
checkAndSyncBusinessStatus($conn, $fbowner_id);

// PUBLIC PROFILE MODE
if ($isPublicView && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $query = "SELECT * FROM fb_owner WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $business = $result->fetch_assoc();

    if (!$business) {
        echo "<div class='flex h-screen items-center justify-center'><p class='text-red-600 font-semibold text-xl'>Business not found or not registered.</p></div>";
        exit;
    }
    $fbowner_id = $business['fbowner_id'];
} else {
    // NORMAL VIEW
    if ($fbowner_id > 0) {
        $stmt = $conn->prepare("SELECT * FROM fb_owner WHERE fbowner_id = ?");
        $stmt->bind_param("i", $fbowner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $business = $result->fetch_assoc();
        } else {
            echo "<div class='flex h-screen items-center justify-center'><p class='text-red-600 font-semibold text-xl'>Business not found.</p></div>";
            exit;
        }
        $stmt->close();
    }
}

// Fetch owner info
$owner = null;
if ($business && !empty($business['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE user_id = ?");
    $stmt->bind_param("i", $business['user_id']);
    $stmt->execute();
    $owner = $stmt->get_result()->fetch_assoc();
}

// --- NEW: Fetch Current User's Favorites to check if this business is liked ---
$my_favorites_array = [];
if ($user_id && !$isPublicView) {
    $stmt_fav = $conn->prepare("SELECT user_favorites FROM accounts WHERE user_id = ?");
    $stmt_fav->bind_param("i", $user_id);
    $stmt_fav->execute();
    $res_fav = $stmt_fav->get_result();
    if ($res_fav->num_rows > 0) {
        $fav_row = $res_fav->fetch_assoc();
        if (!empty($fav_row['user_favorites'])) {
            $my_favorites_array = explode(',', $fav_row['user_favorites']);
        }
    }
    $stmt_fav->close();
}
// ----------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($business['fb_name']) ? html_entity_decode($business['fb_name']) : 'Business Details'; ?> | TasteLibmanan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { 
                primary: "#A80000", 
                secondary: "#FF6B00",
                dark: "#1F2937",
                light: "#F3F4F6"
            },
            fontFamily: {
                brand: ['Pacifico', 'cursive'],
                sans: ['Inter', 'sans-serif'],
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.1)',
                'card': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            }
          },
        },
      }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .tab-content { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="text-gray-800">

<?php if (!$isPublicView): ?>
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="user.php" class="font-brand text-3xl text-primary hover:text-red-700 transition">
                        Taste<span class="text-gray-800">Libmanan</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="user.php" class="text-gray-600 hover:text-primary font-medium text-sm transition">Home</a>
                    <a href="../fbusinessowner/categories.php" class="text-gray-600 hover:text-primary font-medium text-sm transition">Businesses</a>
                    <a href="../FBregistration.php" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-800 transition shadow-sm flex items-center">
                        <i class="ri-store-3-line mr-1"></i> Register Business
                    </a>
                    
                    <div class="relative group">
                         <button class="flex items-center space-x-2 text-gray-600 hover:text-primary transition focus:outline-none">
                            <span class="font-medium text-sm">Account</span>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-card border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                            <a href="#" onclick="openModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary">
                                <i class="ri-settings-3-line mr-2"></i>Settings
                            </a>
                            <a href="favorites.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary">
                                <i class="ri-heart-line mr-2"></i>My Favorites
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="ri-logout-box-r-line mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex md:hidden items-center">
                    <button id="hamburger-btn" class="text-gray-600 hover:text-primary focus:outline-none">
                        <i class="ri-menu-3-line text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="user.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Home</a>
                <a href="../fbusinessowner/categories.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Businesses</a>
                <a href="favorites.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">My Favorites</a>
                <a href="../FBregistration.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Register Business</a>
                <a href="#" onclick="openModal(); return false;" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Account Settings</a>
                <a href="../logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </div>
    </nav>
<?php endif; ?>

<div id="accountModal" class="hidden fixed inset-0 bg-black bg-opacity-60 items-center justify-center z-50">
  <div id="accountModalContent" class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 relative m-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Account Settings</h2>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="ri-close-line text-2xl"></i></button>
    </div>

    <form id="accountForm" method="POST" action="update_account.php">
      <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : ''; ?>">
      
      <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" name="email" 
                 value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : (isset($_SESSION['fb_email_address']) ? htmlspecialchars($_SESSION['fb_email_address']) : ''); ?>"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" required>
      </div>

      <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
          <input type="password" name="password" placeholder="Leave blank to keep current"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
      </div>

      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-lg text-white bg-red-600 hover:bg-red-700 font-medium transition shadow-sm">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 py-0 sm:py-6">

<?php if ($business && $owner): ?>

    <div class="bg-white sm:rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-6">
        <div class="relative h-48 sm:h-64 md:h-80 bg-gray-200">
            <?php if (!empty($business['fb_cover'])): ?>
                <img src="<?php echo '../' . ltrim(htmlspecialchars($business['fb_cover']), './'); ?>" class="w-full h-full object-cover" alt="Cover">
            <?php else: ?>
                <div class="flex items-center justify-center h-full text-gray-400 font-medium">No Cover Photo</div>
            <?php endif; ?>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
        </div>

        <div class="px-4 sm:px-8 pb-6 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-end -mt-12 sm:-mt-16 mb-4 gap-4 sm:gap-6">
                
                <div class="relative flex-shrink-0 z-10">
                    <?php if (!empty($business['fb_photo'])): ?>
                        <img src="<?php echo '../' . ltrim(htmlspecialchars($business['fb_photo']), './'); ?>" 
                             class="w-28 h-28 sm:w-36 sm:h-36 rounded-full border-4 border-white shadow-md bg-white object-cover">
                    <?php else: ?>
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full border-4 border-white shadow-md bg-gray-200 flex items-center justify-center text-gray-400">
                            <i class="ri-store-2-line text-4xl"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex-1 w-full sm:w-auto z-10 pt-2 sm:pt-0">
                    <div class="flex items-center justify-between sm:justify-start gap-4">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">
                            <?php echo html_entity_decode($business['fb_name']); ?>
                        </h1>
                        
                        <div class="flex items-center gap-2">
                             <button id="openCameraBtn" class="bg-white text-gray-700 w-10 h-10 flex items-center justify-center rounded-full shadow border hover:bg-gray-50 transition" title="Scan QR">
                                <i class="ri-qr-scan-2-line text-xl"></i>
                            </button>
                            
                            <?php if (!$isPublicView): ?>
                            <button onclick="toggleFavorite(this, <?php echo $fbowner_id; ?>)"
                                    class="bg-white text-gray-700 w-10 h-10 flex items-center justify-center rounded-full shadow border hover:bg-gray-50 transition"
                                    title="<?php echo in_array($fbowner_id, $my_favorites_array) ? 'Remove from Favorites' : 'Add to Favorites'; ?>">
                                <?php if (in_array($fbowner_id, $my_favorites_array)): ?>
                                    <i class="ri-heart-fill text-xl text-primary"></i>
                                <?php else: ?>
                                    <i class="ri-heart-line text-xl"></i>
                                <?php endif; ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                        <span id="business-status" class="inline-block">
                             <?php if (!empty($business['fb_status']) && strtolower($business['fb_status']) === 'open'): ?>
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200">OPEN</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">CLOSED</span>
                            <?php endif; ?>
                        </span>

                        <?php
                            $stmt_hdr = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE fbowner_id = ?");
                            $stmt_hdr->bind_param("i", $fbowner_id);
                            $stmt_hdr->execute();
                            $hdr = $stmt_hdr->get_result()->fetch_assoc();
                            $hdr_avg = floatval($hdr['avg_rating'] ?? 0);
                            $hdr_total = intval($hdr['total_reviews'] ?? 0);
                        ?>
                        <div class="flex items-center text-sm">
                            <i class="ri-star-fill text-yellow-400 mr-1"></i>
                            <span class="font-semibold text-gray-900"><?php echo number_format($hdr_avg, 1); ?></span>
                            <span class="text-gray-500 ml-1">(<?php echo $hdr_total; ?> reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="qrScannerModal" class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-xl relative max-w-sm w-full mx-4 shadow-2xl">
            <button onclick="closeQRScannerModal()" class="absolute -top-3 -right-3 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700"><i class="ri-close-line text-xl"></i></button>
            <h3 class="text-center font-bold mb-2 text-lg">Scan QR Code</h3>
            <div id="qr-reader" class="rounded overflow-hidden"></div>
            <p id="qrScanResult" class="mt-2 text-center font-semibold text-sm"></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <div class="md:col-span-4 lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Business Info</h3>
                
                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start gap-3">
                        <i class="ri-map-pin-line text-xl text-red-500 mt-0.5"></i>
                        <span><?php echo htmlspecialchars($business['fb_address']); ?></span>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <i class="ri-time-line text-xl text-red-500 mt-0.5"></i>
                        <span><?php echo htmlspecialchars($business['fb_operating_hours']); ?></span>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="ri-phone-line text-xl text-red-500 mt-0.5"></i>
                        <span><?php echo htmlspecialchars($business['fb_phone_number']); ?></span>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="ri-mail-line text-xl text-red-500 mt-0.5"></i>
                        <span class="break-all"><?php echo htmlspecialchars($business['fb_email_address']); ?></span>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="italic text-gray-500 text-xs leading-relaxed">
                             <?php echo !empty($business['fb_description']) ? nl2br(htmlspecialchars($business['fb_description'])) : 'No description available.'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-8 lg:col-span-9">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-[500px]">
                
                <div class="flex overflow-x-auto scrollbar-hide border-b border-gray-200" id="business-tabs">
                    <button class="flex-1 py-4 px-4 text-sm font-medium text-center whitespace-nowrap focus:outline-none transition-colors border-b-2 border-transparent hover:text-red-600 hover:border-red-200" onclick="showTabSection('Menus')">
                        <i class="ri-restaurant-2-line mr-1 align-bottom"></i> Menus
                    </button>
                    <button class="flex-1 py-4 px-4 text-sm font-medium text-center whitespace-nowrap focus:outline-none transition-colors border-b-2 border-transparent hover:text-red-600 hover:border-red-200" onclick="showTabSection('Photos/Videos')">
                        <i class="ri-image-line mr-1 align-bottom"></i> Photos
                    </button>
                    <button class="flex-1 py-4 px-4 text-sm font-medium text-center whitespace-nowrap focus:outline-none transition-colors border-b-2 border-transparent hover:text-red-600 hover:border-red-200" onclick="showTabSection('Location')">
                        <i class="ri-map-2-line mr-1 align-bottom"></i> Location
                    </button>
                    <button class="flex-1 py-4 px-4 text-sm font-medium text-center whitespace-nowrap focus:outline-none transition-colors border-b-2 border-transparent hover:text-red-600 hover:border-red-200" onclick="showTabSection('Ratings')">
                        <i class="ri-star-smile-line mr-1 align-bottom"></i> Reviews
                    </button>
                </div>

                <div class="p-4 sm:p-6 tab-content">

                    <div id="MenusSection">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Our Menu</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-8">
                            <?php
                                $menu_images = !empty($business['menu_images']) ? json_decode($business['menu_images'], true) : [];
                                if(!is_array($menu_images)) $menu_images = [];
                                $has_visible = false;
                                foreach ($menu_images as $idx => $img):
                                    $img_path = is_array($img) ? $img['path'] : $img;
                                    $is_hidden = is_array($img) && isset($img['hidden']) ? $img['hidden'] : 0;
                                    if ($is_hidden) continue;
                                    $has_visible = true;
                            ?>
                                <div class="relative group cursor-pointer overflow-hidden rounded-lg shadow-sm border border-gray-100" onclick="openMenuPhotoModal(<?php echo $idx; ?>)">
                                    <img src="../<?php echo htmlspecialchars($img_path); ?>" class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-110" alt="Menu">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-6">
                            <?php
                            $categories = [];
                            $cat_query = "SELECT * FROM menu_categories";
                            $cat_result = $conn->query($cat_query);

                            if ($cat_result) {
                                while ($cat_row = $cat_result->fetch_assoc()) {
                                    $cid = isset($cat_row['category_id']) ? $cat_row['category_id'] : (isset($cat_row['id']) ? $cat_row['id'] : 0);
                                    $cname = isset($cat_row['category_name']) ? $cat_row['category_name'] : (isset($cat_row['name']) ? $cat_row['name'] : 'Other');

                                    $categories[$cid] = $cname;
                                }
                            }
                            $stmt_menu = $conn->prepare("SELECT * FROM menus WHERE fbowner_id = ? ORDER BY category_id ASC, created_at DESC");
                            $stmt_menu->bind_param("i", $fbowner_id);
                            $stmt_menu->execute();
                            $result = $stmt_menu->get_result();

                            if ($result->num_rows > 0) {
                                $current_category = null;
                                while ($row = $result->fetch_assoc()) {
                                    $menu_name   = htmlspecialchars($row['menu_name']);
                                    $menu_price  = htmlspecialchars($row['menu_price']);
                                    $menu_image  = htmlspecialchars($row['menu_image']);
                                    $cat_id      = $row['category_id'];
                                    $cat_name    = $categories[$cat_id] ?? "Uncategorized";
                                    
                                    $is_available = isset($row['is_available']) ? (int)$row['is_available'] : 1;
                                
                                    if ($current_category !== $cat_name) {
                                        if ($current_category !== null) echo '</div>'; 
                                        echo "<h4 class='text-lg font-semibold text-red-600 border-b border-gray-100 pb-2 mb-3'>$cat_name</h4>";
                                        echo '<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">';
                                        $current_category = $cat_name;
                                    }
                                    $overlay = '';
                                    $image_class = '';
                                    
                                    if (!$is_available) {
                                        // Gray out the image
                                        $image_class = 'grayscale opacity-75';
                                        $overlay = '<div class="absolute inset-0 z-10 flex items-center justify-center bg-black/40 backdrop-blur-[1px]">
                                                        <span class="bg-red-600 text-white text-[10px] sm:text-xs font-bold px-2 py-1 rounded shadow uppercase tracking-wide">Unavailable</span>
                                                    </div>';
                                    }

                                    echo "
                                    <div class='bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group cursor-pointer menu-item-trigger relative'
                                         data-img='../$menu_image' 
                                         data-name='".addslashes($menu_name)."' 
                                         data-price='$menu_price'
                                         onclick='openGallery(this)'>
                                        
                                        <div class='h-32 overflow-hidden relative'>
                                            <img src='../$menu_image' class='w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 $image_class' alt='$menu_name'>
                                            
                                            $overlay
                                        </div>
                                        
                                        <div class='p-3'>
                                            <p class='font-medium text-gray-800 text-sm truncate' title='$menu_name'>$menu_name</p>
                                            <p class='text-red-600 font-bold text-sm mt-1'>₱$menu_price</p>
                                        </div>
                                    </div>";
                                }
                                echo '</div>'; 
                            } else {
                                echo '<p class="text-gray-400 italic text-center py-4">No individual menu items added.</p>';
                            }
                            $stmt_menu->close();
                            ?>
                        </div>
                    </div>

                    <div id="PhotosSection" class="hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Gallery</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <?php
                            $gallery_images = !empty($business['fb_gallery']) ? json_decode($business['fb_gallery'], true) : [];
                            if (!is_array($gallery_images)) $gallery_images = [];
                            
                            if (!empty($gallery_images)) {
                                foreach ($gallery_images as $index => $imagePath) {
                                    echo "
                                    <div class='relative group cursor-pointer aspect-square rounded-lg overflow-hidden' onclick='openPhotoModal($index)'>
                                        <img src='../".htmlspecialchars(trim($imagePath))."' class='w-full h-full object-cover transition-transform duration-300 group-hover:scale-110' alt='Gallery'>
                                    </div>";
                                }
                            } else {
                                echo '<p class="text-gray-500 italic col-span-3">No photos uploaded yet.</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <div id="LocationSection" class="hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Location</h3>
                        <div class="flex flex-col gap-4">
                            <div class="w-full h-[400px] rounded-xl overflow-hidden shadow-sm border border-gray-200 relative">
                                <div id="businessMap" class="w-full h-full"></div>
                                <div class="absolute bottom-4 left-4 right-4 bg-white p-3 rounded-lg shadow-lg flex items-center justify-between opacity-90">
                                    <span class="text-sm font-medium text-gray-700 truncate mr-2"><?php echo htmlspecialchars($business['fb_address']); ?></span>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($business['fb_latitude'] . ',' . $business['fb_longitude']); ?>" 
                                       target="_blank" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded font-medium flex-shrink-0">
                                        Get Directions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="RatingsSection" class="hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Customer Reviews</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <div class="bg-gradient-to-br from-red-50 to-white p-5 rounded-xl border border-red-100 shadow-sm h-full flex flex-col">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-red-800 font-semibold flex items-center">
                                        <i class="ri-magic-line mr-2"></i> Review Summary
                                    </h4>
                                    
                                    <div class="flex bg-white rounded-lg border border-red-100 p-1 shadow-sm overflow-x-auto scrollbar-hide max-w-[200px] sm:max-w-none">
                                        <button onclick="filterSummary('all')" id="btn-all" class="summary-filter-btn px-2 py-1 text-xs font-bold rounded bg-red-600 text-white transition">All</button>
                                        <button onclick="filterSummary('5')" id="btn-5" class="summary-filter-btn px-2 py-1 text-xs font-medium rounded text-gray-600 hover:text-red-600 hover:bg-red-50 transition">5★</button>
                                        <button onclick="filterSummary('4')" id="btn-4" class="summary-filter-btn px-2 py-1 text-xs font-medium rounded text-gray-600 hover:text-red-600 hover:bg-red-50 transition">4★</button>
                                        <button onclick="filterSummary('3')" id="btn-3" class="summary-filter-btn px-2 py-1 text-xs font-medium rounded text-gray-600 hover:text-red-600 hover:bg-red-50 transition">3★</button>
                                        <button onclick="filterSummary('2')" id="btn-2" class="summary-filter-btn px-2 py-1 text-xs font-medium rounded text-gray-600 hover:text-red-600 hover:bg-red-50 transition">2★</button>
                                        <button onclick="filterSummary('1')" id="btn-1" class="summary-filter-btn px-2 py-1 text-xs font-medium rounded text-gray-600 hover:text-red-600 hover:bg-red-50 transition">1★</button>
                                    </div>
                                </div>

                                <div id="summaryLoader" class="hidden flex-1 flex items-center justify-center py-4">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                                    <span class="ml-2 text-xs text-gray-500">Generating AI Summary...</span>
                                </div>

                                <div id="summaryContent" class="flex-1">
                                    <p id="summaryText" class="text-gray-700 text-sm leading-relaxed break-words whitespace-normal">
                                        Loading summary...
                                    </p>
                                    <p id="summaryTag" class="text-xs text-red-400 font-medium mt-2 italic">Showing summary for: All Reviews</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 p-5 bg-gray-50 rounded-xl h-full border border-gray-100">
                                <div class="text-center min-w-[80px]">
                                    <div class="text-4xl font-bold text-gray-900"><?php echo number_format($hdr_avg ?? 0, 1); ?></div>
                                    <div class="text-yellow-400 text-sm mt-1 whitespace-nowrap">
                                        <?php
                                        $stars = $hdr_avg ?? 0;
                                        for($i=1; $i<=5; $i++) echo ($i <= $stars) ? '<i class="ri-star-fill"></i>' : ((($i - $stars) < 1) ? '<i class="ri-star-half-line"></i>' : '<i class="ri-star-line text-gray-300"></i>');
                                        ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo $hdr_total ?? 0; ?> Reviews</div>
                                </div>
                                <div class="h-10 w-px bg-gray-300"></div>
                                <div class="text-sm text-gray-600 flex-1">
                                    Filter by star rating above to see specific AI summaries for high or low ratings.
                                </div>
                            </div>
                        </div>

                        <div id="reviewsList" class="space-y-6">
                            <?php
                            $stmt = $conn->prepare("
                                SELECT id, user_id, reviewer_name, rating, comment, photo, video, created_at, updated_at
                                FROM reviews
                                WHERE fbowner_id = ?
                                ORDER BY (user_id = ?) DESC, created_at DESC
                            ");
                            $stmt->bind_param("ii", $fbowner_id, $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $result->num_rows > 0):
                                while ($review = $result->fetch_assoc()):
                            ?>
                                <div class="review-item border-b border-gray-100 pb-6 last:border-0" 
                                     data-review-id="<?= $review['id'] ?>" 
                                     data-rating="<?= $review['rating'] ?>">

                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold shrink-0">
                                                <?= strtoupper(substr($review['reviewer_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($review['reviewer_name']) ?></h5>
                                                <div class="text-yellow-400 text-xs">
                                                    <?php for($i=0; $i<$review['rating']; $i++) echo '★'; ?>
                                                    <span class="text-gray-300"><?php for($i=$review['rating']; $i<5; $i++) echo '★'; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                            <?php 
                                                $displayDate = $review['created_at'];
                                                $editedLabel = "";
                                                if (!empty($review['updated_at']) && $review['updated_at'] != '0000-00-00 00:00:00' && $review['updated_at'] != $review['created_at']) {
                                                    $displayDate = $review['updated_at'];
                                                    $editedLabel = " (Edited)";
                                                }
                                                echo date('M d, Y', strtotime($displayDate)) . $editedLabel;
                                            ?>
                                        </span>
                                    </div>
                                            
                                    <div class="pl-13 ml-13 review-comment md:pl-12"> 
                                        <p class="text-gray-700 text-sm leading-relaxed mb-3 review-text">
                                            <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                        </p>
                                            
                                        <div class="flex gap-2 mb-2">
                                            <?php if (!empty($review['photo'])): ?>
                                                <img src="../<?= htmlspecialchars($review['photo']) ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 review-photo" onclick="viewImage(this.src, 'Review Photo', '')">
                                            <?php endif; ?>
                                            <?php if (!empty($review['video'])): ?>
                                                <video controls class="w-20 h-20 rounded-lg border border-gray-200 object-cover review-video">
                                                    <source src="../<?= htmlspecialchars($review['video']) ?>" type="video/mp4">
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                            
                                        <?php if ($review['user_id'] == $user_id): ?>
                                            <div class="flex items-center gap-3 mt-3">
                                                <button class="text-sm text-blue-600 font-medium hover:text-blue-800 hover:underline flex items-center gap-1 edit-review-btn"
                                                        data-review-id="<?= $review['id'] ?>"
                                                        data-review-comment="<?= htmlspecialchars($review['comment'], ENT_QUOTES) ?>"
                                                        data-review-rating="<?= $review['rating'] ?>"
                                                        data-review-photo="<?= htmlspecialchars($review['photo'] ?? '', ENT_QUOTES) ?>"
                                                        data-review-video="<?= htmlspecialchars($review['video'] ?? '', ENT_QUOTES) ?>">
                                                    <i class="ri-edit-line"></i> Edit
                                                </button>
                                        
                                                <button onclick="deleteReview(<?= $review['id'] ?>)" 
                                                        class="text-sm text-red-600 font-medium hover:text-red-800 hover:underline flex items-center gap-1">
                                                    <i class="ri-delete-bin-line"></i> Delete
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php
                                endwhile;
                            else:
                                echo '<div class="text-center py-8 text-gray-400">No reviews yet. Be the first to review!</div>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
        <?php endif; ?>

<?php if (!$isPublicView): ?>
<footer class="bg-primary text-white py-10 mt-12 border-t border-red-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        
        <h1 class="font-brand text-3xl mb-6">TasteLibmanan</h1>
        
        <nav class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-medium">
            <a href="#" onclick="document.getElementById('aboutModal').classList.remove('hidden'); return false;" class="hover:text-red-200 transition-colors">About</a>
            <a href="#" onclick="document.getElementById('contactModal').classList.remove('hidden'); return false;" class="hover:text-red-200 transition-colors">Contact</a>
            <a href="#" onclick="document.getElementById('privacyModal').classList.remove('hidden'); return false;" class="hover:text-red-200 transition-colors">Privacy Policy</a>
        </nav>
        
        <p class="text-xs text-white/80">
            © 2025 TasteLibmanan. All rights reserved.
        </p>
        
    </div>
</footer>
<?php endif; ?>

<div id="aboutModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl p-8 max-w-4xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('aboutModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors z-10">
            <i class="ri-close-line text-3xl"></i>
        </button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary">About Us</h2>
        <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
          <p><strong>About the Business Permits and Licensing Office (BPLO)</strong><br>
          • The Business Permits and Licensing Office (BPLO) is the official arm of the Local Government Unit (LGU) responsible for overseeing the regulation, registration, and monitoring of all businesses operating within the municipality. Its primary mandate is to ensure that enterprises comply with national and local laws, ordinances, and standards to promote fair, safe, and legal business practices.</p>
          
          <p><strong>Purpose and Mandate</strong><br>
          • The BPLO serves as the frontline office for business owners who wish to establish, renew, or expand their businesses. Its core mission is to streamline the permitting process, enhance transparency, and support economic growth by making business registration accessible and efficient.</p>
          
          <p><strong>The BPLO plays a vital role in:</strong><br>
          • Regulating the entry and operation of businesses.<br>
          •Issuing permits and licenses in compliance with the law.<br>
          •Promoting a business-friendly environment that fosters local economic development.</p>
          
          <p><strong>Management and Organizational Role</strong><br>
          •The BPLO operates under the supervision of the Local Chief Executive (Mayor) and works closely with other government offices such as:<br>
          1. Treasurer's Office - for payment of business taxes and fees.<br>
          2. Zoning and Planning Office - to ensure business locations comply with zoning laws.<br>
          3. Health and Sanitation Office - to guarantee compliance with public health standards.<br>
          4. Bureau of Fire Protection (BFP) - for fire safety inspection and clearance.</p>
          
          <p><strong>5. Analytics and Insights</strong><br>
          The analytics provided are for reference only and should not be considered financial or legal advice.</p>
          
          <p><strong>6. Limitation of Liability</strong><br>
          Tastelibmanan is provided “as is.” We make no warranties of uninterrupted service or error-free functionality. We are not responsible for losses, damages, or issues arising from the use of the platform.</p>
          
          <p><strong>The BPLO team is responsible for receiving applications, validating requirements, processing permits, and maintaining accurate business records.</strong></p>
          
          <p><strong>Key Processes</strong><br>
            <strong>1. Application and Renewal of Business Permits</strong><br>
          • Business owners submit application forms with required documents (such as barangay clearance, zoning clearance, occupancy permit, fire safety clearance, and health permits).<br>
          • Applications are reviewed and verified by BPLO staff.</p>
          
          <p><strong>2. Assessment and Payment of Fees</strong><br>
          •The BPLO, in coordination with the Treasurer's Office, assesses applicable taxes, regulatory fees, and charges based on business type and size.<br>
          •Payment is made at the Treasurer's Office, and receipts are issued.</p>
          
          <p><strong>3. Evaluation and Approval</strong><br>
          •After payments, the application undergoes further review by other concerned offices (health, zoning, fire).<br>
          •Upon clearance, the BPLO endorses the approval.</p>
          
          <p><strong>4. Issuance of Business Permit/License</strong><br>
          •Once all requirements are completed and verified, the official business permit is issued to the applicant.<br>
          •Businesses are then allowed to legally operate within the municipality.</p>
          
          <p><strong>5. Monitoring and Compliance</strong><br>
          • The BPLO monitors businesses to ensure compliance with regulations.<br>
          • Inspections and audits may be conducted to verify that businesses maintain legal and safety standards.</p>
          
          <p><strong>Commitment to Service</strong><br>
          The BPLO is committed to:<br>
          •Delivering fast, efficient, and transparent services.<br>
          •Reducing bureaucratic delays by digitizing processes.<br>
          •Supporting business owners through guidance and assistance.<br>
          •Upholding accountability in all transactions.</p>
        </div>
    </div>
</div>

<div id="contactModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('contactModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
            <i class="ri-close-line text-3xl"></i>
        </button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary">Contact Us</h2>
        <div class="space-y-6 text-gray-700">
            <p class="text-center text-base">We'd love to hear from you! Reach out to us through any of the following channels:</p>
            <div class="bg-gray-50 p-6 rounded-xl space-y-4 border border-gray-100">
                <p class="flex items-center"><i class="ri-map-pin-line mr-3 text-primary text-xl"></i> <span><strong>Address:</strong> Municipal Hall, Libmanan, Camarines Sur, Philippines</span></p>
                <p class="flex items-center"><i class="ri-phone-line mr-3 text-primary text-xl"></i> <span><strong>Phone:</strong> (054) 123-4567</span></p>
                <p class="flex items-center"><i class="ri-mail-line mr-3 text-primary text-xl"></i> <span><strong>Email:</strong> tastelibmanan@gmail.com</span></p>
                <p class="flex items-center"><i class="ri-time-line mr-3 text-primary text-xl"></i> <span><strong>Office Hours:</strong> Mon – Fri, 8:00 AM – 5:00 PM</span></p>
            </div>
        </div>
    </div>
</div>

<div id="privacyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl p-8 max-w-3xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('privacyModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
            <i class="ri-close-line text-3xl"></i>
        </button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary">Privacy Policy</h2>
        <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
          <p class="text-center text-gray-500 text-xs uppercase tracking-widest mb-4">Last Updated: October 2, 2025</p>
          <div class="space-y-2">
              <p><strong>1. Introduction</strong><br>• The BPLO System respects your privacy and is committed to protecting the personal information you provide. This  Privacy Policy explains how we collect, use, and safeguard your information.</p>
              <p><strong>2. Information We Collect</strong><br>•  Personal Information: Name, address, email, contact number, and business details (when applying for permits). <br>
                          • System Data: Login credentials, account activity, and application status. <br>
                          • Technical Data: IP address, browser type, and usage logs for security and analytics.</p>
              <p><strong>3. How We Use Your Information</strong><br>We use collected data for: <br>
                          • Processing and verifying business permit applications. <br>
                          • Communicating with applicants regarding their permits or inquiries. <br>
                          • Ensuring system security and preventing unauthorized access. <br>
                          • Generating reports for transparency and internal use.</p>
              <p><strong>4. Data Sharing</strong><br>• Your information will only be shared with authorized government personnel and will not be sold or disclosed to third parties, except as required by law.</p>
              <p><strong>5. Data Security</strong><br>• We implement security measures such as encryption, access control, and regular system monitoring to safeguard your information.</p>
              <p><strong>6. User Rights</strong><br>You have the right to: <br>
                          • Access and update your personal information. <br>
                          • Request correction of inaccurate records. <br>
                          •Request deletion of your data, subject to government retention requirements.</p>
              <p><strong>7. Updates to This Policy</strong><br>• The BPLO System may update this Privacy Policy from time to time. Any changes will be posted on the system with a revised “Last Updated” date.</p>
          </div>
        </div>
    </div>
</div>  

<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-[60]">
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden">
        
        <button onclick="closeImageModal()" class="absolute top-2 right-2 bg-gray-100 hover:bg-gray-200 rounded-full p-2 z-10">
            <i class="ri-close-line text-xl"></i>
        </button>
        
        <div class="h-[60vh] bg-black flex items-center justify-center">
            <img id="modalImg" src="" class="max-h-full max-w-full object-contain select-none" draggable="false">
        </div>
        
        <div class="p-4 bg-white flex justify-between items-center">
            <div>
                <h3 id="modalName" class="font-bold text-lg text-gray-900"></h3>
                <p id="modalPrice" class="text-red-600 font-bold text-xl mt-1"></p>
            </div>
            <div class="text-gray-400 text-xs md:hidden">
                <i class="ri-arrow-left-s-line"></i> Swipe <i class="ri-arrow-right-s-line"></i>
            </div>
        </div>
    </div>
</div>

<div id="menuPhotoModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-[60]">
    <button onclick="closeMenuPhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300"><i class="ri-close-line text-4xl"></i></button>
    <img id="modalMenuPhotoImg" src="" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg">
</div>

<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-[60]">
    <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300"><i class="ri-close-line text-4xl"></i></button>
    <img id="modalPhotoImg" src="" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg">
</div>

<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[60] hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md m-4 transform transition-all">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h2 class="text-xl font-bold text-gray-800">Write a Review</h2>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-2xl"></i></button>
        </div>
        
        <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" id="reviewer_name" value="<?php echo isset($owner['name']) ? htmlspecialchars($owner['name']) : 'User'; ?>">

        <div class="mb-4 text-center">
            <p class="text-sm text-gray-500 mb-2">How was your experience?</p>
            <div id="starRating" class="flex justify-center gap-2"></div>
        </div>

        <textarea id="comment" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-sm mb-4" placeholder="Tell us what you liked or didn't like..."></textarea>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Photo</label>
                <input type="file" id="photo" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Video</label>
                <input type="file" id="video" accept="video/*" class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button onclick="closeReviewModal()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-medium">Cancel</button>
            <button onclick="submitReview()" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm font-medium shadow">Submit Review</button>
        </div>
    </div>
</div>

<div id="editReviewModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-[60]">
  <div class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6 m-4">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Your Review</h2>

    <form id="editReviewForm" enctype="multipart/form-data">
      <input type="hidden" id="editReviewId" name="review_id">
      <input type="hidden" id="editRating" name="rating" value="0">

      <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Rating</label>
          <div id="editStarRating" class="flex space-x-1">
            <span data-value="1" class="star cursor-pointer text-gray-300 text-3xl">★</span>
            <span data-value="2" class="star cursor-pointer text-gray-300 text-3xl">★</span>
            <span data-value="3" class="star cursor-pointer text-gray-300 text-3xl">★</span>
            <span data-value="4" class="star cursor-pointer text-gray-300 text-3xl">★</span>
            <span data-value="5" class="star cursor-pointer text-gray-300 text-3xl">★</span>
          </div>
      </div>

      <label class="block mb-1 text-sm font-medium">Comment</label>
      <textarea id="editComment" name="comment" rows="3" class="w-full border p-2 rounded-lg mb-4 text-sm focus:ring-red-500"></textarea>

      <div class="flex gap-4 mb-4">
          <div class="w-1/2">
              <label class="block text-xs font-bold text-gray-500 mb-1">New Photo</label>
              <input type="file" id="editPhoto" name="photo" accept="image/*" class="text-xs w-full">
              <img id="editPhotoPreviewImg" class="mt-2 h-20 w-20 object-cover rounded hidden">
          </div>
          <div class="w-1/2">
              <label class="block text-xs font-bold text-gray-500 mb-1">New Video</label>
              <input type="file" id="editVideo" name="video" accept="video/*" class="text-xs w-full">
              <video id="editVideoPreviewVideo" class="mt-2 h-20 w-20 object-cover rounded hidden" controls><source src=""></video>
          </div>
      </div>

      <div class="flex justify-end space-x-3">
        <button type="button" id="closeEditBtn" class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700 text-sm hover:bg-gray-200">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 shadow">Update</button>
      </div>
    </form>
  </div>
</div>

<div id="notification-toast" class="fixed top-24 right-4 z-[100] transform transition-all duration-300 translate-x-full opacity-0 flex items-center p-4 rounded-lg shadow-soft border-l-4 bg-white max-w-xs w-full">
    <div id="notification-icon" class="mr-3 text-2xl"></div>
    <div>
        <h4 id="notification-title" class="font-bold text-gray-800 text-sm"></h4>
        <p id="notification-message" class="text-xs text-gray-600"></p>
    </div>
</div>

<script>
    // --- Mobile Menu Toggle ---
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if(hamburgerBtn && mobileMenu){
        hamburgerBtn.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });
    }

    // --- Modal Logic (Account) ---
    function openModal() { document.getElementById('accountModal').classList.remove('hidden'); document.getElementById('accountModal').classList.add('flex'); }
    function closeModal() { document.getElementById('accountModal').classList.add('hidden'); document.getElementById('accountModal').classList.remove('flex'); }

    // --- Tab Logic ---
    function showTabSection(tab) {
        // Hide all
        ['MenusSection', 'PhotosSection', 'LocationSection', 'RatingsSection'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.add('hidden');
        });

        // Show selected
        const idMap = {'Menus':'MenusSection', 'Photos/Videos':'PhotosSection', 'Location':'LocationSection', 'Ratings':'RatingsSection'};
        const selectedId = idMap[tab];
        if(document.getElementById(selectedId)) {
            document.getElementById(selectedId).classList.remove('hidden');
            if(tab === 'Location') initBusinessMap(); 
        }

        // Active State styling
        const tabs = document.querySelectorAll('#business-tabs button');
        tabs.forEach(btn => {
            // Reset
            btn.classList.remove('text-red-600', 'border-red-600');
            btn.classList.add('border-transparent', 'text-gray-500');
            
            // Check text content matches
            if(btn.textContent.trim().includes(tab.split('/')[0])) {
                btn.classList.remove('border-transparent', 'text-gray-500');
                btn.classList.add('text-red-600', 'border-red-600');
            }
        });

        // URL Update
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }

    // Init correct tab on load
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'Menus';
        showTabSection(tab);
        
        // Auto show review modal?
        if (urlParams.get('showmodal') === '1') openReviewModal();
    };

    // Open Image 
    let galleryItems = [];
    let currentGalleryIndex = 0;
    
    // Initialize the Gallery
    function openGallery(element) {
        // Collect all menu items currently on the page
        const elements = document.querySelectorAll('.menu-item-trigger');
        galleryItems = Array.from(elements).map(el => ({
            img: el.getAttribute('data-img'),
            name: el.getAttribute('data-name'),
            price: el.getAttribute('data-price')
        }));

        // Find the index of the clicked element
        currentGalleryIndex = galleryItems.findIndex(item => 
            item.img === element.getAttribute('data-img') && 
            item.name === element.getAttribute('data-name')
        );

        updateModalContent();
        document.getElementById('imageModal').classList.remove('hidden');
    }

    // Update the Image/Text based on Index
    function updateModalContent() {
        if(currentGalleryIndex < 0 || currentGalleryIndex >= galleryItems.length) return;
        
        const item = galleryItems[currentGalleryIndex];
        document.getElementById('modalImg').src = item.img;
        document.getElementById('modalName').textContent = item.name;
        document.getElementById('modalPrice').textContent = item.price ? "₱" + item.price : "";
    }

    // Close Function
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Click Outside to Close 
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target.id === 'imageModal') {
            closeImageModal();
        }
    });

    // --- Gallery/Menu Image Logic with Swipe ---

    const menuImagesData = <?php echo json_encode(array_values(array_filter($menu_images, function($i){ return !(is_array($i) && isset($i['hidden']) && $i['hidden']); }))); ?>;
    const galleryImagesData = <?php echo json_encode($gallery_images); ?>;

    // Track current indices
    let currentMenuIdx = 0;
    let currentPhotoIdx = 0;

    // --- MENU PHOTOS ---
    function openMenuPhotoModal(idx) {
        currentMenuIdx = idx;
        updateMenuModalImage();
        document.getElementById('menuPhotoModal').classList.remove('hidden');
    }

    function updateMenuModalImage() {
        if (currentMenuIdx < 0) currentMenuIdx = 0;
        if (currentMenuIdx >= menuImagesData.length) currentMenuIdx = menuImagesData.length - 1;

        let src = menuImagesData[currentMenuIdx];
        if(typeof src !== 'string') src = src.path; // Handle object structure if necessary
        document.getElementById('modalMenuPhotoImg').src = '../' + src;
    }

    function closeMenuPhotoModal() { 
        document.getElementById('menuPhotoModal').classList.add('hidden'); 
    }

    // --- GALLERY PHOTOS ---
    function openPhotoModal(idx) {
        currentPhotoIdx = idx;
        updatePhotoModalImage();
        document.getElementById('photoModal').classList.remove('hidden');
    }

    function updatePhotoModalImage() {
        if (currentPhotoIdx < 0) currentPhotoIdx = 0;
        if (currentPhotoIdx >= galleryImagesData.length) currentPhotoIdx = galleryImagesData.length - 1;
        
        document.getElementById('modalPhotoImg').src = '../' + galleryImagesData[currentPhotoIdx];
    }

    function closePhotoModal() { 
        document.getElementById('photoModal').classList.add('hidden'); 
    }

    // --- CLICK OUTSIDE TO CLOSE (From previous step) ---
    document.getElementById('menuPhotoModal').addEventListener('click', function(e) {
        if (e.target.id === 'menuPhotoModal') closeMenuPhotoModal();
    });

    document.getElementById('photoModal').addEventListener('click', function(e) {
        if (e.target.id === 'photoModal') closePhotoModal();
    });

    // --- SWIPE LOGIC FOR MOBILE ---
    
    function addSwipeListener(elementId, nextCallback, prevCallback) {
        let touchStartX = 0;
        let touchEndX = 0;
        const el = document.getElementById(elementId);

        el.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});

        el.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleGenericSwipe();
        }, {passive: true});

        function handleGenericSwipe() {
            const threshold = 50; // min distance
            if (touchEndX < touchStartX - threshold) {
                // Swiped Left -> Next
                nextCallback();
            }
            if (touchEndX > touchStartX + threshold) {
                // Swiped Right -> Previous
                prevCallback();
            }
        }
    }

    // Attach Swipe to Menu Modal
    addSwipeListener('menuPhotoModal', 
        () => { // Next
            if(currentMenuIdx < menuImagesData.length - 1) {
                currentMenuIdx++;
                updateMenuModalImage();
            }
        },
        () => { // Prev
            if(currentMenuIdx > 0) {
                currentMenuIdx--;
                updateMenuModalImage();
            }
        }
    );

    // Attach Swipe to Gallery Modal
    addSwipeListener('photoModal', 
        () => { // Next
            if(currentPhotoIdx < galleryImagesData.length - 1) {
                currentPhotoIdx++;
                updatePhotoModalImage();
            }
        },
        () => { // Prev
            if(currentPhotoIdx > 0) {
                currentPhotoIdx--;
                updatePhotoModalImage();
            }
        }
    );

    // --- Reviews Logic (Star Rating) ---
    let selectedRating = 0;
    function openReviewModal() {
        document.getElementById('reviewModal').classList.remove('hidden');
        renderStars(0);
    }
    function closeReviewModal() { document.getElementById('reviewModal').classList.add('hidden'); }
    
    function renderStars(rating) {
        const container = document.getElementById('starRating');
        container.innerHTML = '';
        for(let i=1; i<=5; i++) {
            const star = document.createElement('i');
            star.className = (i <= rating) ? 'ri-star-fill text-3xl text-yellow-400 cursor-pointer transition' : 'ri-star-line text-3xl text-gray-300 cursor-pointer transition';
            star.onclick = () => { selectedRating = i; renderStars(i); };
            container.appendChild(star);
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('notification-toast');
        const title = document.getElementById('notification-title');
        const msg = document.getElementById('notification-message');
        const icon = document.getElementById('notification-icon');

        toast.className = "fixed top-24 right-4 z-[100] transform transition-all duration-300 flex items-center p-4 rounded-lg shadow-card border-l-4 bg-white max-w-xs w-full translate-x-0 opacity-100";

        if (type === 'success') {
            toast.classList.add('border-green-500');
            icon.className = "ri-checkbox-circle-fill text-green-500 text-2xl mr-3";
            title.innerText = "Success";
        } else {
            toast.classList.add('border-red-500');
            icon.className = "ri-error-warning-fill text-red-500 text-2xl mr-3";
            title.innerText = "Error"; // Or "Warning"
        }

        msg.innerText = message;

        // Hide after 2 seconds
        setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
        }, 2000);
    }

    async function submitReview() {
        if(selectedRating === 0) { 
            showToast('Please select a rating before submitting.', 'error'); 
            return; 
        }

        const formData = new FormData();
        formData.append('rating', selectedRating);
        formData.append('fbowner_id', '<?php echo $fbowner_id; ?>');
        formData.append('user_id', document.getElementById('user_id').value);
        formData.append('reviewer_name', document.getElementById('reviewer_name').value);
        formData.append('comment', document.getElementById('comment').value);

        const p = document.getElementById('photo').files[0]; if(p) formData.append('photo', p);
        const v = document.getElementById('video').files[0]; if(v) formData.append('video', v);

        try {
            const res = await fetch('submit_review.php', { method:'POST', body:formData });
            const text = await res.text();

            if(res.ok && text.trim() === "success") {
                // Close modal immediately
                closeReviewModal();

                // Show success notification
                showToast('Review Submitted Successfully!', 'success');

                // Wait 1.5 seconds so user sees notification, then reload clean URL
                setTimeout(() => {
                    const url = new URL(window.location);
                    url.searchParams.delete('showmodal'); // Remove the trigger param
                    window.location.href = url.toString();
                }, 1500);

            } else {
                showToast('Error: ' + text, 'error');
            }
        } catch(e) { 
            console.error(e); 
            showToast('Something went wrong. Please try again.', 'error');
        }
    }

    // --- Edit Review Logic ---
    const editModal = document.getElementById('editReviewModal');
    const editForm = document.getElementById('editReviewForm');
    const editStars = document.querySelectorAll('#editStarRating .star');
    const editRatingInput = document.getElementById('editRating');
    const editClose = document.getElementById('closeEditBtn');

    // Star Click in Edit
    editStars.forEach(s => {
        s.addEventListener('click', () => {
            const val = parseInt(s.dataset.value);
            editRatingInput.value = val;
            editStars.forEach(star => {
                star.classList.toggle('text-yellow-400', parseInt(star.dataset.value) <= val);
                star.classList.toggle('text-gray-300', parseInt(star.dataset.value) > val);
            });
        });
    });

    // Open Edit Modal
    document.querySelectorAll('.edit-review-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.reviewId;
            const comment = btn.dataset.reviewComment;
            const rating = parseInt(btn.dataset.reviewRating);
            const photo = btn.dataset.reviewPhoto;
            const video = btn.dataset.reviewVideo;

            document.getElementById('editReviewId').value = id;
            document.getElementById('editComment').value = comment;
            editRatingInput.value = rating;

            // Set stars
            editStars.forEach(star => {
                star.classList.toggle('text-yellow-400', parseInt(star.dataset.value) <= rating);
                star.classList.toggle('text-gray-300', parseInt(star.dataset.value) > rating);
            });

            // Previews
            const imgPrev = document.getElementById('editPhotoPreviewImg');
            if(photo) { imgPrev.src = '../'+photo; imgPrev.classList.remove('hidden'); } 
            else { imgPrev.classList.add('hidden'); }

            const vidPrev = document.querySelector('#editVideoPreviewVideo');
            if(video) { vidPrev.querySelector('source').src = '../'+video; vidPrev.load(); vidPrev.classList.remove('hidden'); }
            else { vidPrev.classList.add('hidden'); }

            editModal.classList.remove('hidden');
        });
    });

    editClose.addEventListener('click', () => editModal.classList.add('hidden'));

    // Handle Edit Submit
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(editForm);

        fetch('edit_review.php', { method:'POST', body:fd })
        .then(r => r.json())
        .then(d => {
            if(d.status === 'success') {
                document.getElementById('editReviewModal').classList.add('hidden'); // Close modal
                showToast('Review updated successfully!', 'success');

                // Wait 1.5s then reload clean URL
                setTimeout(() => {
                    const url = new URL(window.location);
                    url.searchParams.delete('showmodal'); 
                    window.location.href = url.toString();
                }, 1500);
            }
            else {
                showToast('Update failed: ' + d.msg, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('An error occurred while updating.', 'error');
        });
    });

    function deleteReview(reviewId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                const formData = new FormData();
                formData.append('review_id', reviewId);

                fetch('delete_review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'Your review has been deleted.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Something went wrong: ' + data,
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'A network error occurred.',
                        'error'
                    );
                });
            }
        });
    }
    // --- FILTERABLE SUMMARY LOGIC ---

    document.addEventListener("DOMContentLoaded", () => {
        // Load 'all' summary by default
        filterSummary('all');
    });

    function filterSummary(rating) {
        const fbowner_id = <?php echo json_encode($fbowner_id); ?>;
        const summaryText = document.getElementById("summaryText");
        const summaryTag = document.getElementById("summaryTag");
        const loader = document.getElementById("summaryLoader");
        const content = document.getElementById("summaryContent");
        
        // Update Button Styles
        document.querySelectorAll('.summary-filter-btn').forEach(btn => {
            btn.classList.remove('bg-red-600', 'text-white', 'shadow-sm', 'hover:bg-red-700');
            btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-gray-50');
        });
        const activeBtn = document.getElementById(`btn-${rating}`);
        if(activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-gray-50');
            activeBtn.classList.add('bg-red-600', 'text-white', 'shadow-sm', 'hover:bg-red-700');
        }

        // FILTER THE REVIEWS LIST & COUNT
        const allReviews = document.querySelectorAll('.review-item');
        const totalReviews = allReviews.length; // Total reviews loaded
        let visibleCount = 0;

        allReviews.forEach(item => {
            const itemRating = item.getAttribute('data-rating');
            if (rating === 'all' || itemRating === rating) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });

        // --- NEW: Generate the specific count message ---
        let countMessage = "";
        if (rating === 'all') {
            countMessage = `Showing all ${totalReviews} reviews`;
        } else {
            countMessage = `${visibleCount} out of ${totalReviews} reviews with ${rating} stars`;
        }

        // Update the tag immediately so user sees the count
        if (summaryTag) {
            summaryTag.innerText = countMessage;
            summaryTag.classList.remove('hidden');
        }

        // Optional: Show message if no reviews match filter
        const noReviewMsg = document.getElementById('no-reviews-msg');
        if (visibleCount === 0 && allReviews.length > 0) {
            if(!noReviewMsg) {
                const msg = document.createElement('div');
                msg.id = 'no-reviews-msg';
                msg.className = 'text-center py-8 text-gray-400 italic';
                msg.innerText = 'No reviews found for this rating.';
                document.getElementById('reviewsList').appendChild(msg);
            } else {
                noReviewMsg.classList.remove('hidden');
            }
        } else if (noReviewMsg) {
            noReviewMsg.classList.add('hidden');
        }

        // Show Loader & Fetch Summary
        loader.classList.remove('hidden');
        content.classList.add('opacity-50');

        const formData = new FormData();
        formData.append('fbowner_id', fbowner_id);
        formData.append('rating', rating);

        fetch('fetch_summary_logic.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loader.classList.add('hidden');
            content.classList.remove('opacity-50');

            if (data.status === 'success') {
                summaryText.innerHTML = data.summary.replace(/\n/g, "<br>");
                // Keep the count message we generated earlier
                if (summaryTag) summaryTag.innerText = countMessage; 
            } else {
                summaryText.innerText = data.message || "No reviews available to summarize for this rating.";
                // Even on error/empty, we can still show the count
                if (summaryTag) summaryTag.innerText = countMessage;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loader.classList.add('hidden');
            content.classList.remove('opacity-50');
            summaryText.innerText = "Error loading summary.";
        });
    }

    // --- Realtime Status Update ---
    const businessUserId = <?php echo json_encode($business['user_id'] ?? $fbowner_id ?? $user_id); ?>;
    let lastStatus = <?php echo json_encode(strtolower($business['fb_status'] ?? 'closed')); ?>;

    setInterval(() => {
        fetch('get_status.php?user_id=' + businessUserId + '&t=' + Date.now())
        .then(r => r.json())
        .then(d => {
            if(d && d.status && d.status !== lastStatus) {
                lastStatus = d.status;
                const el = document.getElementById('business-status');
                if(el) {
                    if(d.status === 'open') el.innerHTML = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200">OPEN</span>';
                    else el.innerHTML = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">CLOSED</span>';
                }
            }
        });
    }, 2000);

    // --- Map Logic ---

function initBusinessMap() {
  const lat = <?php echo json_encode($business['fb_latitude']); ?>;
  const lng = <?php echo json_encode($business['fb_longitude']); ?>;

  if (lat && lng && document.getElementById('businessMap')) {
    const libmananCoords = [
      { lat: 13.6070, lng: 123.0645 },
      { lat: 13.6155, lng: 123.1050 },
      { lat: 13.5923, lng: 123.1210 },
      { lat: 13.5730, lng: 123.0870 },
      { lat: 13.5890, lng: 123.0600 }
    ];

    const map = new google.maps.Map(document.getElementById('businessMap'), {
      center: { lat: parseFloat(lat), lng: parseFloat(lng) },
      zoom: 15,
      styles: [
        {
          featureType: "all",
          elementType: "labels",
          stylers: [{ visibility: "off" }]
        }
      ]
    });

    const libmananPolygon = new google.maps.Polygon({
      paths: libmananCoords,
      strokeColor: "#A80000",
      strokeOpacity: 1,
      strokeWeight: 2,
      fillColor: "transparent",
      fillOpacity: 0.7,
      clickable: false
    });
    libmananPolygon.setMap(map);

    new google.maps.Marker({
      position: { lat: parseFloat(lat), lng: parseFloat(lng) },
      map: map,
      title: "Business Location"
    });
  }
}
    
    // --- QR Scanner ---
    const openCameraBtn = document.getElementById('openCameraBtn');
    let html5QrCode = null;
    if(openCameraBtn) {
        openCameraBtn.onclick = async () => {
            document.getElementById('qrScannerModal').classList.remove('hidden');
            html5QrCode = new Html5Qrcode("qr-reader");
            const cams = await Html5Qrcode.getCameras();
            if(cams && cams.length) {
                const cam = cams.find(c => /back|rear/i.test(c.label)) || cams[0];
                html5QrCode.start(cam.id, { fps:10, qrbox:250 }, (decodedText) => {
                    html5QrCode.stop();
                    document.getElementById('qrScannerModal').classList.add('hidden');
                    let url = decodedText;
                    if (!/^https?:\/\//i.test(url)) url = 'businessdetails.php?fbowner_id=' + encodeURIComponent(decodedText);
                    url += (url.includes('?') ? '&' : '?') + 'showmodal=1';
                    window.location.href = url;
                });
            }
        };
    }
    function closeQRScannerModal() {
        document.getElementById('qrScannerModal').classList.add('hidden');
        if(html5QrCode) html5QrCode.stop().then(() => html5QrCode.clear());
    }

    // --- FAVORITES LOGIC (NEW) ---
    function toggleFavorite(btn, fbownerId) {
        const icon = btn.querySelector('i');
        const isFavorited = icon.classList.contains('ri-heart-fill');

        // Optimistic UI Update
        if (isFavorited) {
            icon.classList.remove('ri-heart-fill', 'text-primary');
            icon.classList.add('ri-heart-line');
            btn.title = "Add to Favorites";
        } else {
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill', 'text-primary');
            btn.title = "Remove from Favorites";
        }

        // Send Request
        fetch('toggle_favorites.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `fbowner_id=${fbownerId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                // Revert on error
                if (isFavorited) {
                    icon.classList.add('ri-heart-fill', 'text-primary');
                    icon.classList.remove('ri-heart-line');
                } else {
                    icon.classList.add('ri-heart-line');
                    icon.classList.remove('ri-heart-fill', 'text-primary');
                }
                showToast('Error updating favorites', 'error');
            } else {
                showToast(isFavorited ? 'Removed from favorites' : 'Added to favorites', 'success');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Network error', 'error');
        });
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo htmlspecialchars($maps_api_key, ENT_QUOTES, 'UTF-8'); ?>&callback=initBusinessMap" async defer></script>
</body>
</html>