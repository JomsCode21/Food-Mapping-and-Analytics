<?php
session_start();
if (file_exists('db_con.php')) {
    include 'db_con.php';
} else {
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Libmanan Food - Taste the Tradition</title>
    
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { 
                primary: "#A80000", 
                primaryHover: "#8a0000",
                secondary: "#FF6B00" 
            },
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
            fontFamily: {
                pacific: ['Pacifico', 'cursive'],
                sans: ['Inter', 'sans-serif'],
            }
          },
        },
      };
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <link rel="stylesheet" href="vendors/css/style.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      body {
        font-family: 'Inter', sans-serif;
        scroll-behavior: smooth;
      }
      
      /* Custom Scrollbar */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-track {
        background: #f1f1f1; 
      }
      ::-webkit-scrollbar-thumb {
        background: #A80000; 
        border-radius: 4px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #8a0000; 
      }

      .modal-content {
        background-color: white;
        border-radius: 12px;
        max-width: 95%;
        width: 400px; /* Slightly wider for better spacing */
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: modalFadeIn 0.3s ease-out;
      }

      @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
      }

      .login-modal, .register-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 50;
        align-items: center;
        justify-content: center;
      }

      #register-business-warning-modal {
        z-index: 1000 !important;
      }
      
      /* Card Hover Effects */
      .business-card:hover .card-img {
        transform: scale(1.1);
      }
      
      .floating-icon {
        animation: float 3s ease-in-out infinite;
      }
      
      @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
      }

      .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #fff;
            border-left: 4px solid #A80000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 16px;
            border-radius: 8px;
            z-index: 1000;
            transform: translateX(150%);
            transition: transform 0.3s ease-in-out;
            max-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .close-notification {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
    </style>
  </head>
  <body class="bg-gray-50 text-gray-800 flex-col min-h-screen">
    
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md text-gray-800 shadow-sm z-40 transition-all duration-300">
      <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
        <div class="flex items-center group cursor-pointer">
          <h1 class="font-pacific text-2xl text-primary transform transition-transform group-hover:scale-105">
            Taste<span class="text-gray-800">Libmanan</span>
          </h1>
        </div>

        <div class="flex items-center gap-4">
          <button
            id="login-btn"
            class="inline-flex items-center justify-center h-10 px-4 md:px-6 border-2 border-primary bg-transparent text-primary rounded-full text-xs md:text-sm font-semibold transition-all duration-300 hover:bg-primary hover:text-white hover:shadow-md active:scale-95 whitespace-nowrap"
            type="button"
          >
            Login
          </button>

          <button
            id="register-btn"
            class="inline-flex items-center justify-center h-10 px-4 md:px-6 border-2 border-primary bg-primary text-white rounded-full text-xs md:text-sm font-semibold transition-all duration-300 hover:bg-primaryHover hover:border-primaryHover hover:shadow-md active:scale-95 shadow-red-200 whitespace-nowrap"
            type="button"
          >
            Create Account
          </button>
        </div>
      </div>
    </nav>

    <main class="pt-16 pb-16 overflow-x-hidden">
        
        <section class="hero-section relative bg-gradient-to-br from-red-50 via-orange-50 to-white pt-10 pb-16 lg:pt-20 lg:pb-24">
          <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-primary/5 blur-3xl"></div>
          
          <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-10">
                <div class="hero-text lg:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                  <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider text-orange-600 uppercase bg-orange-100 rounded-full">
                    Discover Local Flavors
                  </div>
                  <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-gray-900">
                    Your Guide to<br />Libmanan's
                    <span class="text-primary relative inline-block">
                        Culinary Delights
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-yellow-400 opacity-60" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.00025 6.99997C25.7509 2.486 132.492 -3.02334 198.006 4.99986" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    </span>
                  </h1>
                  <p class="text-gray-600 text-lg mb-8 max-w-lg leading-relaxed">
                    Discover the best local food businesses, explore authentic cuisines,
                    and connect with the vibrant food community in Libmanan.
                  </p>              
                  <div class="flex flex-col sm:flex-row gap-4">
                    <button id="exploreBusinessesButton" class="bg-primary hover:bg-primaryHover text-white px-8 py-3.5 rounded-full font-semibold text-lg transition-all duration-300 shadow-lg shadow-red-200 transform hover:-translate-y-1">
                      Explore Businesses
                    </button>
                    <button id="registerBusinessBtn" class="bg-white text-gray-700 hover:text-primary px-8 py-3.5 rounded-full font-semibold text-lg border border-gray-200 hover:border-primary transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1" type="button">
                      Register Business
                    </button>
                  </div>
                </div>
                <div class="lg:w-1/2 relative hidden lg:block" data-aos="fade-left" data-aos-duration="1000">
                  <?php
                    if ($conn) {
                      $featured_img_sql = "SELECT f.*, 
                            (SELECT AVG(rating) FROM reviews WHERE fbowner_id = f.fbowner_id) as avg_rating 
                            FROM fb_owner f 
                            WHERE f.activation = 'Active' 
                            ORDER BY avg_rating DESC 
                            LIMIT 1";

                      $featured_img_result = mysqli_query($conn, $featured_img_sql);

                      if (mysqli_num_rows($featured_img_result) > 0) {
                        while ($row = mysqli_fetch_assoc($featured_img_result)) {
                          $featured_photo = $row['fb_photo'];
                          $clean_featured_photo = str_replace('../', '', $featured_photo);
                          $clean_featured_photo = str_replace('./', '', $clean_featured_photo);
                          $featured_img = !empty($clean_featured_photo) ? $clean_featured_photo : 'vendors/imgsource/default.jpg';

                          $featured_business_name = $row['fb_name'];
                        }
                      }
                    }
                  ?>
                    <div class="relative w-full h-[400px] rounded-2xl overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition-all duration-500">
                         <img src="<?php echo htmlspecialchars($featured_img) ?>" alt="Libmanan Food" class="w-full h-full object-cover">
                         <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                         <div class="absolute bottom-6 left-6 text-white">
                             <p class="font-pacific text-2xl">Featuring: <?php echo htmlspecialchars($featured_business_name) ?></p>
                         </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
       
        <section class="px-4 py-12 bg-white">
          <div class="max-w-6xl mx-auto">
              <div class="text-center mb-10" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-3">
                  Discover <span class="text-primary">Food Places</span> Near You
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                  Explore our interactive map to find the best food spots in
                  Libmanan. Sign up to access the full experience with reviews and recommendations.
                </p>
              </div>
              
              <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100" data-aos="zoom-in">
                <div class="relative mb-6 group max-w-lg mx-auto">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="ri-search-line text-lg"></i>
                  </div>
                  <input
                    type="text"
                    placeholder="Search for food places..."
                    class="w-full pl-10 pr-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all cursor-not-allowed text-sm"
                    disabled
                  />
                  <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-max bg-gray-800 text-white text-xs rounded-md px-3 py-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none shadow-lg">
                    Login required to search
                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 transform rotate-45"></div>
                  </div>
                </div>
                  
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/4 space-y-6">
                        <div>
                          <p class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-3">Categories</p>
                          <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 text-xs font-medium bg-red-50 text-primary rounded-full border border-red-100">Restaurants</span>
                            <span class="px-3 py-1 text-xs font-medium bg-orange-50 text-secondary rounded-full border border-orange-100">Cafes</span>
                            <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Street Food</span>
                            <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Bakeries</span>
                          </div>
                        </div>
                        <div>
                          <p class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-3">Popular Locations</p>
                          <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors cursor-pointer">
                              <i class="ri-map-pin-fill mr-2 text-primary"></i>
                              <span>Centro, Libmanan</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors cursor-pointer">
                              <i class="ri-map-pin-fill mr-2 text-primary"></i>
                              <span>San Isidro, Libmanan</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors cursor-pointer">
                              <i class="ri-map-pin-fill mr-2 text-primary"></i>
                              <span>Bagumbayan, Libmanan</span>
                            </div>
                          </div>
                        </div>
                    </div>
          
                    <div class="md:w-3/4 relative rounded-xl overflow-hidden h-[400px] border border-gray-200 shadow-inner group"> 
                      <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124061.10759452378!2d122.90189315577693!3d13.663259827099685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a21886a19dfc3f%3A0x91fb88e544c926cc!2sLibmanan%2C%20Camarines%20Sur!5e0!3m2!1sen!2sph!4v1745640028294!5m2!1sen!2sph"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="absolute inset-0 w-full h-full filter blur-[2px] group-hover:blur-[1px] transition-all duration-500">
                      </iframe>
        
                      <div class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-sm">
                        <div class="text-center p-6 bg-white rounded-2xl shadow-2xl max-w-sm transform transition-transform hover:scale-105">
                            <div class="w-12 h-12 bg-red-100 text-primary rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ri-lock-2-line text-xl"></i>
                            </div>
                          <h3 class="text-lg font-bold mb-2 text-gray-900">
                            Unlock Interactive Map
                          </h3>
                          <p class="text-sm text-gray-500 mb-5 leading-relaxed">
                            Sign up to access our interactive map with all food businesses, complete with reviews and directions!
                          </p>
                          <button id="getFullAccessButton" class="w-full bg-primary hover:bg-primaryHover text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md">
                            Get Full Access
                          </button>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
          </div>
        </section>

        <section class="px-4 py-16 bg-gray-50">
          <div class="max-w-7xl mx-auto">
              <div class="flex flex-col md:flex-row justify-between items-end mb-10 px-2" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Featured <span class="text-primary">Businesses</span>
                    </h2>
                    <p class="text-gray-600 text-base">
                    Explore some of the most beloved and highest-rated food establishments in Libmanan.
                    </p>
                </div>
                <button id="viewAllBusinessesButton" class="hidden md:block text-primary font-semibold text-sm hover:underline mt-4 md:mt-0">
                    View All Businesses <i class="ri-arrow-right-line ml-1 align-bottom"></i>
                </button>  
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                  if($conn) {
                    // Fetch active businesses, sorted by highest rating first
                    $sql = "SELECT f.*, 
                            (SELECT AVG(rating) FROM reviews WHERE fbowner_id = f.fbowner_id) as avg_rating 
                            FROM fb_owner f 
                            WHERE f.activation = 'Active' 
                            ORDER BY avg_rating DESC 
                            LIMIT 4";
                    
                    $result = mysqli_query($conn, $sql);
                  
                    // Fallback if reviews table doesn't exist yet
                    if(!$result) {
                        $sql = "SELECT *, 0 as avg_rating FROM fb_owner WHERE activation = 'Active' LIMIT 4";
                        $result = mysqli_query($conn, $sql);
                    }
                  
                    if (mysqli_num_rows($result) > 0) {
                        $delay = 0;
                        while($row = mysqli_fetch_assoc($result)) {
                             $db_photo = $row['fb_photo'];
                             $clean_photo = str_replace('../', '', $db_photo);
                             $clean_photo = str_replace('./', '', $clean_photo);
                             $img = !empty($clean_photo) ? $clean_photo : 'vendors/imgsource/default.jpg';
                        
                             $rating = $row['avg_rating'] ? number_format($row['avg_rating'], 1) : 'N/A';
                             $category = !empty($row['fb_type']) ? $row['fb_type'] : 'Food Business';
                             $desc = !empty($row['fb_description']) ? substr($row['fb_description'], 0, 20) . '...' : 'Delicious food';
                             $hours = !empty($row['fb_operating_hours']) ? $row['fb_operating_hours'] : 'See details';
                ?>
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group business-card overflow-hidden border border-gray-100" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                  <div class="h-48 overflow-hidden relative">
                    <img src="<?php echo htmlspecialchars($img); ?>" 
                         onerror="this.src='vendors/imgsource/default.jpg'" 
                         alt="<?php echo html_entity_decode($row['fb_name']); ?>" 
                         class="w-full h-full object-cover object-center card-img transition-transform duration-700"/>
                        
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm rounded-lg px-2 py-1 flex items-center shadow-sm">
                      <i class="ri-star-fill text-yellow-400 text-sm"></i>
                      <span class="ml-1 text-xs font-bold text-gray-800"><?php echo $rating; ?></span>
                    </div>
                  </div>
                  <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-primary transition-colors truncate"><?php echo html_entity_decode($row['fb_name']); ?></h3>
                    <p class="text-xs text-gray-500 font-medium mb-3 uppercase tracking-wide truncate"><?php echo htmlspecialchars($category . ' • ' . $desc); ?></p>
                        
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600 truncate">
                        <i class="ri-map-pin-line mr-2 text-secondary"></i>
                        <span class="truncate"><?php echo htmlspecialchars($row['fb_address']); ?></span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                        <i class="ri-time-line mr-2 text-secondary"></i>
                        <span><?php echo htmlspecialchars($hours); ?></span>
                        </div>
                    </div>
                        
                    <button class="viewDetailsGeneric w-full bg-gray-50 hover:bg-primary text-primary hover:text-white py-2.5 rounded-lg font-medium transition-all duration-300 border border-gray-200 hover:border-primary">
                      View Details
                    </button>
                  </div>
                </div>
                <?php 
                        $delay += 100; 
                        }
                    } else {
                        echo '<p class="col-span-4 text-center text-gray-500">No active businesses found.</p>';
                    }
                  } else {
                    echo '<p class="col-span-4 text-center text-red-500">Database connection error.</p>';
                  }
                ?>
              </div>
              
              <div id="notification" class="notification hidden">
                  <div class="notification-content">
                    <button class="close-notification">×</button>
                    <p class="text-sm text-gray-700">You'll need to log in first to have access.</p>
                    <div class="flex justify-end mt-2">
                        <button id="notificationLoginButton" class="bg-primary text-white text-xs py-1.5 px-4 rounded shadow-sm hover:bg-primaryHover">Login</button>
                    </div>
                  </div>
              </div>

              <div class="text-center mt-8 md:hidden">
                <button class="text-primary font-medium text-sm border border-primary px-6 py-2.5 rounded-full hover:bg-primary hover:text-white transition-colors duration-300 w-full">
                  View All Businesses
                </button>  
              </div>
          </div>
        </section>

        <section class="px-4 py-16 bg-white overflow-hidden">
          <div class="max-w-5xl mx-auto">
              <h2 class="text-2xl font-bold mb-10 text-center" data-aos="fade-down">Popular Categories</h2>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="0">
                  <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-red-50 mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg flex items-center justify-center">
                    <img
                      src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Filipino%20cuisine%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=4&orientation=squarish"
                      alt="Filipino Cuisine"
                      class="w-16 h-16 object-cover floating-icon"
                    />
                  </div>
                  <p class="font-semibold text-gray-700 group-hover:text-primary transition-colors">Bakeries</p>
                </div>
                <div class="text-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="100">
                  <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-orange-50 mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg flex items-center justify-center">
                    <img
                      src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Fast%20food%20burger%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=5&orientation=squarish"
                      alt="Fast Food"
                      class="w-16 h-16 object-cover floating-icon" style="animation-delay: 0.5s;"
                    />
                  </div>
                  <p class="font-semibold text-gray-700 group-hover:text-primary transition-colors">Fast Food</p>
                </div>
                <div class="text-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="200">
                  <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-yellow-50 mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg flex items-center justify-center">
                    <img
                      src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Coffee%20cup%20with%20steam%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=6&orientation=squarish"
                      alt="Cafes"
                      class="w-16 h-16 object-cover floating-icon" style="animation-delay: 1s;"
                    />
                  </div>
                  <p class="font-semibold text-gray-700 group-hover:text-primary transition-colors">Cafes</p>
                </div>
                <div class="text-center group cursor-pointer" data-aos="zoom-in" data-aos-delay="300">
                  <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-green-50 mb-4 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg flex items-center justify-center">
                    <img
                      src="https://readdy.ai/api/search-image?query=icon%2C%203D%20cartoon%2C%20Street%20food%20stall%2C%20the%20icon%20should%20take%20up%2070%25%20of%20the%20frame%2C%20vibrant%20colors%20with%20soft%20gradients%2C%20minimalist%20design%2C%20smooth%20rounded%20shapes%2C%20subtle%20shading%2C%20no%20outlines%2C%20centered%20composition%2C%20isolated%20on%20white%20background%2C%20playful%20and%20friendly%20aesthetic%2C%20isometric%20perspective%2C%20high%20detail%20quality%2C%20clean%20and%20modern%20look%2C%20single%20object%20focus&width=64&height=64&seq=7&orientation=squarish"
                      alt="Street Food"
                      class="w-16 h-16 object-cover floating-icon" style="animation-delay: 1.5s;"
                    />
                  </div>
                  <p class="font-semibold text-gray-700 group-hover:text-primary transition-colors">Restaurants</p>
                </div>
              </div>
          </div>
        </section>

        <section class="px-4 py-16 bg-gray-50">
          <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center">What Our Users Say</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden mr-4 ring-2 ring-primary ring-offset-2">
                    <img src="vendors/imgsource/dale.jpg" alt="User" class="w-full h-full object-cover"/>
                    </div>
                    <div>
                    <p class="font-bold text-gray-900">Dale Mar</p>
                    <div class="flex text-yellow-400 text-sm">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                    </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "The TasteLibManan website excels in providing safe and secure food business locations, giving users confidence and convenience with every search."
                </p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden mr-4 ring-2 ring-primary ring-offset-2">
                    <img src="vendors/imgsource/edr.jpg" alt="User" class="w-full h-full object-cover"/>
                    </div>
                    <div>
                    <p class="font-bold text-gray-900">Alex Edrian</p>
                    <div class="flex text-yellow-400 text-sm">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-half-fill"></i>
                    </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "TasteLibManan stands out for its reliable and secure platform that helps users easily find trusted food business locations."
                </p>
                </div>
            </div>
          </div>
        </section>

        <section class="px-4 py-16 bg-white">
            <div class="max-w-3xl mx-auto bg-gradient-to-r from-primary to-red-700 rounded-3xl p-8 md:p-12 text-center text-white shadow-2xl" data-aos="zoom-in">
                <h2 class="text-3xl font-bold mb-4">Ready to Explore?</h2>
                <p class="text-red-100 mb-8 max-w-lg mx-auto">
                Join our growing community to discover the best food spots in Libmanan. Sign up now for exclusive access.
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <button
                    id="cta-login-btn"
                    class="bg-white text-primary px-8 py-3 rounded-full font-bold shadow-lg transition-transform transform hover:-translate-y-1 hover:shadow-xl"
                >
                    Login Now
                </button>
                <button
                    id="cta-register-btn"
                    class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-bold transition-transform transform hover:-translate-y-1 hover:bg-white/10"
                >
                    Create Account
                </button>
                </div>
            </div>
        </section>

        <footer class="bg-primary text-white py-10 mt-auto border-t border-red-800">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
                        
            <h1 class="font-pacific text-3xl mb-6">TasteLibmanan</h1>
                        
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
    </main>
    
    <div id="aboutModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm">
      <div class="bg-white flex-1 overflow-y-auto p-8 relative shadow-2xl rounded-xl mx-4 my-6 md:mx-12 md:my-10 max-w-4xl w-full border border-gray-200">
        <button id="closeAboutModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors">×</button>
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

    <div id="contactModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm">
      <div class="bg-white overflow-y-auto p-8 relative shadow-2xl rounded-xl mx-4 my-6 max-w-2xl w-full">
        <button id="closeContactModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors">×</button>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-primary">Contact Us</h2>
        <div class="space-y-6 text-gray-700">
          <p class="text-center text-base">We'd love to hear from you! Reach out to us through any of the following channels:</p>
          <div class="bg-gray-50 p-6 rounded-xl space-y-4">
            <p class="flex items-center"><i class="ri-map-pin-line mr-3 text-primary"></i> <strong>Address:</strong> Municipal Hall, Libmanan, Camarines Sur, Philippines</p>
            <p class="flex items-center"><i class="ri-phone-line mr-3 text-primary"></i> <strong>Phone:</strong> (054) 123-4567</p>
            <p class="flex items-center"><i class="ri-mail-line mr-3 text-primary"></i> <strong>Email:</strong> tastelibmanan@gmail.com</p>
            <p class="flex items-center"><i class="ri-time-line mr-3 text-primary"></i> <strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM (excluding holidays)</p>
          </div>
        </div>
      </div>
    </div>

    <div id="privacyModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex flex-col items-center justify-center backdrop-blur-sm">
      <div class="bg-white overflow-y-auto p-8 relative shadow-2xl rounded-xl mx-4 my-6 max-w-3xl w-full">
        <button id="closePrivacyModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors">×</button>
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
    
    <div id="login-modal" class="login-modal">
      <div class="modal-content p-8">
          <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Login</h2>
              <button class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer text-gray-400 hover:text-gray-700 transition-colors">
                  <i class="ri-close-line ri-xl"></i>
              </button>
          </div>
          <form id="login-form">
              <div class="mb-5">
                  <label for="login-email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                  <input type="email" id="login-email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                      placeholder="Enter your email" required />
              </div>
              <div class="mb-5 relative">
                  <label for="login-password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                  <input type="password" id="login-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg pr-10 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                    placeholder="Enter your password"
                    required />
                    <span id="toggle-password-visibility" class="absolute top-8 right-3 cursor-pointer text-gray-400 hover:text-gray-600 transition-colors" >
                    <i id="eye-icon" class="ri-eye-off-line text-lg"></i>
                  </span>
              </div>
              <div class="flex justify-between items-center mb-6">
                  <div class="flex items-center">
                      <input type="checkbox" id="remember-me" class="mr-2 rounded text-primary focus:ring-primary" />
                      <label for="remember-me" class="text-sm text-gray-600">Remember me</label>
                  </div>
                  <a href="#" id="forgot-password-link" class="text-sm text-primary hover:underline font-medium">Forgot password?</a>
              </div>
              <div id="login-error-message" class="text-red-600 text-sm mb-4 text-center bg-red-50 p-2 rounded hidden"></div>
              <button type="submit"
                  class="w-full bg-primary hover:bg-primaryHover text-white py-3 rounded-lg font-bold shadow-md transition-all duration-300 transform active:scale-95">
                  Login
              </button>
              <div class="mt-6 text-center">
                  <p class="text-sm text-gray-600">
                      Don't have an account?
                      <a href="#" id="switch-to-register" class="text-primary font-bold hover:underline">Create Account</a>
                  </p>
              </div>
          </form>
      </div>
    </div>

    <div id="forgot-password-modal" class="login-modal hidden fixed inset-0 z-50 bg-black bg-opacity-50 justify-center items-center">
      <div class="modal-content bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold">Recover Account</h2>
          <button class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer text-gray-400 hover:text-gray-600" id="close-forgot-password-modal">
            <i class="ri-close-line ri-lg"></i>
          </button>
        </div>

        <form id="forgot-password-form">
          <div class="mb-4">
            <label for="forgot-password-email-phone" class="block text-sm font-medium text-gray-700 mb-1">
              Email Address
            </label>
            <input type="text" id="forgot-password-email-phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary outline-none" placeholder="Enter your email" required />
          </div>
          <button type="submit" class="w-full bg-primary hover:bg-primaryHover text-white py-2.5 rounded-lg font-bold transition-all">Recover Account</button>
        </form>

        <form id="otp-verification-form" class="hidden mt-4">
          <div class="mb-4">
            <label for="otp-code" class="block text-sm font-medium text-gray-700 mb-1">Enter OTP sent to your email</label>
            <input type="text" id="otp-code" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-center tracking-widest font-bold" placeholder="Enter OTP" required />
          </div>
          <button type="submit" class="w-full bg-primary hover:bg-primaryHover text-white py-2.5 rounded-lg font-bold transition-all">Verify OTP</button>
        </form>

        <form id="change-password-form" class="hidden mt-4">
          <div class="mb-4">
            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" id="new-password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Enter new password" required />
          </div>
          <div class="mb-4">
            <label for="confirm-new-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input type="password" id="confirm-new-password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Confirm new password" required />
          </div>
          <button type="submit" class="w-full bg-primary hover:bg-primaryHover text-white py-2.5 rounded-lg font-bold transition-all">Change Password</button>
        </form>

        <div id="forgot-password-message" class="text-center text-sm mt-4 text-gray-600 min-h-[20px]"></div>
      </div>
    </div>

  <div id="register-modal" class="register-modal">
    <div class="modal-content p-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
        <button class="close-modal w-8 h-8 flex items-center justify-center cursor-pointer text-gray-400 hover:text-gray-700 transition-colors">
          <i class="ri-close-line ri-xl"></i>
        </button>
      </div>
      <form id="register-form">
        <div class="mb-4">
          <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input type="text" id="register-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary outline-none" placeholder="Enter your full name" required/>
        </div>
        <div class="mb-4">
          <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" id="register-email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary outline-none" placeholder="Enter your VALID Email" required/>
           <div id="register-email-error" class="text-red-600 text-xs mt-1"></div>
        </div>
        <div class="mb-4">
          <label for="register-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (Optional)</label>
          <input type="tel" id="register-phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary outline-none" placeholder="Enter your phone number"/>
        </div>
        
        <div class="mb-4">
          <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="register-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg pr-10 focus:ring-primary focus:border-primary outline-none" placeholder="Create a password (8–16 chars)" minlength="8" maxlength="16" pattern=".{8,16}" required />
            <span id="toggle-register-password" class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
              <i id="register-eye-icon" class="ri-eye-off-line"></i>
            </span>
          </div>
        </div>

        <div class="mb-4">
          <label for="register-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <div class="relative">
            <input type="password" id="register-confirm-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg pr-10 focus:ring-primary focus:border-primary outline-none" placeholder="Confirm your password" minlength="8" maxlength="16" pattern=".{8,16}" required />
            <span id="toggle-register-confirm-password" class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
              <i id="register-confirm-eye-icon" class="ri-eye-off-line"></i>
            </span>
          </div>
        </div>

        <div class="mb-6">
          <div class="flex items-start">
            <input type="checkbox" id="terms" class="mt-1 mr-2 rounded text-primary focus:ring-primary" required />
            <label for="terms" class="text-sm text-gray-600">I agree to the <a href="#" id="tOs" class="text-primary hover:underline">Terms of Service</a> and <a href="#" id="pP" class="text-primary hover:underline">Privacy Policy</a></label>
          </div>
        </div>
        <div id="register-error-message" class="text-red-600 text-sm mb-4 text-center"></div>
        <button type="submit" class="w-full bg-primary hover:bg-primaryHover text-white py-3 rounded-lg font-bold shadow-md transition-all active:scale-95">
          Create Account
        </button>
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <a href="#" id="switch-to-login" class="text-primary font-bold hover:underline">Login</a>
          </p>
        </div>
      </form>
    </div>
  </div>
    
  <div id="register-business-warning-modal" class="login-modal">
    <div class="modal-content p-6">
      <div class="flex flex-col items-center">
        <div class="w-16 h-16 mx-auto flex items-center justify-center bg-yellow-100 rounded-full text-yellow-500 mb-4 animate-bounce">
          <i class="ri-error-warning-line ri-2x"></i>
        </div>
        <h2 class="text-lg font-bold mb-2 text-center text-gray-900">Create an Account First</h2>
        <p class="text-gray-600 mb-6 text-center">
          You need to create an account before registering your business.
        </p>
        <div class="flex justify-center gap-4 w-full">
          <button id="register-business-warning-ok" class="bg-primary hover:bg-primaryHover text-white py-2.5 px-6 rounded-lg font-medium transition-colors w-1/2">OK</button>
          <button id="register-business-warning-cancel" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors w-1/2">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-8 relative">
      <h2 class="text-xl font-bold mb-4 text-center text-primary">📑 Terms and Conditions</h2>
      <div class="h-64 overflow-y-auto text-sm text-gray-700 leading-relaxed space-y-3 pr-2 custom-scrollbar">
        <p><strong>1. Acceptance of Terms</strong><br>By accessing or using Tastelibmanan, you confirm that you have read, understood, and agreed to these Terms and Conditions.</p>
        <p><strong>2. User Accounts</strong><br>You must provide accurate information. You are responsible for safeguarding your login credentials.</p>
        </div>
      <button id="closeTerms" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">×</button>
    </div>
  </div>

  <div id="pPModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-8 relative">
      <h2 class="text-xl font-bold mb-4 text-center text-primary">📑 Privacy Policy</h2>
      <div class="h-64 overflow-y-auto text-sm text-gray-700 leading-relaxed space-y-3 pr-2 custom-scrollbar">
        <p><strong>1. Account Data:</strong> Name, email, and password.</p>
        <p><strong>2. How We Use Information:</strong> To create accounts and provide mapping services.</p>
        </div>
      <button id="closePP" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">×</button>
    </div>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
        once: true, // Animation happens only once - while scrolling down
        offset: 100, // Offset (in px) from the original trigger point
        duration: 800, // Duration of animation
    });
  </script>

  <script>
      // ABOUT MODAL
const aboutModal = document.getElementById('aboutModal');
const about = document.getElementById('about');
const closeAboutModal = document.getElementById('closeAboutModal');

if(about && aboutModal) {
    about.addEventListener('click', (event) => {
    event.preventDefault(); // prevent page jump
    aboutModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    });

    closeAboutModal.addEventListener('click', () => {
    aboutModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    });
}


// CONTACT MODAL
const contactModal = document.getElementById('contactModal');
const contact = document.getElementById('contact');
const closeContactModal = document.getElementById('closeContactModal');

if(contact && contactModal) {
    contact.addEventListener('click', (e) => {
    e.preventDefault();
    contactModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    });

    closeContactModal.addEventListener('click', () => {
    contactModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    });
}


// PRIVACY POLICY MODAL
const privacyModal = document.getElementById('privacyModal');
const privacy = document.getElementById('privacy');
const closePrivacyModal = document.getElementById('closePrivacyModal');

if(privacy && privacyModal) {
    privacy.addEventListener('click', (e) => {
    e.preventDefault();
    privacyModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    });

    closePrivacyModal.addEventListener('click', () => {
    privacyModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    });
}
      
      
    const forgotPasswordLink = document.getElementById("forgot-password-link");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const closeForgotPasswordModal = document.getElementById("close-forgot-password-modal");
    const forgotPasswordForm = document.getElementById("forgot-password-form");
    const otpVerificationForm = document.getElementById("otp-verification-form");
    const changePasswordForm = document.getElementById("change-password-form");
    const forgotPasswordMessage = document.getElementById("forgot-password-message");
    const loginModal = document.getElementById("login-modal");

    let forgotEmail = "";
    let serverOtpToken = "";

    // Open modal
    if (forgotPasswordLink) {
      forgotPasswordLink.addEventListener("click", function (e) {
        e.preventDefault();
        loginModal?.style.setProperty("display", "none");
        forgotPasswordModal?.style.setProperty("display", "flex");
        forgotPasswordForm.classList.remove("hidden");
        otpVerificationForm.classList.add("hidden");
        changePasswordForm.classList.add("hidden");
        forgotPasswordMessage.textContent = "";
      });
    }

    // Close modal
    if (closeForgotPasswordModal) {
      closeForgotPasswordModal.addEventListener("click", function () {
        forgotPasswordModal?.style.setProperty("display", "none");
        loginModal?.style.setProperty("display", "flex");
      });
    }

    // Step 1: Send OTP
    if(forgotPasswordForm){
        forgotPasswordForm.addEventListener("submit", function (e) {
        e.preventDefault();
        forgotEmail = document.getElementById("forgot-password-email-phone").value.trim();
        forgotPasswordMessage.innerHTML = `
            <span class="inline-flex items-center font-semibold text-green-600">
            Sending OTP
            <span class="ml-2 flex space-x-1 text-green-600">
                <span class="animate-bounce text-xl" style="animation-delay:0s;">.</span>
                <span class="animate-bounce text-xl" style="animation-delay:0.2s;">.</span>
                <span class="animate-bounce text-xl" style="animation-delay:0.4s;">.</span>
            </span>
            </span>
        `;
        forgotPasswordMessage.classList.add("text-green-600", "font-semibold");
        fetch("forgot_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ step: "send_otp", email: forgotEmail })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
            forgotPasswordForm.classList.add("hidden");
            otpVerificationForm.classList.remove("hidden");
            // Animate "Sending OTP..." to "OTP sent to your email. Please check your inbox." with a check icon
            forgotPasswordMessage.innerHTML = `
                <span class="inline-flex items-center font-semibold text-green-600">
                OTP sent to your email. Please check your inbox.
                <span id="otp-check-icon" class="ml-2 opacity-0 transition-opacity duration-500">
                    <i class="ri-check-double-line text-green-500 text-xl"></i>
                </span>
                </span>
            `;
            forgotPasswordMessage.classList.add("text-green-600", "font-semibold");
            // Animate the check icon fade-in
            setTimeout(() => {
                const checkIcon = document.getElementById("otp-check-icon");
                if (checkIcon) checkIcon.classList.remove("opacity-0");
            }, 300);
            serverOtpToken = data.otp_token || "";
            } else {
            forgotPasswordMessage.textContent = data.message || "Failed to send OTP.";
            }
        })
        .catch(() => {
            forgotPasswordMessage.textContent = "Network error. Please try again.";
        });
        });
    }

    // Step 2: Verify OTP
    if(otpVerificationForm){
        otpVerificationForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const otp = document.getElementById("otp-code").value.trim();
        forgotPasswordMessage.textContent = "Verifying OTP...";
        fetch("forgot_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ step: "verify_otp", email: forgotEmail, otp: otp, otp_token: serverOtpToken })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
            otpVerificationForm.classList.add("hidden");
            changePasswordForm.classList.remove("hidden");
            forgotPasswordMessage.textContent = "OTP verified. Please enter your new password.";
            } else {
            forgotPasswordMessage.textContent = data.message || "Invalid OTP.";
            }
        })
        .catch(() => {
            forgotPasswordMessage.textContent = "Network error. Please try again.";
        });
        });
    }

    // Step 3: Change Password
    if(changePasswordForm){
        changePasswordForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const newPassword = document.getElementById("new-password").value;
        const confirmNewPassword = document.getElementById("confirm-new-password").value;
        if (newPassword !== confirmNewPassword) {
            forgotPasswordMessage.textContent = "Passwords do not match.";
            return;
        }
        forgotPasswordMessage.textContent = "Changing password...";
        fetch("forgot_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
            step: "change_password",
            email: forgotEmail,
            new_password: newPassword,
            otp_token: serverOtpToken
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
            changePasswordForm.classList.add("hidden");
            forgotPasswordMessage.textContent = "Password changed successfully! You can now log in.";
            setTimeout(() => {
                forgotPasswordModal.style.display = "none";
                loginModal.style.display = "flex";
            }, 2000);
            } else {
            forgotPasswordMessage.textContent = data.message || "Failed to change password.";
            }
        })
        .catch(() => {
            forgotPasswordMessage.textContent = "Network error. Please try again.";
        });
        });
    }

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
        const registerErrorMessage = document.getElementById("register-error-message");
        // Open login modal
        loginBtns.forEach((btn) => {
            if(btn){
                btn.addEventListener("click", function () {
                    loginModal.style.display = "flex";
                });
            }
        });
         // Show/hide password functionality
        const passwordInput = document.getElementById("login-password");
        const togglePassword = document.getElementById("toggle-password-visibility");
        const eyeIcon = document.getElementById("eye-icon");
        if (togglePassword && passwordInput && eyeIcon) {
          togglePassword.addEventListener("click", function () {
            if (passwordInput.type === "password") {
              passwordInput.type = "text";
              eyeIcon.classList.remove("ri-eye-off-line");
              eyeIcon.classList.add("ri-eye-line");
            } else {
              passwordInput.type = "password";
              eyeIcon.classList.remove("ri-eye-line");
              eyeIcon.classList.add("ri-eye-off-line");
            }
          });
        }
        // Open register modal
        registerBtns.forEach((btn) => {
            if(btn){
                btn.addEventListener("click", function () {
                    registerModal.style.display = "flex";
                });
            }
        });
      
        // Close modals
        closeModalBtns.forEach((btn) => {
          btn.addEventListener("click", function () {
            if(loginModal) loginModal.style.display = "none";
            if(registerModal) registerModal.style.display = "none";
          });
        });
      
        // Switch between modals
        if(switchToRegister){
            switchToRegister.addEventListener("click", function (e) {
            e.preventDefault();
            loginModal.style.display = "none";
            registerModal.style.display = "flex";
            });
        }
      
        if(switchToLogin){
            switchToLogin.addEventListener("click", function (e) {
            e.preventDefault();
            registerModal.style.display = "none";
            loginModal.style.display = "flex";
            });
        }
      
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
      if(loginForm){
        loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const email = document.getElementById("login-email").value;
        const password = document.getElementById("login-password").value;
        const errMsg = document.getElementById("login-error-message");

        // Send login data to login_process.php using fetch
            fetch("login_process.php", {
            method: "POST",
            headers: {
            "Content-Type": "application/json",
            },
            body: JSON.stringify({ email: email, password: password}),
        })
            .then((response) => response.json())
            .then((data) => {
            console.log(data); // Log the response for debugging


            if (data.success) {
              // Remove red borders and error messages
              document.getElementById("login-password").classList.remove("border-red-600");
              document.getElementById("login-password").classList.add("border-gray-300");
              if(errMsg) errMsg.classList.add("hidden");
              loginModal.style.display = "none";
                      
              // Fire the SweetAlert Notification
              Swal.fire({
                  icon: 'success',
                  title: 'Login Successful!',
                  text: 'Welcome to TasteLibmanan.',
                  showConfirmButton: false, // Hides the "OK" button
                  timer: 3000,              // Auto closes after 2 seconds
                  timerProgressBar: true,
                  didOpen: () => {
                      Swal.showLoading(); 
                  }
              }).then(() => {
                  
                  if (sessionStorage.getItem("registerBusinessFlow") === "1") {
                      sessionStorage.removeItem("registerBusinessFlow");
                      window.location.href = "FBregistration.php";
                  } else if (data.user_type === "user") {
                      window.location.href = "users/user.php";
                  } else if (data.user_type === "fb_owner") {
                      window.location.href = "fbusinessowner/fbusinessowner.php";
                  } else if (data.user_type === "admin") {
                      window.location.href = "admin_folder/bplo.php";
                  } else {
                      // Fallback
                      window.location.href = "index.php"; 
                  }
              });
              
            } else {
            // Login failed
            if(errMsg) {
                errMsg.textContent = data.message || "Incorrect Email or Password.";
                errMsg.classList.remove("hidden");
            }
            // Add red border to password input
            const passwordInput = document.getElementById("login-password");
            passwordInput.classList.remove("border-gray-300");
            passwordInput.classList.add("border-red-600");
        }
            })
            .catch((error) => {
            // Handle network errors
            console.error("Network error:", error);
            alert("Network error: Could not connect to the server.");
            });
        });
      }

              // Handle register form submission
    if(registerForm){
        registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const name = document.getElementById("register-name").value.trim();
        const email = document.getElementById("register-email").value.trim();
        const phone = document.getElementById("register-phone").value.trim();
        const password = document.getElementById("register-password").value;
        const confirmPassword = document.getElementById("register-confirm-password").value;

        
        // Remove previous error state
        const registerConfirmPasswordInput = document.getElementById("register-confirm-password");
        const registerConfirmPasswordError = document.getElementById("register-email-error"); 

        // Remove previous error message and border
        let confirmPasswordErrorDiv = document.getElementById("register-confirm-password-error");
        if (!confirmPasswordErrorDiv) {
            confirmPasswordErrorDiv = document.createElement("div");
            confirmPasswordErrorDiv.id = "register-confirm-password-error";
            confirmPasswordErrorDiv.className = "text-red-600 text-xs mt-1";
            registerConfirmPasswordInput.parentNode.appendChild(confirmPasswordErrorDiv);
        }
        confirmPasswordErrorDiv.textContent = "";
        registerConfirmPasswordInput.classList.remove("border-red-600");

        if (password !== confirmPassword) {
            confirmPasswordErrorDiv.textContent = "Passwords do not match!";
            registerConfirmPasswordInput.classList.remove("border-gray-300");
            registerConfirmPasswordInput.classList.add("border-red-600");
            return;
        } else {
            confirmPasswordErrorDiv.textContent = "";
            registerConfirmPasswordInput.classList.remove("border-red-600");
            registerConfirmPasswordInput.classList.add("border-gray-300");
        }
        const userData = { name, email, phone_number: phone, password, user_type: "user" };

        try {
            const response = await fetch("register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData),
            });

            const result = await response.json();
            console.log(result);

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
            // Get the email input and error div
                const registerEmailInput = document.getElementById("register-email");
                const registerEmailError = document.getElementById("register-email-error");

                // Reset previous error state
                registerEmailError.textContent = "";
                registerEmailInput.classList.remove("border-red-600");

                if (result.message && result.message.includes("already been used")) {
                // Show error below email input and highlight input
                registerEmailError.textContent = result.message;
                registerEmailInput.classList.remove("border-gray-300");
                registerEmailInput.classList.add("border-red-600");
                } else {
                // Show other errors in the general error message area
                registerErrorMessage.textContent = result.message || "Registration failed.";
                registerErrorMessage.classList.remove("text-green-600");
                registerErrorMessage.classList.add("text-red-600");
                }
            }
        } catch (error) {
            registerErrorMessage.textContent = "Failed to register. Please try again.";
            registerErrorMessage.classList.remove("text-green-600");
            registerErrorMessage.classList.add("text-red-600");
            console.error(error);
        }
        });
    }

            });

            const tosBtn = document.getElementById('tOs');
            if(tosBtn) {
                tosBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('termsModal').classList.remove('hidden');
                });
            }

            const closeTos = document.getElementById('closeTerms');
            if(closeTos){
                closeTos.addEventListener('click', function() {
                document.getElementById('termsModal').classList.add('hidden');
                });
            }

            const ppBtn = document.getElementById('pP');
            if(ppBtn){
                ppBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('pPModal').classList.remove('hidden');
                });
            }

            const closePP = document.getElementById('closePP');
            if(closePP){
                closePP.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('pPModal').classList.add('hidden');
                });
            }

            //explore button event listener
            document.addEventListener('DOMContentLoaded', function() {
            const exploreButton = document.getElementById('exploreBusinessesButton');
            const loginModal = document.getElementById('login-modal');

            if(exploreButton){
                exploreButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior if any
                if(loginModal) loginModal.style.display = 'flex'; // Show the modal
                });
            }
    });


    document.addEventListener('DOMContentLoaded', function () {
      // Login modal handlers
      const exploreButton = document.getElementById('exploreBusinessesButton');
      const getFullAccessButton = document.getElementById('getFullAccessButton');
      const loginModal = document.getElementById('login-modal');

      function showLoginModal() {
        if (loginModal) loginModal.style.display = 'flex';
      }

      if (exploreButton) {
        exploreButton.addEventListener('click', function (event) {
          event.preventDefault();
          showLoginModal();
        });
      }

      if (getFullAccessButton) {
        getFullAccessButton.addEventListener('click', function (event) {
          event.preventDefault();
          showLoginModal();
        });
      }

      // Notification modal handlers
      const viewDetailsButton = document.getElementById('viewDetailsButton');
      const notification = document.getElementById('notification');
      const notificationLoginButton = document.getElementById('notificationLoginButton');
      const closeNotificationButton = document.querySelector('.close-notification');
      const viewAllBusinessesButton = document.getElementById('viewAllBusinessesButton');

      if (viewAllBusinessesButton) {
        viewAllBusinessesButton.addEventListener('click', function (event) {
          event.preventDefault();
          showLoginModal();
        });
      }

      // Generic handler for all "View Details" buttons
      const detailButtons = document.querySelectorAll('.viewDetailsGeneric, #viewDetailsButton');
      detailButtons.forEach(btn => {
          btn.addEventListener('click', function(event){
              event.preventDefault();
              if(notification) notification.classList.add('show');
              // Auto hide after 3 seconds
              setTimeout(() => {
                if(notification) notification.classList.remove('show');
              }, 3000);
          });
      });


      if (notificationLoginButton) {
        notificationLoginButton.addEventListener('click', function (event) {
          event.preventDefault();
          showLoginModal();
          if (notification) notification.classList.remove('show');
        });
      }

      if (closeNotificationButton && notification) {
        closeNotificationButton.addEventListener('click', function () {
          notification.classList.remove('show');
        });
      }


    });
    document.addEventListener("DOMContentLoaded", function () {
    const registerBusinessBtn = document.getElementById("registerBusinessBtn");
    const registerBusinessWarningModal = document.getElementById("register-business-warning-modal");
    const registerModal = document.getElementById("register-modal");
    const loginModal = document.getElementById("login-modal");
    const okBtn = document.getElementById("register-business-warning-ok");
    const cancelBtn = document.getElementById("register-business-warning-cancel");

     // Defensive: check all elements exist
      if (!registerBusinessBtn || !registerBusinessWarningModal || !registerModal || !loginModal || !okBtn || !cancelBtn) {
        // console.error("Modal elements not found in DOM!");
        // return;
      }

    function isLoggedIn() {
      return false; // Assuming PHP session check isn't injected here via JS
    }


    if(registerBusinessBtn){
        registerBusinessBtn.addEventListener("click", function (e) {
        e.preventDefault();
        sessionStorage.setItem("registerBusinessFlow", "1");
        if (isLoggedIn()) {
            // User is logged in, go to business registration
            window.location.href = "FBregistration.php";
        } else {
            // User is not logged in, show login modal
            if(loginModal) loginModal.style.display = "flex";
        }
        });
    }

    if(okBtn){
        okBtn.addEventListener("click", function () {
            registerBusinessWarningModal.style.display = "none";
            registerModal.style.display = "flex";
        });
    }

    if(cancelBtn){
        cancelBtn.addEventListener("click", function () {
            registerBusinessWarningModal.style.display = "none";
        });
    }
    });  
        document.addEventListener("DOMContentLoaded", function () {
      // Password field
      const registerPasswordInput = document.getElementById("register-password");
      const toggleRegisterPassword = document.getElementById("toggle-register-password");
      const registerEyeIcon = document.getElementById("register-eye-icon");

      if (toggleRegisterPassword && registerPasswordInput && registerEyeIcon) {
        toggleRegisterPassword.addEventListener("click", function () {
          if (registerPasswordInput.type === "password") {
            registerPasswordInput.type = "text";
            registerEyeIcon.classList.remove("ri-eye-off-line");
            registerEyeIcon.classList.add("ri-eye-line");
          } else {
            registerPasswordInput.type = "password";
            registerEyeIcon.classList.remove("ri-eye-line");
            registerEyeIcon.classList.add("ri-eye-off-line");
          }
        });
      }

      // Confirm password field
      const registerConfirmPasswordInput = document.getElementById("register-confirm-password");
      const toggleRegisterConfirmPassword = document.getElementById("toggle-register-confirm-password");
      const registerConfirmEyeIcon = document.getElementById("register-confirm-eye-icon");

      if (toggleRegisterConfirmPassword && registerConfirmPasswordInput && registerConfirmEyeIcon) {
        toggleRegisterConfirmPassword.addEventListener("click", function () {
          if (registerConfirmPasswordInput.type === "password") {
            registerConfirmPasswordInput.type = "text";
            registerConfirmEyeIcon.classList.remove("ri-eye-off-line");
            registerConfirmEyeIcon.classList.add("ri-eye-line");
          } else {
            registerConfirmPasswordInput.type = "password";
            registerConfirmEyeIcon.classList.remove("ri-eye-line");
            registerConfirmEyeIcon.classList.add("ri-eye-off-line");
          }
        });
      }
    });      
  </script>
  </body>
</html>