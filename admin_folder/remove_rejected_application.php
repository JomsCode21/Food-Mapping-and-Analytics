<?php
require_once '../db_con.php';
require_once '../upload_utils.php';

if (!isset($_POST['ba_id'])) {
    echo 'invalid';
    exit;
}

$ba_id = intval($_POST['ba_id']);
$projectRoot = dirname(__DIR__);

$documentColumns = [
    'barangay_clearance_owner',
    'barangay_business_clearance',
    'police_clearance',
    'cedula',
    'lease_contract',
    'business_permit_form',
    'dti_registration',
    'sec_registration',
    'rhu_permit',
    'meo_clearance',
    'mpdc_clearance',
    'menro_clearance',
    'bfp_certificate',
    'applicantSignature'
];

// Collect uploaded files for cleanup after DB commit.
$pathsToDelete = [];
$stmtDocs = $conn->prepare("SELECT * FROM application_documents WHERE ba_id = ?");
$stmtDocs->bind_param("i", $ba_id);
$stmtDocs->execute();
$resDocs = $stmtDocs->get_result();
while ($docRow = $resDocs->fetch_assoc()) {
    foreach ($documentColumns as $col) {
        if (!empty($docRow[$col])) {
            $pathsToDelete[] = $docRow[$col];
        }
    }
}
$stmtDocs->close();

$conn->begin_transaction();

try {
    $baIdSql = (int)$ba_id;
    $deleteQueries = [
        "DELETE FROM application_documents WHERE ba_id = {$baIdSql}",
        "DELETE FROM business_details WHERE ba_id = {$baIdSql}",
        "DELETE FROM emergency_contact WHERE ba_id = {$baIdSql}",
        "DELETE FROM lessor_details WHERE ba_id = {$baIdSql}",
        "DELETE FROM owner_details WHERE ba_id = {$baIdSql}",
        "DELETE FROM taxpayer_details WHERE ba_id = {$baIdSql}",
        "DELETE FROM business_application WHERE ba_id = {$baIdSql}"
    ];

    foreach ($deleteQueries as $query) {
        if (!$conn->query($query) && $conn->errno !== 1146) {
            throw new Exception($conn->error);
        }
    }

    $conn->commit();

    foreach (array_unique($pathsToDelete) as $path) {
        tlm_delete_storage_file($path, $projectRoot);
    }

    echo 'success';
} catch (Exception $e) {
    $conn->rollback();
    echo 'error: ' . $e->getMessage();
}
?>