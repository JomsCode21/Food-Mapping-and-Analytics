<?php
header('Content-Type: application/json');
session_start();
require_once '../db_con.php'; // your database connection


// ✅ Ensure the business owner is logged in
if (!isset($_SESSION['fbowner_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in as business owner"]);
    exit;
}

$fbowner_id = intval($_SESSION['fbowner_id']); // no more hardcoding
$response = ["success" => true, "saved" => []];

// Get POST arrays
$names = $_POST['name'];
$categories = $_POST['category'];
$prices = $_POST['price'];

// Prepare upload directory
$uploadDir = __DIR__ . '/../uploads/menus/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// ✅ Process each menu item
for ($i = 0; $i < count($names); $i++) {
    $menu_name = trim($names[$i]);
    $category_name = trim($categories[$i]);
    $price = floatval($prices[$i]);

    if (empty($menu_name) || empty($category_name) || $price <= 0) continue;

    // --- Find or create category ---
    $stmt = $conn->prepare("SELECT category_id FROM menu_categories WHERE category_name=? AND fbowner_id=?");
    $stmt->bind_param("si", $category_name, $fbowner_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $category_id = $row['category_id'];
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO menu_categories (fbowner_id, category_name) VALUES (?, ?)");
        $stmt->bind_param("is", $fbowner_id, $category_name);
        $stmt->execute();
        $category_id = $stmt->insert_id;
    }
    $stmt->close();

    // --- Handle image upload ---
    $menu_image = NULL;
    if (isset($_FILES['image']['name'][$i]) && $_FILES['image']['error'][$i] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'][$i];
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name'][$i]));
        $fileName = time() . "_" . $safeName;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $menu_image = 'uploads/menus/' . $fileName;
        }
    }

    // --- Insert menu item ---
    $stmt = $conn->prepare("
        INSERT INTO menus (fbowner_id, category_id, menu_name, menu_price, menu_image)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisds", $fbowner_id, $category_id, $menu_name, $price, $menu_image);
    $ok = $stmt->execute();

    if ($ok) {
        $response['saved'][] = [
            "name" => $menu_name,
            "category" => $category_name,
            "price" => $price,
            "image" => $menu_image
        ];
    } else {
        $response['success'] = false;
        $response['error'] = $stmt->error;
    }

    $stmt->close();
}

echo json_encode($response);
?>
