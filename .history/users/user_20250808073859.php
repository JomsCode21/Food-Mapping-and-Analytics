<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TasteLibmanan</title>
      
        <!-- Load TailwindCSS 3 (only this) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
          tailwind.config = {
            theme: {
              extend: {
                colors: { primary: "#A80000", secondary: "#FF6B00" },
                borderRadius: {
                  none: "0px",
                  sm: "4px",
                  DEFAULT: "8px",
                  md: "12px",
                  lg: "16px",
                  xl: "20px",
                  "2xl": "24px",
                  "3xl": "32px",
                  full: "9999px",
                  button: "8px",
                },
              },
            },
          }
        </script>
            <?php
          session_start();
          if (!isset($_SESSION['user_id'])) {
              header("Location: ../index.php"); // Redirect to login page
              exit();
          }

          // Include database connection
          require_once '../db_con.php';

          // Get user information from the database
          $user_id = $_SESSION['user_id'];
          $stmt = $conn->prepare("SELECT name FROM accounts WHERE user_id = ?");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              $user = $result->fetch_assoc();
              $user_name = htmlspecialchars($user['name']); // Sanitize output
          } else {
              $user_name = "User"; // Default name if not found
          }

          $conn->close();
?>
        <!-- FontAwesome icons -->
        <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/styles.css">
        <script src="../js/script.js"></script> 
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/@heroicons/vue@2.0.13/20/solid/heart.js"></script>
        <script src="https://unpkg.com/@heroicons/vue@2.0.13/20/outline/heart.js"></script>

        <!-- Remix Icons -->
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
        <!-- Google Maps JS API (replace YOUR_API_KEY with your actual key) -->
       <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1A2Mej_RdKT_Lq-y0kYIcNW93yY-RrBY"></script>
      </head>

      
<body class="bg-gray-100">
<!-- Navigation Bar -->
<nav class="bg-white shadow-md px-6 py-4 flex items-center justify-between">
    <h1 class="font-['Pacifico'] text-4xl text-primary">Taste<span class="text-gray-800">Libmanan</span></h1>

    <div class="flex items-center space-x-6">
      <!-- Desktop Links -->
      <div class="hidden md:flex space-x-6">
        <a href="user.html" class="text-gray-800 hover:text-primary">Home</a>
        <a href="categories.html" class="text-gray-800 hover:text-primary">Businesses</a>
        <a href="#" class="text-gray-800 hover:text-primary">About Us</a>
        <a href="landing.html" class="text-gray-800 hover:text-primary">Menus</a>
      </div>

      <!-- Hamburger: always visible -->
      <div class="relative">
        <button id="hamburger-btn" class="text-gray-800 text-2xl focus:outline-none hover:text-red-500">
          ☰
        </button>
 
        <!-- Mobile Menu: hidden by default with smooth fade-out transition -->
        <div id="mobile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 z-50 opacity-0 visibility-hidden transition-all duration-300 ease-in-out">
          <a href="user.html" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Home</a>
          <a href="categories.html" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Businesses</a>
          <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">About Us</a>
          <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Menus</a>
          <a href="../logout.php" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Logout</a>
        </div>
      </div>
    </div>
  </nav>

<script>
  const btn = document.getElementById('hamburger-btn');
  const menu = document.getElementById('mobile-menu');
  const menuItems = menu.querySelectorAll('a');

  // Toggle mobile menu when hamburger is clicked
  btn.addEventListener('click', (event) => {
    event.stopPropagation(); // Prevent the click from propagating to the document
    if (menu.classList.contains('hidden')) {
      // Show the menu with smooth transition
      menu.classList.remove('hidden');
      menu.classList.remove('opacity-0', 'visibility-hidden');
      menu.classList.add('opacity-100', 'visibility-visible');
    } else {
      // Hide the menu with smooth transition
      menu.classList.remove('opacity-100', 'visibility-visible');
      menu.classList.add('opacity-0', 'visibility-hidden');
      setTimeout(() => {
        menu.classList.add('hidden');
      }, 300);  
    }
  });

  document.addEventListener('click', (event) => {
    if (!menu.contains(event.target) && !btn.contains(event.target)) {
      menu.classList.remove('opacity-100', 'visibility-visible');
      menu.classList.add('opacity-0', 'visibility-hidden');
      setTimeout(() => {
        menu.classList.add('hidden');
      }, 300); 
    }
  });
  // Prevent the menu from closing if a menu item is clicked
  menuItems.forEach(item => {
    item.addEventListener('click', (event) => {
      event.stopPropagation(); // Prevents the click from propagating to the document
      // Optionally, you can also close the menu after clicking a link
      menu.classList.remove('opacity-100', 'visibility-visible');
      menu.classList.add('opacity-0', 'visibility-hidden');
      setTimeout(() => {
        menu.classList.add('hidden');
      }, 300); // Matches the transition duration for a smooth effect
    });
  });
</script>

    <!-- User Dashboard -->
    <section class="p-6 bg-gray-50">
    <div class="text-3xl font-semibold text-gray-800 mb-6">Welcome, <?php echo $user_name; ?>!</div>        <h2 class="text-xl font-semibold mb-4">Popular Categories</h2>
        
    <!-- Main page -->
<div class="grid grid-cols-4 gap-4">
  <a href="../fbusinessowner/categories.php?cat=restaurants" class="text-center transform transition-transform duration-300 hover:scale-150">
    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
      <img src="../vendors/imgsource/restaurants.jpg" alt="Restaurants" class="w-full h-full object-cover">
    </div>
    <p class="text-xs">Restaurants</p>
  </a>

  <a href="../fbusinessowner/categories.php?cat=fastfoods" class="text-center transform transition-transform duration-300 hover:scale-150">
    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
      <img src="../vendors/imgsource/fastfoods.jpg" alt="Fast Foods" class="w-full h-full object-cover">
    </div>
    <p class="text-xs">Fast Foods</p>
  </a>

  <a href="../fbusinessowner/categories.php?cat=cafes" class="text-center transform transition-transform duration-300 hover:scale-150">
    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
      <img src="../vendors/imgsource/cafes.jpg" alt="Cafes" class="w-full h-full object-cover">
    </div>
    <p class="text-xs">Cafes</p>
  </a>

  <a href="../fbusinessowner/categories.php?cat=bakery" class="text-center transform transition-transform duration-300 hover:scale-150">
    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
      <img src="../vendors/imgsource/bakeries.jpg" alt="Bakery" class="w-full h-full object-cover">
    </div>
    <p class="text-xs">Bakeries</p>
  </a>
</div>

        <br>
        <!-- Food Business Search -->
    <section class="p-6">
        <div class="flex items-center space-x-4 mb-6">
            <input type="text" placeholder="Search for food businesses..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary w-full md:w-1/2">
            <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark hover:scale-105 transition-transform duration-200">Search</button>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary w-full sm:w-auto">
                <option value="">Cuisine Type</option>
                <option value="pizza">Restaurants</option>
                <option value="burger">FastFoods</option>
                <option value="Milktea">MilkteaShops</option>
                <option value="Bakery">Bakeries</option>
            </select>
           
        </div>

      <div id="libmanan-map" style="width: 100%; height: 400px;" class="mb-6"></div>
        
    </section>
    <!-- Food Business Profile -->
    <section class="p-6">
        <div class="mb-6">
            <img src="../vendors/imgsource/8teatripcafe.jpg" alt="Business Banner" class="w-full object-cover rounded-lg" style="margin-top:4cm; height: 50vh;">

                <style>
                /* Default to 50vh for mobile or smaller screens */
                img {
                    height: 200vh;
                }

                /* For desktop or larger screens, set height to 100vh */
                @media (min-width: 769px) {
                    img {
                    height: 100vh;
                    }
                }
                </style>
            <div class="mt-4">
                <h2 class="text-2xl font-semibold">8 Tea Trip Cafe</h2>
                <p class="text-gray-600">Restaurant | Poblacion, Libmanan</p>
                <p class="mt-2 text-gray-600">Operating Hours: 10 AM - 10 PM</p>
                <p class="text-gray-600">Contact: (123) 456-7890</p>
            </div>
        </div>
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Menu</h3>
            <ul class="space-y-4 mt-4">
                <li class="flex justify-between items-center bg-white p-4 shadow-lg rounded-lg">
                    <div>
                        <p style="font-size: 15px; font-style: italic; color: red; transform: rotate(-4deg); display: inline-block;">most popular!</p>
                        <p class="font-semibold">3pcs Wings w/ unli rice</p>
                        <p class="text-sm text-gray-500">Many delicious flavors!</p>
                    </div>
                    
                    <div>
                        <p class="text-primary font-semibold">₱99</p>
                        
                        <button id="viewAllMenusButton" class="bg-primary text-white px-4 py-2 rounded-lg transform transition-transform duration-200 hover:scale-105 hover:bg-primary-dark">
                          View All Menus
                        </button>
                        
                        <script>
                          document.addEventListener('DOMContentLoaded', function() {
                            const viewAllMenusButton = document.getElementById('viewAllMenusButton');
                        
                            viewAllMenusButton.addEventListener('click', function(event) {
                              event.preventDefault(); // Prevent default link behavior
                              const businessName = "8 Tea Trip Cafe";
                              const encodedName = encodeURIComponent(businessName);
                              window.location.href = `http://127.0.0.1:5500/businessdetails.html?name=${encodedName}`;
                            });
                          });
                        </script>
                        

                        
                        </script>
                    
                    </div>
                </li>
            </ul>
        </div>
 
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Reviews</h3>
            <ul class="space-y-4 mt-4">
                <li class="bg-white p-4 shadow-lg rounded-lg">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold">Toni Fowler</span>
                        <span class="text-yellow-500">★★★★★</span>
                        <span class="text-gray-500">7/19/25</span>
                    </div>
                    <p class="mt-2">Great food and service!</p>
                    <br>
                    <div class="flex items-center space-x-2">
                      <span class="font-semibold">BongBong Marcos</span>
                      <span class="text-yellow-500">★★★★★</span>
                      <span class="text-gray-500">10/29/25</span>
                  </div>
                  <p class="mt-2">Sarap ulit ulitin, sulit!</p>
                </li>
            </ul>
        </div>
    
<!-- Top 10 Most Visited Food Businesses -->
<div class="mb-6">
  <h3 class="text-xl font-semibold text-gray-800">Top 10 Most Visited Food Businesses</h3>
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 mt-4">
    <!-- CARD 1 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/8teatripcafe.jpg" alt="Most Visited Business 1" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">8 Tea Trip Cafe <br>Top 1</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Poblacion</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a id="viewDetailsLink" href="#" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const viewDetailsLink = document.getElementById('viewDetailsLink');
              const businessName = "8 Tea Trip Cafe";
              const encodedName = encodeURIComponent(businessName);
              viewDetailsLink.href = `businessdetails.html?name=${encodedName}`;
            });
          </script>

          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 2 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/rekados.jpg" alt="Most Visited Business 2" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Rekados <br>Top 2</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Libod 1</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="http://127.0.0.1:5500/businessdetails.html?name=Rekados" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>

          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 3 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/miggys.jpg" alt="Most Visited Business 3" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Miggys Grill<br>Top 3</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="http://127.0.0.1:5500/businessdetails.html?name=Miggy%27s%20Grill%20House" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>

          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 4 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/kaponi.jpg" alt="Most Visited Business 4" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Kap Oni Samgyupsal <br>Top 4</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="http://127.0.0.1:5500/businessdetails.html?name=Kap%20Oni%20Samgyupsal" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>

          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 5 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/blackpepers.jpg" alt="Most Visited Business 5" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Blackpepers <br>Top 5</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 6 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/tatatiama.jpg" alt="Most Visited Business 6" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Tata Tiama <br>Top 6</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 7 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/liters.jpg" alt="Most Visited Business 7" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Liters Cafe <br>Top 7</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 8 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/bigbrew.jpg" alt="Most Visited Business 8" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Big Brew Cafe <br>Top 8</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 9 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/bakersplaza.jpg" alt="Most Visited Business 9" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Baker's Plaza<br>Top 9</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- CARD 10 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-xs">
      <img src="../vendors/imgsource/atlantic.jpg" alt="Most Visited Business 10" class="w-full h-32 object-cover">
      <div class="p-4">
        <h4 class="font-semibold text-lg">Atlantic Bakery <br>Top 10</h4>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-yellow-500">★★★★★</span>
          <span class="text-gray-600">Location</span>
        </div>
        <div class="flex items-center justify-between mt-3">
          <a href="categories.html" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
          <button class="favorite-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 heart-icon text-gray-400 hover:text-red-500 transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // Get all the heart icons
  const heartIcons = document.querySelectorAll('.favorite-btn');

  heartIcons.forEach((icon) => {
    // Add click event listener to each heart button
    icon.addEventListener('click', function () {
      // Toggle the class for filled or empty heart
      const svg = this.querySelector('svg');
      const path = svg.querySelector('path');

      // Toggle heart color between red and gray
      if (path.getAttribute('fill') === 'currentColor') {
        path.setAttribute('fill', 'red'); // filled heart
      } else {
        path.setAttribute('fill', 'currentColor'); // unfilled heart
      }
    });
  });
</script>




</section>

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

  <script>
  function initMap() {
  const libmanan = { lat: 13.6632598, lng: 122.9018931 };
  const map = new google.maps.Map(document.getElementById("libmanan-map"), {
    zoom: 13,
    center: libmanan,
  });

  // Example polygon coordinates for Libmanan (replace with actual boundary)
  const libmananCoords = [
    { lat: 13.670, lng: 122.890 },
    { lat: 13.670, lng: 122.910 },
    { lat: 13.655, lng: 122.910 },
    { lat: 13.655, lng: 122.890 }
    // ...add more points for accurate shape
  ];

  // Draw the polygon
 

  // Optionally, remove the center marker
  // new google.maps.Marker({
  //   position: libmanan,
  //   map: map,
  //   title: "Libmanan Center"
  // });
}

window.onload = initMap;
</script>

</body>
</html>
