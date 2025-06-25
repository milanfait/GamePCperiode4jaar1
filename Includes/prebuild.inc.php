<?php

$prebuiltId = 3; // Prebuilt PC ID in the `pc` table

// 1. Fetch build part IDs
$sqlBuild = "SELECT CPU, GPU, Motherboard, RAM, SSDHDD, PSU, Cabinet, CPU_cooler, Monitor, Keyboard, Mouse 
             FROM pc WHERE id = :id";
$stmtBuild = $conn->prepare($sqlBuild);
$stmtBuild->bindValue(':id', $prebuiltId, PDO::PARAM_INT);
$stmtBuild->execute();
$build = $stmtBuild->fetch(PDO::FETCH_ASSOC);

if (!$build) {
    echo "<div class='alert alert-warning'>Prebuilt PC with ID 3 not found.</div>";
    exit();
}

// 2. Fetch part names/specs
$partIds = array_values($build);
$placeholders = implode(',', array_fill(0, count($partIds), '?'));

$sqlParts = "SELECT id, partname, specs FROM pcparts WHERE id IN ($placeholders)";
$stmtParts = $conn->prepare($sqlParts);
$stmtParts->execute($partIds);
$partsData = $stmtParts->fetchAll(PDO::FETCH_ASSOC);

// 3. Index by ID
$partsById = [];
foreach ($partsData as $part) {
    $partsById[$part['id']] = $part;
}

// 4. Map parts to categories
$buildPartsNamed = [];
foreach ($build as $category => $partId) {
    if (isset($partsById[$partId])) {
        $buildPartsNamed[$category] = [
            'name' => $partsById[$partId]['partname'],
            'specs' => $partsById[$partId]['specs']
        ];
    } else {
        $buildPartsNamed[$category] = [
            'name' => 'Unknown Part',
            'specs' => ''
        ];
    }
}
?>

<!-- Bootstrap Table Output -->
<div class="container my-4">
    <h3 class="mb-3">Prebuilt PC (ID: 3) Build Details</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center align-middle">
            <!-- Header -->
            <thead class="table-light">
            <tr>
                <?php foreach (array_keys($buildPartsNamed) as $category): ?>
                    <th><?= htmlspecialchars($category) ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>

            <!-- Part Names -->
            <tr>
                <?php foreach ($buildPartsNamed as $part): ?>
                    <td><strong><?= htmlspecialchars($part['name']) ?></strong></td>
                <?php endforeach; ?>
            </tr>
            <!-- Specs with Dropdown -->
            <tr>
                <?php foreach ($buildPartsNamed as $part): ?>
                    <td>
                        <details>
                            <summary class="text-primary small">View Specs</summary>
                            <div class="mt-2 text-muted small">
                                <?php
                                $specLines = explode("\n", $part['specs']);
                                foreach ($specLines as $line):
                                    if (trim($line) !== ''): ?>
                                        <div><?= htmlspecialchars(trim($line)) ?></div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </details>
                    </td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td colspan="<?= count($buildPartsNamed) ?>">
                    <a href="?page=pcpayment&id=<?= urlencode($prebuiltId) ?>" class="btn btn-outline btn-primary">
                        Buy PC
                        <?php
                        $prebuiltId = 3; // Prebuilt PC ID in the `pc` table
                        ?>
                    </a>
                </td>
            </tr>
        </table>
    </div>
</div>
