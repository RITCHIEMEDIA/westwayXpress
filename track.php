<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$shipment = null;
$tracking_history = [];
$error = '';

if ($_POST && isset($_POST['tracking_id'])) {
    $tracking_id = trim($_POST['tracking_id']);
    
    if ($tracking_id) {
        // Get shipment details
        $query = "SELECT * FROM shipments WHERE tracking_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$tracking_id]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($shipment) {
            // Get tracking history
            $tracking_query = "SELECT * FROM shipment_tracking WHERE shipment_id = ? ORDER BY created_at ASC";
            $tracking_stmt = $db->prepare($tracking_query);
            $tracking_stmt->execute([$shipment['id']]);
            $tracking_history = $tracking_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "Shipment not found. Please check your tracking ID and try again.";
        }
    } else {
        $error = "Please enter a tracking ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Shipment - Westway Express</title>
    <link href="assets/css/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .neo-card {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            box-shadow: 20px 20px 60px #d9d9d9, -20px -20px 60px #ffffff;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .neo-card:hover {
            transform: translateY(-5px);
            box-shadow: 25px 25px 70px #d9d9d9, -25px -25px 70px #ffffff;
        }

        .gradient-border {
            position: relative;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            padding: 2px;
            border-radius: 20px;
        }

        .gradient-border-inner {
            background: white;
            border-radius: 18px;
            padding: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .tracking-timeline {
            position: relative;
        }
        
        .tracking-timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #667eea, #764ba2, #f093fb, #f5576c);
            border-radius: 2px;
        }
        
        .tracking-step {
            position: relative;
            padding-left: 70px;
            margin-bottom: 40px;
        }
        
        .tracking-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            z-index: 10;
            border: 3px solid white;
        }
        
        .tracking-step.completed .tracking-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .tracking-step.current .tracking-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            animation: pulse-glow 2s infinite;
        }
        
        .tracking-step.pending .tracking-icon {
            background: #f8fafc;
            color: #94a3b8;
            border: 3px solid #e2e8f0;
        }

        @keyframes pulse-glow {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
            }
        }

        .plane-animation {
            animation: fly 3s ease-in-out infinite;
        }
        
        @keyframes fly {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(15px); }
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 12px;
            background: rgba(255,255,255,0.5);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .contact-info:hover {
            background: rgba(255,255,255,0.8);
            transform: translateX(5px);
        }

        .contact-info i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .detail-item {
            background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(255,255,255,0.4));
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .search-container {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 2rem;
        }

        .search-input {
            background: rgba(255,255,255,0.9);
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .search-button {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(245, 87, 108, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Enhanced Navigation -->
    <nav class="glass-effect sticky top-0 z-50 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-shipping-fast text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">Westway Express</h1>
                        <p class="text-sm text-gray-600 font-medium">Advanced Package Tracking</p>
                    </div>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 transition-all duration-300 font-medium flex items-center">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="index.php#services" class="text-gray-700 hover:text-blue-600 transition-all duration-300 font-medium">Services</a>
                    <a href="index.php#contact" class="text-gray-700 hover:text-blue-600 transition-all duration-300 font-medium">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section py-20 relative z-10">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-black text-white mb-6 floating-animation">Track Your Shipment</h1>
                <p class="text-xl text-blue-100 font-medium">Enter your tracking ID for real-time package updates and detailed information</p>
            </div>

            <!-- Enhanced Tracking Form -->
            <div class="search-container max-w-4xl mx-auto">
                <form method="POST" class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <input type="text" name="tracking_id" placeholder="Enter your tracking ID (e.g., AWB-001234567)" 
                               class="search-input w-full"
                               value="<?php echo htmlspecialchars($_POST['tracking_id'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="search-button text-white font-bold">
                        <i class="fas fa-search mr-3"></i>Track Package
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <?php if ($error): ?>
            <div class="neo-card bg-red-50 border-l-4 border-red-500 p-8 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl mr-6"></i>
                    <div>
                        <h3 class="text-lg font-bold text-red-800">Tracking Error</h3>
                        <p class="text-red-700 font-medium"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($shipment): ?>
            <!-- Enhanced Shipment Overview -->
            <div class="gradient-border mb-12">
                <div class="gradient-border-inner">
                    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8">
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 mb-3">Tracking ID: <?php echo $shipment['tracking_id']; ?></h2>
                            <div class="flex flex-wrap gap-4 text-gray-600 font-medium">
                                <span class="flex items-center"><i class="fas fa-truck mr-2 text-blue-600"></i>Service: <?php echo ucfirst($shipment['service_type']); ?></span>
                                <span class="flex items-center"><i class="fas fa-route mr-2 text-purple-600"></i>Mode: <?php echo ucfirst(str_replace('_', ' ', $shipment['mode_of_transport'])); ?></span>
                                <span class="flex items-center"><i class="fas fa-calendar mr-2 text-green-600"></i>Created: <?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="mt-6 xl:mt-0">
                            <span class="status-badge
                                <?php 
                                $status_styles = [
                                    'new_status_created' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white',
                                    'in_process' => 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white',
                                    'onhold' => 'bg-gradient-to-r from-orange-500 to-red-500 text-white',
                                    'intransit' => 'bg-gradient-to-r from-purple-500 to-pink-500 text-white',
                                    'delayed' => 'bg-gradient-to-r from-red-500 to-red-600 text-white',
                                    'cancelled' => 'bg-gradient-to-r from-gray-500 to-gray-600 text-white',
                                    'delivered' => 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                                ];
                                echo $status_styles[$shipment['status']];
                                ?>">
                                <i class="fas fa-circle mr-3 text-sm"></i>
                                <?php echo ucfirst(str_replace('_', ' ', $shipment['status'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Consigner & Consignee Information -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
                <!-- Consigner (Sender) Details -->
                <div class="neo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-xl mr-4">
                            <i class="fas fa-user-circle text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Consigner Details</h3>
                            <p class="text-gray-600 font-medium">Sender Information</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="contact-info">
                            <i class="fas fa-user text-blue-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_name']); ?></p>
                                <p class="text-sm text-gray-600">Full Name</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-phone text-green-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_phone']); ?></p>
                                <p class="text-sm text-gray-600">Phone Number</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-envelope text-purple-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_email'] ?? 'Not provided'); ?></p>
                                <p class="text-sm text-gray-600">Email Address</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-building text-orange-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_company'] ?? 'Individual'); ?></p>
                                <p class="text-sm text-gray-600">Company/Organization</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo nl2br(htmlspecialchars($shipment['sender_address'])); ?></p>
                                <p class="text-sm text-gray-600">Complete Address</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-globe text-teal-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_country'] ?? 'Not specified'); ?></p>
                                <p class="text-sm text-gray-600">Country</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-mail-bulk text-indigo-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['sender_postal_code'] ?? 'Not provided'); ?></p>
                                <p class="text-sm text-gray-600">Postal Code</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consignee (Receiver) Details -->
                <div class="neo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 rounded-xl mr-4">
                            <i class="fas fa-user-check text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Consignee Details</h3>
                            <p class="text-gray-600 font-medium">Receiver Information</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="contact-info">
                            <i class="fas fa-user text-blue-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_name']); ?></p>
                                <p class="text-sm text-gray-600">Full Name</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-phone text-green-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_phone']); ?></p>
                                <p class="text-sm text-gray-600">Phone Number</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-envelope text-purple-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_email'] ?? 'Not provided'); ?></p>
                                <p class="text-sm text-gray-600">Email Address</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-building text-orange-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_company'] ?? 'Individual'); ?></p>
                                <p class="text-sm text-gray-600">Company/Organization</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo nl2br(htmlspecialchars($shipment['receiver_address'])); ?></p>
                                <p class="text-sm text-gray-600">Complete Address</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-globe text-teal-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_country'] ?? 'Not specified'); ?></p>
                                <p class="text-sm text-gray-600">Country</p>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-mail-bulk text-indigo-600"></i>
                            <div>
                                <p class="font-bold text-gray-900"><?php echo htmlspecialchars($shipment['receiver_postal_code'] ?? 'Not provided'); ?></p>
                                <p class="text-sm text-gray-600">Postal Code</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Package & Shipment Details -->
            <div class="neo-card p-8 mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <i class="fas fa-box-open mr-4 text-blue-600"></i>Package & Shipment Information
                </h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-tag text-blue-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Package Description</h4>
                        </div>
                        <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($shipment['package_description'] ?: 'General Cargo'); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-weight-hanging text-green-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Weight</h4>
                        </div>
                        <p class="text-gray-700 font-medium text-2xl"><?php echo $shipment['weight']; ?> <span class="text-lg">kg</span></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-ruler-combined text-purple-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Dimensions</h4>
                        </div>
                        <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($shipment['dimensions'] ?? 'Standard Package'); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-dollar-sign text-orange-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Shipping Cost</h4>
                        </div>
                        <p class="text-gray-700 font-medium text-2xl">$<?php echo number_format($shipment['shipping_cost'], 2); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-shield-alt text-red-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Insurance</h4>
                        </div>
                        <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($shipment['insurance_value'] ? '$' . number_format($shipment['insurance_value'], 2) : 'Not Insured'); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-clock text-teal-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Estimated Delivery</h4>
                        </div>
                        <p class="text-gray-700 font-medium"><?php echo $shipment['estimated_delivery_date'] ? date('M d, Y', strtotime($shipment['estimated_delivery_date'])) : 'TBD'; ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-circle text-indigo-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Priority Level</h4>
                        </div>
                        <p class="text-gray-700 font-medium"><?php echo ucfirst($shipment['priority'] ?? 'Standard'); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-file-invoice text-pink-600 text-xl mr-3"></i>
                            <h4 class="font-bold text-gray-900">Reference Number</h4>
                        </div>
                        <p class="text-gray-700 font-medium font-mono"><?php echo htmlspecialchars($shipment['reference_number'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Print Invoice Button -->
            <div class="text-center mb-12">
                <a href="invoice.php?tracking_id=<?php echo $shipment['tracking_id']; ?>" 
                   target="_blank"
                   class="inline-flex items-center bg-gradient-to-r from-green-600 via-green-700 to-green-800 hover:from-green-700 hover:via-green-800 hover:to-green-900 text-grey-300 px-12 py-6 rounded-2xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-green-500/25">
                    <i class="fas fa-print mr-4 text-2xl"></i>Generate Official Invoice
                </a>
            </div>

            <!-- Enhanced Tracking Timeline -->
            <div class="neo-card p-8">
                <h3 class="text-3xl font-bold text-gray-900 mb-10 flex items-center">
                    <i class="fas fa-route mr-4 text-blue-600"></i>Detailed Tracking Timeline
                </h3>
                
                <div class="tracking-timeline">
                    <?php 
                    foreach ($tracking_history as $index => $track): 
                        $is_current = ($index === count($tracking_history) - 1);
                        $is_completed = !$is_current;
                        $step_class = $is_current ? 'current' : ($is_completed ? 'completed' : 'pending');
                        
                        // Get appropriate icon based on status and transport mode
                        $icon = 'fas fa-circle';
                        if ($shipment['mode_of_transport'] === 'air_freight') {
                            switch ($track['status']) {
                                case 'new_status_created':
                                    $icon = 'fas fa-file-alt';
                                    break;
                                case 'in_process':
                                    $icon = 'fas fa-cogs';
                                    break;
                                case 'intransit':
                                    $icon = 'fas fa-plane plane-animation';
                                    break;
                                case 'delivered':
                                    $icon = 'fas fa-check-circle';
                                    break;
                                default:
                                    $icon = 'fas fa-plane';
                            }
                        } else {
                            switch ($track['status']) {
                                case 'new_status_created':
                                    $icon = 'fas fa-file-alt';
                                    break;
                                case 'in_process':
                                    $icon = 'fas fa-cogs';
                                    break;
                                case 'intransit':
                                    $icon = 'fas fa-truck';
                                    break;
                                case 'delivered':
                                    $icon = 'fas fa-check-circle';
                                    break;
                                case 'onhold':
                                    $icon = 'fas fa-pause-circle';
                                    break;
                                case 'delayed':
                                    $icon = 'fas fa-exclamation-triangle';
                                    break;
                                case 'cancelled':
                                    $icon = 'fas fa-times-circle';
                                    break;
                                default:
                                    $icon = 'fas fa-circle';
                            }
                        }
                    ?>
                        <div class="tracking-step <?php echo $step_class; ?>">
                            <div class="tracking-icon">
                                <i class="<?php echo $icon; ?>"></i>
                            </div>
                            <div class="info-card">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-xl font-bold text-gray-900">
                                        <?php echo ucfirst(str_replace('_', ' ', $track['status'])); ?>
                                    </h4>
                                    <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                        <?php echo date('M d, Y • g:i A', strtotime($track['created_at'])); ?>
                                    </span>
                                </div>
                                <p class="text-gray-700 mb-3 font-medium"><?php echo htmlspecialchars($track['notes']); ?></p>
                                <?php if ($track['location']): ?>
                                    <p class="text-blue-600 font-bold flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <?php echo htmlspecialchars($track['location']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Enhanced Estimated Delivery -->
            <?php if ($shipment['estimated_delivery_date'] && $shipment['status'] !== 'delivered'): ?>
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 rounded-3xl shadow-2xl p-10 mt-12 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400/20 to-purple-400/20"></div>
                    <div class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-5xl mr-8 floating-animation"></i>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold mb-3">Estimated Delivery Date</h3>
                            <p class="text-4xl font-black"><?php echo date('F d, Y', strtotime($shipment['estimated_delivery_date'])); ?></p>
                            <p class="text-blue-200 mt-2 font-medium">Delivery time may vary based on location and circumstances</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white py-16 mt-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 rounded-xl mr-6 shadow-lg">
                        <i class="fas fa-shipping-fast text-3xl"></i>
                    </div>
                    <h3 class="text-4xl font-bold">Westway Express</h3>
                </div>
                <p class="text-gray-300 mb-8 text-lg font-medium">Your Trusted Global Logistics Partner</p>
                <div class="flex justify-center space-x-12 text-lg">
                    <a href="index.php" class="text-gray-300 hover:text-white transition-all duration-300 font-medium">Home</a>
                    <a href="index.php#services" class="text-gray-300 hover:text-white transition-all duration-300 font-medium">Services</a>
                    <a href="index.php#contact" class="text-gray-300 hover:text-white transition-all duration-300 font-medium">Contact</a>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700">
                    <p class="text-gray-400">&copy; 2024 Westway Express. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
