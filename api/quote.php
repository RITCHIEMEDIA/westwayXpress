<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Generate quote number
$quote_number = 'QT' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

// Calculate estimated cost
$service_multipliers = [
    'standard' => 1.0,
    'express' => 2.5,
    'overnight' => 5.0
];

$weight = floatval($_POST['weight'] ?? 1);
$service_type = $_POST['service_type'] ?? 'standard';
$base_cost = 15.99;
$estimated_cost = $base_cost * $service_multipliers[$service_type] + ($weight * 2);

// Set valid until date (30 days from now)
$valid_until = date('Y-m-d', strtotime('+30 days'));

$query = "INSERT INTO quotes (quote_number, customer_name, customer_email, customer_phone, pickup_address, delivery_address, package_description, weight, dimensions, service_type, estimated_cost, valid_until) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($query);

if ($stmt->execute([
    $quote_number,
    $_POST['customer_name'],
    $_POST['customer_email'],
    $_POST['customer_phone'],
    $_POST['pickup_address'],
    $_POST['delivery_address'],
    $_POST['package_description'] ?? '',
    $weight,
    $_POST['dimensions'] ?? '',
    $service_type,
    $estimated_cost,
    $valid_until
])) {
    echo json_encode([
        'success' => true,
        'quote_number' => $quote_number,
        'estimated_cost' => number_format($estimated_cost, 2)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error generating quote. Please try again.'
    ]);
}
?>
