<?php
session_start();
require_once '../db_con.php';

// Check Auth
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get User's Favorite IDs
$stmt = $conn->prepare("SELECT user_favorites FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$favorites_string = $user_data['user_favorites'] ?? '';
$fav_ids = [];

if (!empty($favorites_string)) {
    $fav_ids = explode(',', $favorites_string);
    $fav_ids = array_map('intval', $fav_ids);
}

// Fetch Details for these Favorites
$fav_businesses = [];
if (!empty($fav_ids)) {
    $ids_placeholder = implode(',', $fav_ids);
    
    // Updated SQL to fetch Ratings, Reviews, Address like in categories.php
    $sql = "SELECT 
                f.fbowner_id, f.fb_name, f.fb_type, f.fb_photo, f.fb_status, f.fb_description, f.fb_address,
                COUNT(r.id) AS total_reviews,
                COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating
            FROM fb_owner f
            LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
            WHERE f.fbowner_id IN ($ids_placeholder) AND f.activation = 'Active'
            GROUP BY f.fbowner_id
            ORDER BY f.fb_name ASC";
            
    $fav_result = $conn->query($sql);
    
    if ($fav_result) {
        while($row = $fav_result->fetch_assoc()) {
            // Fetch Price Range for each business
            $price_sql = "SELECT MIN(menu_price) as min_p, MAX(menu_price) as max_p FROM menus WHERE is_available = 1 AND fbowner_id = " . $row['fbowner_id'];
            $price_res = $conn->query($price_sql);
            $price_data = $price_res->fetch_assoc();

            $row['min_price'] = $price_data['min_p'] ? (float)$price_data['min_p'] : 0;
            $row['max_price'] = $price_data['max_p'] ? (float)$price_data['max_p'] : 0;

            // Fix photo path
            if (!empty($row['fb_photo'])) {
                $row['fb_photo'] = '../' . ltrim($row['fb_photo'], './');
            } else {
                $row['fb_photo'] = '../vendors/imgsource/default.jpg';
            }

            $fav_businesses[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites | TasteLibmanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#A80000", secondary: "#FF6B00", dark: "#1F2937", light: "#F3F4F6" },
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
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
</head>
<body class="bg-gray-50 font-['Inter'] flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="user.php" class="font-['Pacifico'] text-2xl text-primary">TasteLibmanan</a>
                <a href="user.php" class="text-gray-600 hover:text-primary font-medium">← Back to Home</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-10 flex-grow w-full">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Favorites ❤️</h1>
                <p class="text-gray-500">Your curated list of must-visit food spots.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" id="searchInput" oninput="clientSideSearch()" placeholder="Search favorites..." 
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

        <?php if (empty($fav_businesses)): ?>
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                <i class="ri-heart-add-line text-6xl text-gray-300 mb-4 inline-block"></i>
                <h3 class="text-xl font-medium text-gray-900">No favorites yet</h3>
                <p class="text-gray-500 mt-2">Go back and explore to save businesses here!</p>
                <a href="categories.php" class="mt-4 inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-800 transition">Explore Now</a>
            </div>
        <?php else: ?>
            <div id="favorites-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($fav_businesses as $biz): ?>
                    <div onclick="location.href='businessdetails.php?fbowner_id=<?php echo $biz['fbowner_id']; ?>'" 
                         class="fav-card bg-white rounded-2xl shadow-soft hover:shadow-card overflow-hidden transition-all duration-300 flex flex-col h-full border border-gray-100 relative cursor-pointer group"
                         data-name="<?= strtolower(html_entity_decode($biz['fb_name'])) ?>"
                         data-min-price="<?= $biz['min_price']; ?>"
                         data-max-price="<?= $biz['max_price']; ?>">
                        
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo $biz['fb_photo']; ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 onerror="this.src='../vendors/imgsource/default.jpg'">
                                 
                            <button onclick="event.stopPropagation(); removeFavorite(this, <?php echo $biz['fbowner_id']; ?>)" 
                                    class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full text-red-600 flex items-center justify-center shadow-md hover:bg-red-50 z-10 transition-transform hover:scale-110" 
                                    title="Remove from favorites">
                                <i class="ri-delete-bin-line"></i>
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
                            <h3 class="text-lg font-bold text-gray-900 mb-1 truncate group-hover:text-primary transition">
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

                            <p class="text-xs text-gray-500 mb-3 flex items-center truncate">
                                <i class="ri-map-pin-line mr-1"></i> <?php echo htmlspecialchars($biz['fb_address']); ?>
                            </p>

                            <p class="text-xs font-semibold text-secondary mb-3">
                                <?php if($biz['min_price'] > 0): ?>
                                    ₱<?= number_format($biz['min_price'],0) ?> - ₱<?= number_format($biz['max_price'],0) ?>
                                <?php else: ?>
                                    Price not available
                                <?php endif; ?>
                            </p>
                            
                            <div class="block w-full text-center py-2.5 rounded-xl bg-gray-50 text-gray-700 font-semibold text-sm group-hover:bg-primary group-hover:text-white transition-colors border border-gray-100 mt-auto">
                                View Details
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="noResultsMsg" class="hidden flex-col items-center justify-center py-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="ri-search-2-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">No favorites found</h3>
                <p class="text-gray-500">Try adjusting your search or filter.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-primary text-white py-10 mt-12 border-t border-red-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <h1 class="font-brand text-3xl mb-6">TasteLibmanan</h1>
            <nav class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-medium">
                <a href="#" onclick="document.getElementById('aboutModal').classList.remove('hidden'); return false;" class="hover:text-red-200">About</a>
                <a href="#" onclick="document.getElementById('contactModal').classList.remove('hidden'); return false;" class="hover:text-red-200">Contact</a>
                <a href="#" onclick="document.getElementById('privacyModal').classList.remove('hidden'); return false;" class="hover:text-red-200">Privacy Policy</a>
            </nav>
            <p class="text-xs text-white/80">© 2025 TasteLibmanan. All rights reserved.</p>
        </div>
    </footer>

    <div id="aboutModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('aboutModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors z-10"><i class="ri-close-line text-3xl"></i></button>
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
    
    <div id="contactModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('contactModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors"><i class="ri-close-line text-3xl"></i></button>
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
            <button onclick="document.getElementById('privacyModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors"><i class="ri-close-line text-3xl"></i></button>
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
        // --- SEARCH AND FILTER LOGIC ---
        function clientSideSearch() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            filterItems(query, null);
        }

        function applyPriceFilter() {
            const range = document.getElementById("priceRangeDropdown").value;
            const query = document.getElementById('searchInput').value.toLowerCase();
            filterItems(query, range);
        }

        function filterItems(searchQuery, priceRange) {
            const cards = document.querySelectorAll('.fav-card');
            let visibleCount = 0;
            
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
                    matchesPrice = (minPrice > 0 && minPrice < 100);
                } else if (priceRange === '100to200') {
                    matchesPrice = (minPrice <= 200 && maxPrice >= 100);
                } else if (priceRange === 'above200') {
                    matchesPrice = (maxPrice > 200);
                }

                // Toggle Visibility
                if (matchesSearch && matchesPrice) {
                    card.style.display = ''; // Show
                    visibleCount++;
                } else {
                    card.style.display = 'none'; // Hide
                }
            });

            // Show "No Results" message if needed
            const noResMsg = document.getElementById('noResultsMsg');
            if(noResMsg) {
                if(visibleCount === 0 && cards.length > 0) {
                    noResMsg.classList.remove('hidden');
                    noResMsg.classList.add('flex');
                } else {
                    noResMsg.classList.add('hidden');
                    noResMsg.classList.remove('flex');
                }
            }
        }

        // --- REMOVE FAVORITE LOGIC (with SweetAlert) ---
        function removeFavorite(btn, id) {
            Swal.fire({
                title: 'Remove from Favorites?',
                text: "Are you sure you want to remove this business?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A80000',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('toggle_favorites.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `fbowner_id=${id}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') {
                            Swal.fire({
                                title: 'Removed!',
                                text: 'The business has been removed from your list.',
                                icon: 'success',
                                confirmButtonColor: '#A80000'
                            }).then(() => {
                                const card = btn.closest('.fav-card');
                                card.style.transition = "all 0.3s ease";
                                card.style.opacity = "0";
                                card.style.transform = "scale(0.9)";
                                
                                setTimeout(() => {
                                    card.remove();
                                    // Check if any cards remain (not hidden ones, but actual nodes)
                                    const remainingCards = document.querySelectorAll('.fav-card');
                                    if (remainingCards.length === 0) {
                                        location.reload(); 
                                    } else {
                                        // Re-run filter in case layout needs adjustment
                                        const query = document.getElementById('searchInput').value.toLowerCase();
                                        const range = document.getElementById("priceRangeDropdown").value;
                                        filterItems(query, range);
                                    }
                                }, 300);
                            });
                        } else {
                            Swal.fire('Error!', "Error removing favorite: " + (data.message || "Unknown"), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', "Server communication error.", 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>