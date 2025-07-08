<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$success = '';
$error = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Generate tracking ID
    $tracking_id = 'AWB-' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
    
    // Calculate shipping cost based on service type, weight, and transport mode
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
    
    $query = "INSERT INTO shipments (tracking_id, sender_name, sender_phone, sender_email, sender_address, receiver_name, receiver_phone, receiver_email, receiver_address, package_description, package_type, weight, quantity, service_type, mode_of_transport, shipping_cost, departure_date, estimated_delivery_date, origin_service_area, destination_service_area, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([
        $tracking_id,
        $_POST['sender_name'],
        $_POST['sender_phone'],
        $_POST['sender_email'],
        $_POST['sender_address'],
        $_POST['receiver_name'],
        $_POST['receiver_phone'],
        $_POST['receiver_email'],
        $_POST['receiver_address'],
        $_POST['package_description'],
        $_POST['package_type'],
        $weight,
        $quantity,
        $service_type,
        $transport_mode,
        $shipping_cost,
        $_POST['departure_date'],
        $_POST['estimated_delivery_date'],
        $_POST['origin_service_area'],
        $_POST['destination_service_area'],
        $_SESSION['user_id']
    ])) {
        // Get the shipment ID for tracking entry
        $shipment_id = $db->lastInsertId();
        
        // Add initial tracking entry
        $tracking_query = "INSERT INTO shipment_tracking (shipment_id, status, description, location, notes) VALUES (?, ?, ?, ?, ?)";
        $tracking_stmt = $db->prepare($tracking_query);
        $tracking_stmt->execute([
            $shipment_id,
            'new_status_created',
            'shipment_created_at',
            $_POST['origin_service_area'],
            'Shipment created and ready for processing'
        ]);
        
        $success = "Shipment created successfully! Tracking ID: $tracking_id";
    } else {
        $error = "Error creating shipment. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shipment - Westway Express Admin</title>
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
    <!-- Enhanced Navigation -->
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
                    <a href="create-shipment.php" class="flex items-center px-4 py-3 text-white bg-cargo-blue rounded-lg shadow-md">
                        <i class="fas fa-plus mr-3 text-lg"></i>
                        <span class="font-medium">Create Shipment</span>
                    </a>
                    <a href="shipments.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-box mr-3 text-lg text-blue-600"></i>
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
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Create New Shipment</h1>
                <p class="text-gray-600">Enter comprehensive shipment details and generate tracking</p>
            </div>

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

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <form method="POST" class="p-6 md:p-8 space-y-8">
                    <!-- Sender Information -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-cargo-blue mb-6 flex items-center">
                            <i class="fas fa-user mr-2"></i>Sender Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sender Name *</label>
                                <input type="text" name="sender_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sender Phone *</label>
                                <input type="tel" name="sender_phone" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sender Email</label>
                                <input type="email" name="sender_email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Origin Service Area *</label>
                                <input type="text" name="origin_service_area" required placeholder="e.g., United States" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sender Address *</label>
                                <textarea name="sender_address" required rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-blue focus:border-transparent transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Receiver Information -->
                    <div class="bg-orange-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-cargo-orange mb-6 flex items-center">
                            <i class="fas fa-user-check mr-2"></i>Receiver Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Name *</label>
                                <input type="text" name="receiver_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Phone *</label>
                                <input type="tel" name="receiver_phone" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Email</label>
                                <input type="email" name="receiver_email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Destination Service Area *</label>
                                <input type="text" name="destination_service_area" required placeholder="e.g., Malaysia" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Receiver Address *</label>
                                <textarea name="receiver_address" required rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cargo-orange focus:border-transparent transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Package Information -->
                    <div class="bg-purple-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-purple-700 mb-6 flex items-center">
                            <i class="fas fa-box mr-2"></i>Package Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Type *</label>
                                <select name="package_type" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Select Package Type</option>
                                    <option value="Documents">Documents</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Medical">Medical Supplies</option>
                                    <option value="Clothing">Clothing</option>
                                    <option value="Food">Food Items</option>
                                    <option value="Machinery">Machinery</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg) *</label>
                                <input type="number" name="weight" step="0.1" required min="0.1" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input type="number" name="quantity" required min="1" value="1" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                                <select name="service_type" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="standard">Standard Delivery</option>
                                    <option value="express">Express Delivery</option>
                                    <option value="overnight">Overnight Delivery</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mode of Transport *</label>
                                <select name="mode_of_transport" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="air_freight">Air Freight</option>
                                    <option value="sea_freight">Sea Freight</option>
                                    <option value="road_transport">Road Transport</option>
                                    <option value="rail_transport">Rail Transport</option>
                                </select>
                            </div>
                              <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shipping_cost" class="form-label">Shipping Cost ($)</label>
                                    <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        
                            <div class="md:col-span-2 lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Description</label>
                                <textarea name="package_description" rows="3" placeholder="Detailed description of the package contents" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    <div class="bg-green-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-green-700 mb-6 flex items-center">
                            <i class="fas fa-calendar mr-2"></i>Shipping Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
                                <input type="date" name="departure_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery Date</label>
                                <input type="date" name="estimated_delivery_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4 pt-6 border-t">
                        <a href="dashboard.php" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cargo-blue to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Create Shipment & Generate Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate estimated delivery date based on service type
        document.querySelector('select[name="service_type"]').addEventListener('change', function() {
            const departureDate = document.querySelector('input[name="departure_date"]').value;
            if (departureDate) {
                const departure = new Date(departureDate);
                let deliveryDate = new Date(departure);
                
                switch(this.value) {
                    case 'standard':
                        deliveryDate.setDate(departure.getDate() + 5);
                        break;
                    case 'express':
                        deliveryDate.setDate(departure.getDate() + 2);
                        break;
                    case 'overnight':
                        deliveryDate.setDate(departure.getDate() + 1);
                        break;
                }
                
                document.querySelector('input[name="estimated_delivery_date"]').value = deliveryDate.toISOString().split('T')[0];
            }
        });

        // Auto-set departure date to today if not set
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="departure_date"]').value = today;
        });
    </script>
</body>
</html>
