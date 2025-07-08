<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Get enhanced statistics
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM shipments) as total_shipments,
    (SELECT COUNT(*) FROM shipments WHERE status = 'delivered') as delivered_shipments,
    (SELECT COUNT(*) FROM shipments WHERE status = 'intransit') as in_transit_shipments,
    (SELECT COUNT(*) FROM shipments WHERE status = 'onhold') as onhold_shipments,
    (SELECT COUNT(*) FROM quotes WHERE status = 'pending') as pending_quotes,
    (SELECT SUM(shipping_cost) FROM shipments WHERE status = 'delivered') as total_revenue";
$stats = $db->query($stats_query)->fetch(PDO::FETCH_ASSOC);

// Get recent shipments
$recent_shipments_query = "SELECT * FROM shipments ORDER BY created_at DESC LIMIT 5";
$recent_shipments = $db->query($recent_shipments_query)->fetchAll(PDO::FETCH_ASSOC);

// Get recent quotes
$recent_quotes_query = "SELECT * FROM quotes ORDER BY created_at DESC LIMIT 5";
$recent_quotes = $db->query($recent_quotes_query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Westway Express</title>
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
    <!-- Enhanced Mobile-First Navigation -->
    <nav class="bg-gradient-to-r from-cargo-blue to-blue-800 text-white shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="bg-cargo-orange p-2 rounded-lg">
                        <i class="fas fa-shipping-fast text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg md:text-xl font-bold">Westway Express</h1>
                        <p class="text-xs text-blue-200">Admin Dashboard</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <span class="text-sm">Welcome, <span class="font-semibold"><?php echo $_SESSION['full_name']; ?></span></span>
                    <a href="../index.php" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="../logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <div class="px-4 py-2 text-sm text-blue-200">
                        Welcome, <?php echo $_SESSION['full_name']; ?>
                    </div>
                    <a href="../index.php" class="block px-4 py-2 hover:bg-blue-700 rounded">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="../logout.php" class="block px-4 py-2 hover:bg-blue-700 rounded text-red-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Enhanced Mobile-First Sidebar -->
        <div class="lg:w-64 bg-white shadow-xl">
            <!-- Mobile Sidebar Toggle -->
            <div class="lg:hidden bg-gray-100 p-4">
                <button id="sidebar-toggle" class="w-full flex items-center justify-between p-3 bg-white rounded-lg shadow">
                    <span class="font-medium">Navigation Menu</span>
                    <i class="fas fa-chevron-down transition-transform" id="sidebar-icon"></i>
                </button>
            </div>

            <!-- Sidebar Content -->
            <nav id="sidebar-content" class="hidden lg:block p-4">
                <!-- Clock Widget -->
                <div class="bg-gradient-to-br from-cargo-orange to-orange-600 rounded-xl p-4 mb-6 text-white text-center">
                    <div class="text-2xl font-bold" id="current-time"></div>
                    <div class="text-sm opacity-90" id="current-date"></div>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-3 text-white bg-cargo-blue rounded-lg shadow-md">
                        <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
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
                    <a href="search-edit.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-search mr-3 text-lg text-purple-600"></i>
                        <span class="font-medium">Search & Edit</span>
                    </a>
                    <a href="update-shipment.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-3 text-lg text-indigo-600"></i>
                        <span class="font-medium">Update Shipment</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 lg:p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">Overview of your logistics operations</p>
            </div>

            <!-- Enhanced Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-cargo-blue">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Shipments</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_shipments']; ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100">
                            <i class="fas fa-box text-cargo-blue text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Delivered</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['delivered_shipments']; ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">In Transit</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['in_transit_shipments']; ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100">
                            <i class="fas fa-truck text-purple-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-cargo-orange">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">On Hold</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['onhold_shipments']; ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-orange-100">
                            <i class="fas fa-pause-circle text-cargo-orange text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending Quotes</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $stats['pending_quotes']; ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100">
                            <i class="fas fa-file-invoice text-yellow-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">$<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                <a href="create-shipment.php" class="bg-gradient-to-r from-cargo-blue to-blue-700 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-plus text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold">Create Shipment</h3>
                            <p class="text-blue-100 text-sm">Add new shipment</p>
                        </div>
                    </div>
                </a>
                
                <a href="search-edit.php" class="bg-gradient-to-r from-cargo-orange to-orange-700 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-search text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold">Search & Edit</h3>
                            <p class="text-orange-100 text-sm">Find and modify shipments</p>
                        </div>
                    </div>
                </a>
                
                <a href="shipments.php" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-list text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-semibold">View All</h3>
                            <p class="text-purple-100 text-sm">All shipments list</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
                <!-- Recent Shipments -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Shipments</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($recent_shipments as $shipment): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900"><?php echo $shipment['tracking_id']; ?></p>
                                        <p class="text-sm text-gray-600"><?php echo $shipment['receiver_name']; ?></p>
                                        <p class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs rounded-full font-medium 
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
                                        <p class="text-sm font-semibold text-gray-900 mt-1">$<?php echo $shipment['shipping_cost']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-6">
                            <a href="shipments.php" class="text-cargo-blue hover:text-blue-700 text-sm font-medium">
                                View all shipments →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Quotes -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Quotes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($recent_quotes as $quote): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900"><?php echo $quote['quote_number']; ?></p>
                                        <p class="text-sm text-gray-600"><?php echo $quote['customer_name']; ?></p>
                                        <p class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($quote['created_at'])); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs rounded-full font-medium 
                                            <?php 
                                            $quote_status_colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'accepted' => 'bg-green-100 text-green-800',
                                                'converted' => 'bg-blue-100 text-blue-800',
                                                'expired' => 'bg-red-100 text-red-800'
                                            ];
                                            echo $quote_status_colors[$quote['status']];
                                            ?>">
                                            <?php echo ucfirst($quote['status']); ?>
                                        </span>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">$<?php echo $quote['estimated_cost']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-6">
                            <a href="quotes.php" class="text-cargo-blue hover:text-blue-700 text-sm font-medium">
                                View all quotes →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Sidebar toggle for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebarContent = document.getElementById('sidebar-content');
            const sidebarIcon = document.getElementById('sidebar-icon');
            
            sidebarContent.classList.toggle('hidden');
            sidebarIcon.classList.toggle('rotate-180');
        });

        // Clock functionality
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric' 
            });
            
            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
