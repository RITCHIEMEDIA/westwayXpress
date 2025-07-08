<?php
require_once 'config/database.php';

$tracking_id = $_GET['tracking_id'] ?? '';

if (!$tracking_id) {
    header('Location: track.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM shipments WHERE tracking_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$tracking_id]);

if (!$shipment = $stmt->fetch(PDO::FETCH_ASSOC)) {
    header('Location: track.php');
    exit();
}

// Get tracking history for invoice
$tracking_query = "SELECT * FROM shipment_tracking WHERE shipment_id = ? ORDER BY created_at DESC LIMIT 1";
$tracking_stmt = $db->prepare($tracking_query);
$tracking_stmt->execute([$shipment['id']]);
$latest_tracking = $tracking_stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Invoice - <?php echo $shipment['tracking_id']; ?> | Westway Express</title>
    <link href="assets/css/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @page {
            size: A4;
            margin: 0.4in;
        }
        
        /* Force color printing */
        * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important;
                font-size: 12px !important;
            }
            .invoice-container { 
                box-shadow: none !important;
                page-break-inside: avoid;
                position: relative;
            }
            .invoice-watermark { 
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) rotate(-45deg) !important;
                z-index: 0 !important;
                opacity: 0.03 !important;
            }
            .official-stamp { display: flex !important; }
            .security-strip { display: block !important; }
            .gradient-header { 
                background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #f97316 100%) !important;
                color: white !important;
            }
            .neo-section {
                background: linear-gradient(145deg, #f8fafc, #e2e8f0) !important;
                border: 2px solid #cbd5e1 !important;
            }
            .payment-badges {
                background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
            }
            .total-section {
                background: linear-gradient(135deg, #059669, #047857) !important;
                color: white !important;
            }
        }

        .invoice-container {
            position: relative;
            background: white;
        }

        .invoice-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(59, 130, 246, 0.04);
            z-index: 0;
            pointer-events: none;
            font-family: 'Inter', sans-serif;
            letter-spacing: 8px;
            user-select: none;
            white-space: nowrap;
        }

        .invoice-content {
            position: relative;
            z-index: 1;
        }

        .security-strip {
            height: 8px;
            background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3);
            background-size: 400% 100%;
            animation: rainbow 3s linear infinite;
        }

        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .gradient-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #f97316 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><defs><pattern id="hexagons" width="60" height="60" patternUnits="userSpaceOnUse"><polygon points="30,2 52,17 52,43 30,58 8,43 8,17" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23hexagons)"/></svg>');
            opacity: 0.3;
        }

        .official-stamp {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 100px;
            height: 100px;
            border: 3px solid #dc2626;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            background: radial-gradient(circle, rgba(220, 38, 38, 0.1), rgba(220, 38, 38, 0.05));
            z-index: 10;
        }

        .official-stamp .stamp-text {
            color: #dc2626;
            font-weight: 900;
            font-size: 11px;
            text-align: center;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .official-stamp .stamp-date {
            color: #dc2626;
            font-weight: 600;
            font-size: 8px;
            margin-top: 2px;
        }

        .company-logo {
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3);
            transform: perspective(1000px) rotateX(5deg);
        }

        .neo-section {
            background: linear-gradient(145deg, #f8fafc, #e2e8f0);
            border: 2px solid #cbd5e1;
            border-radius: 16px;
            position: relative;
        }

        .gradient-border-left {
            border-left: 4px solid;
        }

        .sender-border {
            border-image: linear-gradient(to bottom, #3b82f6, #1d4ed8) 1;
        }

        .receiver-border {
            border-image: linear-gradient(to bottom, #f97316, #ea580c) 1;
        }

        .invoice-table {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: white;
        }

        .invoice-table th {
            background: linear-gradient(135deg, #374151, #1f2937);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 11px;
            padding: 12px 8px;
        }

        .total-section {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .total-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="stars" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23stars)"/></svg>');
            opacity: 0.4;
        }

        .barcode-frame {
            border: 3px dashed #374151;
            background: linear-gradient(45deg, #f9fafb 25%, transparent 25%), 
                        linear-gradient(-45deg, #f9fafb 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f9fafb 75%), 
                        linear-gradient(-45deg, transparent 75%, #f9fafb 75%);
            background-size: 12px 12px;
            background-position: 0 0, 0 6px, 6px -6px, -6px 0px;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
        }

        .verification-code {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border: 2px solid #d1d5db;
            padding: 8px 12px;
            font-size: 11px;
            letter-spacing: 1px;
            border-radius: 6px;
            font-weight: bold;
        }

        .payment-badges {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .payment-badge {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 10px;
            font-weight: 700;
            color: #374151;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .security-features {
            background: linear-gradient(45deg, #fef7ff 25%, transparent 25%), 
                        linear-gradient(-45deg, #fef7ff 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #fef7ff 75%), 
                        linear-gradient(-45deg, transparent 75%, #fef7ff 75%);
            background-size: 16px 16px;
            background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
            border: 2px dashed #d8b4fe;
            border-radius: 12px;
            padding: 16px;
        }

        .holographic-effect {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffeaa7, #dda0dd);
            background-size: 400% 400%;
            animation: holographic 4s ease-in-out infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes holographic {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .info-item {
            background: rgba(255,255,255,0.8);
            border-radius: 8px;
            padding: 12px;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .compact-spacing {
            margin-bottom: 8px;
        }

        .text-compact {
            font-size: 11px;
            line-height: 1.3;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Security Strip -->
    <div class="security-strip"></div>
    
    <div class="max-w-4xl mx-auto p-3">
        <!-- Print Controls -->
        <div class="no-print mb-4 flex justify-between items-center bg-white rounded-lg shadow p-3">
            <a href="track.php" class="text-blue-600 hover:text-blue-500 flex items-center font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tracking
            </a>
            <button onclick="window.print()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-lg flex items-center font-medium">
                <i class="fas fa-print mr-2"></i>Print Invoice
            </button>
        </div>

        <!-- Official Invoice -->
        <div class="bg-white shadow-2xl invoice-container relative">
            <!-- Invoice Watermark -->
            <div class="invoice-watermark">WESTWAY EXPRESS</div>
            
            <!-- Invoice Content -->
            <div class="invoice-content">
                <!-- Official Stamp -->
                <div class="official-stamp">
                    <div class="stamp-text">
                        OFFICIAL<br>INVOICE
                    </div>
                    <div class="stamp-date">
                        <?php echo date('M Y'); ?>
                    </div>
                </div>

                <!-- Enhanced Header -->
                <div class="gradient-header p-6 relative z-10">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="company-logo p-3 rounded-xl mr-4">
                                <i class="fas fa-shipping-fast text-2xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black mb-1 holographic-effect">WESTWAY EXPRESS</h1>
                                <p class="text-blue-100 text-sm font-medium">Premium Global Logistics Solutions</p>
                                <p class="text-blue-200 text-xs mt-1">ISO 9001:2015 Certified | License #WE-2024-GL</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-black mb-3">OFFICIAL INVOICE</h2>
                            <div class="bg-white bg-opacity-20 rounded-lg p-3 backdrop-blur-sm">
                                <div class="info-grid text-xs text-gray-900 font-medium">
                                    <div>
                                        <strong>Invoice #:</strong><br>
                                        <span class="font-mono text-xs"><?php echo 'INV-' . date('Y') . '-' . str_pad($shipment['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div>
                                        <strong>Date:</strong><br>
                                        <?php echo date('M d, Y', strtotime($shipment['created_at'])); ?>
                                    </div>
                                    <div>
                                        <strong>Tracking ID:</strong><br>
                                        <span class="font-mono font-bold text-xs"><?php echo $shipment['tracking_id']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Details -->
                    <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                        <div class="grid grid-cols-3 gap-4 text-xs text-blue-100">
                            <div>
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <strong>Head Office:</strong><br>
                                123 Business Ave, Global Trade Center<br>
                                New York, NY 10001, USA
                            </div>
                            <div>
                                <i class="fas fa-phone mr-1"></i>
                                <strong>Contact:</strong><br>
                                Phone: +1 (555) 123-4567<br>
                                Email: billing@westwayexpress.com
                            </div>
                            <div>
                                <i class="fas fa-certificate mr-1"></i>
                                <strong>Certifications:</strong><br>
                                IATA Certified | FIATA Member<br>
                                Tax ID: 12-3456789
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barcode Section -->
                <div class="p-4 bg-gray-50">
                    <div class="barcode-frame max-w-md mx-auto">
                        <svg id="barcode"></svg>
                        <div class="mt-2 font-mono text-xs font-bold"><?php echo $shipment['tracking_id']; ?></div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <!-- Sender -->
                        <div class="neo-section p-4 gradient-border-left sender-border">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-blue-600"></i>SHIP FROM (CONSIGNER)
                            </h3>
                            <div class="space-y-1 text-xs">
                                <p class="font-bold"><?php echo htmlspecialchars($shipment['sender_name']); ?></p>
                                <p><i class="fas fa-phone mr-1 text-green-600"></i><?php echo htmlspecialchars($shipment['sender_phone']); ?></p>
                                <p><i class="fas fa-envelope mr-1 text-purple-600"></i><?php echo htmlspecialchars($shipment['sender_email'] ?? 'Not provided'); ?></p>
                                <p><i class="fas fa-building mr-1 text-orange-600"></i><?php echo htmlspecialchars($shipment['sender_company'] ?? 'Individual'); ?></p>
                                <p><i class="fas fa-map-marker-alt mr-1 text-red-600"></i><?php echo nl2br(htmlspecialchars($shipment['sender_address'])); ?></p>
                            </div>
                        </div>

                        <!-- Receiver -->
                        <div class="neo-section p-4 gradient-border-left receiver-border">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user-check mr-2 text-orange-600"></i>SHIP TO (CONSIGNEE)
                            </h3>
                            <div class="space-y-1 text-xs">
                                <p class="font-bold"><?php echo htmlspecialchars($shipment['receiver_name']); ?></p>
                                <p><i class="fas fa-phone mr-1 text-green-600"></i><?php echo htmlspecialchars($shipment['receiver_phone']); ?></p>
                                <p><i class="fas fa-envelope mr-1 text-purple-600"></i><?php echo htmlspecialchars($shipment['receiver_email'] ?? 'Not provided'); ?></p>
                                <p><i class="fas fa-building mr-1 text-orange-600"></i><?php echo htmlspecialchars($shipment['receiver_company'] ?? 'Individual'); ?></p>
                                <p><i class="fas fa-map-marker-alt mr-1 text-red-600"></i><?php echo nl2br(htmlspecialchars($shipment['receiver_address'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Details Table -->
                    <div class="compact-spacing">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">SERVICE DETAILS</h3>
                        <table class="w-full border-collapse border border-gray-300 text-xs">
                            <thead class="invoice-table">
                                <tr>
                                    <th class="border border-gray-300 text-left">Description</th>
                                    <th class="border border-gray-300 text-center">Weight</th>
                                    <th class="border border-gray-300 text-center">Mode</th>
                                    <th class="border border-gray-300 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 p-2">
                                        <strong><?php echo ucfirst($shipment['service_type']); ?> Delivery Service</strong><br>
                                        <small class="text-gray-600"><?php echo htmlspecialchars($shipment['package_description'] ?: 'General Cargo'); ?></small>
                                    </td>
                                    <td class="border border-gray-300 p-2 text-center font-mono"><?php echo $shipment['weight']; ?> kg</td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                            <?php echo ucfirst(str_replace('_', ' ', $shipment['mode_of_transport'] ?: 'Standard')); ?>
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-2 text-right font-bold text-sm">
                                        $<?php echo number_format($shipment['shipping_cost'], 2); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Section -->
                    <div class="total-section rounded-lg p-4 compact-spacing relative z-10">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold">TOTAL AMOUNT</h3>
                                <p class="text-green-200 text-xs">All taxes and fees included</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-black">${<?php echo number_format($shipment['shipping_cost'], 2); ?></div>
                                <p class="text-green-200 text-xs">USD</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-badges compact-spacing">
                        <div class="payment-badge"><i class="fab fa-cc-visa mr-1"></i>VISA</div>
                        <div class="payment-badge"><i class="fab fa-cc-mastercard mr-1"></i>MASTERCARD</div>
                        <div class="payment-badge"><i class="fab fa-cc-paypal mr-1"></i>PAYPAL</div>
                        <div class="payment-badge"><i class="fas fa-shield-alt mr-1"></i>SSL SECURED</div>
                        <div class="payment-badge"><i class="fas fa-lock mr-1"></i>256-BIT ENCRYPTION</div>
                    </div>

                    <!-- Status & Verification -->
                    <div class="grid grid-cols-2 gap-4 compact-spacing">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-2">SHIPMENT STATUS</h3>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    <?php 
                                    $status_colors = [
                                        'new_status_created' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white',
                                        'in_process' => 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white',
                                        'onhold' => 'bg-gradient-to-r from-orange-500 to-red-500 text-white',
                                        'intransit' => 'bg-gradient-to-r from-purple-500 to-pink-500 text-white',
                                        'delayed' => 'bg-gradient-to-r from-red-500 to-red-600 text-white',
                                        'cancelled' => 'bg-gradient-to-r from-gray-500 to-gray-600 text-white',
                                        'delivered' => 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                                    ];
                                    echo $status_colors[$shipment['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $shipment['status'])); ?>
                                </span>
                            </div>
                            <?php if ($latest_tracking): ?>
                                <div class="mt-2 p-2 bg-gray-50 rounded text-xs">
                                    <p><strong>Latest Update:</strong></p>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($latest_tracking['notes']); ?></p>
                                    <?php if ($latest_tracking['location']): ?>
                                        <p class="text-blue-600 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($latest_tracking['location']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-2">SECURITY & VERIFICATION</h3>
                            <div class="security-features">
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Verification Code:</label>
                                        <div class="verification-code">
                                            <?php echo strtoupper(substr(md5($shipment['tracking_id'] . $shipment['created_at']), 0, 10)); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Digital Signature:</label>
                                        <div class="text-xs text-gray-600 font-mono">
                                            SHA256: <?php echo strtoupper(substr(hash('sha256', $shipment['tracking_id']), 0, 12)); ?>...
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Document ID:</label>
                                        <div class="text-xs text-gray-600 font-mono">
                                            <?php echo strtoupper(substr(md5($shipment['id'] . time()), 0, 8)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Footer -->
                    <div class="border-t pt-3 text-xs text-gray-600">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">TERMS & CONDITIONS</h4>
                                <ul class="space-y-1 text-xs">
                                    <li>• This invoice is computer generated and legally valid</li>
                                    <li>• All shipments are subject to our standard terms of service</li>
                                    <li>• Insurance coverage available upon request</li>
                                    <li>• Delivery times are estimates and may vary</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">CUSTOMER SERVICE</h4>
                                <div class="text-xs space-y-1">
                                    <p><strong>24/7 Support:</strong> +1 (555) 123-4567</p>
                                    <p><strong>Email:</strong> support@westwayexpress.com</p>
                                    <p><strong>Website:</strong> www.westwayexpress.com</p>
                                    <p><strong>Track Online:</strong> Use tracking ID above</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4 pt-3 border-t">
                            <p class="font-medium text-gray-900">Thank you for choosing Westway Express - Your Global Logistics Partner</p>
                            <p class="text-xs mt-1">This document was generated on <?php echo date('F d, Y \a\t g:i A T'); ?> | Document ID: <?php echo strtoupper(substr(md5($shipment['id'] . time()), 0, 8)); ?></p>
                            <p class="text-xs mt-1 font-medium">🔒 This invoice is secured with 256-bit SSL encryption and digital verification</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate barcode
        JsBarcode("#barcode", "<?php echo $shipment['tracking_id']; ?>", {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: false,
            background: "#ffffff",
            lineColor: "#000000"
        });

        // Auto-print functionality (optional)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 1000);
            };
        }
    </script>
</body>
</html>
