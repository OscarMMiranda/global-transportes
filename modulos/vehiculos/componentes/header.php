<?php
// archivo: /modulos/componentes/header.php
?>

<header class="bg-light border-bottom py-3 mb-3">
    <div class="container d-flex justify-content-between align-items-center">

        <h2 class="m-0 fw-bold">
            <i class="fa-solid fa-layer-group me-2"></i>
            ERP Global Transportes
        </h2>

        <div>
            <span class="text-muted me-3">
                Usuario: <?= isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Invitado' ?>
            </span>

            <a href="/logout.php" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket me-1"></i>
                Salir
            </a>
        </div>

    </div>
</header>