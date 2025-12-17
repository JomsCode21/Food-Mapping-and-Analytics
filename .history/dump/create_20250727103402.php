<?php
require_once 'db_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // e.g., 'user', 'fb_owner', 'admin'

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO accounts (email, password, user_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $user_type);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!-- Simple HTML form -->
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="user_type" required>
        <option value="user">User</option>
        <option value="fb_owner">Food Business Owner</option>
        <option value="admin">Admin</option>
    </select><br>
    <button type="submit">Register</button>
</form>