<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Libmanan Food</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
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
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <link rel="stylesheet" href="vendors/css/style.css"/>
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      body {
      font-family: 'Inter', sans-serif;
      }
      .map-container {
      position: relative;
      }
      .map-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      padding: 1rem;
      }
      .blurred-image {
      filter: blur(5px);
      }
      .login-modal, .register-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 50;
      align-items: center;
      justify-content: center;
      }
      .modal-content {
      background-color: white;
      border-radius: 8px;
      max-width: 90%;
      width: 340px;
      max-height: 90vh;
      overflow-y: auto;
      }
    </style>
  </head>
  <body class="bg-gray-50 text-gray-800">
    <!-- Navigation Bar -->
    <nav class="fixed top-0 w-full bg-white text-gray-800 shadow-sm z-40">
        <div class="flex items-center justify-between px-4 py-3">
          <div class="flex items-center">
            <h1 class="font-['Pacifico'] text-xl text-primary">
              Taste<span class="text-gray-800">Libmanan</span>
            </h1>
          </div>
          <div class="flex items-center space-x-3">
            <button
             id="login-btn"
              class="text-sm border border-primary text-primary px-3 py-1.5 rounded-button cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Login
            </button> 
            <button
              id="register-btn"
              class="text-sm bg-primary text-white px-3 py-1.5 rounded-button cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Create Account
            </button>
          </div>
        </div>
      </nav>
      
    <!-- Main Content -->
    <main class="pt-16 pb-16">
        <section class="hero-section">
          <div class="container mx-auto px-4">  <!-- Center the content -->
            <div class="hero-text">
              <h1 class="text-3xl font-bold mb-3 text-left">
                Your Guide to<br />Libmanan's
                <span class="text-primary">Culinary<br />Delights</span>
              </h1>
              <p class="text-gray-800 text-base text-left shadow-sm">
                Discover the best local food businesses, explore authentic cuisines,
                and connect with the vibrant food community in Libmanan.
              </p>              
              <div class="mt-6 space-x-3">
                <button id="exploreBusinessesButton" class="bg-primary text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-primary border border-primary">
                  Explore Businesses
                </button>
                <button
                class="bg-primary text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-primary border border-primary"
                onclick="window.location.href='registration.html';"
              >
                Register Your Business
              </button>
              </div>
                  
              
            </div>
          </div>
        </section>
       
        <!-- Map Section -->
        <section class="px-4 py-6">
          <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">
              Discover <span class="text-primary">Food Places</span> Near You
            </h2>
            <p class="text-gray-600 mt-2">
              Explore our Interactive map to find the best food spots in
              Libmanan. Sign up to access the full experience with reviews and
              recommendations.
            </p>
          </div>
          <div class="bg-white rounded-lg p-4 shadow-sm">
            <!-- Search Bar -->
            <div class="relative mb-4 group">
                <input
                  type="text"
                  placeholder="Search for food places..."
                  class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-lg border-none text-sm cursor-not-allowed"
                  disabled
                />
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                  <i class="ri-search-line"></i>
                </div>
              
                <!-- Tooltip -->
                <div class="absolute top-full left-0 mt-2 w-max bg-primary text-white text-xs rounded-md px-3 py-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  You need to login first to access this.
                </div>
              </div>
              
            <!-- Categories -->
            <div class="mb-4">
              <p class="text-sm font-medium mb-2">Popular Categories</p>
              <div class="flex flex-wrap gap-2">
                <button
                  class="px-3 py-1 text-xs bg-gray-50 text-gray-700 rounded-full"
                >
                  Restaurants
                </button>
                <button
                  class="px-3 py-1 text-xs bg-gray-50 text-gray-700 rounded-full"
                >
                  Cafes
                </button>
                <button
                  class="px-3 py-1 text-xs bg-gray-50 text-gray-700 rounded-full"
                >
                  Street Food
                </button>
                <button
                  class="px-3 py-1 text-xs bg-gray-50 text-gray-700 rounded-full"
                >
                  Bakeries
                </button>
                <button
                  class="px-3 py-1 text-xs bg-gray-50 text-gray-700 rounded-full"
                >
                  Local Delicacies
                </button>
              </div>
            </div>
            <!-- Popular Locations -->
            <div class="mb-4">
              <p class="text-sm font-medium mb-2">Popular Locations</p>
              <div class="space-y-2">
                <div class="flex items-center text-sm text-gray-600">
                  <i class="ri-map-pin-line mr-2"></i>
                  <span>Centro, Libmanan</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                  <i class="ri-map-pin-line mr-2"></i>
                  <span>San Isidro, Libmanan</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                  <i class="ri-map-pin-line mr-2"></i>
                  <span>Bagumbayan, Libmanan</span>
                </div>
              </div>
            </div>
      
            <!-- Map Preview -->
<div class="relative rounded-lg overflow-hidden h-80"> <!-- Increased height for iframe -->
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124061.10759452378!2d122.90189315577693!3d13.663259827099685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a21886a19dfc3f%3A0x91fb88e544c926cc!2sLibmanan%2C%20Camarines%20Sur!5e0!3m2!1sen!2sph!4v1745640028294!5m2!1sen!2sph"
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade"
      class="absolute inset-0 w-full h-full">
    </iframe>
  
    <!-- Optional overlay if you still want a signup message -->
    <div class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-60">
      <div class="text-center p-4">
        <h3 class="text-lg font-semibold mb-2">
          Interactive Map Preview
        </h3>
        <p class="text-sm text-gray-600 mb-3 shadow-sm">
          Sign up to access our interactive map with all food businesses in Libmanan, complete with reviews, directions, and more!
        </p>
        <button id="getFullAccessButton" class="bg-primary text-white px-4 py-2 rounded-button text-sm font-medium transform transition-transform duration-300 hover:scale-105">
          Get Full Access
        </button>
      </div>
    </div>
  </div>
          </div>
        </section>
      </section>
      <!-- Featured Businesses -->
      <section class="px-4 py-4">
        <div class="text-center mb-6">
          <h2 class="text-2xl font-semibold text-gray-800">
            Featured <span class="text-primary">Businesses</span>
          </h2>
          <p class="text-gray-600 text-sm mt-2">
            Explore some of the most beloved food establishments in Libmanan,
            known for their exceptional offerings and service.
          </p>
        </div>
        <div class="grid grid-cols-1 gap-4">
          <!-- Featured Business 1 -->
          <div class="bg-white rounded-lg shadow-sm overflow-hidden relative">
            <div class="h-48 overflow-hidden">
              <img
                src="vendors/imgsource/2.jpg"
                alt="Grilled Haven"
                class="w-full h-full object-cover object-center"
              />
              <div
                class="absolute top-2 right-2 bg-white rounded-full px-2 py-1 flex items-center"
              >
                <i class="ri-star-fill text-yellow-400 text-sm"></i>
                <span class="ml-1 text-sm font-medium">4.8</span>
              </div>
            </div>
            <div class="p-4">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-lg">Grilled Haven</h3>
                  <div class="text-sm text-gray-500">
                    Restaurant • Filipino BBQ
                  </div>
                </div>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-map-pin-line"></i>
                </div>
                <span>San Isidro, Libmanan</span>
              </div>
              <div class="flex items-center text-sm text-gray-500 mt-1">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-time-line"></i>
                </div>
                <span>8 AM - 8 PM</span>
              </div>

              <button id="viewDetailsButton" class="w-full mt-4 bg-primary text-white py-2 rounded-button font-medium transform transition-transform duration-300 hover:scale-105">
                View Details
              </button>
                <div id="notification" class="notification hidden">
                  <div class="notification-content">
                    <button class="close-notification" style="margin-bottom: px;">&times;</button>
                    <p>You'll need to log in first to have access.</p>
                    <button id="notificationLoginButton" class="bg-primary text-white py-1 px-3 rounded" style="margin-left: 2cm; margin-top: 10px;">Login</button>
                  </div>
                
              </div>
            </div>
          </div>
          <!-- Featured Business 2 -->
          <div class="bg-white rounded-lg shadow-sm overflow-hidden relative">
            <div class="h-48 overflow-hidden">
              <img
                src="vendors/imgsource/sweet.jpg"
                alt="Sweet Delights Bakery"
                class="w-full h-full object-cover object-center"
              />
              <div
                class="absolute top-2 right-2 bg-white rounded-full px-2 py-1 flex items-center"
              >
                <i class="ri-star-fill text-yellow-400 text-sm"></i>
                <span class="ml-1 text-sm font-medium">4.9</span>
              </div>
            </div>
            <div class="p-4">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-lg">Sweet Delights Bakery</h3>
                  <div class="text-sm text-gray-500">
                    Bakery • Pastries & Desserts
                  </div>
                </div>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-map-pin-line"></i>
                </div>
                <span>Centro, Libmanan</span>
              </div>
              <div class="flex items-center text-sm text-gray-500 mt-1">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-time-line"></i>
                </div>
                <span>6 AM - 8 PM</span>
              </div>

              <button id="viewDetailsButton" class="w-full mt-4 bg-primary text-white py-2 rounded-button font-medium transform transition-transform duration-300 hover:scale-105">
                View Details
              </button>
                <div id="notification" class="notification hidden">
                  <div class="notification-content">
                    <button class="close-notification" style="margin-bottom: px;">&times;</button>
                    <p>You'll need to log in first to have access.</p>
                    <button id="notificationLoginButton" class="bg-primary text-white py-1 px-3 rounded" style="margin-left: 2cm; margin-top: 10px;">Login</button>
                  </div>
                
              </div>

            </div>
          </div>
          <!-- Featured Business 3 -->
          <div class="bg-white rounded-lg shadow-sm overflow-hidden relative">
            <div class="h-48 overflow-hidden">
              <img
                src="vendors/imgsource/seafood.jpg"
                alt="Seafood Junction"
                class="w-full h-full object-cover object-center"
              />
              <div
                class="absolute top-2 right-2 bg-white rounded-full px-2 py-1 flex items-center"
              >
                <i class="ri-star-fill text-yellow-400 text-sm"></i>
                <span class="ml-1 text-sm font-medium">4.9</span>
              </div>
            </div>
            <div class="p-4">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-lg">Seafood Junction</h3>
                  <div class="text-sm text-gray-500">
                    Restaurant • Fresh Seafood
                  </div>
                </div>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-map-pin-line"></i>
                </div>
                <span>Bagumbayan, Libmanan</span>
              </div>
              <div class="flex items-center text-sm text-gray-500 mt-1">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-time-line"></i>
                </div>
                <span>10 AM - 10 PM</span>
              </div>
              <button
              class="w-full mt-4 bg-primary text-white py-2 rounded-button font-medium transform transition-transform duration-300 hover:scale-105"
            >
              View Details
            </button>            
            </div>
          </div>
          <!-- Featured Business 4 -->
          <div class="bg-white rounded-lg shadow-sm overflow-hidden relative">
            <div class="h-48 overflow-hidden">
              <img
                src="vendors/imgsource/kape.jpg"
                alt="Bahay Kape"
                class="w-full h-full object-cover object-center"
              />
              <div
                class="absolute top-2 right-2 bg-white rounded-full px-2 py-1 flex items-center"
              >
                <i class="ri-star-fill text-yellow-400 text-sm"></i>
                <span class="ml-1 text-sm font-medium">4.6</span>
              </div>
            </div>
            <div class="p-4">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-semibold text-lg">Bahay Kape</h3>
                  <div class="text-sm text-gray-500">
                    Cafe • Coffee & Snacks
                  </div>
                </div>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-map-pin-line"></i>
                </div>
                <span>San Vicente, Libmanan</span>
              </div>
              <div class="flex items-center text-sm text-gray-500 mt-1">
                <div class="w-4 h-4 flex items-center justify-center mr-1">
                  <i class="ri-time-line"></i>
                </div>
                <span>7 AM - 8 PM</span>
              </div>
              <button
              class="w-full mt-4 bg-primary text-white py-2 rounded-button font-medium transform transition-transform duration-300 hover:scale-105"
            >
              View Details
            </button>
            
            </div>
          </div>
        </div>
        <div class="text-center mt-6">
          <button id="viewAllBusinessesButton" class="text-primary font-medium text-sm border border-primary px-6 py-2 rounded-button hover:bg-primary hover:text-white transition-colors duration-300">
            View All Businesses
          </button>
          
        </div>
      </section>
      <!-- Categories Section -->
      <section class="px-4 py-4">
        <h2 class="text-xl font-semibold mb-4">Popular Categories</h2>
        <div class="grid grid-cols-4 gap-3">
          <!-- Category 1 -->
          <div class="text-center transform transition-transform duration-300 hover:scale-150">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
              <img
                src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Filipino%20cuisine%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=4&orientation=squarish"
                alt="Filipino Cuisine"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-xs whitespace-nowrap overflow-hidden text-overflow-ellipsis">
              Bakeries
            </p>
          </div>
          <!-- Category 2 -->
          <div class="text-center transform transition-transform duration-300 hover:scale-150">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
              <img
                src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Fast%20food%20burger%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=5&orientation=squarish"
                alt="Fast Food"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-xs whitespace-nowrap overflow-hidden text-overflow-ellipsis">
              Fast Food
            </p>
          </div>
          <!-- Category 3 -->
          <div class="text-center transform transition-transform duration-300 hover:scale-150">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
              <img
                src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Coffee%20cup%20with%20steam%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=6&orientation=squarish"
                alt="Cafes"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-xs whitespace-nowrap overflow-hidden text-overflow-ellipsis">
              Cafes
            </p>
          </div>
          <!-- Category 4 -->
          <div class="text-center transform transition-transform duration-300 hover:scale-150">
            <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
              <img
                src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Street%20food%20stall%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=7&orientation=squarish"
                alt="Street Food"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-xs whitespace-nowrap overflow-hidden text-overflow-ellipsis">
              Restaurants
            </p>
          </div>
        </div>
      </section>
      
      <!-- Call to Action -->
      <section class="px-4 py-6 bg-gray-100 rounded-lg mx-4 my-6">
        <div class="text-center mb-4">
          <h2 class="text-xl font-semibold mb-2">Ready to Explore?</h2>
          <p class="text-gray-600 text-sm">
            Join our community to discover the best food spots in Libmanan
          </p>
        </div>
        <div class="flex flex-col space-y-3">
            <button
              id="cta-login-btn"
              class="bg-primary text-white py-3 rounded-button font-medium cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Login
            </button>
            <button
              id="cta-register-btn"
              class="bg-white border border-primary text-primary py-3 rounded-button font-medium cursor-pointer transform transition-transform duration-300 hover:scale-105"
            >
              Create Account
            </button>
          </div>
          
      </section>
      <!-- Testimonials -->
      <section class="px-4 py-4">
        <h2 class="text-xl font-semibold mb-4">What Our Users Say</h2>
        <div class="space-y-4">
          <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-3">
              <div
                class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3"
              >
                <img
                  src="vendors/imgsource/dale.jpg"
                  alt="User"
                  class="w-full h-full object-cover"
                />
              </div>
              <div>
                <p class="font-medium">Dale Mar</p>
                <div class="flex text-yellow-400 text-sm">
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                </div>
              </div>
            </div>
            <p class="text-gray-600 text-sm">
              "Ice Cream Yummy"
            </p>
          </div>
          <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-3">
              <div
                class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3"
              >
                <img
                  src="vendors/imgsource/edr.jpg"
                  alt="User"
                  class="w-full h-full object-cover"
                />
              </div>
              <div>
                <p class="font-medium">Alex Edrian</p>
                <div class="flex text-yellow-400 text-sm">
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-fill"></i>
                  <i class="ri-star-half-fill"></i>
                </div>
              </div>
            </div>
            <p class="text-gray-600 text-sm">
              "Ice Cream Good"
            </p>
          </div>
        </div>
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
      
    </main>
    <!-- Login Modal -->
    <div id="login-modal" class="login-modal">
      <div class="modal-content p-5">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Login</h2>
          <button
            class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer">
            <i class="ri-close-line ri-lg"></i>
          </button>
        </div>
        <form id="login-form">
          <div class="mb-4">
            <label
              for="login-email"
              class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              type="email"
              id="login-email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Enter your email"
              required/>
          </div>
          <div class="mb-4">
            <label
              for="login-password"
              class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              id="login-password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Enter your password"
              required
            />
          </div>
          <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
              <input type="checkbox" id="remember-me" class="mr-2" />
              <label for="remember-me" class="text-sm text-gray-600"
                >Remember me</label
              >
            </div>
            <a href="#" class="text-sm text-primary">Forgot password?</a>
          </div>
          <button
            type="submit"
            class="w-full bg-primary text-white py-2 rounded-button font-medium cursor-pointer"
          >
            Login
          </button>
          <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
              Don't have an account?
              <a href="#" id="switch-to-register" class="text-primary"
                >Create Account</a
              >
            </p>
          </div>
        </form>
      </div>
    </div>
    <!-- Register Modal -->
    <div id="register-modal" class="register-modal">
      <div class="modal-content p-5">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Create Account</h2>
          <button
            class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer">
            <i class="ri-close-line ri-lg"></i>
          </button>
        </div>
        <form id="register-form">
          <div class="mb-4">
            <label
              for="register-name"
              class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
              type="text"
              id="register-name"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Enter your full name"
              required/>
          </div>
          <div class="mb-4">
            <label
              for="register-email"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Email</label>
            <input
              type="email"
              id="register-email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Enter your email"
              required/>
          </div>
          <div class="mb-4">
            <label
              for="register-phone"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Phone Number (Optional)</label
            >
            <input
              type="tel"
              id="register-phone"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Enter your phone number"
            />
          </div>
          <div class="mb-4">
            <label
              for="register-password"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Password</label
            >
            <input
              type="password"
              id="register-password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Create a password"
              required
            />
          </div>
          <div class="mb-4">
            <label
              for="register-confirm-password"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Confirm Password</label
            >
            <input
              type="password"
              id="register-confirm-password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Confirm your password"
              required
            />
          </div>
          <div class="mb-4">
            <div class="flex items-start">
              <input type="checkbox" id="terms" class="mt-1 mr-2" required />
              <label for="terms" class="text-sm text-gray-600"
                >I agree to the
                <a href="#" class="text-primary">Terms of Service</a> and
                <a href="#" class="text-primary">Privacy Policy</a></label
              >
            </div>
          </div>
          <button
            type="submit"
            class="w-full bg-primary text-white py-2 rounded-button font-medium cursor-pointer">
            Create Account
          </button>
          <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
              Already have an account?
              <a href="#" id="switch-to-login" class="text-primary">Login</a>
            </p>
          </div>
        </form>
      </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
          // Modal elements
          const loginModal = document.getElementById("login-modal");
          const registerModal = document.getElementById("register-modal");
          const loginBtns = [
            document.getElementById("login-btn"),
            document.getElementById("cta-login-btn"),
          ];
          const registerBtns = [
            document.getElementById("register-btn"),
            document.getElementById("cta-register-btn"),
          ];
          const closeModalBtns = document.querySelectorAll(".close-modal");
          const switchToRegister = document.getElementById("switch-to-register");
          const switchToLogin = document.getElementById("switch-to-login");
          // Login form
          const loginForm = document.getElementById("login-form");
          // Register form
          const registerForm = document.getElementById("register-form");
          // Open login modal
          loginBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              loginModal.style.display = "flex";
            });
          });
      
          // Open register modal
          registerBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              registerModal.style.display = "flex";
            });
          });
      
          // Close modals
          closeModalBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              loginModal.style.display = "none";
              registerModal.style.display = "none";
            });
          });
      
          // Switch between modals
          switchToRegister.addEventListener("click", function (e) {
            e.preventDefault();
            loginModal.style.display = "none";
            registerModal.style.display = "flex";
          });
      
          switchToLogin.addEventListener("click", function (e) {
            e.preventDefault();
            registerModal.style.display = "none";
            loginModal.style.display = "flex";
          });
      
          // Close modal when clicking outside
          window.addEventListener("click", function (e) {
            if (e.target === loginModal) {
              loginModal.style.display = "none";
            }
            if (e.target === registerModal) {
              registerModal.style.display = "none";
            }
          });
      
          // Handle login form submission
loginForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const email = document.getElementById("login-email").value;
  const password = document.getElementById("login-password").value;

  // Send login data to login_process.php using fetch
  fetch("login_process.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ email: email, password: password }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Login successful
        // Show success message
        const successModal = document.createElement("div");
        successModal.className =
          "fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50";
        successModal.innerHTML = `
          <div class="bg-white p-5 rounded-lg max-w-xs w-full text-center">
            <div class="w-16 h-16 mx-auto flex items-center justify-center bg-green-100 rounded-full text-green-500 mb-4">
              <i class="ri-check-line ri-2x"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Login Successful!</h3>
            <p class="text-gray-600 mb-4">Welcome back to Libmanan Food.</p>
            <button class="w-full bg-primary text-white py-2 rounded-button">Continue</button>
          </div>
        `;
        document.body.appendChild(successModal);

        // Redirect to user.php after 2 seconds
        setTimeout(() => {
          document.body.removeChild(successModal);
          loginModal.style.display = "none";
          window.location.href = "users/user.php"; // Redirect to user page
        }, 2000);
      } else {
        // Login failed
        // Show error message
        alert("Login failed: " + data.message);
      }
    })
    .catch((error) => {
      // Handle network errors
      console.error("Network error:", error);
      alert("Network error: Could not connect to the server.");
    });
});
      
          // Handle register form submission
            registerForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  const name = document.getElementById("register-name").value.trim();
  const email = document.getElementById("register-email").value.trim();
  const phone = document.getElementById("register-phone").value.trim();
  const password = document.getElementById("register-password").value;
  const confirmPassword = document.getElementById("register-confirm-password").value;

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  const userData = { name, email, phone_number: phone, password, user_type: "user" };

  try {
    const response = await fetch("register.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(userData),
    });

    const result = await response.json();

    if (result.success) {
      // Show success modal as you already do
      const successModal = document.createElement("div");
      successModal.className =
        "fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50";
      successModal.innerHTML = `
        <div class="bg-white p-5 rounded-lg max-w-xs w-full text-center">
          <div class="w-16 h-16 mx-auto flex items-center justify-center bg-green-100 rounded-full text-green-500 mb-4">
            <i class="ri-check-line ri-2x"></i>
          </div>
          <h3 class="text-lg font-semibold mb-2">Registration Successful!</h3>
          <p class="text-gray-600 mb-4">Welcome to Libmanan Food. Your account has been created.</p>
          <button class="w-full bg-primary text-white py-2 rounded-button">Continue</button>
        </div>
      `;
      document.body.appendChild(successModal);

      setTimeout(() => {
        document.body.removeChild(successModal);
        registerModal.style.display = "none"; // Close registration modal
        loginModal.style.display = "flex"; // Show login modal
      }, 2000);
    } else {
      alert("Error: " + result.message);
    }
  } catch (error) {
    alert("Failed to register. Please try again.");
    console.error(error);
  }
});

        });

        //explore button event listener
        document.addEventListener('DOMContentLoaded', function() {
        const exploreButton = document.getElementById('exploreBusinessesButton');
        const loginModal = document.getElementById('login-modal');

  exploreButton.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior if any
    loginModal.style.display = 'block'; // Show the modal
  });
});


//get full access button event listener
document.addEventListener('DOMContentLoaded', function() {
  const exploreButton = document.getElementById('exploreBusinessesButton');
  const getFullAccessButton = document.getElementById('getFullAccessButton');
  const loginModal = document.getElementById('login-modal');

  function showLoginModal() {
    loginModal.style.display = 'block';
  }

  exploreButton.addEventListener('click', function(event) {
    event.preventDefault();
    showLoginModal();
  });

  getFullAccessButton.addEventListener('click', function(event) {
    event.preventDefault();
    showLoginModal();
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const viewDetailsButton = document.getElementById('viewDetailsButton');
  const notification = document.getElementById('notification');
  const notificationLoginButton = document.getElementById('notificationLoginButton');
  const loginModal = document.getElementById('login-modal');
  const closeNotificationButton = document.querySelector('.close-notification'); //Improved selector
  const viewAllBusinessesButton = document.getElementById('viewAllBusinessesButton');
  viewAllBusinessesButton.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior if any
    loginModal.style.display = 'flex'; // Show the login modal
  });


  function showLoginModal() {
    loginModal.style.display = 'block';
  }

  viewDetailsButton.addEventListener('click', function(event) {
    event.preventDefault();
    notification.classList.add('show');
  });

  notificationLoginButton.addEventListener('click', function(event) {
    event.preventDefault();
    showLoginModal();
    notification.classList.remove('show');
  });

  closeNotificationButton.addEventListener('click', function() {
    notification.classList.remove('show');
  });
});


      </script>
      
      
  </body>
</html>
