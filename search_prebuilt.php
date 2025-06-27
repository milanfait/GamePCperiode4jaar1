<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "mygamepc");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$search = $_GET['query'] ?? '';
$brands = $_GET['brands'] ?? [];

if (!is_array($brands) || empty($brands)) {
    $brands = []; // no filter on brand
}

$params = [];
$types = '';
$brandFilterSql = '';

$searchParam = '%' . $search . '%';

// Start SQL and params with the search filter
$sql = "SELECT id, name, description, price, brand FROM prebuilt WHERE name LIKE ? ";
$types = 's';
$params = [$searchParam];

if (count($brands) > 0) {
    $brandPlaceholders = implode(',', array_fill(0, count($brands), '?'));
    $sql .= " AND brand IN ($brandPlaceholders)";
    $types .= str_repeat('s', count($brands));
    $params = array_merge($params, $brands);
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

// Now bind params in correct order: searchParam first, then brands
$stmt->bind_param($types, ...$params);


if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

// Bind params dynamically
if ($types) {
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param('s', $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["prebuilts" => $data]);
