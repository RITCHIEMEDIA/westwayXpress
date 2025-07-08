<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Handle quote status updates
if ($_POST && isset($_POST['update_quote_status'])) {
    $quote_id = $_POST['quote_id'];
    $new_status = $_POST['status'];
    
    $update_query = "UPDATE quotes SET status = ? WHERE id = ?";
    $stmt = $db->prepare($update_query);
    $stmt->execute([$new_status, $quote_id]);
}

// Get all quotes
$query = "SELECT * FROM quotes ORDER BY created_at DESC";
$quotes = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes - Westway Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold">Westway Express - Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="hover:text-blue-200">Dashboard</a>
                    <a href="../logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="mt-8">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="shipments.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-box mr-3"></i>
                    Shipments
                </a>
                <a href="quotes.php" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100 border-r-4 border-blue-500">
                    <i class="fas fa-file-invoice-dollar mr-3"></i>
                    Quotes
                </a>
                <a href="create-shipment.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-plus mr-3"></i>
                    Create Shipment
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Quotes</h1>
                <p class="text-gray-600">Manage customer quotes and convert to shipments</p>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Until</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($quotes as $quote): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $quote['quote_number']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($quote['created_at'])); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($quote['customer_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($quote['customer_email']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($quote['customer_phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo ucfirst($quote['service_type']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $quote['weight']; ?> kg</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        $<?php echo number_format($quote['estimated_cost'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 font-medium border-0 
                                                <?php 
                                                $status_colors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'accepted' => 'bg-green-100 text-green-800',
                                                    'converted' => 'bg-blue-100 text-blue-800',
                                                    'expired' => 'bg-red-100 text-red-800'
                                                ];
                                                echo $status_colors[$quote['status']];
                                                ?>">
                                                <option value="pending" <?php echo $quote['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="accepted" <?php echo $quote['status'] === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                <option value="converted" <?php echo $quote['status'] === 'converted' ? 'selected' : ''; ?>>Converted</option>
                                                <option value="expired" <?php echo $quote['status'] === 'expired' ? 'selected' : ''; ?>>Expired</option>
                                            </select>
                                            <input type="hidden" name="update_quote_status" value="1">
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($quote['valid_until'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewQuoteDetails(<?php echo htmlspecialchars(json_encode($quote)); ?>)" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Details Modal -->
    <div id="quoteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quote Details</h3>
                    <button onclick="closeModal()" class="float-right text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="quoteDetails" class="p-6">
                    <!-- Quote details will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewQuoteDetails(quote) {
            const modal = document.getElementById('quoteModal');
            const detailsDiv = document.getElementById('quoteDetails');
            
            detailsDiv.innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Quote Information</h4>
                            <p><strong>Quote Number:</strong> ${quote.quote_number}</p>
                            <p><strong>Service Type:</strong> ${quote.service_type.charAt(0).toUpperCase() + quote.service_type.slice(1)}</p>
                            <p><strong>Estimated Cost:</strong> $${parseFloat(quote.estimated_cost).toFixed(2)}</p>
                            <p><strong>Valid Until:</strong> ${new Date(quote.valid_until).toLocaleDateString()}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Customer Information</h4>
                            <p><strong>Name:</strong> ${quote.customer_name}</p>
                            <p><strong>Email:</strong> ${quote.customer_email}</p>
                            <p><strong>Phone:</strong> ${quote.customer_phone}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pickup Address</h4>
                        <p class="text-gray-700">${quote.pickup_address}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Delivery Address</h4>
                        <p class="text-gray-700">${quote.delivery_address}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Weight</h4>
                            <p class="text-gray-700">${quote.weight || 'N/A'} kg</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Dimensions</h4>
                            <p class="text-gray-700">${quote.dimensions || 'N/A'}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Status</h4>
                            <p class="text-gray-700">${quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}</p>
                        </div>
                    </div>
                    
                    ${quote.package_description ? `
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Package Description</h4>
                            <p class="text-gray-700">${quote.package_description}</p>
                        </div>
                    ` : ''}
                </div>
            `;
            
            modal.classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('quoteModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('quoteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
