<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Handle status updates
if ($_POST && isset($_POST['update_status'])) {
    $shipment_id = $_POST['shipment_id'];
    $new_status = $_POST['status'];
    $location = $_POST['location'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    // Update shipment status
    $update_query = "UPDATE shipments SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt = $db->prepare($update_query);
    $stmt->execute([$new_status, $shipment_id]);
    
    // Add tracking entry
    $description_map = [
        'new_status_created' => 'shipment_created_at',
        'in_process' => 'item_received_at',
        'onhold' => 'item_on_hold_at',
        'intransit' => 'item_is_on_its_way_to',
        'delayed' => 'item_delayed_at',
        'cancelled' => 'item_on_hold_at',
        'delivered' => 'item_is_ready_for_collection_at'
    ];
    
    $tracking_query = "INSERT INTO shipment_tracking (shipment_id, status, description, location, notes) VALUES (?, ?, ?, ?, ?)";
    $tracking_stmt = $db->prepare($tracking_query);
    $tracking_stmt->execute([
        $shipment_id,
        $new_status,
        $description_map[$new_status],
        $location,
        $notes ?: "Status updated to " . str_replace('_', ' ', $new_status)
    ]);
}

// Get all shipments with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$count_query = "SELECT COUNT(*) as total FROM shipments";
$total_records = $db->query($count_query)->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_records / $per_page);

$query = "SELECT * FROM shipments ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$shipments = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Shipments - Westway Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cargo-blue': '#1e40af',
                        'cargo-orange': '#f97316'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-cargo-blue to-blue-800 text-white shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-cargo-orange p-2 rounded-lg">
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
                        <i class="fas fa-plus mr-3 text-lg text-cargo-orange"></i>
                        <span class="font-medium">Create Shipment</span>
                    </a>
                    <a href="shipments.php" class="flex items-center px-4 py-3 text-white bg-blue-600 rounded-lg shadow-md">
                        <i class="fas fa-box mr-3 text-lg"></i>
                        <span class="font-medium">All Shipments</span>
                    </a>
                 <a href="update-shipment.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-3 text-lg text-indigo-600"></i>
                    <span class="font-medium">Update Shipment</span>
                </a>
                    <a href="quotes.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-file-invoice-dollar mr-3 text-lg text-green-600"></i>
                        <span class="font-medium">Quotes</span>
                    </a>
                    <a href="search-edit.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-search mr-3 text-lg text-purple-600"></i>
                        <span class="font-medium">Search & Edit</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 lg:p-8">
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">All Shipments</h1>
                    <p class="text-gray-600">Manage and track all shipments with real-time status updates</p>
                </div>
                <a href="create-shipment.php" class="mt-4 md:mt-0 bg-gradient-to-r from-cargo-blue to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>New Shipment
                </a>
            </div>

            <!-- Mobile Cards View -->
            <div class="lg:hidden space-y-4">
                <?php foreach ($shipments as $shipment): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900"><?php echo $shipment['tracking_id']; ?></h3>
                                <p class="text-sm text-gray-600"><?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full font-medium 
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
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">From:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($shipment['sender_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">To:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($shipment['receiver_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service:</span>
                                <span class="font-medium"><?php echo ucfirst($shipment['service_type']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cost:</span>
                                <span class="font-bold text-cargo-blue">$<?php echo number_format($shipment['shipping_cost'], 2); ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                            <a href="update-shipment.php?tracking_id=<?php echo $shipment['tracking_id']; ?>" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-edit mr-1"></i>Update Status
                            </a>
                            <a href="invoice.php?tracking_id=<?php echo $shipment['tracking_id']; ?>" 
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                <i class="fas fa-file-invoice mr-1"></i>Invoice
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receiver</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($shipments as $shipment): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $shipment['tracking_id']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($shipment['sender_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($shipment['sender_phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($shipment['receiver_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($shipment['receiver_phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo ucfirst($shipment['service_type']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $shipment['weight']; ?> kg</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="shipment_id" value="<?php echo $shipment['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 font-medium border-0 
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
                                                <option value="new_status_created" <?php echo $shipment['status'] === 'new_status_created' ? 'selected' : ''; ?>>New Status Created</option>
                                                <option value="in_process" <?php echo $shipment['status'] === 'in_process' ? 'selected' : ''; ?>>In Process</option>
                                                <option value="onhold" <?php echo $shipment['status'] === 'onhold' ? 'selected' : ''; ?>>On Hold</option>
                                                <option value="intransit" <?php echo $shipment['status'] === 'intransit' ? 'selected' : ''; ?>>In Transit</option>
                                                <option value="delayed" <?php echo $shipment['status'] === 'delayed' ? 'selected' : ''; ?>>Delayed</option>
                                                <option value="cancelled" <?php echo $shipment['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                <option value="delivered" <?php echo $shipment['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        $<?php echo number_format($shipment['shipping_cost'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="invoice.php?tracking_id=<?php echo $shipment['tracking_id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-file-invoice"></i> Invoice
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
