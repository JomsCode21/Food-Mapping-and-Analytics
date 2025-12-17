<?php
session_start();
require_once 'db_con.php';

$ba_id = isset($_GET['ba_id']) ? intval($_GET['ba_id']) : 0;
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_message = $_POST['reply_message'] ?? '';
    if ($user_id && $ba_id && $reply_message) {
        // Store reply
        $stmt = $conn->prepare("INSERT INTO replies (ba_id, user_id, reply_message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $ba_id, $user_id, $reply_message);
        $stmt->execute();
        $reply_id = $stmt->insert_id;
        $stmt->close();

        // Store notification for admin
        $notif_message = "replied to application.";
        $conn->query("INSERT INTO notification (user_id, type, message, ref_id) VALUES ($user_id, 'Email_Reply', '$notif_message', $reply_id)");

        echo "<script>alert('Reply sent!'); window.location='index.php';</script>";
        exit;
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reply to Application</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 flex items-center justify-center min-h-screen">

  <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Reply to Application</h2>
    <p class="text-sm text-gray-500 mb-6">Write your response below. The Admin will be notified once you send it.</p>

    <?php if (!empty($error)) : ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label for="reply_message" class="block text-gray-700 font-medium mb-1">Your Message</label>
        <textarea 
          name="reply_message" 
          id="reply_message" 
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
          rows="6" 
          placeholder="Type your reply here..." 
          required></textarea>
      </div>

      <div class="flex items-center justify-end space-x-3">
        <a href="index.php" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</a>
        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Send Reply</button>
      </div>
    </form>
  </div>

</body>
</html>
