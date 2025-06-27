<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_quantity':
                $index = intval($_POST['index']);
                $action = $_POST['update']; // 'increase' or 'decrease'

                if (isset($_SESSION['cart'][$index])) {
                    if ($action === 'increase') {
                        $_SESSION['cart'][$index]['quantity'] += 1;
                    } elseif ($action === 'decrease' && $_SESSION['cart'][$index]['quantity'] > 1) {
                        $_SESSION['cart'][$index]['quantity'] -= 1;
                    }
                }
                echo json_encode(['success' => true]);
                exit;

            case 'remove':
                $index = intval($_POST['index']);
                if (isset($_SESSION['cart'][$index])) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
                }
                header("Location: cart.php");
                exit;
        }
    }
}
?>
