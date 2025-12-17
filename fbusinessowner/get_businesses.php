<?php
require_once '../db_con.php';

$cat = $_GET['cat'] ?? '';
$price = $_GET['price'] ?? '';

// Map URL category to database category type
$category_map = [
    'restaurants' => 'Restaurant',
    'fastfoods'   => 'Fastfood',
    'cafes'       => 'Cafe',
    'bakery'      => 'Bakery'
];

$type = $category_map[$cat] ?? '';
if (!$type) {
    echo json_encode([]);
    exit;
}

// Price filter
$price_sql = "";
$params = [$type];
$types = "s"; // fb_type is string

switch ($price) {
    case "below50":
        $price_sql = "AND m.menu_price <= ?";
        $params[] = 50;
        $types .= "i";
        break;
    case "below100":
        $price_sql = "AND m.menu_price <= ?";
        $params[] = 100;
        $types .= "i";
        break;
    case "50to100":
        $price_sql = "AND m.menu_price BETWEEN ? AND ?";
        $params[] = 50;
        $params[] = 100;
        $types .= "ii";
        break;
    case "100to200":
        $price_sql = "AND m.menu_price BETWEEN ? AND ?";
        $params[] = 100;
        $params[] = 200;
        $types .= "ii";
        break;
    case "above200":
        $price_sql = "AND m.menu_price >= ?";
        $params[] = 200;
        $types .= "i";
        break;
}

// Build SQL
$sql = "
    SELECT DISTINCT b.fbowner_id, b.fb_name, b.fb_address, b.fb_photo, b.fb_status
    FROM fb_owner b
    LEFT JOIN menus m ON m.fbowner_id = b.fbowner_id
    WHERE b.fb_type = ?
    $price_sql
    ORDER BY b.fbowner_id DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters dynamically
$refs = [];
foreach ($params as $key => $value) {
    $refs[$key] = &$params[$key];
}
array_unshift($refs, $types); // first element is types string
call_user_func_array([$stmt, 'bind_param'], $refs);

$stmt->execute();
$result = $stmt->get_result();

// Map database fields to front-end keys
$businesses = [];
while ($row = $result->fetch_assoc()) {
    $businesses[] = [
        'id'         => $row['fbowner_id'],
        'store_name' => $row['fb_name'],
        'address'    => $row['fb_address'],
        'cover'      => $row['fb_photo'],
        'status'     => $row['fb_status']
    ];
}

$stmt->close();
echo json_encode($businesses);
