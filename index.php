<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'home';

$pages_directory = 'includes';

$page_path = "{$pages_directory}/{$page}.inc.php";

if (!file_exists($page_path)) {
    http_response_code(404);
    $page = '404';
    $page_path = "{$pages_directory}/404.inc.php";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="this is a test, hello!">
    <meta name="keywords" content="codefest, HTML, CSS, PHP, JavaScript, test, test, test">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyGamePC</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script>
        let darkmode = localStorage.getItem('darkmode');
        document.documentElement.setAttribute('data-bs-theme', darkmode === 'active' ? 'dark' : 'light');
    </script>
</head>
<body>

<?php
include("conn.php");
include "includes/nav/navbar.inc.php";

if (isset($_SESSION['error'])){
    echo "
        <div class='error-message'>
            {$_SESSION['error']}
            <span class='close-btn' onclick='closeErrorMessage()'>&times;</span>
        </div>";
    unset($_SESSION['error']);
}
?>

<div style="min-height: 100vh;">
    <?php include $page_path; ?>
</div>

<?php include "{$pages_directory}/footer.inc.php";?>

<script src="js/functions.js"></script>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</body>
</html>