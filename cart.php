<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("conn.php");
include("includes/nav/navbar.inc.php");
include("cart.inc.php"); // Include cart display and interaction logic

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Shopping Cart - MyGamePC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <!-- The cart content will be rendered here by cart.inc.php -->
</div>
</body>
</html>
