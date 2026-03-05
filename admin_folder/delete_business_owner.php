<?php
session_start();
require_once '../db_con.php';
require_once '../upload_utils.php';

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $projectRoot = dirname(__DIR__);

    // Fetch owner assets before deletion.
    $fbowner_id = 0;
    $fb_photo = '';
    $fb_cover = '';
    $fb_gallery = '[]';
    $menu_images = '[]';

    $stmtOwner = $conn->prepare("SELECT fbowner_id, fb_photo, fb_cover, fb_gallery, menu_images FROM fb_owner WHERE user_id = ?");
    $stmtOwner->bind_param("i", $user_id);
    $stmtOwner->execute();
    $resOwner = $stmtOwner->get_result();
    if ($owner = $resOwner->fetch_assoc()) {
        $fbowner_id = (int)$owner['fbowner_id'];
        $fb_photo = $owner['fb_photo'] ?? '';
        $fb_cover = $owner['fb_cover'] ?? '';
        $fb_gallery = $owner['fb_gallery'] ?? '[]';
        $menu_images = $owner['menu_images'] ?? '[]';
    }
    $stmtOwner->close();

    $menuItemImages = [];
    if ($fbowner_id > 0) {
        $stmtMenus = $conn->prepare("SELECT menu_image FROM menus WHERE fbowner_id = ?");
        $stmtMenus->bind_param("i", $fbowner_id);
        $stmtMenus->execute();
        $resMenus = $stmtMenus->get_result();
        while ($menuRow = $resMenus->fetch_assoc()) {
            if (!empty($menuRow['menu_image'])) {
                $menuItemImages[] = $menuRow['menu_image'];
            }
        }
        $stmtMenus->close();
    }

    // Collect owner image assets for cleanup after DB commit.
    $pathsToDelete = [];
    if (!empty($fb_photo)) {
        $pathsToDelete[] = $fb_photo;
    }
    if (!empty($fb_cover)) {
        $pathsToDelete[] = $fb_cover;
    }

    $galleryList = json_decode($fb_gallery, true);
    if (is_array($galleryList)) {
        foreach ($galleryList as $img) {
            if (!empty($img)) {
                $pathsToDelete[] = $img;
            }
        }
    }

    $menuGalleryList = json_decode($menu_images, true);
    if (is_array($menuGalleryList)) {
        foreach ($menuGalleryList as $item) {
            $path = is_array($item) ? ($item['path'] ?? '') : $item;
            if (!empty($path)) {
                $pathsToDelete[] = $path;
            }
        }
    }

    foreach ($menuItemImages as $menuImagePath) {
        if (!empty($menuImagePath)) {
            $pathsToDelete[] = $menuImagePath;
        }
    }

    // Start Transaction to ensure all data is deleted safely
    $conn->begin_transaction();

    try {
        if ($fbowner_id > 0) {
            $stmtDeleteMenus = $conn->prepare("DELETE FROM menus WHERE fbowner_id = ?");
            $stmtDeleteMenus->bind_param("i", $fbowner_id);
            $stmtDeleteMenus->execute();
            $stmtDeleteMenus->close();

            $stmtDeleteCats = $conn->prepare("DELETE FROM menu_categories WHERE fbowner_id = ?");
            $stmtDeleteCats->bind_param("i", $fbowner_id);
            $stmtDeleteCats->execute();
            $stmtDeleteCats->close();
        }

        $stmt1 = $conn->prepare("DELETE FROM fb_owner WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("DELETE FROM business_application WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();

        $stmt3 = $conn->prepare("UPDATE accounts SET user_type = 'user' WHERE user_id = ?");
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $stmt3->close();

        $conn->commit();

        // Remove files only after DB records are successfully deleted.
        foreach (array_unique($pathsToDelete) as $path) {
            tlm_delete_storage_file($path, $projectRoot);
        }

        echo "success";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid Request";
}
?>