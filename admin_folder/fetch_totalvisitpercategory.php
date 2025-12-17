<?php 

    include_once '../db_con.php';

    header ('Content-Type: application/json');

    if (isset($_POST['category'])){
        $category = $_POST['category'];

        // Query to get businesses under business category
        $stmt = $conn->prepare("SELECT f.fb_name, COUNT(r.id) as totalVisit
                                    FROM fb_owner f
                                    LEFT JOIN reviews r ON f.fbowner_id = r.fbowner_id
                                    WHERE f.fb_type = ? AND activation = 'Active'
                                    GROUP BY f.fbowner_id
                                    ORDER BY totalVisit DESC");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $stmt_result = $stmt->get_result();

        $labels = [];
        $data = [];

        while ($row = $stmt_result->fetch_assoc()) {
            $labels[] = html_entity_decode($row['fb_name'], ENT_QUOTES);
            $data[] = $row['totalVisit'];
        }

        echo json_encode(['labels' => $labels, 'data' => $data]);
    }
?>