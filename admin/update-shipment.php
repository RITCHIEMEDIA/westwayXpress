<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$database = new Database();
$db = $database->getConnection();

$success = '';
$error = '';
$shipment = null;

// Get shipment details if tracking ID is provided
if (isset($_GET['tracking_id'])) {
    $tracking_id = $_GET['tracking_id'];
    $query = "SELECT * FROM shipments WHERE tracking_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$tracking_id]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$shipment) {
        $error = "Shipment not found with tracking ID: $tracking_id";
    }
}

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $shipment_id = $_POST['shipment_id'];
    $new_status = $_POST['status'];
    $location = $_POST['location'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    try {
        // Update shipment status
        $update_query = "UPDATE shipments SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $db->prepare($update_query);
        $stmt->execute([$new_status, $shipment_id]);
        
        // Add tracking entry
        $description = $_POST['description'] ?? 'shipment_created_at';

        $tracking_query = "INSERT INTO shipment_tracking (shipment_id, status, description, location, notes) VALUES (?, ?, ?, ?, ?)";
        $tracking_stmt = $db->prepare($tracking_query);
        $tracking_stmt->execute([
            $shipment_id,
            $new_status,
            $description,
            $location,
            $notes ?: "Status updated to " . str_replace('_', ' ', $new_status)
        ]);
        
        // If delivered, update actual delivery date
        if ($new_status === 'delivered') {
            $delivery_query = "UPDATE shipments SET actual_delivery_date = CURRENT_DATE WHERE id = ?";
            $delivery_stmt = $db->prepare($delivery_query);
            $delivery_stmt->execute([$shipment_id]);
        }
        
        $success = "Shipment status updated successfully!";
        
        // Refresh shipment data
        $query = "SELECT * FROM shipments WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$shipment_id]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error = "Error updating shipment: " . $e->getMessage();
    }
}

// Get tracking history if shipment exists
$tracking_history = [];
if ($shipment) {
    $tracking_query = "SELECT * FROM shipment_tracking WHERE shipment_id = ? ORDER BY created_at DESC";
    $tracking_stmt = $db->prepare($tracking_query);
    $tracking_stmt->execute([$shipment['id']]);
    $tracking_history = $tracking_stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Shipment - Westway Express Admin</title>
    <link href="../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-500 p-2 rounded-lg">
                        <i class="fas fa-shipping-fast text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg md:text-xl font-bold">Westway Express</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="../logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="lg:w-64 bg-white shadow-xl">
            <nav class="p-4">
                <div class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-tachometer-alt mr-3 text-lg text-gray-500"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="create-shipment.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-3 text-lg text-orange-500"></i>
                        <span class="font-medium">Create Shipment</span>
                    </a>
                    <a href="shipments.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-box mr-3 text-lg text-blue-600"></i>
                        <span class="font-medium">All Shipments</span>
                    </a>
                    <a href="update-shipment.php" class="flex items-center px-4 py-3 text-white bg-blue-600 rounded-lg shadow-md">
                        <i class="fas fa-edit mr-3 text-lg"></i>
                        <span class="font-medium">Update Shipment</span>
                    </a>
                    <a href="quotes.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-file-invoice-dollar mr-3 text-lg text-green-600"></i>
                        <span class="font-medium">Quotes</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 lg:p-8">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Update Shipment Status</h1>
                <p class="text-gray-600">Search for a shipment and update its status with tracking information</p>
            </div>

            <!-- Search Form -->
            <?php if (!$shipment): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Shipment</h3>
                    <form method="GET" class="flex flex-col sm:flex-row gap-4">
                        <input type="text" name="tracking_id" placeholder="Enter Tracking ID (e.g., AWB-001234567)" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="<?php echo htmlspecialchars($_GET['tracking_id'] ?? ''); ?>">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <p class="text-green-800 font-medium"><?php echo htmlspecialchars($success); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                        <p class="text-red-800 font-medium"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($shipment): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Shipment Details -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-box mr-2 text-blue-600"></i>Shipment Details
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tracking ID:</span>
                                <span class="font-medium"><?php echo $shipment['tracking_id']; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Status:</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    <?php 
                                    $status_colors = [
                                        'new_status_created' => 'bg-blue-100 text-blue-800',
                                        'in_process' => 'bg-yellow-100 text-yellow-800',
                                        'onhold' => 'bg-orange-100 text-orange-800',
                                        'intransit' => 'bg-purple-100 text-purple-800',
                                        'delayed' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'delivered' => 'bg-green-100 text-green-800'
                                    ];
                                    echo $status_colors[$shipment['status']];
                                    ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $shipment['status'])); ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">From:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($shipment['sender_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">To:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($shipment['receiver_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service Type:</span>
                                <span class="font-medium"><?php echo ucfirst($shipment['service_type']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Weight:</span>
                                <span class="font-medium"><?php echo $shipment['weight']; ?> kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cost:</span>
                                <span class="font-bold text-blue-600">$<?php echo number_format($shipment['shipping_cost'], 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-edit mr-2 text-green-600"></i>Update Status
                        </h3>
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="shipment_id" value="<?php echo $shipment['id']; ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                                <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="new_status_created" <?php echo $shipment['status'] === 'new_status_created' ? 'selected' : ''; ?>>NEW STATUS CREATED</option>
                                    <option value="in_process" <?php echo $shipment['status'] === 'in_process' ? 'selected' : ''; ?>>IN PROCESS</option>
                                    <option value="onhold" <?php echo $shipment['status'] === 'onhold' ? 'selected' : ''; ?>>ONHOLD</option>
                                    <option value="intransit" <?php echo $shipment['status'] === 'intransit' ? 'selected' : ''; ?>>INTRANSIT</option>
                                    <option value="delayed" <?php echo $shipment['status'] === 'delayed' ? 'selected' : ''; ?>>DELAYED</option>
                                    <option value="cancelled" <?php echo $shipment['status'] === 'cancelled' ? 'selected' : ''; ?>>CANCELLED</option>
                                    <option value="delivered" <?php echo $shipment['status'] === 'delivered' ? 'selected' : ''; ?>>DELIVERED</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <select name="description" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="shipment_created_at">SHIPMENT CREATED AT</option>
                                    <option value="item_received_at">ITEM RECEIVED AT</option>
                                    <option value="item_accepted_at">ITEM ACCEPTED AT</option>
                                    <option value="item_has_arrived_at">ITEM HAS ARRIVED AT</option>
                                    <option value="item_has_left">ITEM HAS LEFT</option>
                                    <option value="item_under_custom_review_at">ITEM UNDER CUSTOM REVIEW AT</option>
                                    <option value="item_under_screening_at">ITEM UNDER SCREENING AT</option>
                                    <option value="item_on_hold_at">ITEM ON HOLD AT</option>
                                    <option value="item_delayed_at">ITEM DELAYED AT</option>
                                    <option value="item_is_on_its_way_to">ITEM IS ON ITS WAY TO</option>
                                    <option value="item_is_ready_for_collection_at">ITEM IS READY FOR COLLECTION AT</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Location</label>
                                <input type="text" name="location" placeholder="e.g., New York Distribution Center" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" rows="3" placeholder="Additional notes about this status update..." 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <button type="submit" name="update_status" value="1" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-save mr-2"></i>Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tracking History -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>Tracking History
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($tracking_history as $track): ?>
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo ucfirst(str_replace('_', ' ', $track['status'])); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($track['notes']); ?></p>
                                            <?php if ($track['location']): ?>
                                                <p class="text-sm text-blue-600"><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($track['location']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm text-gray-500"><?php echo date('M d, Y H:i', strtotime($track['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
