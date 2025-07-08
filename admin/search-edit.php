<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$database = new Database();
$db = $database->getConnection();

$shipment = null;
$error = '';
$success = '';

// Handle search
if (isset($_GET['tracking_id']) && !empty($_GET['tracking_id'])) {
    $tracking_id = $_GET['tracking_id'];
    
    $query = "SELECT * FROM shipments WHERE tracking_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$tracking_id]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$shipment) {
        $error = "Shipment not found with tracking ID: " . htmlspecialchars($tracking_id);
    }
}

// Handle update
if ($_POST && isset($_POST['update_shipment'])) {
    $shipment_id = $_POST['shipment_id'];
    
    // Calculate new shipping cost if weight or service changed
    $service_multipliers = [
        'standard' => 1.0,
        'express' => 2.5,
        'overnight' => 5.0
    ];
    
    $transport_multipliers = [
        'air_freight' => 3.0,
        'sea_freight' => 1.0,
        'road_transport' => 1.5,
        'rail_transport' => 1.2
    ];
    
    $weight = floatval($_POST['weight']);
    $quantity = intval($_POST['quantity']);
    $service_type = $_POST['service_type'];
    $transport_mode = $_POST['mode_of_transport'];
    $base_cost = 15.99;
    
    $shipping_cost = $base_cost * $service_multipliers[$service_type] * $transport_multipliers[$transport_mode] + ($weight * $quantity * 2);
    
    $update_query = "UPDATE shipments SET 
        sender_name = ?, sender_phone = ?, sender_email = ?, sender_address = ?,
        receiver_name = ?, receiver_phone = ?, receiver_email = ?, receiver_address = ?,
        package_description = ?, package_type = ?, weight = ?, quantity = ?, dimensions = ?,
        service_type = ?, mode_of_transport = ?, shipping_cost = ?,
        departure_date = ?, estimated_delivery_date = ?,
        origin_service_area = ?, destination_service_area = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = ?";
    
    $stmt = $db->prepare($update_query);
    
    if ($stmt->execute([
        $_POST['sender_name'], $_POST['sender_phone'], $_POST['sender_email'], $_POST['sender_address'],
        $_POST['receiver_name'], $_POST['receiver_phone'], $_POST['receiver_email'], $_POST['receiver_address'],
        $_POST['package_description'], $_POST['package_type'], $weight, $quantity, $_POST['dimensions'],
        $service_type, $transport_mode, $shipping_cost,
        $_POST['departure_date'], $_POST['estimated_delivery_date'],
        $_POST['origin_service_area'], $_POST['destination_service_area'],
        $shipment_id
    ])) {
        $success = "Shipment updated successfully!";
        
        // Refresh shipment data
        $query = "SELECT * FROM shipments WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$shipment_id]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "Error updating shipment. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search & Edit - Westway Express Admin</title>
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
                    <a href="shipments.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-box mr-3 text-lg text-blue-600"></i>
                        <span class="font-medium">All Shipments</span>
                    </a>
                    <a href="quotes.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-file-invoice-dollar mr-3 text-lg text-green-600"></i>
                        <span class="font-medium">Quotes</span>
                    </a>
                    <a href="search-edit.php" class="flex items-center px-4 py-3 text-white bg-purple-600 rounded-lg shadow-md">
                        <i class="fas fa-search mr-3 text-lg"></i>
                        <span class="font-medium">Search & Edit</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 lg:p-8">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Search & Edit Shipments</h1>
                <p class="text-gray-600">Find and modify shipment details efficiently</p>
            </div>

            <!-- Search Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-search mr-2 text-purple-600"></i>Enter Consignment Number
                </h3>
                <p class="text-gray-600 mb-6">Key in the Shipment Number to MODIFY the data. This is helpful if you have made spelling errors while adding the shipment.</p>
                
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="tracking_id" 
                               placeholder="Enter Consignment Number (e.g., AWB-795362231)" 
                               value="<?php echo isset($_GET['tracking_id']) ? htmlspecialchars($_GET['tracking_id']) : ''; ?>"
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 text-lg transition-all">
                    </div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </form>
            </div>

            <!-- Messages -->
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

            <!-- Edit Form -->
            <?php if ($shipment): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
                        <h3 class="text-xl font-semibold flex items-center">
                            <i class="fas fa-edit mr-2"></i>Edit Shipment: <?php echo htmlspecialchars($shipment['tracking_id']); ?>
                        </h3>
                    </div>
                    
                    <form method="POST" class="p-6 md:p-8 space-y-8">
                        <input type="hidden" name="shipment_id" value="<?php echo $shipment['id']; ?>">
                        
                        <!-- Sender Information -->
                        <div class="bg-blue-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-cargo-blue mb-6 flex items-center">
                                <i class="fas fa-user mr-2"></i>Sender Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender Name</label>
                                    <input type="text" name="sender_name" value="<?php echo htmlspecialchars($shipment['sender_name']); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender Phone</label>
                                    <input type="tel" name="sender_phone" value="<?php echo htmlspecialchars($shipment['sender_phone']); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender Email</label>
                                    <input type="email" name="sender_email" value="<?php echo htmlspecialchars($shipment['sender_email'] ?? ''); ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin Service Area</label>
                                    <input type="text" name="origin_service_area" value="<?php echo htmlspecialchars($shipment['origin_service_area'] ?? ''); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender Address</label>
                                    <textarea name="sender_address" required rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all"><?php echo htmlspecialchars($shipment['sender_address']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Receiver Information -->
                        <div class="bg-orange-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-cargo-orange mb-6 flex items-center">
                                <i class="fas fa-user-check mr-2"></i>Receiver Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Name</label>
                                    <input type="text" name="receiver_name" value="<?php echo htmlspecialchars($shipment['receiver_name']); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Phone</label>
                                    <input type="tel" name="receiver_phone" value="<?php echo htmlspecialchars($shipment['receiver_phone']); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Email</label>
                                    <input type="email" name="receiver_email" value="<?php echo htmlspecialchars($shipment['receiver_email'] ?? ''); ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination Service Area</label>
                                    <input type="text" name="destination_service_area" value="<?php echo htmlspecialchars($shipment['destination_service_area'] ?? ''); ?>" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Address</label>
                                    <textarea name="receiver_address" required rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all"><?php echo htmlspecialchars($shipment['receiver_address']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Package Information -->
                        <div class="bg-purple-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-purple-700 mb-6 flex items-center">
                                <i class="fas fa-box mr-2"></i>Package Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Type</label>
                                    <select name="package_type" required 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="Documents" <?php echo $shipment['package_type'] === 'Documents' ? 'selected' : ''; ?>>Documents</option>
                                        <option value="Electronics" <?php echo $shipment['package_type'] === 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                                        <option value="Medical" <?php echo $shipment['package_type'] === 'Medical' ? 'selected' : ''; ?>>Medical Supplies</option>
                                        <option value="Clothing" <?php echo $shipment['package_type'] === 'Clothing' ? 'selected' : ''; ?>>Clothing</option>
                                        <option value="Food" <?php echo $shipment['package_type'] === 'Food' ? 'selected' : ''; ?>>Food Items</option>
                                        <option value="Machinery" <?php echo $shipment['package_type'] === 'Machinery' ? 'selected' : ''; ?>>Machinery</option>
                                        <option value="Other" <?php echo $shipment['package_type'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                                    <input type="number" name="weight" step="0.1" value="<?php echo $shipment['weight']; ?>" required min="0.1" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                    <input type="number" name="quantity" value="<?php echo $shipment['quantity']; ?>" required min="1" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dimensions (L x W x H cm)</label>
                                    <input type="text" name="dimensions" value="<?php echo htmlspecialchars($shipment['dimensions'] ?? ''); ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                                    <select name="service_type" required 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="standard" <?php echo $shipment['service_type'] === 'standard' ? 'selected' : ''; ?>>Standard Delivery</option>
                                        <option value="express" <?php echo $shipment['service_type'] === 'express' ? 'selected' : ''; ?>>Express Delivery</option>
                                        <option value="overnight" <?php echo $shipment['service_type'] === 'overnight' ? 'selected' : ''; ?>>Overnight Delivery</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode of Transport</label>
                                    <select name="mode_of_transport" required 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="air_freight" <?php echo $shipment['mode_of_transport'] === 'air_freight' ? 'selected' : ''; ?>>Air Freight</option>
                                        <option value="sea_freight" <?php echo $shipment['mode_of_transport'] === 'sea_freight' ? 'selected' : ''; ?>>Sea Freight</option>
                                        <option value="road_transport" <?php echo $shipment['mode_of_transport'] === 'road_transport' ? 'selected' : ''; ?>>Road Transport</option>
                                        <option value="rail_transport" <?php echo $shipment['mode_of_transport'] === 'rail_transport' ? 'selected' : ''; ?>>Rail Transport</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Description</label>
                                    <textarea name="package_description" rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"><?php echo htmlspecialchars($shipment['package_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Details -->
                        <div class="bg-green-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-green-700 mb-6 flex items-center">
                                <i class="fas fa-calendar mr-2"></i>Shipping Details
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
                                    <input type="date" name="departure_date" value="<?php echo $shipment['departure_date']; ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery Date</label>
                                    <input type="date" name="estimated_delivery_date" value="<?php echo $shipment['estimated_delivery_date']; ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4 pt-6 border-t">
                            <a href="dashboard.php" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                                Cancel
                            </a>
                            <button type="submit" name="update_shipment" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i>Update Shipment
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
