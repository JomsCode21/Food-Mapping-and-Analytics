<?php
session_start();
require_once '../db_con.php';

    // Security Check
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    $user_id = (int)$_SESSION['user_id'];
    $cat = isset($_GET['cat']) ? $_GET['cat'] : '';

    // Fetch User Info & Favorites
    $stmt = $conn->prepare("SELECT name, email, user_favorites FROM accounts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $my_favorites_array = [];

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (!empty($user['user_favorites'])) {
            $my_favorites_array = explode(',', $user['user_favorites']);
        }
    }

    // Category Mapping
    $category_map = [
        'restaurants' => 'Restaurant',
        'fastfoods'   => 'Fastfood',
        'cafes'       => 'Cafe',
        'bakery'      => 'Bakery'
    ];

    $businesses = [];
    $current_category_name = "Select a Category";

    $sql = "";
    $stmt = null;

    if (isset($category_map[$cat])) {
        $type = $category_map[$cat];
        $current_category_name = $type . "s";
        $stmt = $conn->prepare("
            SELECT 
                f.fbowner_id, f.fb_name, f.fb_address, f.fb_photo, f.user_id, f.fb_status, f.fb_description, f.fb_operating_hours,
                COUNT(r.id) AS total_reviews,
                COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating
            FROM fb_owner f
            LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
            WHERE f.fb_type = ? AND f.activation = 'Active'
            GROUP BY f.fbowner_id
            ORDER BY f.fb_name ASC
        ");
        $stmt->bind_param("s", $type);

    } else {
        $current_category_name = "All businesses";
        $stmt = $conn->prepare("
            SELECT 
                f.fbowner_id, f.fb_name, f.fb_address, f.fb_photo, f.user_id, f.fb_status, f.fb_description, f.fb_operating_hours,
                COUNT(r.id) AS total_reviews,
                COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating
            FROM fb_owner f
            LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
            WHERE f.activation = 'Active'
            GROUP BY f.fbowner_id
            ORDER BY f.fb_name ASC
        ");
    }

    // Execute the prepared statement
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $price_sql = "SELECT MIN(menu_price) as min_p, MAX(menu_price) as max_p FROM menus WHERE  is_available = 1 AND fbowner_id = " . $row['fbowner_id'];
            $price_res = $conn->query($price_sql);
            $price_data = $price_res->fetch_assoc();

            $row['min_price'] = $price_data['min_p'] ? (float)$price_data['min_p'] : 0;
            $row['max_price'] = $price_data['max_p'] ? (float)$price_data['max_p'] : 0;

            if (!empty($row['fb_photo'])) {
                $row['fb_photo'] = '../' . ltrim($row['fb_photo'], './');
            } else {
                $row['fb_photo'] = '../vendors/imgsource/default.jpg';
            }

            $businesses[] = $row;
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteLibmanan | Categories</title>
  
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

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="../users/user.php" class="font-brand text-3xl text-primary hover:text-red-700 transition">
                        Taste<span class="text-gray-800">Libmanan</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="../users/user.php" class="text-gray-600 hover:text-primary font-medium text-sm transition">Home</a>
                    <a href="categories.php" class="text-primary font-semibold text-sm uppercase tracking-wide">Businesses</a>
                    <a href="../FBregistration.php" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-800 transition shadow-sm flex items-center">
                        <i class="ri-store-3-line mr-1"></i> Register Business
                    </a>
                    
                    <div class="relative group">
                         <button class="flex items-center space-x-2 text-gray-600 hover:text-primary transition focus:outline-none">
                            <span class="font-medium text-sm">Account</span>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-card border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                            <a href="#" onclick="openModal('accountModal')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary"><i class="ri-settings-3-line mr-2"></i>Settings</a>
                            <a href="../users/favorites.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary"><i class="ri-heart-line mr-2"></i>My Favorites</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="ri-logout-box-r-line mr-2"></i>Logout</a>
                        </div>
                    </div>
                </div>
                <div class="flex md:hidden items-center">
                    <button id="hamburger-btn" class="text-gray-600 hover:text-primary focus:outline-none"><i class="ri-menu-3-line text-2xl"></i></button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="../users/user.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary">Home</a>
                <a href="categories.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-primary">Businesses</a>
                <a href="../users/favorites.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary">My Favorites</a>
                <a href="../FBregistration.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Register Business</a>
                <a href="#" onclick="openModal('accountModal')" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary">Account Settings</a>
                <a href="../logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php 
                $nav_cats = [
                    ['name' => 'All', 'key' => 'all', 'icon' => 'ri-store-2-line', 'color' => 'bg-gray-100 text-gray-600'],
                    ['name' => 'Restaurants', 'key' => 'restaurants', 'icon' => 'ri-restaurant-line', 'color' => 'bg-orange-100 text-orange-600'],
                    ['name' => 'Fast Food', 'key' => 'fastfoods', 'icon' => 'ri-goblet-line', 'color' => 'bg-yellow-100 text-yellow-600'],
                    ['name' => 'Cafes', 'key' => 'cafes', 'icon' => 'ri-cup-line', 'color' => 'bg-amber-100 text-amber-800'],
                    ['name' => 'Bakeries', 'key' => 'bakery', 'icon' => 'ri-cake-3-line', 'color' => 'bg-pink-100 text-pink-600']
                ];
                foreach($nav_cats as $nc): 
                    $isActive = ($cat == $nc['key']);
                ?>
                <a href="categories.php?cat=<?= $nc['key'] ?>" 
                   class="flex items-center p-3 sm:p-4 rounded-xl transition-all duration-300 border 
                          <?= $isActive ? 'bg-white border-primary shadow-md transform -translate-y-1' : 'bg-white border-gray-100 shadow-sm hover:shadow-md hover:border-red-200' ?>">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full <?= $nc['color'] ?> flex items-center justify-center text-lg sm:text-xl mr-3 sm:mr-4 flex-shrink-0">
                        <i class="<?= $nc['icon'] ?>"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base text-gray-800 <?= $isActive ? 'text-primary' : '' ?>"><?= $nc['name'] ?></h3>
                        <p class="text-[10px] sm:text-xs text-gray-500">View Listings</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900"><?= $current_category_name ?></h1>
                <p class="text-gray-500 mt-1">Found <?= count($businesses) ?> locations nearby</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" id="searchInput" oninput="clientSideSearch()" placeholder="Search for food business..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                    <i class="ri-search-line absolute left-3 top-3 text-gray-400"></i>
                </div>

                <div class="relative w-full sm:w-48">
                    <select id="priceRangeDropdown" onchange="applyPriceFilter()" 
                            class="w-full pl-3 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary appearance-none shadow-sm cursor-pointer">
                        <option value="all">All Prices</option>
                        <option value="below100">Below ₱100</option>
                        <option value="100to200">₱100 – ₱200</option>
                        <option value="above200">Above ₱200</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <div id="business-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($businesses)): ?>
                <?php foreach ($businesses as $biz): ?>
                    <div onclick="location.href='../users/businessdetails.php?fbowner_id=<?php echo $biz['fbowner_id']; ?>'" 
                         class="business-card group bg-white rounded-2xl shadow-soft hover:shadow-card overflow-hidden transition-all duration-300 flex flex-col h-full border border-gray-100 relative cursor-pointer"
                         data-name="<?= strtolower(html_entity_decode($biz['fb_name'])) ?>"
                         data-min-price="<?= $biz['min_price']; ?>"
                         data-max-price="<?= $biz['max_price']; ?>"
                         data-id="<?= $biz['fbowner_id'] ?>">
                        
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo $biz['fb_photo']; ?>" 
                                onerror="this.onerror=null;this.src='../vendors/imgsource/default.jpg';"
                                alt="<?php echo html_entity_decode($biz['fb_name']); ?>" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <button onclick="event.stopPropagation(); toggleFavorite(this, <?php echo $biz['fbowner_id']; ?>)" 
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-primary hover:bg-red-50 transition transform hover:scale-110 z-10">
                                <?php if (in_array($biz['fbowner_id'], $my_favorites_array)): ?>
                                    <i class="ri-heart-fill text-lg"></i>
                                <?php else: ?>
                                    <i class="ri-heart-line text-lg"></i>
                                <?php endif; ?>
                            </button>

                            <div class="absolute bottom-3 left-3">
                                <?php if(strtolower($biz['fb_status']) === 'open'): ?>
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
                                <?php echo html_entity_decode($biz['fb_name']); ?>
                            </h3>

                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    <?php
                                        $avg = floatval($biz['avg_rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= floor($avg)) echo '<i class="ri-star-fill"></i>';
                                            elseif ($i == ceil($avg) && $avg - floor($avg) >= 0.5) echo '<i class="ri-star-half-fill"></i>';
                                            else echo '<i class="ri-star-line text-gray-300"></i>';
                                        }
                                    ?>
                                </div>
                                <span class="text-[12px] text-gray-500 ml-1.5 font-medium">(<?= $biz['total_reviews'] ?> reviews)</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3 flex items-center">
                                <i class="ri-map-pin-line mr-1"></i> <?php echo htmlspecialchars($biz['fb_address']); ?>
                            </p>
                                    
                            <p class="text-xs font-semibold text-secondary mb-3">
                                <?php if($biz['min_price'] > 0): ?>
                                    ₱<?= number_format($biz['min_price'],0) ?> - ₱<?= number_format($biz['max_price'],0) ?>
                                <?php else: ?>
                                    Price not available
                                <?php endif; ?>
                            </p>
                                
                            <div class="block w-full text-center py-2.5 rounded-xl bg-gray-50 text-gray-700 font-semibold text-sm group-hover:bg-primary group-hover:text-white transition-colors border border-gray-100">
                                View Details
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <i class="ri-store-3-line text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">No businesses found</h3>
                    <p class="text-gray-500">Try selecting a different category above.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <footer class="bg-primary text-white py-10 mt-12 border-t border-red-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <h1 class="font-brand text-3xl mb-6">TasteLibmanan</h1>
            <nav class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-medium">
                <a href="#" onclick="openModal('aboutModal')" class="hover:text-red-200">About</a>
                <a href="#" onclick="openModal('contactModal')" class="hover:text-red-200">Contact</a>
                <a href="#" onclick="openModal('privacyModal')" class="hover:text-red-200">Privacy Policy</a>
            </nav>
            <p class="text-xs text-white/80">© 2025 TasteLibmanan. All rights reserved.</p>
        </div>
    </footer>

    <div id="accountModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative">
            <button onclick="closeModal('accountModal')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="ri-close-line text-2xl"></i></button>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Account Settings</h2>
            <form method="POST" action="../users/update_account.php">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary outline-none" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">New Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('accountModal')" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-red-800 rounded-lg shadow-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="aboutModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="closeModal('aboutModal')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors z-10">
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
            <button onclick="closeModal('contactModal')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
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
            <button onclick="closeModal('privacyModal')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
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

    <script>
        // Modal & Menu Toggles
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        document.getElementById('hamburger-btn').addEventListener('click', () => { document.getElementById('mobile-menu').classList.toggle('hidden'); });

        // Search Logic
        function clientSideSearch() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            filterItems(query, null);
        }

        // --- PRICE FILTER LOGIC ---
        function applyPriceFilter() {
            const range = document.getElementById("priceRangeDropdown").value;
            const query = document.getElementById('searchInput').value.toLowerCase();
            filterItems(query, range);
        }

        function filterItems(searchQuery, priceRange) {
            const cards = document.querySelectorAll('.business-card');
            
            // If priceRange is null (called from search), grab current value
            if (priceRange === null) {
                priceRange = document.getElementById("priceRangeDropdown").value;
            }

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const minPrice = parseFloat(card.getAttribute('data-min-price')) || 0;
                const maxPrice = parseFloat(card.getAttribute('data-max-price')) || 0;
                
                // 1. Check Search Match
                let matchesSearch = name.includes(searchQuery);

                // 2. Check Price Match
                let matchesPrice = false;
                if (priceRange === 'all') {
                    matchesPrice = true;
                } else if (priceRange === 'below100') {
                    // Match if at least one item is below 100
                    matchesPrice = (minPrice > 0 && minPrice < 100);
                } else if (priceRange === '100to200') {
                    // Match if there is overlap with 100-200 range
                    // i.e., cheapest item is <= 200 AND most expensive is >= 100
                    matchesPrice = (minPrice <= 200 && maxPrice >= 100);
                } else if (priceRange === 'above200') {
                    // Match if there is an item above 200 (max price > 200)
                    matchesPrice = (maxPrice > 200);
                }

                // Toggle Visibility
                if (matchesSearch && matchesPrice) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        // Favorite Logic
        function toggleFavorite(btn, fbownerId) {
            const icon = btn.querySelector('i');
            const isFavorited = icon.classList.contains('ri-heart-fill');

            if (isFavorited) {
                icon.classList.remove('ri-heart-fill'); icon.classList.add('ri-heart-line');
            } else {
                icon.classList.remove('ri-heart-line'); icon.classList.add('ri-heart-fill');
            }

            fetch('../users/toggle_favorites.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `fbowner_id=${fbownerId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') {
                    // Revert on error
                    if (isFavorited) { icon.classList.add('ri-heart-fill'); icon.classList.remove('ri-heart-line'); }
                    else { icon.classList.add('ri-heart-line'); icon.classList.remove('ri-heart-fill'); }
                }
            });
        }
    </script>
</body>
</html>