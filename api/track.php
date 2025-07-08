<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$tracking_id = $input['tracking_id'] ?? '';

if (!$tracking_id) {
    echo json_encode(['success' => false, 'message' => 'Tracking ID is required']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Get shipment details
$query = "SELECT * FROM shipments WHERE tracking_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$tracking_id]);

if ($shipment = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Get tracking history
    $tracking_query = "SELECT * FROM shipment_tracking WHERE shipment_id = ? ORDER BY created_at ASC";
    $tracking_stmt = $db->prepare($tracking_query);
    $tracking_stmt->execute([$shipment['id']]);
    $tracking_history = $tracking_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'shipment' => $shipment,
        'tracking' => $tracking_history
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Shipment not found. Please check your tracking ID and try again.'
    ]);
}
?>
