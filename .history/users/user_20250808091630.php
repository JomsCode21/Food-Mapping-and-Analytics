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
  const bounds = {
    north: 13.77,
    south: 13.58,
    east: 123.12,
    west: 122.80
  };
const map = new google.maps.Map(document.getElementById("libmanan-map"), {
  zoom: 10, // or higher
  center: libmanan,
  restriction: {
    latLngBounds: {
      north: 13.77,
      south: 13.58,
      east: 123.12,
      west: 122.80
    },
    strictBounds: true
  }
});

  // Large rectangle covering the map
  const outerCoords = [
    {lat: 14.0, lng: 122.6},
    {lat: 14.0, lng: 123.3},
    {lat: 13.4, lng: 123.3},
    {lat: 13.4, lng: 122.6}
  ];
  //  polygon coordinates for Libmanan
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

  // Draw the polygon
 const maskPolygon = new google.maps.Polygon({
    paths: [outerCoords, libmananCoords],
    strokeColor: "transparent",
    strokeOpacity: 0,
    strokeWeight: 0,
    fillColor: "#fff",
    fillOpacity: 1,
    clickable: false
  });
  maskPolygon.setMap(map);

  // Draw Libmanan boundary on top
  const libmananPolygon = new google.maps.Polygon({
    paths: libmananCoords,
    strokeColor: "#A80000",
    strokeOpacity: 1,
    strokeWeight: 2,
    fillColor: "transparent",   // No fill
  fillOpacity: 0,
    clickable: false
  });
  libmananPolygon.setMap(map);
}

window.onload = initMap;


</script>

</body>
</html>
