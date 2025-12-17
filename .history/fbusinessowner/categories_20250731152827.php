<?php
require_once '../db_con.php';
  // Fetch all businesses
$result = $conn->query("SELECT DISTINCT user_id, fb_name, fb_type, fb_address, fb_photo 
                       FROM fb_owner 
                       WHERE fb_type IS NOT NULL 
                       GROUP BY user_id");
// Group businesses by type
$categories = [
    'Restaurant' => [],
    'Fastfood' => [],
    'Cafe' => [],
    'Bakery' => []
];

while ($row = $result->fetch_assoc()) {
    if (isset($categories[$row['fb_type']])) {
        $categories[$row['fb_type']][] = $row;
    }
}
$cat = isset($_GET['cat']) ? $_GET['cat'] : '';

$category_map = [
    'restaurants' => 'Restaurant',
    'fastfoods' => 'Fastfood',
    'cafes' => 'Cafe',
    'bakery' => 'Bakery'
];

$businesses = [];
if (isset($category_map[$cat])) {
    $type = $category_map[$cat];
    $stmt = $conn->prepare("SELECT DISTINCT fb_name, fb_address, fb_photo, user_id 
                           FROM fb_owner 
                           WHERE fb_type = ? 
                           GROUP BY user_id 
                           ORDER BY fb_name");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $businesses[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Load TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Custom Config -->
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

  <script defer>
    
  function getCategoryFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('cat');
  }

  function loadCategory() {
    const category = getCategoryFromUrl();
    const categories = {
      restaurants: {
        title: "Restaurants",
        
      },
      fastfoods: {
        title: "Fast Foods",
        
      },
      cafes: {
        title: "Cafes",
       
      },
      bakery: {
        title: "Bakeries",
        
      }
    };

    const data = categories[category];

    if (data) {
      document.getElementById('category-title').textContent = data.title;
      const container = document.getElementById('business-list');
      container.innerHTML = ""; // Clear previous content

      data.businesses.forEach(business => {
        const card = `
          <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <img src="${business.image}" alt="${business.name}" class="w-full h-32 object-cover">
            <div class="p-4">
              <h4 class="font-semibold text-lg">${business.name}<br>${business.rank ? business.rank : ''}</h4>
              <div class="flex items-center space-x-2 mt-2">
                <span class="text-yellow-500">★★★★★</span>
                <span class="text-gray-600">${business.location}</span>
              </div>
              <div class="flex justify-between items-center mt-3">
                <a href="businessdetails.html?name=${encodeURIComponent(business.name)}" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
                <!-- Heart Icon -->
                <button class="favorite-btn text-gray-600 hover:text-red-500">
                  <i class="ri-heart-line text-2xl"></i>
                </button>
              </div>
            </div>
          </div>
        `;
        container.innerHTML += card;
      });

      // Heart click functionality
      const heartButtons = document.querySelectorAll('.favorite-btn');
      heartButtons.forEach(button => {
        button.addEventListener('click', function () {
          const heartIcon = this.querySelector('i');
          if (heartIcon.classList.contains('ri-heart-line')) {
            heartIcon.classList.remove('ri-heart-line');
            heartIcon.classList.add('ri-heart-fill');
            heartIcon.style.color = 'red'; // Change color to red when filled
          } else {
            heartIcon.classList.remove('ri-heart-fill');
            heartIcon.classList.add('ri-heart-line');
            heartIcon.style.color = ''; // Revert color to default
          }
        });
      });

    } else {
      document.getElementById('category-title').textContent = "Please select a category icon above";
      document.getElementById('business-list').innerHTML = `<p class="text-center text-gray-600">No businesses found</p>`;
    }
  }
</script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />

   <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>

   <!-- Google Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com" />
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
   <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
 
   <!-- Remix Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
 </head>
 
<body class="bg-gray-100 px-6 md:px-12 min-h-screen py-8 flex flex-col"> 
    <nav class="bg-white shadow-md px-6 py-4 flex items-center justify-between no-margin">
        <h1 class="font-['Pacifico'] text-4xl text-primary">Taste<span class="text-gray-800">Libmanan</span></h1>
     
        <div class="flex items-center space-x-6">
          <!-- Desktop Links -->
          <div class="hidden md:flex space-x-6">
            <a href="user.html" class="text-gray-800 hover:text-primary">Home</a>
            <a href="categories.html" class="text-gray-800 hover:text-primary">Businesses</a>
            <a href="#" class="text-gray-800 hover:text-primary">About Us</a>
            <a href="landing.html" class="text-gray-800 hover:text-primary">Menus</a>
          </div>
     
          <!-- Hamburger Menu -->
          <div class="relative">
            <button id="hamburger-btn" class="text-gray-800 text-2xl focus:outline-none hover:text-red-500">
              ☰
            </button>
     
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 z-50 opacity-0 visibility-hidden transition-all duration-300 ease-in-out">
              <a href="user.html" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Home</a>
              <a href="categories.html" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Businesses</a>
              <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">About Us</a>
              <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Menus</a>
<a href="../logout.php" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white">Logout</a>            </div>
          </div>
        </div>
     </nav>
     <br>

    
     <a href="../users/user.php" class="text-gray-800 hover:text-primary">
      <i class="ri-arrow-left-line text-2xl" style="font-size: 40px;"></i>
  </a>

    <!-- Hamburger Menu Script -->
<script>
    const btn = document.getElementById('hamburger-btn');
    const menu = document.getElementById('mobile-menu');
    const menuItems = menu.querySelectorAll('a');

 
    btn.addEventListener('click', (event) => {
      event.stopPropagation();
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.remove('opacity-0', 'visibility-hidden');
        menu.classList.add('opacity-100', 'visibility-visible');
      } else {
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
  
    menuItems.forEach(item => {
      item.addEventListener('click', (event) => {
        event.stopPropagation();
        menu.classList.remove('opacity-100', 'visibility-visible');
        menu.classList.add('opacity-0', 'visibility-hidden');
        setTimeout(() => {
          menu.classList.add('hidden');
        }, 300);
      });
    });
  </script>
   
</head>
<body>
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-gray-800">Popular Categories</h2>
    <button onclick="showFavorites()" class="text-blue-600 hover:underline font-medium">View All Favorites</button>
  </div>
  

    <!-- Main page -->
<div class="grid grid-cols-4 gap-4">
<a href="categories.php?cat=restaurants"class="text-center transform transition-transform duration-300 hover:scale-150">
<div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
  <img src="../vendors/imgsource/restaurants.jpg" alt="Restaurants" class="w-full h-full object-cover">
</div>
<p class="text-xs">Restaurants</p>
</a>

<a href="categories.php?cat=fastfoods"class="text-center transform transition-transform duration-300 hover:scale-150">
<div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
  <img src="../vendors/imgsource/fastfoods.jpg" alt="Fast Foods" class="w-full h-full object-cover">
</div>
<p class="text-xs">Fast Foods</p>
</a>

<a href="categories.php?cat=cafes" class="text-center transform transition-transform duration-300 hover:scale-150">
<div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
  <img src="../vendors/imgsource/cafes.jpg" alt="Cafes" class="w-full h-full object-cover">
</div>
<p class="text-xs">Cafes</p>
</a>

<a href="categories.php?cat=bakery" class="text-center transform transition-transform duration-300 hover:scale-150">
<div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-gray-100 mb-2">
  <img src="../vendors/imgsource/bakeries.jpg" alt="Bakery" class="w-full h-full object-cover">
</div>
<p class="text-xs">Bakeries</p>
</a>
</div>
<br>
<!-- Search Bar -->
<div class="mb-6 max-w-lg mx-auto" style="margin-top: 15px; margin-bottom: -10px;">
  <div class="flex">
    <input
      type="text"
      id="search-bar"
      placeholder="Search businesses..."
      class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
      oninput="filterBusinesses()"
    />
    <button
      id="search-button"
      class="ml-2 px-4 py-2 bg-primary text-white font-medium rounded-md hover:scale-105 transition-transform duration-200"
    >
      Search
    </button>
  </div>
</div>


<br>
  <div class="text-center mb-8">
  <h1 id="category-title" class="text-3xl font-bold text-gray-800">
    <?php
      echo isset($category_map[$cat]) ? htmlspecialchars(ucfirst($cat)) : "Please select a category icon above";
    ?>
  </h1>
</div>

<div id="business-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
  <?php if (!empty($businesses)): ?>
    <?php foreach ($businesses as $index => $biz): ?>
      <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <img src="<?php echo htmlspecialchars($biz['fb_photo'] ?: '../imgsource/default.jpg'); ?>" alt="<?php echo htmlspecialchars($biz['fb_name']); ?>" class="w-full h-32 object-cover">
        <div class="p-4">
          <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($biz['fb_name']); ?><br>
            <span class="text-sm text-gray-500">Top <?php echo $index + 1; ?></span>
          </h4>
          <div class="flex items-center space-x-2 mt-2">
            <span class="text-yellow-500">★★★★★</span>
            <span class="text-gray-600"><?php echo htmlspecialchars($biz['fb_address']); ?></span>
          </div>
          <div class="flex justify-between items-center mt-3">
            <a href="businessdetails.php?user_id=<?php echo urlencode($biz['user_id']); ?>" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
            <!-- Heart Icon -->
            <button class="favorite-btn text-gray-600 hover:text-red-500">
              <i class="ri-heart-line text-2xl"></i>
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php elseif(isset($category_map[$cat])): ?>
    <p class="text-center text-gray-600 col-span-4">No businesses found</p>
  <?php endif; ?>
</div>
  </div>
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
  <!-- Favorites Modal Overlay -->
<div id="favoritesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6 relative">
    <button onclick="hideFavorites()" class="absolute top-2 right-2 text-gray-500 hover:text-black">
      <i class="ri-close-line text-2xl"></i>
    </button>
    <h3 class="text-xl font-bold mb-4">Your Favorite Businesses</h3>
    <ul id="favoritesList" class="space-y-2 max-h-60 overflow-y-auto">
      <!-- Favorites will be listed here -->
    </ul>
  </div>
</div>

<script>
  const favoriteBusinesses = [];

  function getCategoryFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('cat');
  }
window.addEventListener('load', function() {
    // Refresh every 5 seconds or when coming back to page
    setInterval(refreshCategory, 5000);
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        refreshCategory();
    }
});
    const data = categories[category];

    if (data) {
      document.getElementById('category-title').textContent = data.title;
      const container = document.getElementById('business-list');
      container.innerHTML = "";

      data.businesses.forEach(business => {
        const card = `
          <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <img src="${business.image}" alt="${business.name}" class="w-full h-32 object-cover">
            <div class="p-4">
              <h4 class="font-semibold text-lg">${business.name}</h4>
              <div class="flex items-center space-x-2 mt-2">
                <span class="text-yellow-500">★★★★★</span>
                <span class="text-gray-600">${business.location}</span>
              </div>
              <div class="flex justify-between items-center mt-3">
                <a href="businessdetails.html?name=${encodeURIComponent(business.name)}" class="text-primary transition-transform duration-200 hover:scale-110">View Details</a>
                <button class="favorite-btn text-gray-600 hover:text-red-500">
                  <i class="ri-heart-line text-2xl"></i>
                </button>
              </div>
            </div>
          </div>
        `;
        container.innerHTML += card;
      });

      const heartButtons = document.querySelectorAll('.favorite-btn');
      heartButtons.forEach(button => {
        button.addEventListener('click', function () {
          const heartIcon = this.querySelector('i');
          const card = this.closest('.bg-white');
          const name = card.querySelector('h4').textContent.trim();
          const location = card.querySelector('span.text-gray-600').textContent;
          const image = card.querySelector('img').src;

          if (heartIcon.classList.contains('ri-heart-line')) {
            heartIcon.classList.remove('ri-heart-line');
            heartIcon.classList.add('ri-heart-fill');
            heartIcon.style.color = 'red';

            if (!favoriteBusinesses.some(b => b.name === name)) {
              favoriteBusinesses.push({ name, location, image });
            }
          } else {
            heartIcon.classList.remove('ri-heart-fill');
            heartIcon.classList.add('ri-heart-line');
            heartIcon.style.color = '';

            const index = favoriteBusinesses.findIndex(b => b.name === name);
            if (index !== -1) favoriteBusinesses.splice(index, 1);
          }
        });
      });
    } else {
      document.getElementById('category-title').textContent = "Please select a category icon above";
      document.getElementById('business-list').innerHTML = `<p class="text-center text-gray-600">No businesses found</p>`;
    }
  

  function showFavorites() {
    const modal = document.getElementById('favoritesModal');
    const list = document.getElementById('favoritesList');
    list.innerHTML = '';

    if (favoriteBusinesses.length === 0) {
      list.innerHTML = '<li class="text-gray-600">No favorites added yet.</li>';
    } else {
      favoriteBusinesses.forEach(biz => {
        const item = document.createElement('li');
        item.innerHTML = `
          <div class="flex items-center space-x-3">
            <img src="${biz.image}" alt="${biz.name}" class="w-12 h-12 object-cover rounded">
            <div>
              <p class="font-semibold">${biz.name}</p>
              <p class="text-sm text-gray-500">${biz.location}</p>
            </div>
          </div>
        `;
        list.appendChild(item);
      });
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function hideFavorites() {
    const modal = document.getElementById('favoritesModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
  }

   function refreshCategory() {
    const currentUrl = new URL(window.location.href);
    fetch(currentUrl)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newList = doc.getElementById('business-list');
            if (newList) {
                document.getElementById('business-list').innerHTML = newList.innerHTML;
            }
        })
        .catch(error => console.error('Error refreshing category:', error));
}
</script>

</body>
</html>
