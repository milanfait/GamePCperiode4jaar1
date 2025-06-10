<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to see your PC build.";
    exit();
}

$userId = $_SESSION['user_id'];

// 1. Get the latest payment record for this user
$sqlPayment = "SELECT id, paymentstatus, price FROM payment WHERE userid = :userid ORDER BY id DESC LIMIT 1";
$stmtPayment = $conn->prepare($sqlPayment);
$stmtPayment->bindValue(':userid', $userId, PDO::PARAM_INT);
$stmtPayment->execute();
$payment = $stmtPayment->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    echo "You have no PC builds yet.";
    exit();
}

$paymentId = $payment['id'];

// 2. Get the PC build record for this payment
$sqlBuild = "SELECT CPU, GPU, Motherboard, RAM, SSDHDD, PSU, Cabinet, CPU_cooler, Monitor, Keyboard, Mouse FROM pc WHERE partid = :paymentId";
$stmtBuild = $conn->prepare($sqlBuild);
$stmtBuild->bindValue(':paymentId', $paymentId, PDO::PARAM_INT);
$stmtBuild->execute();
$build = $stmtBuild->fetch(PDO::FETCH_ASSOC);

if (!$build) {
    echo "No PC build found for this payment.";
    exit();
}

// 3. Fetch part names for all selected parts in the build
$partIds = array_values($build);

$placeholders = implode(',', array_fill(0, count($partIds), '?'));
$sqlParts = "SELECT id, partname FROM pcparts WHERE id IN ($placeholders)";
$stmtParts = $conn->prepare($sqlParts);
$stmtParts->execute($partIds);
$partsData = $stmtParts->fetchAll(PDO::FETCH_KEY_PAIR); // id => partname

// 4. Replace IDs in $build with part names
$buildPartsNamed = [];
foreach ($build as $category => $partId) {
    $buildPartsNamed[$category] = $partsData[$partId] ?? 'Unknown Part';
}

// --- Display combined table ---
echo "<h3>Your PC Build & Payment Info</h3>";
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr>';

// Headers for PC parts
foreach (array_keys($buildPartsNamed) as $category) {
    echo '<th>' . htmlspecialchars($category) . '</th>';
}
// Additional headers for payment info
echo '<th>Payment Status</th><th>Price</th>';

echo '</tr><tr>';

// PC parts values
foreach ($buildPartsNamed as $partName) {
    echo '<td>' . htmlspecialchars($partName) . '</td>';
}

// Payment info values
echo '<td>' . htmlspecialchars($payment['paymentstatus']) . '</td>';
echo '<td>$' . number_format($payment['price'], 2) . '</td>';

echo '</tr>';
echo '</table>';
?>
