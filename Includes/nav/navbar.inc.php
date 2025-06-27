<?php
// navbar.inc.php — uses $baseUrl, $links, $currentPage
?>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid d-flex align-items-center justify-content-between">


        <?php
        // Zorg dat sessie gestart is
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Bepaal link op basis van rol
        $logoHref = 'index.php'; // standaard
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'customer') {
                $logoHref = 'index.php?page=overviewcustomer';
            } elseif ($_SESSION['role'] === 'employee') {
                $logoHref = 'index.php?page=overviewemployee';
            }
        }
        ?>

        <a class="navbar-brand" href="<?= $logoHref ?>">
            <img src="assets/logo.png" alt="Logo" height="40">
        </a>


        <!-- Hamburger menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links fully right -->
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <?php foreach ($links as $page => $label):
                    $isPhp = str_starts_with($page, 'php/');
                    $url = $isPhp ? $page : "{$baseUrl}/index.php?page={$page}";
                    $isActive = !$isPhp && $page === $currentPage;
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= $url ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
