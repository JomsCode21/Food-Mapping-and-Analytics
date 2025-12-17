<?php
require_once '../db_con.php';
$ba_id = isset($_GET['ba_id']) ? intval($_GET['ba_id']) : 0;

if ($ba_id <= 0) {
    echo "Invalid application ID.";
    exit;
}

// Fetch all details
$sql = "SELECT * FROM business_application WHERE ba_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ba_id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch related details
$details = [];
$tables = [
    'business_details',
    'owner_details',
    'emergency_contact',
    'lessor_details',
    'taxpayer_details',
    'application_documents'
];
foreach ($tables as $table) {
    $sql = "SELECT * FROM $table WHERE ba_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ba_id);
    $stmt->execute();
    $details[$table] = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Helper function to format labels
function formatLabel($string) {
    return ucwords(str_replace('_', ' ', $string));
}

// Helper for status colors
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'approved': return 'bg-green-100 text-green-800 border-green-200';
        case 'declined': 
        case 'rejected': return 'bg-red-100 text-red-800 border-red-200';
        case 'pending': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        default: return 'bg-gray-100 text-gray-800 border-gray-200';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application #<?php echo $ba_id; ?> Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Application View</h1>
                <p class="text-sm text-slate-500">ID: #<?php echo str_pad($ba_id, 6, '0', STR_PAD_LEFT); ?></p>
            </div>
            <button onclick="window.location.href='business_request.php?status=<?php echo urlencode($_GET['status'] ?? $app['status']); ?>&type=<?php echo urlencode($app['application_type']); ?>'"
                class="flex items-center gap-2 text-slate-600 hover:text-blue-600 transition font-medium">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </button>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-file-contract text-blue-500 mr-2"></i> Application Overview
                </h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border <?php echo getStatusColor($app['status'] ?? 'Unknown'); ?>">
                    <?php echo htmlspecialchars($app['status'] ?? 'Unknown'); ?>
                </span>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                foreach ($app as $field => $value) {
                    if (preg_match('/_id$/', $field) || $field == 'status') continue; // Skip IDs and Status (shown above)
                    echo '<div>';
                    echo '<p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">' . formatLabel($field) . '</p>';
                    echo '<p class="text-base font-medium text-slate-900">' . htmlspecialchars($value) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php
            $sections = [
                'business_details' => ['icon' => 'fa-store', 'label' => 'Business Details', 'color' => 'text-indigo-500'],
                'owner_details' => ['icon' => 'fa-user-tie', 'label' => 'Owner Details', 'color' => 'text-emerald-500'],
                'taxpayer_details' => ['icon' => 'fa-file-invoice-dollar', 'label' => 'Taxpayer Details', 'color' => 'text-orange-500'],
                'lessor_details' => ['icon' => 'fa-building', 'label' => 'Lessor Details', 'color' => 'text-purple-500'],
                'emergency_contact' => ['icon' => 'fa-truck-medical', 'label' => 'Emergency Contact', 'color' => 'text-red-500'],
            ];

            foreach ($sections as $tbl => $meta) {
                if (!empty($details[$tbl])) {
            ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col h-full">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid <?php echo $meta['icon'] . ' ' . $meta['color']; ?>"></i>
                        <h3 class="font-semibold text-slate-800"><?php echo $meta['label']; ?></h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-4">
                        <?php
                        foreach ($details[$tbl] as $field => $value) {
                            if (preg_match('/_id$/', $field)) continue;
                            echo '<div class="border-b border-slate-50 last:border-0 pb-2 last:pb-0">';
                            echo '<span class="block text-xs text-slate-500 mb-0.5">' . formatLabel($field) . '</span>';
                            echo '<span class="block text-sm font-medium text-slate-800">' . ($value ? htmlspecialchars($value) : '<span class="text-slate-300 italic">N/A</span>') . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-folder-open text-yellow-500 mr-2"></i> Submitted Requirements</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php
                    $hasDocs = false;
                    foreach ($details['application_documents'] ?? [] as $key => $val) {
                        if (preg_match('/_id$/', $key) || $key === 'ba_id' || !$val) continue;
                        $hasDocs = true;
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $val);
                        $filename = basename($val);
                        ?>
                        <div class="group relative border border-slate-200 rounded-lg p-2 hover:shadow-md transition bg-slate-50 flex flex-col items-center text-center">
                            <?php if ($isImage): ?>
                                <div class="h-24 w-full bg-gray-200 rounded overflow-hidden cursor-pointer mb-2" onclick="showImageModal('../<?php echo $val; ?>')">
                                    <img src="../<?php echo $val; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                </div>
                                <button onclick="showImageModal('../<?php echo $val; ?>')" class="text-xs font-semibold text-blue-600 hover:underline truncate w-full">
                                    <?php echo formatLabel($key); ?>
                                </button>
                            <?php else: ?>
                                <div class="h-24 w-full flex items-center justify-center text-slate-400 mb-2">
                                    <i class="fa-solid fa-file-pdf text-4xl"></i>
                                </div>
                                <a href="../<?php echo $val; ?>" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline truncate w-full">
                                    <?php echo formatLabel($key); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php
                    }
                    if (!$hasDocs) {
                        echo '<p class="text-slate-500 italic col-span-full">No documents submitted.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div id="imageModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
      <div class="relative max-w-4xl w-full mx-4">
        <button id="closeImageModal" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img id="modalImage" src="" alt="Document Preview" class="rounded shadow-2xl max-h-[85vh] mx-auto border-4 border-white">
      </div>
    </div>

    <script>
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const closeBtn = document.getElementById('closeImageModal');

        function showImageModal(src) {
            modalImg.src = src;
            modal.classList.remove('hidden');
            // Small delay to allow display:block to apply before changing opacity for fade-in effect
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modalImg.src = '';
            }, 300); // Wait for transition
        }

        closeBtn.onclick = closeModal;
        modal.onclick = function(e) {
            if (e.target === this) closeModal();
        };
    </script>
</body>
</html>