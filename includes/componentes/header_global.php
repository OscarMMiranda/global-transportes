<?php
	// archivo: /includes/componentes/header_global.php
	// Barra superior global del sistema
	// Compatible con PHP 5.6 (sin operadores ?? ni sintaxis moderna)
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
    <div class="container-fluid">

        <!-- Botón Volver -->
        <a href="javascript:history.back()" class="btn btn-outline-light me-3">
            <i class="fa fa-arrow-left"></i> Volver
        </a>

        <!-- Título del sistema -->
        <span class="navbar-brand mb-0 h1">Global Transportes</span>

        <div class="ms-auto d-flex align-items-center">

            <!-- Panel principal -->
            <a href="/paneles/panel_admin.php" class="btn btn-outline-info me-2">
                <i class="fa fa-home"></i> Panel Principal
            </a>

            <!-- Usuario conectado -->
            <span class="text-white me-3">
                <i class="fa fa-user"></i>
                <?php
                    if (isset($_SESSION['usuario'])) {
                        echo htmlspecialchars($_SESSION['usuario']);
                    } else {
                        echo 'Usuario';
                    }
                ?>
            </span>

            <!-- Salir -->
            <a href="/logout.php" class="btn btn-outline-danger">
                <i class="fa fa-sign-out-alt"></i> Salir
            </a>

        </div>
    </div>
</nav>