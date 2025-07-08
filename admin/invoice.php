<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$tracking_id = $_GET['tracking_id'] ?? '';

if (!$tracking_id) {
    header('Location: shipments.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM shipments WHERE tracking_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$tracking_id]);

if (!$shipment = $stmt->fetch(PDO::FETCH_ASSOC)) {
    header('Location: shipments.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo $shipment['tracking_id']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .print-stamp {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.1);
            z-index: -1;
            pointer-events: none;
            font-family: 'Arial Black', Arial, sans-serif;
            letter-spacing: 10px;
        }

        @media print {
            .print-stamp {
                display: block !important;
                color: rgba(220, 38, 38, 0.15) !important;
            }
            .no-print { display: none !important; }
            body { background: white !important; }
            .invoice-container {
                box-shadow: none !important;
                border: 2px solid #e5e7eb !important;
            }
        }

        .invoice-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px 12px 0 0;
        }

        .invoice-section {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="print-stamp">COPY</div>
    <div class="max-w-4xl mx-auto p-8">
        <!-- Print Button -->
        <div class="no-print mb-6 flex justify-between items-center">
            <a href="shipments.php" class="text-blue-600 hover:text-blue-500">← Back to Shipments</a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Print Invoice
            </button>
        </div>

        <!-- Invoice -->
        <div class="bg-white rounded-lg shadow-2xl invoice-container">
            <!-- Enhanced Invoice Header -->
            <div class="invoice-header">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="bg-orange-500 p-3 rounded-lg mr-4">
                                <i class="fas fa-shipping-fast text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">Westway Express</h1>
                                <p class="text-blue-100">Fast & Reliable Delivery Solutions</p>
                            </div>
                        </div>
                        <div class="text-sm text-blue-100">
                            <p><i class="fas fa-map-marker-alt mr-2"></i>123 Business Avenue, City, State 12345</p>
                            <p><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</p>
                            <p><i class="fas fa-envelope mr-2"></i>info@westwayexpress.com</p>
                            <p><i class="fas fa-globe mr-2"></i>www.westwayexpress.com</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-3xl font-bold mb-4">INVOICE</h2>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <p><strong>Invoice Date:</strong> <?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></p>
                            <p><strong>Tracking ID:</strong> <?php echo $shipment['tracking_id']; ?></p>
                            <p><strong>Service Type:</strong> <?php echo ucfirst($shipment['service_type']); ?> Delivery</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Ship From</h3>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium"><?php echo htmlspecialchars($shipment['sender_name']); ?></p>
                        <p><?php echo htmlspecialchars($shipment['sender_phone']); ?></p>
                        <p class="mt-2"><?php echo nl2br(htmlspecialchars($shipment['sender_address'])); ?></p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Ship To</h3>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium"><?php echo htmlspecialchars($shipment['receiver_name']); ?></p>
                        <p><?php echo htmlspecialchars($shipment['receiver_phone']); ?></p>
                        <p class="mt-2"><?php echo nl2br(htmlspecialchars($shipment['receiver_address'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Package Details -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Package Details</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Description:</span>
                            <p class="text-gray-600"><?php echo htmlspecialchars($shipment['package_description'] ?: 'N/A'); ?></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Weight:</span>
                            <p class="text-gray-600"><?php echo $shipment['weight']; ?> kg</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Dimensions:</span>
                            <p class="text-gray-600"><?php echo htmlspecialchars($shipment['dimensions'] ?: 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charges -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Charges</h3>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Description</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-700">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3 px-4 text-gray-700"><?php echo ucfirst($shipment['service_type']); ?> Delivery Service</td>
                            <td class="py-3 px-4 text-right text-gray-700">$<?php echo number_format($shipment['shipping_cost'], 2); ?></td>
                        </tr>
                        <tr class="border-b-2 border-gray-300">
                            <td class="py-3 px-4 font-semibold text-gray-900">Total Amount</td>
                            <td class="py-3 px-4 text-right font-bold text-xl text-gray-900">$<?php echo number_format($shipment['shipping_cost'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Shipment Status</h3>
                <div class="flex items-center">
                    <span class="px-4 py-2 rounded-full text-sm font-medium 
                        <?php 
                        $status_colors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'picked_up' => 'bg-blue-100 text-blue-800',
                            'in_transit' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                        echo $status_colors[$shipment['status']];
                        ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $shipment['status'])); ?>
                    </span>
                    <span class="ml-4 text-sm text-gray-600">
                        Last updated: <?php echo date('M d, Y g:i A', strtotime($shipment['updated_at'])); ?>
                    </span>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t pt-6 text-center text-sm text-gray-600">
                <p>Thank you for choosing Westway Express!</p>
                <p class="mt-2">For tracking updates, visit our website or call customer service.</p>
                <p class="mt-4 font-medium">This is a computer-generated invoice and does not require a signature.</p>
            </div>
        </div>
    </div>
</body>
</html>
