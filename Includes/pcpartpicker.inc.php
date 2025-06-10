<?php
$formData = isset($_SESSION['formData']) ? $_SESSION['formData'] : [];

function getPartOptions($conn, $partType) {
    $stmt = $conn->prepare("SELECT id, partname FROM pcparts WHERE part = :partType");
    $stmt->bindParam(':partType', $partType);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // returns ['id' => ..., 'partname' => ...]
}
$categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'SSDHDD', 'PSU', 'Cabinet', 'CPU_cooler', 'Monitor', 'Keyboard', 'Mouse'];
$options = [];

foreach ($categories as $category) {
    $options[$category] = getPartOptions($conn, $category);
}

?>
<form action="php/pcpartpicker.php" method="POST">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card my-5 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Choose PC Parts</h3>

                        <?php foreach ($options as $category => $parts): ?>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="<?= $category ?>" id="<?= $category ?>" required>
                                    <option value="">-- Select <?= $category ?> --</option>
                                    <?php foreach ($parts as $part): ?>
                                        <option value="<?= $part['id'] ?>"><?= htmlspecialchars($part['partname']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="<?= $category ?>"><?= $category ?></label>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" class="btn btn-primary w-100">Build PC</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php

foreach ($_POST as $key => $value) {
    $_SESSION[$key] = htmlspecialchars($value);
}

exit();
?>


