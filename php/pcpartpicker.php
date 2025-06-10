<?php
session_start();
include("../conn.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to build a PC.";
    header("Location: ../index.php?page=login");
    exit();
}

$userId = $_SESSION['user_id'];

$categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'SSDHDD', 'PSU', 'Cabinet', 'CPU_cooler', 'Monitor', 'Keyboard', 'Mouse'];

function getPartOptions($conn, $partType) {
    $stmt = $conn->prepare("SELECT id, partname FROM pcparts WHERE part = :partType");
    $stmt->bindParam(':partType', $partType);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [];

    foreach ($categories as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $_SESSION['error'] = "Missing selection for $field.";
            header("Location: ../index.php?page=pcpartpicker");
            exit();
        }
        $formData[$field] = (int) $_POST[$field];
    }

    // Calculate total price (example: sum prices of selected parts)
    $totalPrice = 0;
    foreach ($formData as $partId) {
        $priceStmt = $conn->prepare("SELECT price FROM pcparts WHERE id = :id");
        $priceStmt->bindValue(':id', $partId, PDO::PARAM_INT);
        $priceStmt->execute();
        $price = $priceStmt->fetchColumn();
        $totalPrice += $price ?: 0;
    }

    // Insert payment WITHOUT partid
    $paymentSql = "INSERT INTO payment (userid, price, paymentstatus) VALUES (:userid, :price, :paymentstatus)";
    $paymentStmt = $conn->prepare($paymentSql);
    $paymentStmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $paymentStmt->bindValue(':price', $totalPrice, PDO::PARAM_STR);
    $paymentStmt->bindValue(':paymentstatus', 'pending', PDO::PARAM_STR);
    $paymentStmt->execute();
    $paymentId = $conn->lastInsertId();

    // Insert PC build with payment id
    $sql = "INSERT INTO pc (partid, CPU, GPU, Motherboard, RAM, SSDHDD, PSU, Cabinet, CPU_cooler, Monitor, Keyboard, Mouse)
            VALUES (:partid, :CPU, :GPU, :Motherboard, :RAM, :SSDHDD, :PSU, :Cabinet, :CPU_cooler, :Monitor, :Keyboard, :Mouse)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':partid', $paymentId, PDO::PARAM_INT);

    foreach ($formData as $key => $value) {
        $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
    }

    $stmt->execute();

    $_SESSION['notification'] = "✅ PC built successfully!";
    header("Location: ../index.php?page=pcpartpicker");
    exit();
}

$options = [];
foreach ($categories as $category) {
    $options[$category] = getPartOptions($conn, $category);
}
?>
