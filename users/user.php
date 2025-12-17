<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:../index.php");
    exit();
}

require_once '../db_con.php';
require_once '../status_logic.php';

// Enable Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$user_id = (int)$_SESSION['user_id'];

// Get User Info & Favorites List
$stmt = $conn->prepare("SELECT name, email, user_favorites FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$my_favorites_array = []; // Default empty array

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = htmlspecialchars($user['name']);
    $first_name = explode(' ', trim($user_name))[0];
    
    // Parse the favorites string into an array for checking later
    if (!empty($user['user_favorites'])) {
        $my_favorites_array = explode(',', $user['user_favorites']);
    }
} else {
    $user_name = "User";
    $first_name = "Guest";
}

// Fetch Top 10 Businesses
$top10_query = "
    SELECT 
        f.fbowner_id,
        f.fb_name,
        f.fb_type,
        f.fb_description,
        f.fb_photo,
        f.fb_status,
        COUNT(r.id) AS total_reviews,
        COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating
    FROM fb_owner f
    LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
    WHERE f.activation = 'Active'
    GROUP BY f.fbowner_id
    ORDER BY total_reviews DESC
    LIMIT 10
";
$top10_result = $conn->query($top10_query);

// Fetch Data for Map (Initial Load)
$map_businesses = [];
$sql_for_map = "SELECT fbowner_id, user_id, fb_name, fb_type, fb_description, fb_operating_hours, fb_photo, fb_latitude, fb_longitude, fb_status, is_new FROM fb_owner WHERE activation = 'Active' ";
$result_for_map = $conn->query($sql_for_map);

if ($result_for_map) {
    while ($row = $result_for_map->fetch_assoc()) {
        $real_time_status = checkAndSyncBusinessStatus($conn, $row['user_id']);
        $row['fb_status'] = $real_time_status;
        
        if (!empty($row['fb_photo'])) {
            $row['fb_photo'] = '../' . ltrim($row['fb_photo'], './');
        } else {
            $row['fb_photo'] = '../vendors/imgsource/default.jpg';
        }

        $map_businesses[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteLibmanan | Explore</title>
  
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1A2Mej_RdKT_Lq-y0kYIcNW93yY-RrBY"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Map Info Window Styling */
        .gm-style .gm-style-iw-c { padding: 0 !important; border-radius: 12px !important; }
        .gm-style-iw-d { overflow: hidden !important; }
        .gm-style .gm-style-iw-t::after { display: none; }
        
        #searchSuggestions {
          z-index: 9999;
          max-height: 250px;
          overflow-y: auto;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="user.php" class="font-brand text-3xl text-primary hover:text-red-700 transition">
                        Taste<span class="text-gray-800">Libmanan</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="user.php" class="text-primary font-semibold text-sm uppercase tracking-wide">Home</a>
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
                            <a href="#" onclick="openModal()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary"><i class="ri-settings-3-line mr-2"></i>Settings</a>
                            <a href="favorites.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary">
                                <i class="ri-heart-line mr-2"></i>My Favorites
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="ri-logout-box-r-line mr-2"></i>Logout</a>
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
                <a href="user.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-primary">Home</a>
                <a href="../fbusinessowner/categories.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Businesses</a>     
                <a href="favorites.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">My Favorites</a>
                <a href="../FBregistration.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Register Business</a>  
                <a href="#" onclick="openModal()" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Account Settings</a>
                <a href="../logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </div>
    </nav>

    <div id="accountModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[60] transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Account Settings</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="ri-close-line text-2xl"></i></button>
            </div>
            <form id="accountForm" method="POST" action="update_account.php">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Email Address</label>
                    <div class="relative">
                        <i class="ri-mail-line absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">New Password</label>
                    <div class="relative">
                        <i class="ri-lock-line absolute left-3 top-3 text-gray-400"></i>
                        <input type="password" name="password" placeholder="••••••••" class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition" minlength="8" maxlength="16">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-red-800 rounded-lg shadow-md transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="aboutModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closeAboutModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors z-10">
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
                • Issuing permits and licenses in compliance with the law.<br>
                • Promoting a business-friendly environment that fosters local economic development.</p>
                
                <p><strong>Management and Organizational Role</strong><br>
                • The BPLO operates under the supervision of the Local Chief Executive (Mayor) and works closely with other government offices such as:<br>
                1. Treasurer's Office - for payment of business taxes and fees.<br>
                2. Zoning and Planning Office - to ensure business locations comply with zoning laws.<br>
                3. Health and Sanitation Office - to guarantee compliance with public health standards.<br>
                4. Bureau of Fire Protection (BFP) - for fire safety inspection and clearance.</p>
                
                <p><strong>Limit of Liability</strong><br>
                Tastelibmanan is provided “as is.” We make no warranties of uninterrupted service. We are not responsible for losses, damages, or issues arising from the use of the platform.</p>

                <p><strong>Commitment to Service</strong><br>
                The BPLO is committed to delivering fast, efficient, and transparent services, reducing bureaucratic delays by digitizing processes, and upholding accountability in all transactions.</p>
            </div>
        </div>
    </div>

    <div id="contactModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closeContactModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
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

    <div id="privacyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-3xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closePrivacyModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
            <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary">Privacy Policy</h2>
            <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
                <p class="text-center text-gray-500 text-xs uppercase tracking-widest mb-4">Last Updated: October 2, 2025</p>
                <div class="space-y-4">
                    <p><strong>1. Introduction</strong><br>The BPLO System respects your privacy and is committed to protecting the personal information you provide. This Privacy Policy explains how we collect, use, and safeguard your information.</p>
                    
                    <p><strong>2. Information We Collect</strong><br>
                    • Personal Information: Name, address, email, contact number.<br>
                    • System Data: Login credentials, account activity.<br>
                    • Technical Data: IP address, browser type for security.</p>
                    
                    <p><strong>3. How We Use Your Information</strong><br>
                    • Processing and verifying business permit applications.<br>
                    • Communicating regarding permits or inquiries.<br>
                    • Ensuring system security.</p>
                    
                    <p><strong>4. Data Sharing</strong><br>Your information will only be shared with authorized government personnel and will not be sold to third parties, except as required by law.</p>
                    
                    <p><strong>5. Data Security</strong><br>We implement encryption, access control, and regular system monitoring.</p>
                    
                    <p><strong>6. User Rights</strong><br>You have the right to access, update, or request deletion of your data, subject to government retention requirements.</p>
                </div>
            </div>
        </div>
    </div>

    <header class="relative bg-gradient-to-r from-red-950 to-primary pb-24 pt-12 lg:pt-20">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/food.png');"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-30 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-2">
                Magandang Araw, <span class="text-secondary"><?php echo $first_name; ?>!</span>
            </h1>
            <p class="text-red-100 text-lg md:text-xl max-w-2xl mx-auto mb-8">
                Craving something specific? Find the best food spots in Libmanan.
            </p>

            <div class="max-w-2xl mx-auto relative">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ri-search-line text-gray-400 text-xl group-focus-within:text-primary transition"></i>
                    </div>
                    <input type="text" id="searchBusinessInput" 
                        class="block w-full pl-12 pr-12 py-4 bg-white border-0 rounded-full shadow-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-red-500/30 transition-shadow text-lg" 
                        placeholder="Search for food businesses..." autocomplete="off">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button id="startScanner" class="p-2 text-gray-400 hover:text-primary transition rounded-full hover:bg-red-50" title="Scan QR Code">
                            <i class="ri-qr-scan-2-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="searchSuggestions" class="absolute top-full left-0 right-0 bg-white rounded-xl shadow-2xl mt-2 overflow-hidden hidden border border-gray-100 divide-y divide-gray-100"></div>
            </div>
            
            <div id="qr-reader" class="mt-4 mx-auto bg-white p-2 rounded-lg hidden shadow-lg relative max-w-sm"></div>
            <div id="scanResult" class="mt-4 mx-auto max-w-md bg-white/10 backdrop-blur text-white p-3 rounded-lg hidden text-center border border-white/20">
                <p class="font-medium text-sm">Found:</p>
                <a id="scannedLink" href="#" class="text-yellow-300 hover:underline break-all font-bold"></a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 pb-16">
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <?php 
            $cats = [
                ['name' => 'Restaurant', 'img' => '../vendors/imgsource/restaurants.jpg', 'link' => 'restaurants'],
                ['name' => 'Fast Food', 'img' => '../vendors/imgsource/fastfoods.jpg', 'link' => 'fastfoods'],
                ['name' => 'Cafes', 'img' => '../vendors/imgsource/cafes.jpg', 'link' => 'cafes'],
                ['name' => 'Bakeries', 'img' => '../vendors/imgsource/bakeries.jpg', 'link' => 'bakery']
            ];
            foreach($cats as $cat): ?>
            <a href="../fbusinessowner/categories.php?cat=<?= $cat['link'] ?>" class="group bg-white rounded-xl shadow-md p-4 flex items-center space-x-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0 border-2 border-gray-100 group-hover:border-primary transition">
                    <img src="<?= $cat['img'] ?>" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 group-hover:text-primary transition"><?= $cat['name'] ?></h3>
                    <p class="text-xs text-gray-500">View All →</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <section class="bg-white rounded-2xl shadow-card overflow-hidden mb-12 border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="ri-map-pin-user-fill text-primary mr-2"></i> Food Map
                    </h2>
                    <p class="text-sm text-gray-500">Discover what's nearby</p>
                </div>
                <div class="relative w-full sm:w-48">
                    <select id="cuisineTypeDropdown" class="w-full pl-3 pr-10 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition cursor-pointer appearance-none">
                        <option value="">All Categories</option>
                        <option value="1">✨ Newly Opened</option>
                        <option value="Restaurant">Restaurants</option>
                        <option value="Fastfood">Fast Food</option>
                        <option value="Cafe">Cafes</option>
                        <option value="Bakery">Bakeries</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
            
            <div id="libmanan-map" class="w-full h-[500px] z-0"></div>
        </section>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Top Rated Spots</h2>
                <p class="text-gray-500 text-sm">Based on local visits and reviews</p>
            </div>
            <a href="../fbusinessowner/categories.php" class="text-primary font-medium hover:text-red-700 text-sm flex items-center">
                View All <i class="ri-arrow-right-line ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php while ($row = $top10_result->fetch_assoc()):
                $display_photo = '';
    
                if (!empty($row['fb_photo'])) {
                    // Remove './' if it exists and prepend '../' to go up one directory level
                    $display_photo = '../' . ltrim($row['fb_photo'], './');
                } else {
                    // Set default if database field is empty
                    $display_photo = '../vendors/imgsource/default.jpg';
                } 
            ?>
                <div onclick="location.href='businessdetails.php?fbowner_id=<?php echo $row['fbowner_id']; ?>'" 
                     class="group bg-white rounded-2xl shadow-soft hover:shadow-card overflow-hidden transition-all duration-300 flex flex-col h-full border border-gray-100 relative cursor-pointer">
                    
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?php echo $display_photo ?>" 
                        onerror="this.onerror=null;this.src='../vendors/imgsource/default.jpg';"
                        alt="<?php echo html_entity_decode($row['fb_name']); ?>" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                       <button onclick="event.stopPropagation(); toggleFavorite(this, <?php echo $row['fbowner_id']; ?>)" 
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-primary hover:bg-red-50 transition transform hover:scale-110 z-10">
                            <?php 
                                // Check if this business ID is in the user's favorites array
                                if (in_array($row['fbowner_id'], $my_favorites_array)): 
                            ?>
                                <i class="ri-heart-fill text-lg"></i>
                            <?php else: ?>
                                <i class="ri-heart-line text-lg"></i>
                            <?php endif; ?>
                        </button>

                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                            <?php echo $row['fb_type']; ?>
                        </div>
                         <div class="absolute bottom-3 left-3">
                            <?php if(strtolower($row['fb_status']) === 'open'): ?>
                                <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded-md shadow-sm flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Open
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-gray-600 text-white text-xs font-bold rounded-md shadow-sm">Closed</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-primary transition truncate">
                            <?php echo html_entity_decode($row['fb_name']); ?>
                        </h3>
                        
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 text-sm">
                                <?php
                                    $avg = floatval($row['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($avg)) echo '<i class="ri-star-fill"></i>';
                                        elseif ($i == ceil($avg) && $avg - floor($avg) >= 0.5) echo '<i class="ri-star-half-fill"></i>';
                                        else echo '<i class="ri-star-line text-gray-300"></i>';
                                    }
                                ?>
                            </div>
                            <span class="text-xs text-gray-500 ml-2 font-medium">(<?= $row['total_reviews'] ?> reviews)</span>
                        </div>
                        
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4 flex-grow">
                            <?php echo htmlspecialchars($row['fb_description'] ?: 'No description available.'); ?>
                        </p>
                        
                        <div class="block w-full text-center py-2.5 rounded-xl bg-gray-50 text-gray-700 font-semibold text-sm group-hover:bg-primary group-hover:text-white transition-colors">
                            Visit Page
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </main>

    <footer class="bg-primary text-white py-10 mt-12 border-t border-red-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            
            <h1 class="font-brand text-3xl mb-6">TasteLibmanan</h1>
            
            <nav class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-medium">
                <a href="#" id="about" class="hover:text-red-200 transition-colors">About</a>
                <a href="#" id="contact" class="hover:text-red-200 transition-colors">Contact</a>
                <a href="#" id="privacy" class="hover:text-red-200 transition-colors">Privacy Policy</a>
            </nav>
            
            <p class="text-xs text-white/80">
                © 2025 TasteLibmanan. All rights reserved.
            </p>
            
        </div>
    </footer>

    <script>
        // --- Pass PHP Data to JS ---
        let mapBusinesses = <?php echo json_encode($map_businesses) ?>;

        // --- FAVORITE LOGIC ---
        function toggleFavorite(btn, fbownerId) {
            const icon = btn.querySelector('i');
            
            // Optimistic UI Update (Change icon immediately)
            const isFavorited = icon.classList.contains('ri-heart-fill');
            if (isFavorited) {
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
            } else {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill');
            }

            // Send Request
            fetch('toggle_favorites.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `fbowner_id=${fbownerId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    // Revert if failed
                    alert('Error saving favorite');
                    if (isFavorited) {
                        icon.classList.add('ri-heart-fill');
                        icon.classList.remove('ri-heart-line');
                    } else {
                        icon.classList.add('ri-heart-line');
                        icon.classList.remove('ri-heart-fill');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // --- UI / Modal Handlers ---
        function openModal() { 
            const m = document.getElementById("accountModal");
            m.classList.remove("hidden");
            setTimeout(() => { m.querySelector('div').classList.remove('scale-95', 'opacity-0'); }, 10);
        }
        function closeModal() { 
            const m = document.getElementById("accountModal");
            m.classList.add("hidden");
        }
        
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            hamburgerBtn.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });
        });

        ['about', 'contact', 'privacy'].forEach(id => {
            const modal = document.getElementById(id + 'Modal');
            const link = document.getElementById(id);
            const close = document.getElementById('close' + id.charAt(0).toUpperCase() + id.slice(1) + 'Modal');
            if(link && modal && close) {
                link.onclick = (e) => { e.preventDefault(); modal.classList.remove('hidden'); }
                close.onclick = () => { modal.classList.add('hidden'); }
            }
        });

        // --- QR Scanner Logic ---
        const startScannerBtn = document.getElementById("startScanner");
        const qrReader = document.getElementById("qr-reader");
        const scanResult = document.getElementById("scanResult");
        const scannedLink = document.getElementById("scannedLink");
        let html5QrCode = null;

        async function startQrScanner() {
            try {
                qrReader.classList.remove('hidden');
                html5QrCode = new Html5Qrcode("qr-reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                
                await html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                    stopQrScanner();
                    scanResult.classList.remove('hidden');
                    scannedLink.textContent = decodedText;
                    if (/^(https?:\/\/[^\s]+)/i.test(decodedText)) {
                        scannedLink.href = decodedText; 
                        window.location.href = decodedText; 
                    } else {
                        scannedLink.removeAttribute("href");
                    }
                }, (errorMessage) => { 
                    // parse error, ignore 
                });
            } catch (err) { alert("Camera error: " + err); qrReader.classList.add('hidden'); }
        }

        async function stopQrScanner() {
            if (html5QrCode) { await html5QrCode.stop().catch(()=>{}); html5QrCode.clear(); html5QrCode = null; qrReader.classList.add('hidden'); }
        }
        
        startScannerBtn.addEventListener("click", async () => { if (html5QrCode) await stopQrScanner(); else await startQrScanner(); });

        // ---Google Maps Logic ---
        let allMarkers = [];
        let map;
        let infoWindow;

        function generateInfoContent(biz) {
            const statusColor = biz.fb_status.toLowerCase() === 'open' ? 'text-green-600' : 'text-red-600';
            return `
            <div class="font-sans p-2 min-w-[200px]">
              <div class="h-32 w-full rounded-lg overflow-hidden mb-2 relative">
                  <img src="${biz.fb_photo}" style="width:100%; height:100%; object-fit:cover;">
                  <span class="absolute top-1 right-1 bg-white px-2 py-0.5 text-[10px] font-bold rounded shadow">${biz.fb_type}</span>
              </div>
              <h3 class="text-lg font-bold text-gray-900 leading-tight mb-1">${biz.fb_name}</h3>
              <p class="text-xs text-gray-500 mb-1"><i class="ri-time-line"></i> ${biz.fb_operating_hours || 'Not specified'}</p>
              <p class="text-xs font-bold ${statusColor} uppercase mb-3 tracking-wide">● ${biz.fb_status}</p>
              
              <div class="grid grid-cols-2 gap-2">
                <button class="direction-btn w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 rounded transition">Directions</button>
                <button class="view-details-btn w-full bg-primary hover:bg-red-800 text-white text-xs font-semibold py-2 rounded transition">Details</button>
              </div>
            </div>
            `;
        }

        function attachInfoWindowListeners(biz) {
            setTimeout(() => {
                const dirBtn = document.querySelector(".direction-btn");
                const viewBtn = document.querySelector(".view-details-btn");
                if(dirBtn) dirBtn.onclick = () => window.open(`https://www.google.com/maps/dir/?api=1&destination=${biz.fb_latitude},${biz.fb_longitude}`, `_blank`);
                if(viewBtn) viewBtn.onclick = () => window.location.href = `businessdetails.php?fbowner_id=${biz.fbowner_id}`;
            }, 100);
        }

        function renderMarkers(filteredBusinesses, map, infoWindow) {
            allMarkers.forEach(marker => marker.setMap(null));
            allMarkers = [];

            const iconSize = new google.maps.Size(32, 42);
            
            filteredBusinesses.forEach(biz => {
                let iconUrl = biz.fb_status && String(biz.fb_status).toLowerCase().trim() === 'open' 
                    ? "../vendors/icon/Green_map_pointer.png" 
                    : "../vendors/icon/Red_map_pointer.png";

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(biz.fb_latitude), lng: parseFloat(biz.fb_longitude) },
                    map: map,
                    title: biz.fb_name,
                    icon: { url: iconUrl, scaledSize: iconSize },
                    animation: google.maps.Animation.DROP
                });
                
                marker.set('biz_id', biz.fbowner_id);
                allMarkers.push(marker);

                marker.addListener("click", function() {
                    infoWindow.setContent(generateInfoContent(biz));
                    infoWindow.open(map, marker);
                    google.maps.event.addListenerOnce(infoWindow, 'domready', () => attachInfoWindowListeners(biz));
                });
            });
        }

        // Function to handle real-time updates
        function startLiveStatusUpdates() {
            setInterval(() => {
                mapBusinesses.forEach((biz) => {
                    if (!biz.user_id) return; // Safety check
                
                    // Request the current status from PHP
                    fetch(`get_status.php?user_id=${biz.user_id}`)
                        .then(response => response.json())
                        .then(data => {
                            const newStatus = String(data.status).toLowerCase(); // 'open' or 'closed'
                            const oldStatus = String(biz.fb_status).toLowerCase();
                        
                            // Only update if the status actually changed
                            if (newStatus !== oldStatus) {
                                console.log(`Status changed for ${biz.fb_name}: ${oldStatus} -> ${newStatus}`);

                                biz.fb_status = newStatus;
                                const marker = allMarkers.find(m => m.get('biz_id') == biz.fbowner_id);
                            
                                if (marker) {
                                    const iconUrl = newStatus === 'open' 
                                        ? "../vendors/icon/Green_map_pointer.png" 
                                        : "../vendors/icon/Red_map_pointer.png";
                                
                                    marker.setIcon({
                                        url: iconUrl,
                                        scaledSize: new google.maps.Size(32, 42)
                                    });
                                
                                    if (infoWindow.getMap() && infoWindow.getPosition().equals(marker.getPosition())) {
                                        infoWindow.setContent(generateInfoContent(biz));
                                    }
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching status:', error));
                });
            }, 3000);
        }

        function initMap() {
            // Libmanan Bounds
            const libmananCoords = [
                {lat:13.682649, lng:122.853031},{lat:13.682311, lng:122.853367},{lat:13.678595, lng:122.8611},{lat:13.67859, lng:122.862164},
                {lat:13.675787, lng:122.86659},{lat:13.675712, lng:122.866789},{lat:13.679968, lng:122.874195},{lat:13.686149, lng:122.878743},
                {lat:13.686819, lng:122.882134},{lat:13.68811, lng:122.884451},{lat:13.688315, lng:122.886245},{lat:13.689219, lng:122.888353},
                {lat:13.691419, lng:122.891803},{lat:13.694723, lng:122.899226},{lat:13.69567, lng:122.900158},{lat:13.697842, lng:122.907664},
                {lat:13.705267, lng:122.914593},{lat:13.706819, lng:122.916914},{lat:13.70684, lng:122.917275},{lat:13.707988, lng:122.921635},
                {lat:13.70963, lng:122.924177},{lat:13.712465, lng:122.927822},{lat:13.716211, lng:122.93108},{lat:13.71697, lng:122.931636},
                {lat:13.720796, lng:122.936392},{lat:13.722275, lng:122.943275},{lat:13.722673, lng:122.943923},{lat:13.723727, lng:122.944805},
                {lat:13.726843, lng:122.948147},{lat:13.730517, lng:122.951317},{lat:13.730917, lng:122.951303},{lat:13.732209, lng:122.951455},
                {lat:13.739925, lng:122.953791},{lat:13.740168, lng:122.953922},{lat:13.745601, lng:122.962806},{lat:13.745459, lng:122.973794},
                {lat:13.745895, lng:122.974382},{lat:13.746123, lng:122.976113},{lat:13.746768, lng:122.978104},{lat:13.746772, lng:122.978144},
                {lat:13.749881, lng:122.989315},{lat:13.75077, lng:122.991184},{lat:13.751044, lng:122.993104},{lat:13.752573, lng:122.996381},
                {lat:13.753655, lng:122.997738},{lat:13.753755, lng:122.998082},{lat:13.766517, lng:123.006019},{lat:13.76868, lng:123.01415},
                {lat:13.768753, lng:123.015611},{lat:13.753944, lng:123.022479},{lat:13.753249, lng:123.024249},{lat:13.754403, lng:123.031073},
                {lat:13.748236, lng:123.039475},{lat:13.747289, lng:123.041318},{lat:13.747192, lng:123.041829},{lat:13.747346, lng:123.044648},
                {lat:13.745039, lng:123.049697},{lat:13.741593, lng:123.053908},{lat:13.740757, lng:123.05707},{lat:13.735557, lng:123.063046},
                {lat:13.733916, lng:123.069105},{lat:13.733509, lng:123.069816},{lat:13.733348, lng:123.069953},{lat:13.729687, lng:123.076105},
                {lat:13.728261, lng:123.077763},{lat:13.727927, lng:123.07861},{lat:13.724676, lng:123.082741},{lat:13.72444, lng:123.082796},
                {lat:13.719957, lng:123.084771},{lat:13.714495, lng:123.089615},{lat:13.714277, lng:123.089909},{lat:13.715818, lng:123.10221},
                {lat:13.712659, lng:123.105393},{lat:13.712113, lng:123.107127},{lat:13.703883, lng:123.11115},{lat:13.700846, lng:123.118907},
                {lat:13.694111, lng:123.112862},{lat:13.693306, lng:123.112862},{lat:13.690805, lng:123.11042},{lat:13.687528, lng:123.109581},
                {lat:13.685252, lng:123.111058},{lat:13.684848, lng:123.110142},{lat:13.679786, lng:123.111133},{lat:13.674128, lng:123.109419},
                {lat:13.664152, lng:123.096679},{lat:13.662824, lng:123.096169},{lat:13.660237, lng:123.096055},{lat:13.659176, lng:123.09556},
                {lat:13.661401, lng:123.080042},{lat:13.66134, lng:123.075443},{lat:13.659209, lng:123.07211},{lat:13.659033, lng:123.071992},
                {lat:13.65509, lng:123.07148},{lat:13.650534, lng:123.073092},{lat:13.643591, lng:123.085203},{lat:13.642278, lng:123.08487},
                {lat:13.633416, lng:123.078114},{lat:13.63322, lng:123.070254},{lat:13.633757, lng:123.068366},{lat:13.631761, lng:123.059122},
                {lat:13.63171, lng:123.058332},{lat:13.629937, lng:123.055863},{lat:13.628015, lng:123.052519},{lat:13.631362, lng:123.047115},
                {lat:13.631637, lng:123.041413},{lat:13.630194, lng:123.036743},{lat:13.627041, lng:123.033509},{lat:13.620928, lng:123.032619},
                {lat:13.617383, lng:123.024895},{lat:13.619118, lng:123.021566},{lat:13.617728, lng:123.01802},{lat:13.613982, lng:123.01304},
                {lat:13.611262, lng:123.011607},{lat:13.59932, lng:123.012843},{lat:13.599308, lng:123.012846},{lat:13.597202, lng:123.000083},
                {lat:13.586445, lng:122.996757},{lat:13.586267, lng:122.990042},{lat:13.588004, lng:122.98656},{lat:13.588078, lng:122.985294},
                {lat:13.58847, lng:122.984268},{lat:13.590509, lng:122.981064},{lat:13.590547, lng:122.975475},{lat:13.594159, lng:122.971039},
                {lat:13.592891, lng:122.962977},{lat:13.593262, lng:122.961351},{lat:13.594117, lng:122.960306},{lat:13.595065, lng:122.959389},
                {lat:13.595754, lng:122.95781},{lat:13.596842, lng:122.953664},{lat:13.59703, lng:122.948928},{lat:13.598207, lng:122.946774},
                {lat:13.598508, lng:122.945589},{lat:13.604178, lng:122.936566},{lat:13.606418, lng:122.93442},{lat:13.608057, lng:122.933267},
                {lat:13.608217, lng:122.93292},{lat:13.608482, lng:122.930461},{lat:13.607898, lng:122.92456},{lat:13.607914, lng:122.924522},
                {lat:13.609266, lng:122.919044},{lat:13.609826, lng:122.917998},{lat:13.611492, lng:122.909708},{lat:13.610469, lng:122.906036},
                {lat:13.61, lng:122.905026},{lat:13.61, lng:122.902947},{lat:13.610783, lng:122.900364},{lat:13.608641, lng:122.887917},
                {lat:13.604756, lng:122.884054},{lat:13.601302, lng:122.884095},{lat:13.593726, lng:122.873074},{lat:13.59625, lng:122.869972},
                {lat:13.5975, lng:122.86792},{lat:13.600778, lng:122.869614},{lat:13.602917, lng:122.8675},{lat:13.604555, lng:122.863335},
                {lat:13.607083, lng:122.860809},{lat:13.607889, lng:122.85833},{lat:13.609555, lng:122.857475},{lat:13.610416, lng:122.855003},
                {lat:13.619555, lng:122.844139},{lat:13.62, lng:122.842888},{lat:13.620806, lng:122.842888},{lat:13.627916, lng:122.83667},
                {lat:13.627916, lng:122.835777},{lat:13.631639, lng:122.832085},{lat:13.633306, lng:122.831253},{lat:13.637444, lng:122.831253},
                {lat:13.642028, lng:122.82914},{lat:13.642028, lng:122.827499},{lat:13.642889, lng:122.826668},{lat:13.642889, lng:122.819969},
                {lat:13.646222, lng:122.815804},{lat:13.647083, lng:122.812531},{lat:13.646222, lng:122.811638},{lat:13.646222, lng:122.804138},
                {lat:13.64875, lng:122.800835},{lat:13.649583, lng:122.798309},{lat:13.652528, lng:122.796196},{lat:13.654583, lng:122.79747},
                {lat:13.654583, lng:122.798309},{lat:13.653722, lng:122.799141},{lat:13.653722, lng:122.801666},{lat:13.651222, lng:122.804108},
                {lat:13.651222, lng:122.806641},{lat:13.650416, lng:122.807472},{lat:13.650416, lng:122.811638},{lat:13.651222, lng:122.812531},
                {lat:13.651222, lng:122.818336},{lat:13.652083, lng:122.819168},{lat:13.652889, lng:122.822502},{lat:13.655806, lng:122.825363},
                {lat:13.659139, lng:122.82708},{lat:13.663278, lng:122.82872},{lat:13.665, lng:122.83036},{lat:13.667472, lng:122.830414},
                {lat:13.668278, lng:122.831253},{lat:13.672472, lng:122.832054},{lat:13.676222, lng:122.834999},{lat:13.676222, lng:122.835808},
                {lat:13.678278, lng:122.837914},{lat:13.68, lng:122.837914},{lat:13.681666, lng:122.839554},{lat:13.683333, lng:122.839554},
                {lat:13.684584, lng:122.841614},{lat:13.684584, lng:122.84333},{lat:13.682889, lng:122.846664},{lat:13.681222, lng:122.847504},
                {lat:13.681222, lng:122.850777},{lat:13.682083, lng:122.85247},{lat:13.682649, lng:122.853031}
            ];

            const validPins = mapBusinesses.filter(biz => biz.fb_latitude && biz.fb_longitude);

            let centerLat = 13.7, centerLng = 123.0; 
            if (validPins.length > 0) {
                centerLat = validPins.reduce((sum, biz) => sum + parseFloat(biz.fb_latitude), 0) / validPins.length;
                centerLng = validPins.reduce((sum, biz) => sum + parseFloat(biz.fb_longitude), 0) / validPins.length;
            }
            
            const bounds = new google.maps.LatLngBounds();
            libmananCoords.forEach(pt => bounds.extend(pt));

            map = new google.maps.Map(document.getElementById("libmanan-map"), {
                mapTypeId: "roadmap",
                center: { lat: centerLat, lng: centerLng },
                zoom: 12,
                restriction: { latLngBounds: bounds, strictBounds: true },
                disableDefaultUI: false,
                mapTypeControl: true,
                streetViewControl: false,
                styles: [{ featureType: "all", elementType: "labels", stylers: [{ visibility: "off" }] }]
            });

            const libmananPolygon = new google.maps.Polygon({
                paths: libmananCoords,
                strokeColor: "#A80000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "transparent",
                clickable: false
            });
            libmananPolygon.setMap(map);

            infoWindow = new google.maps.InfoWindow();

            renderMarkers(mapBusinesses, map, infoWindow);

            const cuisineDropdown = document.getElementById('cuisineTypeDropdown');
            cuisineDropdown.addEventListener('change', function() {
                const selectedType = cuisineDropdown.value;
                let filtered = mapBusinesses;

                if (selectedType === "1") {
                    filtered = mapBusinesses.filter(biz => biz.is_new == 1);
                } else if (selectedType) {
                    filtered = mapBusinesses.filter(biz => 
                        biz.fb_type.trim().toLowerCase() === selectedType.trim().toLowerCase()
                    );
                }
                renderMarkers(filtered, map, infoWindow);
                
            });

            const searchInput = document.getElementById('searchBusinessInput');
            const suggestionsDiv = document.getElementById('searchSuggestions');

            function fuzzyMatch(str, search) {
                if (!str || !search) return false;
                str = str.toLowerCase();
                search = search.toLowerCase();
                return str.includes(search);
            }

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                if (searchTerm) {
                    const matches = mapBusinesses.filter(biz => biz.fb_name && fuzzyMatch(biz.fb_name, searchTerm)).slice(0, 5);
                    if (matches.length > 0) {
                        suggestionsDiv.innerHTML = matches.map(biz => `
                            <div class="flex items-center p-3 cursor-pointer hover:bg-gray-50 transition border-b border-gray-100 last:border-0" data-name="${biz.fb_name}">
                                <img src="${biz.fb_photo}" class="w-10 h-10 rounded object-cover mr-3 pointer-events-none">
                                <div>
                                    <p class="font-semibold text-sm text-gray-800 pointer-events-none">${biz.fb_name}</p>
                                    <p class="text-xs text-gray-500 pointer-events-none">${biz.fb_type}</p>
                                </div>
                            </div>
                        `).join('');
                        suggestionsDiv.classList.remove('hidden');
                    } else { suggestionsDiv.classList.add('hidden'); }
                } else { suggestionsDiv.classList.add('hidden'); }
            });

            suggestionsDiv.addEventListener('click', function(e) {
                const item = e.target.closest('div[data-name]');
                if (item) {
                    const selectedName = item.dataset.name;
                    searchInput.value = selectedName;
                    suggestionsDiv.classList.add('hidden');
                    const biz = mapBusinesses.find(b => b.fb_name === selectedName);
                    
                    if (biz && biz.fb_latitude && biz.fb_longitude) {
                        const latLng = { lat: parseFloat(biz.fb_latitude), lng: parseFloat(biz.fb_longitude) };
                        map.setCenter(latLng);
                        map.setZoom(17);
                        const marker = allMarkers.find(m => m.getTitle() === selectedName);
                        if (marker) google.maps.event.trigger(marker, 'click');
                    }
                }
            });
            
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.classList.add('hidden');
                }
            });

            startLiveStatusUpdates();
        }
        
        window.onload = initMap;
    </script>
</body>
</html>