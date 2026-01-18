<?php
//  /modulos/seguridad/roles/componentes/topbar.php

// Asegurarse de que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>  


<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- TÍTULO DEL MÓDULO -->
    <h2 class="m-0">
        <i class="fa-solid fa-shield-halved me-2"></i>
        Seguridad
    </h2>

    <!-- ACCIONES DEL USUARIO -->
    <div class="d-flex align-items-center gap-3">

        <!-- Nombre del usuario -->
        <span class="fw-bold">
            <?php echo isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario'; ?>
        </span>

        <!-- Botón cerrar sesión -->
        <a href="/logout.php" class="btn btn-outline-danger btn-sm">
            <i class="fa-solid fa-right-from-bracket"></i> Salir
        </a>

    </div>

</div>

<hr>