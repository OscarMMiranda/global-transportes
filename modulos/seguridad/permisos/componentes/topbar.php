<?php
// archivo: /modulos/seguridad/permisos/componentes/topbar.php  
?> 

<div class="topbar d-flex justify-content-between align-items-center mb-3 px-3 py-2 bg-white rounded shadow-sm">

    <!-- Botón regresar -->
    <button id="btnVolver" class="btn btn-outline-secondary btn-sm" aria-label="Regresar">
        <i class="fa-solid fa-arrow-left me-1"></i> Regresar
    </button>

    <!-- Título -->
    <h1 class="h5 fw-semibold m-0 text-dark">
        <i class="fa-solid fa-shield-halved me-2 text-primary"></i>
        Gestión de Permisos
    </h1>

    <!-- Salir -->
    <a href="/logout.php" class="btn btn-outline-danger btn-sm" aria-label="Cerrar sesión">
        <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
    </a>

</div>
