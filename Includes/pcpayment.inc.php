d<?php
// Example PartId: Get this from request or context
$PartId = $_GET['partid'] ?? null;

if (!$PartId) {
    echo "Part ID not provided.";
    exit();
}

// 1. Get the prebuilt PC build details using partid
$sqlBuild = "SELECT CPU, GPU, Motherboard, RAM, SSDHDD, PSU, Cabinet, CPU_cooler, Monitor, Keyboard, Mouse 
             FROM pc WHERE partid = :partid";
$stmtBuild = $conn->prepare($sqlBuild);
$stmtBuild->bindValue(':partid', $PartId, PDO::PARAM_INT);
$stmtBuild->execute();
$build = $stmtBuild->fetch(PDO::FETCH_ASSOC);

if (!$build) {
    echo "Prebuilt PC not found.";
    exit();
}

// 2. Get all part IDs from the build
$partIds = array_values($build);
$placeholders = implode(',', array_fill(0, count($partIds), '?'));

// 3. Fetch part names from pcparts
$sqlParts = "SELECT id, partname FROM pcparts WHERE id IN ($placeholders)";
$stmtParts = $conn->prepare($sqlParts);
$stmtParts->execute($partIds);
$partsData = $stmtParts->fetchAll(PDO::FETCH_KEY_PAIR); // id => partname

// 4. Replace part IDs with part names
$buildPartsNamed = [];
foreach ($build as $category => $partId) {
    $buildPartsNamed[$category] = $partsData[$partId] ?? 'Unknown Part';
}

// 5. Display the PC Build
echo "<h3>Prebuilt PC Build Details</h3>";
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr>';
foreach (array_keys($buildPartsNamed) as $category) {
    echo '<th>' . htmlspecialchars($category) . '</th>';
}
echo '</tr><tr>';
foreach ($buildPartsNamed as $partName) {
    echo '<td>' . htmlspecialchars($partName) . '</td>';
}
echo '</tr>';
echo '</table>';
?>
