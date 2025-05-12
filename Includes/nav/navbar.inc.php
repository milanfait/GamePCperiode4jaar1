<?php
$navConfig = require __DIR__ . '/navbarLinks.php';
$role = $_SESSION['role'] ?? 'guest';
$links = $navConfig[$role] ?? [];

$currentPage = $_GET['page'] ?? 'home';
?> 
<nav class="navbar navbar-expand-sm navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="../../index.php?page=home">
            <img src="../../assets/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            GamePC INC.
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <?php foreach ($links as $page => $label):
                    $isPhp = str_starts_with($page, 'php/');
                    $url = $isPhp ? $page : "index.php?page={$page}";
                    $isActive = !$isPhp && $page === $currentPage;
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= $url ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="ms-auto">
                <button class="btn btn-dark shadow" id="darkmodeSwitch">Toggle darkmode</button>
            </div>
        </div>
    </div>
</nav>
