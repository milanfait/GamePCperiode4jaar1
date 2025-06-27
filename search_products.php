<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "mygamepc");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$search = $_GET['query'] ?? '';
$categories = $_GET['categories'] ?? [];
$brands = $_GET['brands'] ?? [];

if (!is_array($categories) || empty($categories)) {
    $categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'SSDHDD', 'PSU', 'Cabinet', 'CPU_cooler', 'Monitor', 'Keyboard', 'Mouse'];
}

$placeholders = implode(',', array_fill(0, count($categories), '?'));
$types = str_repeat('s', count($categories));
$params = $categories;

$brandFilterSql = '';
if (is_array($brands) && count($brands) > 0) {
    $brandPlaceholders = implode(',', array_fill(0, count($brands), '?'));
    $brandFilterSql = " AND brand IN ($brandPlaceholders)";
    $types .= str_repeat('s', count($brands));
    $params = array_merge($params, $brands);
}

$searchParam = '%' . $search . '%';
$types .= 's';
$params[] = $searchParam;

$sql = "SELECT id, name, description, price, part, brand FROM products WHERE part IN ($placeholders) $brandFilterSql AND name LIKE ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["products" => $data]);
