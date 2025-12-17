<?php
session_start();
require_once '../db_con.php';

// Fetch admin info
$result = $conn->query("SELECT * FROM accounts WHERE user_type='admin' LIMIT 1");
$admin = $result ? $result->fetch_assoc() : null;

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];
    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $conn->query("UPDATE accounts SET password='$hashed' WHERE user_type='$admin[user_type]'");
        $success = "Password updated successfully!";
    } else {
        $error = "Password cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Account</title>
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">
    <p class="text-center text-7xl">
        <i class="fa fa-semibold fa-circle-user"></i>
    </p>
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">BPLO Admin Account</h1>
    <?php if (!empty($success)) echo "<div class='text-green-600 mb-2 text-center'>$success</div>"; ?>
    <?php if (!empty($error)) echo "<div class='text-red-600 mb-2 text-center'>$error</div>"; ?>

    <form action="admin_account.php" method="POST" class="space-y-4">
      <!-- Username -->
      <div>
        <label class="block text-gray-700 font-semibold mb-2">Username</label>
        <input type="text" value="<?php echo "$admin[email]";?>" readonly 
          class="w-full border border-gray-300 rounded-lg p-2 bg-gray-100 cursor-not-allowed">
      </div>

      <!-- Change Password -->
      <div>
        <label class="block text-gray-700 font-semibold mb-2">Change Password</label>
        <input type="password" name="new_password" placeholder="Enter new password" 
          class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <!-- Button -->
      <div class="text-center">
        <button type="submit" 
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
          Update Password
        </button>
        <button type="button" id="menu-btn" 
          class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow" onclick="home()">
          Back
        </button>
      </div>
    </form>
  </div>

    <script>
        const menubtn = document.getElementById('menu-btn');

        function home() {
            window.location.href = 'bplo.php'
        }
    </script>

</body>
</html>

